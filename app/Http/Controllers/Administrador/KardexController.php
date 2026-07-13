<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class KardexController extends Controller
{
    public function index()
    {
        // 1. Llenos en local — productos tipo balón con stock > 0
        $llenos = Producto::where('nombre', 'like', '%Balón%')
            ->where('stock_actual', '>', 0)
            ->where('estado', 'disponible')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'marca', 'stock_actual', 'tipo_entrada', 'precio_venta']);

        $llenosAgrupados = $llenos->groupBy(function ($p) {
            preg_match('/(\d+\s*KG)/i', $p->nombre, $m);
            return $m[1] ?? 'Otros';
        })->map(function ($items, $key) {
            return [
                'presentacion' => $key,
                'total' => $items->sum('stock_actual'),
                'detalles' => $items,
            ];
        })->sortByDesc('total');

        // 2. Vacíos en local — balones entregados (en circulación, aún no retornados)
        $totalEntregados = DB::table('detalles_pedido')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->where('productos.nombre', 'like', '%Balón%')
            ->where('pedidos.estado', 'entregado')
            ->sum('detalles_pedido.cantidad');

        $vaciosPorPresentacion = DB::table('detalles_pedido')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->where('productos.nombre', 'like', '%Balón%')
            ->where('pedidos.estado', 'entregado')
            ->select('productos.nombre', DB::raw('SUM(detalles_pedido.cantidad) as total'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total')
            ->get()
            ->groupBy(function ($item) {
                preg_match('/(\d+\s*KG)/i', $item->nombre, $m);
                return $m[1] ?? 'Otros';
            })->map(function ($items) {
                return [
                    'total' => $items->sum('total'),
                ];
            });

        // 3. Deudas de clientes — pedidos entregados con monto no cubierto
        $deudas = Pedido::with('cliente')
            ->where('estado', 'entregado')
            ->whereHas('pagos', function ($q) {
                $q->whereColumn('monto_recibido', '<', 'monto');
            })
            ->orWhere(function ($q) {
                $q->where('estado', 'entregado')
                  ->whereDoesntHave('pagos');
            })
            ->withSum('pagos as total_pagado', 'monto_recibido')
            ->orderBy('fecha_registro', 'desc')
            ->take(20)
            ->get()
            ->map(function ($p) {
                $pagado = $p->total_pagado ?? 0;
                return [
                    'pedido' => $p,
                    'cliente' => $p->cliente,
                    'adeudo' => max(0, $p->monto_total - $pagado),
                ];
            })->filter(fn($d) => $d['adeudo'] > 0);

        $totalDeudaClientes = $deudas->sum('adeudo');

        // Resumen general
        $totalLlenos = $llenos->sum('stock_actual');
        $totalVacios = $totalEntregados;

        return view('administrador.kardex', compact(
            'llenosAgrupados', 'totalLlenos',
            'vaciosPorPresentacion', 'totalVacios',
            'deudas', 'totalDeudaClientes'
        ));
    }
}
