<?php

namespace App\Http\Controllers\Motorizado;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\PagoPedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $hoy = now()->startOfDay();

        $asignados = Pedido::where('motorizado_id', $userId)
            ->whereIn('estado', ['asignado', 'en_ruta'])
            ->whereDate('fecha_registro', $hoy)
            ->count();

        $enRuta = Pedido::where('motorizado_id', $userId)
            ->where('estado', 'en_ruta')
            ->whereDate('fecha_registro', $hoy)
            ->count();

        $entregadosHoy = Pedido::where('motorizado_id', $userId)
            ->where('estado', 'entregado')
            ->where('fecha_entrega', '>=', $hoy)
            ->get();

        $totalCobrado = 0;
        $totalEfectivo = 0;
        $totalYape = 0;
        $totalPlin = 0;
        $totalTarjeta = 0;

        foreach ($entregadosHoy as $p) {
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
                }
                $totalCobrado += $pago->monto;
            }
        }

        $pendientes = Pedido::with(['cliente'])
            ->where('motorizado_id', $userId)
            ->whereIn('estado', ['asignado', 'en_ruta'])
            ->orderBy('fecha_registro', 'asc')
            ->limit(5)
            ->get();

        return view('motorizado.dashboard', compact(
            'asignados', 'enRuta', 'entregadosHoy',
            'totalCobrado', 'totalEfectivo', 'totalYape', 'totalPlin', 'totalTarjeta',
            'pendientes'
        ));
    }

    public function index()
    {
        $userId = Auth::id();

        $hoy = now()->startOfDay();

        $asignados = Pedido::with(['cliente', 'detalles.producto', 'pagos.tipoPago'])
            ->where('motorizado_id', $userId)
            ->whereIn('estado', ['asignado', 'en_ruta'])
            ->orderBy('estado', 'desc')
            ->orderBy('fecha_registro', 'asc')
            ->get();

        $entregadosHoy = Pedido::where('motorizado_id', $userId)
            ->where('estado', 'entregado')
            ->where('fecha_entrega', '>=', $hoy)
            ->count();

        $totalAsignados = $asignados->count();
        $totalEnRuta = $asignados->where('estado', 'en_ruta')->count();

        return view('motorizado.ruta', compact(
            'asignados', 'entregadosHoy', 'totalAsignados', 'totalEnRuta'
        ));
    }

    public function iniciarRuta($id)
    {
        $pedido = Pedido::where('id', $id)->where('motorizado_id', Auth::id())->firstOrFail();
        $pedido->update(['estado' => 'en_ruta', 'fecha_salida' => now()]);
        return back()->with('success', "Pedido {$pedido->codigo_seguimiento} marcado como «En ruta».");
    }

    public function notificarLlegada($id)
    {
        $pedido = Pedido::where('id', $id)->where('motorizado_id', Auth::id())->firstOrFail();
        $pedido->update(['estado' => 'en_ruta']);
        return back()->with('success', "Notificación de llegada enviada para {$pedido->codigo_seguimiento}.");
    }

    public function confirmarEntrega(Request $request, $id)
    {
        $pedido = Pedido::where('id', $id)->where('motorizado_id', Auth::id())->firstOrFail();

        $request->validate([
            'deuda_envases' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($pedido, $request) {
            $pedido->update([
                'estado' => 'entregado',
                'fecha_entrega' => now(),
            ]);

            if ($request->filled('deuda_envases') && $request->deuda_envases > 0) {
                $pedido->cliente->increment('deuda_envases', $request->deuda_envases);
            }
        });

        return back()->with('success', "Pedido {$pedido->codigo_seguimiento} entregado correctamente.");
    }

    public function cobrar($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto', 'pagos.tipoPago'])
            ->where('id', $id)
            ->where('motorizado_id', Auth::id())
            ->whereIn('estado', ['asignado', 'en_ruta'])
            ->firstOrFail();

        return view('motorizado.cobrar', compact('pedido'));
    }

    public function guardarCobro(Request $request, $id)
    {
        $pedido = Pedido::with('detalles')
            ->where('id', $id)
            ->where('motorizado_id', Auth::id())
            ->whereIn('estado', ['asignado', 'en_ruta'])
            ->firstOrFail();

        $request->validate([
            'pagos' => 'required|array|min:1',
            'pagos.*.monto' => 'required|numeric|min:0',
            'pagos.*.monto_recibido' => 'nullable|numeric|min:0',
            'detalles' => 'required|array',
            'detalles.*.envases_devueltos' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($pedido, $request) {
            $totalPagado = 0;
            $totalEnvasesPendientes = 0;

            foreach ($request->pagos as $tipoPagoId => $pago) {
                $monto = (float) $pago['monto'];
                if ($monto <= 0) continue;

                PagoPedido::create([
                    'pedido_id' => $pedido->id,
                    'tipo_pago_id' => $tipoPagoId,
                    'monto' => $monto,
                    'monto_recibido' => !empty($pago['monto_recibido']) ? (float) $pago['monto_recibido'] : null,
                ]);

                $totalPagado += $monto;
            }

            foreach ($request->detalles as $detalleId => $data) {
                $detalle = $pedido->detalles->firstWhere('id', $detalleId);
                if (!$detalle) continue;

                $devueltos = min((int) $data['envases_devueltos'], $detalle->cantidad);
                $detalle->update(['envases_devueltos' => $devueltos]);
                $totalEnvasesPendientes += $detalle->cantidad - $devueltos;
            }

            $pedido->update([
                'estado' => 'entregado',
                'fecha_entrega' => now(),
            ]);

            if ($totalEnvasesPendientes > 0) {
                $pedido->cliente->increment('deuda_envases', $totalEnvasesPendientes);
                session()->flash('envases_pendientes', $totalEnvasesPendientes);
                session()->flash('mensaje_whatsapp_envases', 
                    "Hola {$pedido->cliente->nombre_completo}, tu pedido {$pedido->codigo_seguimiento} fue entregado. Te quedan {$totalEnvasesPendientes} balón(es) vacío(s) pendiente(s) de recojo. Pasaremos en los próximos días a recogerlo(s). ¡Gracias!"
                );
            }

            session()->flash('cobro_exitoso', true);
            session()->flash('pedido_id', $pedido->id);
            session()->flash('codigo', $pedido->codigo_seguimiento);
            session()->flash('cliente_nombre', $pedido->cliente->nombre_completo);
            session()->flash('cliente_telefono', $pedido->cliente->telefono ?? '');
            session()->flash('monto_total', number_format($pedido->monto_total, 2));
        });

        return redirect()->route('motorizado.ruta');
    }

    public function pendientes()
    {
        $userId = Auth::id();
        $hoy = now()->startOfDay();

        $pedidos = Pedido::with(['cliente', 'detalles.producto', 'pagos.tipoPago'])
            ->where('motorizado_id', $userId)
            ->where('estado', 'entregado')
            ->whereHas('detalles', function ($q) {
                $q->whereColumn('envases_devueltos', '<', 'cantidad');
            })
            ->orderBy('fecha_entrega', 'desc')
            ->get();

        $totalEnvasesPendientes = 0;
        $totalPedidos = $pedidos->count();

        foreach ($pedidos as $p) {
            $p->envases_pendientes = $p->detalles->sum(function ($d) {
                return $d->cantidad - $d->envases_devueltos;
            });
            $totalEnvasesPendientes += $p->envases_pendientes;
        }

        return view('motorizado.pendientes', compact('pedidos', 'totalEnvasesPendientes', 'totalPedidos'));
    }

    public function recogerEnvases(Request $request, $pedidoId)
    {
        $pedido = Pedido::with('detalles')
            ->where('id', $pedidoId)
            ->where('motorizado_id', Auth::id())
            ->where('estado', 'entregado')
            ->firstOrFail();

        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.recogido' => 'required|integer|min:0',
        ]);

        $totalRecogido = 0;

        DB::transaction(function () use ($pedido, $request, &$totalRecogido) {
            foreach ($request->detalles as $detalleId => $data) {
                $detalle = $pedido->detalles->firstWhere('id', $detalleId);
                if (!$detalle) continue;

                $pendiente = $detalle->cantidad - $detalle->envases_devueltos;
                $recogido = min((int) $data['recogido'], $pendiente);
                if ($recogido <= 0) continue;

                $detalle->increment('envases_devueltos', $recogido);
                $totalRecogido += $recogido;
            }

            if ($totalRecogido > 0) {
                $pedido->cliente->decrement('deuda_envases', $totalRecogido);
            }
        });

        $mensaje = $totalRecogido > 0
            ? "Se recogieron {$totalRecogido} balón(es) vacío(s) del pedido {$pedido->codigo_seguimiento}."
            : "No se recogió ningún balón.";

        return back()->with('success', $mensaje);
    }
}
