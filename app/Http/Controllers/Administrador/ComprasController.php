<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Reabastecimiento;
use App\Models\DetalleReabastecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Reabastecimiento::with(['proveedor', 'usuario', 'detalles.producto'])
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $totalGastado = Reabastecimiento::sum('monto_total_compra');
        $totalCompras = $compras->count();
        $totalProductos = DetalleReabastecimiento::sum('cantidad_recibida');
        $compraMasReciente = Reabastecimiento::with('proveedor')
            ->latest('fecha_compra')
            ->first();

        $proveedores = Proveedor::where('estado', 'activo')->orderBy('nombre_empresa')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('administrador.compras', compact(
            'compras', 'totalGastado', 'totalCompras',
            'totalProductos', 'compraMasReciente',
            'proveedores', 'productos'
        ));
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado', 'activo')->orderBy('nombre_empresa')->get();
        $productos = Producto::orderBy('nombre')->get();

        return response()->json([
            'proveedores' => $proveedores,
            'productos' => $productos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|numeric|min:1',
            'items.*.costo_unitario' => 'required|numeric|min:0',
        ]);

        $usuarioId = Auth::id();
        $montoTotal = 0;
        $detalles = [];

        foreach ($validated['items'] as $item) {
            $subtotal = $item['cantidad'] * $item['costo_unitario'];
            $montoTotal += $subtotal;
            $detalles[] = [
                'producto_id' => $item['producto_id'],
                'cantidad_recibida' => $item['cantidad'],
                'costo_unitario_compra' => $item['costo_unitario'],
                'subtotal_compra' => $subtotal,
            ];
        }

        DB::transaction(function () use ($validated, $usuarioId, $montoTotal, $detalles) {
            $reabastecimiento = Reabastecimiento::create([
                'proveedor_id' => $validated['proveedor_id'],
                'usuario_id' => $usuarioId,
                'monto_total_compra' => $montoTotal,
                'fecha_compra' => $validated['fecha_compra'],
            ]);

            foreach ($detalles as $detalle) {
                $detalle['reabastecimiento_id'] = $reabastecimiento->id;
                DetalleReabastecimiento::create($detalle);

                Producto::where('id', $detalle['producto_id'])
                    ->increment('stock_actual', $detalle['cantidad_recibida']);
            }
        });

        return redirect()->route('admin.compras')
            ->with('success', 'Compra registrada correctamente. Stock actualizado.');
    }
}
