<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\DetallePedido;
use App\Models\PagoPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\TipoPago;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
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

                // 2. Generar Código Único de Seguimiento (Ej: ANG-8821)
                $codigoSeguimiento = 'ANG-' . rand(1000, 9999);

                // Calcular monto total sumando subtotales
                $montoTotal = 0;
                foreach ($request->productos as $prod) {
                    $montoTotal += ($prod['cantidad'] * $prod['precio']);
                }

                // 3. Crear Cabecera del Pedido (REQ-02)
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
                Comprobante::create([
                    'pedido_id' => $pedido->id,
                    'tipo_comprobante' => strlen($request->documento_identidad) == 11 ? 'factura' : 'boleta',
                    'serie' => strlen($request->documento_identidad) == 11 ? 'F001' : 'B001',
                    'numero_correlativo' => str_pad($pedido->id, 6, '0', STR_PAD_LEFT),
                    'fecha_emision' => now()
                ]);

                return redirect()->route('recepcionista.dashboard')->with('éxito', "Pedido registrado con éxito. Código de rastreo: {$codigoSeguimiento}");
            });

        } catch (\Exception $e) {
            return back()->withErrors(['error_pos' => 'Ocurrió un error en la transacción: ' . $e->getMessage()]);
        }
    }
}