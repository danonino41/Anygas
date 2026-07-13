<?php

namespace App\Http\Controllers\Motorizado;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $hoy = now()->startOfDay();

        $pedidos = Pedido::with(['cliente', 'detalles.producto', 'pagos.tipoPago'])
            ->where('motorizado_id', $userId)
            ->whereIn('estado', ['entregado', 'cancelado'])
            ->where(function ($q) use ($hoy) {
                $q->where('fecha_entrega', '>=', $hoy)
                  ->orWhere('fecha_registro', '>=', $hoy);
            })
            ->orderBy('fecha_entrega', 'desc')
            ->orderBy('fecha_registro', 'desc')
            ->get();

        $entregados = $pedidos->where('estado', 'entregado');
        $cancelados = $pedidos->where('estado', 'cancelado');

        $totalEfectivo = 0;
        $totalYape = 0;
        $totalPlin = 0;
        $totalTarjeta = 0;
        $totalOtros = 0;

        foreach ($entregados as $p) {
            foreach ($p->pagos as $pago) {
                $nombreTipo = strtolower($pago->tipoPago->nombre ?? '');
                if (str_contains($nombreTipo, 'efectivo')) {
                    $totalEfectivo += $pago->monto;
                } elseif (str_contains($nombreTipo, 'yape')) {
                    $totalYape += $pago->monto;
                } elseif (str_contains($nombreTipo, 'plin')) {
                    $totalPlin += $pago->monto;
                } elseif (str_contains($nombreTipo, 'tarjeta')) {
                    $totalTarjeta += $pago->monto;
                } else {
                    $totalOtros += $pago->monto;
                }
            }
        }

        $totalEnvasesRecolectados = DetallePedido::whereHas('pedido', function ($q) use ($userId, $hoy) {
                $q->where('motorizado_id', $userId)
                  ->where('estado', 'entregado')
                  ->where('fecha_entrega', '>=', $hoy);
            })
            ->sum('cantidad');

        $totalVendido = $entregados->sum('monto_total');
        $totalCobrado = $totalEfectivo + $totalYape + $totalPlin + $totalTarjeta + $totalOtros;

        return view('motorizado.historial', compact(
            'pedidos', 'entregados', 'cancelados',
            'totalEfectivo', 'totalYape', 'totalPlin', 'totalTarjeta', 'totalOtros',
            'totalVendido', 'totalCobrado',
            'totalEnvasesRecolectados'
        ));
    }
}
