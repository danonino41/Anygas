<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        $pendientes  = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'pendiente')->count();
        $asignados   = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'asignado')->count();
        $enCamino    = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'en_camino')->count();
        $entregados  = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'entregado')->count();
        $cancelados  = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'cancelado')->count();
        $ingresos    = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'entregado')->sum('monto_total');

        $ultimosPedidos = Pedido::with('cliente')
            ->whereDate('fecha_registro', $hoy)
            ->orderBy('fecha_registro', 'desc')
            ->take(9)
            ->get();

        $motorizados = Usuario::where('rol', 'motorizado')->where('estado', 'activo')->get();

        $motorizados->loadCount(['pedidosMotorizados as carga_actual' => function ($q) use ($hoy) {
            $q->whereDate('fecha_registro', $hoy)->whereIn('estado', ['asignado', 'en_camino']);
        }]);

        $stockBajo = Producto::where('estado', 'disponible')->where('stock_actual', '<', 5)->get();

        return view('recepcionista.dashboard', compact(
            'pendientes', 'asignados', 'enCamino', 'entregados', 'cancelados', 'ingresos',
            'ultimosPedidos', 'motorizados', 'stockBajo'
        ));
    }
}
