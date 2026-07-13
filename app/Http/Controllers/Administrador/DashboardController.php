<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        // Resumen del día
        $ingresosHoy = Pedido::whereDate('fecha_registro', $hoy)->where('estado', 'entregado')->sum('monto_total');
        $pedidosHoy = Pedido::whereDate('fecha_registro', $hoy)->count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $motorizadosActivos = Usuario::where('rol', 'motorizado')->where('estado', 'activo')->count();

        // Estado del servidor (simulado con datos reales del sistema)
        $servidor = [
            'php_version' => PHP_VERSION,
            'base_datos' => 'MySQL',
            'uptime' => $this->getServerUptime(),
            'memoria_usada' => $this->getMemoryUsage(),
        ];

        // Top productos más vendidos del mes
        $topProductos = DB::table('detalles_pedido')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->where('pedidos.estado', 'entregado')
            ->whereDate('pedidos.fecha_registro', '>=', now()->subDays(30))
            ->select('productos.nombre', DB::raw('SUM(detalles_pedido.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();

        return view('administrador.dashboard', compact(
            'ingresosHoy', 'pedidosHoy', 'pedidosPendientes', 'motorizadosActivos',
            'servidor', 'topProductos'
        ));
    }

    private function getServerUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic path Win32_OperatingSystem get LastBootUpTime 2>&1');
            if ($output) {
                preg_match('/\d{14}/', $output, $matches);
                if (!empty($matches[0])) {
                    $bootTime = \DateTime::createFromFormat('YmdHis', $matches[0]);
                    if ($bootTime) {
                        $diff = $bootTime->diff(now());
                        return "{$diff->d}d {$diff->h}h {$diff->i}m";
                    }
                }
            }
            return 'No disponible';
        }
        $output = @file_get_contents('/proc/uptime');
        if ($output) {
            $uptime = (int) explode(' ', $output)[0];
            $d = intdiv($uptime, 86400);
            $h = intdiv($uptime % 86400, 3600);
            $m = intdiv($uptime % 3600, 60);
            return "{$d}d {$h}h {$m}m";
        }
        return 'No disponible';
    }

    private function getMemoryUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize 2>&1');
            if ($output) {
                preg_match_all('/\d+/', $output, $matches);
                if (count($matches[0]) >= 2) {
                    $free = (int) $matches[0][0] * 1024;
                    $total = (int) $matches[0][1] * 1024;
                    $used = $total - $free;
                    return round($used / $total * 100, 1) . '%';
                }
            }
            return 'No disponible';
        }
        $output = @file_get_contents('/proc/meminfo');
        if ($output) {
            preg_match('/MemTotal:\s+(\d+)/', $output, $total);
            preg_match('/MemAvailable:\s+(\d+)/', $output, $available);
            if (!empty($total[1]) && !empty($available[1])) {
                $used = $total[1] - $available[1];
                return round($used / $total[1] * 100, 1) . '%';
            }
        }
        return 'No disponible';
    }
}
