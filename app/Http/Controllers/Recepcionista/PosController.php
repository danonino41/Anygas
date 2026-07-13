<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClienteDireccion;
use App\Models\Comprobante;
use App\Models\DetallePedido;
use App\Models\PagoPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\TipoPago;
use App\Models\Usuario;
use App\Services\ApiperuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // Buscar cliente por DNI, nombre o teléfono para auto-completar
    public function buscarCliente(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 3) {
            return response()->json(['encontrado' => false]);
        }

        $clientes = Cliente::with('direcciones')
            ->where('documento_identidad', $q)
            ->orWhere('telefono', 'like', "%{$q}%")
            ->orWhere('nombres', 'like', "%{$q}%")
            ->orWhere('apellidos', 'like', "%{$q}%")
            ->get();

        if ($clientes->isEmpty()) {
            return response()->json(['encontrado' => false, 'clientes' => []]);
        }

        return response()->json([
            'encontrado' => true,
            'clientes' => $clientes->map(fn($c) => [
                'id' => $c->id,
                'documento_identidad' => $c->documento_identidad,
                'nombre_completo' => $c->nombres . ($c->apellidos ? ' ' . $c->apellidos : ''),
                'telefono' => $c->telefono,
                'direccion' => $c->direccion_principal,
                'direcciones' => $c->direcciones->map(fn($d) => [
                    'id' => $d->id,
                    'direccion' => $d->direccion,
                    'referencia' => $d->referencia,
                    'etiqueta' => $d->etiqueta,
                    'es_principal' => $d->es_principal,
                ]),
            ]),
        ]);
    }

    // Mostrar la pantalla del POS con productos y métodos de pago
    public function index()
    {
        $productos = Producto::where('estado', 'disponible')->get();
        $tiposPago = TipoPago::where('estado', 'activo')->get();
        $motorizados = Usuario::where('rol', 'motorizado')->where('estado', 'activo')->get();

        return view('recepcionista.pos', compact('productos', 'tiposPago', 'motorizados'));
    }

    // Procesar el cobro y guardar en las 5 tablas relacionales
    public function store(Request $request)
    {
        $request->validate([
            'documento_identidad' => 'required|max:15',
            'nombres' => 'required|string|max:100',
            'telefono' => 'required|max:15',
            'direccion' => 'required|string',
            'tipo_despacho' => 'required|in:domicilio,recojo_tienda',
            'tipo_pago_id' => 'required|exists:tipos_pago,id',
            'productos' => 'required|array',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                // 1. Buscar o registrar al Cliente (REQ-01)
                $cliente = Cliente::firstOrCreate(
                    ['documento_identidad' => $request->documento_identidad],
                    [
                        'nombres' => $request->nombres,
                        'apellidos' => $request->apellidos ?? '',
                        'telefono' => $request->telefono,
                        'direccion_principal' => $request->direccion,
                        'referencia_direccion' => $request->referencia ?? '',
                        'estado' => 'activo'
                    ]
                );

                // 2. Guardar dirección si es nueva
                $yaExiste = ClienteDireccion::where('cliente_id', $cliente->id)
                    ->where('direccion', $request->direccion)
                    ->exists();
                if (!$yaExiste) {
                    ClienteDireccion::create([
                        'cliente_id' => $cliente->id,
                        'direccion' => $request->direccion,
                        'referencia' => $request->referencia ?? '',
                        'etiqueta' => 'Dirección ' . ($cliente->direcciones()->count() + 1),
                        'es_principal' => $cliente->direcciones()->count() === 0,
                    ]);
                }

                // 3. Generar Código Único de Seguimiento (Ej: ANG-8821)
                $codigoSeguimiento = 'ANG-' . rand(1000, 9999);

                // Calcular monto total sumando subtotales
                $montoTotal = 0;
                foreach ($request->productos as $prod) {
                    $montoTotal += ($prod['cantidad'] * $prod['precio']);
                }

                // 4. Crear Cabecera del Pedido (REQ-02)
                $pedido = Pedido::create([
                    'codigo_seguimiento' => $codigoSeguimiento,
                    'cliente_id' => $cliente->id,
                    'recepcionista_id' => Auth::id() ?? 2, // Default ID recepcionista
                    'motorizado_id' => $request->motorizado_id ?: null,
                    'direccion_entrega' => $request->direccion,
                    'referencia_entrega' => $request->referencia ?? '',
                    'tipo_despacho' => $request->tipo_despacho,
                    'monto_total' => $montoTotal,
                    'estado' => $request->motorizado_id ? 'asignado' : 'pendiente',
                    'fecha_registro' => now(),
                ]);

                // 4. Guardar Carrito y Descontar Stock (REQ-03)
                foreach ($request->productos as $idProducto => $item) {
                    DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $idProducto,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                        'subtotal' => $item['cantidad'] * $item['precio']
                    ]);

                    // Descuento automático de inventario
                    Producto::where('id', $idProducto)->decrement('stock_actual', $item['cantidad']);
                }

                // 5. Registrar Pago
                PagoPedido::create([
                    'pedido_id' => $pedido->id,
                    'tipo_pago_id' => $request->tipo_pago_id,
                    'monto' => $montoTotal,
                    'monto_recibido' => $request->monto_recibido ?: $montoTotal
                ]);

                // 6. Generar Boleta Digital
                $comprobante = Comprobante::create([
                    'pedido_id' => $pedido->id,
                    'tipo_comprobante' => strlen($request->documento_identidad) == 11 ? 'factura' : 'boleta',
                    'serie' => strlen($request->documento_identidad) == 11 ? 'F001' : 'B001',
                    'numero_correlativo' => str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
                    'fecha_emision' => now()
                ]);

                $items = DetallePedido::where('pedido_id', $pedido->id)
                    ->with('producto')
                    ->get()
                    ->map(fn($d) => [
                        'nombre' => $d->producto?->nombre ?? 'Producto',
                        'cantidad' => $d->cantidad,
                        'precio' => $d->precio_unitario,
                        'subtotal' => $d->subtotal,
                    ]);

                return redirect()->route('recepcionista.pos')->with([
                    'pedido_creado' => true,
                    'comprobante_id' => $comprobante->id,
                    'codigo' => $codigoSeguimiento,
                    'cliente' => $request->nombres,
                    'total' => $montoTotal,
                    'items' => $items,
                ]);
            });

        } catch (\Exception $e) {
            return back()->withErrors(['error_pos' => 'Ocurrió un error en la transacción: ' . $e->getMessage()]);
        }
    }

    public function consultarDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:11',
        ]);

        $numero = trim($request->get('documento'));
        $service = new ApiperuService();

        if (strlen($numero) === 11) {
            $result = $service->consultarRuc($numero);
        } elseif (strlen($numero) === 8) {
            $result = $service->consultarDni($numero);
        } else {
            $result = null;
        }

        if ($result) {
            return response()->json(['success' => true, 'datos' => $result]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se encontraron datos para el número ingresado.',
        ]);
    }
}