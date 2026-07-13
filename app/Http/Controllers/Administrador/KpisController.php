<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Comprobante;
use App\Models\DetallePedido;
use App\Models\PagoPedido;
use App\Models\TipoPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KpisController extends Controller
{
    public function index(Request $request)
    {
        $hoy = now()->startOfDay();
        $inicioMes = now()->startOfMonth();
        $inicioSemana = now()->startOfWeek();
        $hace30d = now()->subDays(30)->startOfDay();

        // --- INGRESOS ---
        $ingresosHoy = Pedido::whereDate('fecha_registro', $hoy)
            ->where('estado', 'entregado')->sum('monto_total');

        $ingresosSemana = Pedido::whereDate('fecha_registro', '>=', $inicioSemana)
            ->where('estado', 'entregado')->sum('monto_total');

        $ingresosMes = Pedido::whereDate('fecha_registro', '>=', $inicioMes)
            ->where('estado', 'entregado')->sum('monto_total');

        // --- PEDIDOS ---
        $pedidosHoy = Pedido::whereDate('fecha_registro', $hoy)->count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $pedidosEnProceso = Pedido::whereIn('estado', ['asignado', 'en_camino'])->count();
        $pedidosEntregadosHoy = Pedido::whereDate('fecha_entrega', $hoy)
            ->where('estado', 'entregado')->count();

        // --- CLIENTES ---
        $totalClientes = Cliente::count();
        $clientesConPedidos = Cliente::whereHas('pedidos', function ($q) {
            $q->where('estado', 'entregado');
        })->count();

        // --- MOTORIZADOS ---
        $motorizadosActivos = Usuario::where('rol', 'motorizado')
            ->where('estado', 'activo')->count();

        // --- PRODUCTOS ---
        $productosConStock = Producto::where('estado', 'disponible')
            ->where('stock_actual', '>', 0)->count();
        $productosAgotados = Producto::where('estado', 'disponible')
            ->where('stock_actual', '<=', 0)->count();
        $valorInventario = Producto::where('estado', 'disponible')
            ->get()->sum(fn($p) => $p->stock_actual * $p->precio_compra);

        // --- TOP 5 PRODUCTOS (30 días) ---
        $topProductos = DetallePedido::selectRaw('
                productos.nombre, productos.marca,
                SUM(detalles_pedido.cantidad) as total_vendido,
                SUM(detalles_pedido.subtotal) as total_ingreso
            ')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.estado', 'entregado')
            ->where('pedidos.fecha_registro', '>=', $hace30d)
            ->groupBy('productos.nombre', 'productos.marca')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // --- INGRESOS POR MÉTODO DE PAGO (mes actual) ---
        $pagosMes = PagoPedido::selectRaw('
                tipos_pago.nombre as tipo,
                SUM(pagos_pedido.monto) as total
            ')
            ->join('tipos_pago', 'pagos_pedido.tipo_pago_id', '=', 'tipos_pago.id')
            ->join('pedidos', 'pagos_pedido.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.estado', 'entregado')
            ->where('pedidos.fecha_registro', '>=', $inicioMes)
            ->groupBy('tipos_pago.nombre')
            ->orderByDesc('total')
            ->get();

        // --- INGRESOS DIARIOS (últimos 15 días) ---
        $ingresosDiarios = Pedido::selectRaw('
                DATE(fecha_registro) as fecha,
                SUM(monto_total) as total
            ')
            ->where('estado', 'entregado')
            ->where('fecha_registro', '>=', now()->subDays(15)->startOfDay())
            ->groupBy(DB::raw('DATE(fecha_registro)'))
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        // Completar días faltantes con 0
        $dias = collect();
        for ($i = 14; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $dias->push([
                'fecha' => $fecha,
                'total' => (float) ($ingresosDiarios[$fecha]->total ?? 0),
            ]);
        }

        // --- DISTRIBUCIÓN POR TIPO DE DESPACHO (mes actual) ---
        $despachoMes = Pedido::selectRaw('
                tipo_despacho,
                COUNT(*) as total_pedidos,
                SUM(monto_total) as total_ingreso
            ')
            ->where('estado', 'entregado')
            ->where('fecha_registro', '>=', $inicioMes)
            ->groupBy('tipo_despacho')
            ->get();

        // --- COMPROBANTES SUNAT (mes actual) ---
        $comprobantesAceptados = Comprobante::where('estado_sincronizacion', 'aceptado')
            ->where('fecha_emision', '>=', $inicioMes)->count();

        $comprobantesPendientes = Comprobante::where('estado_sincronizacion', 'pendiente')
            ->where('fecha_emision', '>=', $inicioMes)->count();

        $comprobantesRechazados = Comprobante::where('estado_sincronizacion', 'rechazado')
            ->where('fecha_emision', '>=', $inicioMes)->count();

        return view('administrador.kpis', compact(
            'ingresosHoy', 'ingresosSemana', 'ingresosMes',
            'pedidosHoy', 'pedidosPendientes', 'pedidosEnProceso', 'pedidosEntregadosHoy',
            'totalClientes', 'clientesConPedidos',
            'motorizadosActivos',
            'productosConStock', 'productosAgotados', 'valorInventario',
            'topProductos',
            'pagosMes',
            'dias',
            'despachoMes',
            'comprobantesAceptados', 'comprobantesPendientes', 'comprobantesRechazados'
        ));
    }
}
