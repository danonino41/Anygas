<?php

namespace App\Http\Controllers\Recepcionista;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with([
            'cliente',
            'detalles.producto',
            'motorizado',
            'recepcionista',
            'pagos.tipoPago',
            'comprobante',
        ])->orderBy('fecha_registro', 'desc');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_registro', $request->fecha);
        }

        if ($request->filled('codigo')) {
            $query->where('codigo_seguimiento', 'LIKE', '%' . strtoupper($request->codigo) . '%');
        }

        if ($request->filled('dni')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('documento_identidad', $request->dni);
            });
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('motorizado')) {
            $query->whereHas('motorizado', function ($q) use ($request) {
                $q->where('nombre_completo', 'LIKE', '%' . $request->motorizado . '%');
            });
        }

        $pedidos = $query->paginate(15)->withQueryString();

        $motorizados = Usuario::where('rol', 'motorizado')
            ->where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('recepcionista.historial', compact('pedidos', 'motorizados'));
    }

    public function ventasDia()
    {
        $hoy = Carbon::today();

        $resumen = Pedido::whereDate('fecha_registro', $hoy)
            ->selectRaw("
                COUNT(*) as total_pedidos,
                SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'entregado' THEN 1 ELSE 0 END) as entregados,
                SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as cancelados,
                SUM(CASE WHEN estado = 'entregado' THEN monto_total ELSE 0 END) as total_ingresos
            ")
            ->first();

        $pedidos = Pedido::with(['cliente', 'detalles.producto', 'motorizado'])
            ->whereDate('fecha_registro', $hoy)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return view('recepcionista.ventas', compact('pedidos', 'resumen'));
    }
}
