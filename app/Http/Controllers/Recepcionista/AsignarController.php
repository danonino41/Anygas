<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignarController extends Controller
{
    public function index(Request $request)
    {
        $codigoFiltro = $request->codigo;

        $pendientes = Pedido::with(['cliente', 'detalles.producto'])
            ->where('estado', 'pendiente')
            ->where('tipo_despacho', 'domicilio')
            ->orderBy('fecha_registro', 'asc')
            ->get();

        $enRuta = Pedido::with(['cliente', 'motorizado'])
            ->whereIn('estado', ['asignado', 'en_camino'])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        $motorizados = Usuario::where('rol', 'motorizado')
            ->where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get()
            ->map(function ($mot) {
                $mot->carga_actual = Pedido::where('motorizado_id', $mot->id)
                    ->whereIn('estado', ['asignado', 'en_camino'])
                    ->count();
                $mot->pedidos_asignados = Pedido::with(['cliente', 'detalles.producto'])
                    ->where('motorizado_id', $mot->id)
                    ->whereIn('estado', ['asignado', 'en_camino'])
                    ->orderBy('fecha_registro', 'asc')
                    ->get();
                return $mot;
            });

        return view('recepcionista.asignar', compact('pendientes', 'enRuta', 'motorizados', 'codigoFiltro'));
    }

    public function store(Request $request, $pedidoId)
    {
        $request->validate([
            'motorizado_id' => 'required|exists:usuarios,id',
        ]);

        $pedido = Pedido::findOrFail($pedidoId);

        if ($pedido->estado !== 'pendiente') {
            return back()->withErrors(['error' => 'El pedido ya fue asignado o está en ruta.']);
        }

        DB::transaction(function () use ($pedido, $request) {
            $pedido->update([
                'motorizado_id' => $request->motorizado_id,
                'estado' => 'asignado',
                'fecha_salida' => now(),
            ]);
        });

        return back()->with('exito', "Pedido {$pedido->codigo_seguimiento} asignado correctamente.");
    }

    public function asignarMultiple(Request $request)
    {
        $request->validate([
            'pedidos' => 'required|array',
            'pedidos.*' => 'exists:pedidos,id',
            'motorizado_id' => 'required|exists:usuarios,id',
        ]);

        $count = 0;
        DB::transaction(function () use ($request, &$count) {
            foreach ($request->pedidos as $pedidoId) {
                $pedido = Pedido::where('id', $pedidoId)->where('estado', 'pendiente')->first();
                if ($pedido) {
                    $pedido->update([
                        'motorizado_id' => $request->motorizado_id,
                        'estado' => 'asignado',
                        'fecha_salida' => now(),
                    ]);
                    $count++;
                }
            }
        });

        return back()->with('exito', "{$count} pedido(s) asignado(s) correctamente.");
    }
}
