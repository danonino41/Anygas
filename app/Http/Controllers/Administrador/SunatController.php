<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use App\Models\Pedido;
use App\Services\SunatService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SunatController extends Controller
{
    public function index(Request $request)
    {
        $query = Comprobante::with(['pedido.cliente', 'pedido.motorizado'])
            ->selectRaw('
                comprobantes.*,
                (comprobantes.numero_correlativo) as correlativo_raw
            ');

        $query->filtrar($request);

        $comprobantes = $query->orderBy('fecha_emision', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString()
            ->onEachSide(0);

        $comprobantes->getCollection()->transform(function ($c) {
            $c->serie_correlativo = "{$c->serie}-{$c->numero_correlativo}";
            $c->cliente_nombre = $c->pedido->cliente
                ? trim("{$c->pedido->cliente->nombres} {$c->pedido->cliente->apellidos}")
                : '—';
            $c->cliente_doc = $c->pedido->cliente->documento_identidad ?? '—';
            $c->monto_total_val = (float) $c->pedido->monto_total;
            $c->base_imponible_val = $c->tipo_comprobante === 'factura'
                ? round($c->monto_total_val / 1.18, 2)
                : $c->monto_total_val;
            $c->igv_val = $c->tipo_comprobante === 'factura'
                ? round($c->monto_total_val - $c->base_imponible_val, 2)
                : 0.00;
            $c->estado_badge_cls = match ($c->estado_sincronizacion) {
                'aceptado' => 'bg-success',
                'pendiente' => 'bg-warning text-dark',
                'rechazado' => 'bg-danger',
                default => 'bg-secondary',
            };
            $c->estado_label_txt = match ($c->estado_sincronizacion) {
                'aceptado' => 'Aceptado',
                'pendiente' => 'Pendiente',
                'rechazado' => 'Rechazado',
                default => $c->estado_sincronizacion,
            };
            $c->tipo_label_txt = match ($c->tipo_comprobante) {
                'boleta' => 'Boleta',
                'factura' => 'Factura',
                'ticket' => 'Ticket',
                default => $c->tipo_comprobante,
            };
            $c->motorizado_nombre = $c->pedido->motorizado
                ? trim($c->pedido->motorizado->nombre_completo)
                : 'Sin asignar';
            return $c;
        });

        $hoy = now()->startOfDay();
        $mesActual = now()->startOfMonth();

        $totalVentasMes = Pedido::where('estado', 'entregado')
            ->whereHas('comprobante', function ($q) use ($mesActual) {
                $q->where('fecha_emision', '>=', $mesActual)
                  ->where('tipo_comprobante', 'factura');
            })
            ->sum('monto_total');

        $totalIgvMes = $totalVentasMes / 1.18;
        $totalIgvMes = round($totalVentasMes - $totalIgvMes, 2);
        $totalVentasMesBase = round($totalVentasMes - $totalIgvMes, 2);

        $pendientes = Comprobante::where('estado_sincronizacion', 'pendiente')->count();
        $aceptados = Comprobante::where('estado_sincronizacion', 'aceptado')->count();
        $rechazados = Comprobante::where('estado_sincronizacion', 'rechazado')->count();

        $totalBoletas = Comprobante::where('tipo_comprobante', 'boleta')->count();
        $totalFacturas = Comprobante::where('tipo_comprobante', 'factura')->count();
        $totalTickets = Comprobante::where('tipo_comprobante', 'ticket')->count();
        $totalGeneral = $totalBoletas + $totalFacturas + $totalTickets;

        return view('administrador.sunat', compact(
            'comprobantes',
            'totalVentasMes', 'totalVentasMesBase', 'totalIgvMes',
            'pendientes', 'aceptados', 'rechazados',
            'totalBoletas', 'totalFacturas', 'totalTickets', 'totalGeneral'
        ));
    }

    public function show($id)
    {
        $c = Comprobante::with(['pedido.detalles.producto', 'pedido.cliente', 'pedido.recepcionista', 'pedido.motorizado', 'pedido.pagos.tipoPago'])
            ->findOrFail($id);

        $cliente = $c->pedido->cliente;
        $montoTotal = (float) $c->pedido->monto_total;

        $baseImponible = $c->tipo_comprobante === 'factura'
            ? round($montoTotal / 1.18, 2)
            : $montoTotal;
        $igv = $c->tipo_comprobante === 'factura'
            ? round($montoTotal - $baseImponible, 2)
            : 0.00;

        return response()->json([
            'id' => $c->id,
            'serie_correlativo' => "{$c->serie}-{$c->numero_correlativo}",
            'tipo_comprobante' => $c->tipo_comprobante,
            'tipo_label' => match ($c->tipo_comprobante) {
                'boleta' => 'Boleta de Venta',
                'factura' => 'Factura',
                'ticket' => 'Ticket Interno',
                default => $c->tipo_comprobante,
            },
            'fecha_emision' => $c->fecha_emision,
            'estado_sincronizacion' => $c->estado_sincronizacion,
            'estado_label' => match ($c->estado_sincronizacion) {
                'aceptado' => 'Aceptado',
                'pendiente' => 'Pendiente',
                'rechazado' => 'Rechazado',
                default => $c->estado_sincronizacion,
            },
            'estado_badge' => match ($c->estado_sincronizacion) {
                'aceptado' => 'bg-success',
                'pendiente' => 'bg-warning text-dark',
                'rechazado' => 'bg-danger',
                default => 'bg-secondary',
            },
            'cliente' => $cliente ? [
                'nombre' => trim("{$cliente->nombres} {$cliente->apellidos}"),
                'documento' => $cliente->documento_identidad ?? '—',
                'telefono' => $cliente->telefono ?? '—',
            ] : null,
            'pedido' => [
                'id' => $c->pedido->id,
                'codigo_seguimiento' => $c->pedido->codigo_seguimiento,
                'estado' => $c->pedido->estado,
                'fecha_registro' => $c->pedido->fecha_registro,
                'fecha_entrega' => $c->pedido->fecha_entrega,
                'direccion_entrega' => $c->pedido->direccion_entrega,
                'recepcionista' => $c->pedido->recepcionista
                    ? trim($c->pedido->recepcionista->nombre_completo)
                    : '—',
                'motorizado' => $c->pedido->motorizado
                    ? trim($c->pedido->motorizado->nombre_completo)
                    : 'Sin asignar',
            ],
            'detalles' => $c->pedido->detalles->map(function ($d) {
                return [
                    'producto' => $d->producto->nombre ?? 'Producto',
                    'marca' => $d->producto->marca ?? '',
                    'cantidad' => $d->cantidad,
                    'precio_unitario' => (float) $d->precio_unitario,
                    'subtotal' => (float) $d->subtotal,
                    'es_obsequio' => (float) $d->precio_unitario === 0.00,
                ];
            }),
            'pagos' => $c->pedido->pagos->map(function ($p) {
                return [
                    'tipo' => $p->tipoPago->nombre ?? '—',
                    'monto' => (float) $p->monto,
                    'monto_recibido' => (float) $p->monto_recibido,
                ];
            }),
            'base_imponible' => $baseImponible,
            'igv' => $igv,
            'monto_total' => $montoTotal,
        ]);
    }

    public function updateEstado(Request $request, $id)
    {
        $c = Comprobante::findOrFail($id);

        $validated = $request->validate([
            'estado_sincronizacion' => 'required|in:pendiente,aceptado,rechazado',
        ]);

        $c->update($validated);

        return back()->with('success', "Estado del comprobante {$c->serie}-{$c->numero_correlativo} actualizado a «{$validated['estado_sincronizacion']}».");
    }

    public function enviar($id)
    {
        $c = Comprobante::with('pedido')->findOrFail($id);

        $sunatService = new SunatService();
        $resultado = $sunatService->enviarComprobante($c->pedido);

        if ($resultado['success']) {
            $desc = $resultado['description'] ?? 'Enviado correctamente';
            $notas = isset($resultado['notes']) && count($resultado['notes'])
                ? ' | Obs: ' . implode(', ', $resultado['notes'])
                : '';
            return back()->with('success', "Comprobante {$c->serie}-{$c->numero_correlativo} enviado. {$desc}{$notas}");
        }

        return back()->with('error', "Error al enviar {$c->serie}-{$c->numero_correlativo}: " . ($resultado['message'] ?? 'Error desconocido'));
    }

    public function pdf($id)
    {
        $c = Comprobante::with(['pedido.detalles.producto', 'pedido.cliente'])
            ->findOrFail($id);

        $pedido = $c->pedido;
        $cliente = $pedido->cliente;
        $montoTotal = (float) $pedido->monto_total;

        $baseImponible = $c->tipo_comprobante === 'factura'
            ? round($montoTotal / 1.18, 2)
            : $montoTotal;
        $igv = $c->tipo_comprobante === 'factura'
            ? round($montoTotal - $baseImponible, 2)
            : 0.00;

        $direccionCliente = $pedido->direccion_entrega;

        $sunatConfig = config('sunat');

        $detalles = $pedido->detalles->map(function ($d) {
            return [
                'codigo' => 'P' . str_pad($d->producto_id, 5, '0', STR_PAD_LEFT),
                'unidad' => 'NIU',
                'cantidad' => $d->cantidad,
                'descripcion' => $d->producto->nombre ?? 'Producto',
                'valor_unitario' => round((float) $d->precio_unitario / 1.18, 2),
                'precio_unitario' => (float) $d->precio_unitario,
            ];
        })->toArray();

        $leyenda = $this->montoEnLetras($montoTotal);

        $data = [
            'tipoComprobante' => $c->tipo_comprobante,
            'serie' => $c->serie,
            'correlativo' => $c->numero_correlativo,
            'fechaEmision' => \Carbon\Carbon::parse($c->fecha_emision)->format('d/m/Y'),
            'moneda' => 'NUEVOS SOLES',
            'company' => [
                'ruc' => $sunatConfig['ruc'],
                'razon_social' => $sunatConfig['razon_social'],
                'nombre_comercial' => $sunatConfig['nombre_comercial'],
                'direccion' => $sunatConfig['direccion']['direccion'],
                'distrito' => $sunatConfig['direccion']['distrito'],
                'provincia' => $sunatConfig['direccion']['provincia'],
                'departamento' => $sunatConfig['direccion']['departamento'],
            ],
            'cliente' => [
                'nombre' => $cliente ? trim("{$cliente->nombres} {$cliente->apellidos}") : '—',
                'documento' => $cliente->documento_identidad ?? '—',
                'direccion' => $direccionCliente,
            ],
            'detalles' => $detalles,
            'totales' => [
                'base_imponible' => $baseImponible,
                'igv' => $igv,
                'monto_total' => $montoTotal,
            ],
            'leyenda' => $leyenda,
        ];

        $pdf = Pdf::loadView('administrador.comprobante_pdf', $data)
            ->setPaper([0, 0, 612, 792], 'portrait')
            ->setOption('isRemoteEnabled', true);

        $nombreArchivo = "{$c->serie}-{$c->numero_correlativo}.pdf";

        return $pdf->download($nombreArchivo);
    }

    public function ticket($id)
    {
        $c = Comprobante::with(['pedido.detalles.producto', 'pedido.cliente', 'pedido.recepcionista', 'pedido.pagos.tipoPago'])
            ->findOrFail($id);

        $pedido = $c->pedido;
        $cliente = $pedido->cliente;
        $montoTotal = (float) $pedido->monto_total;
        $sunatConfig = config('sunat');

        $baseImponible = $c->tipo_comprobante === 'factura'
            ? round($montoTotal / 1.18, 2)
            : $montoTotal;
        $igv = $c->tipo_comprobante === 'factura'
            ? round($montoTotal - $baseImponible, 2)
            : 0.00;

        $detalles = $pedido->detalles->map(function ($d) {
            return [
                'descripcion' => $d->producto->nombre ?? 'Producto',
                'marca' => $d->producto->marca ?? '',
                'cantidad' => $d->cantidad,
                'precio_unitario' => (float) $d->precio_unitario,
                'subtotal' => (float) $d->subtotal,
            ];
        })->toArray();

        $pagos = $pedido->pagos->map(function ($p) {
            return [
                'tipo' => $p->tipoPago->nombre ?? 'Efectivo',
                'monto' => (float) $p->monto,
            ];
        })->toArray();

        $tipoLabel = match ($c->tipo_comprobante) {
            'boleta' => 'Boleta de Venta',
            'factura' => 'Factura',
            default => 'Ticket',
        };

        $docTipo = $cliente
            ? (strlen($cliente->documento_identidad ?? '') === 11 ? 'RUC' : 'DNI')
            : 'DOC';

        $fecha = \Carbon\Carbon::parse($c->fecha_emision);

        $data = [
            'company' => [
                'ruc' => $sunatConfig['ruc'],
                'razon_social' => $sunatConfig['razon_social'],
                'nombre_comercial' => $sunatConfig['nombre_comercial'],
                'direccion' => $sunatConfig['direccion']['direccion'],
                'distrito' => $sunatConfig['direccion']['distrito'],
                'provincia' => $sunatConfig['direccion']['provincia'],
                'departamento' => $sunatConfig['direccion']['departamento'],
            ],
            'tipoComprobante' => $c->tipo_comprobante,
            'tipoLabel' => $tipoLabel,
            'serie' => $c->serie,
            'correlativo' => $c->numero_correlativo,
            'fechaEmision' => $fecha->format('d/m/Y'),
            'horaEmision' => $fecha->format('H:i:s'),
            'cliente' => [
                'nombre' => $cliente ? trim("{$cliente->nombres} {$cliente->apellidos}") : 'Cliente General',
                'documento' => $cliente->documento_identidad ?? '—',
                'doc_tipo' => $docTipo,
                'telefono' => $cliente->telefono ?? '',
                'direccion' => $pedido->direccion_entrega ?? $cliente->direccion_principal ?? '',
            ],
            'detalles' => $detalles,
            'pagos' => $pagos,
            'totales' => [
                'base_imponible' => $baseImponible,
                'igv' => $igv,
                'monto_total' => $montoTotal,
            ],
            'codigoSeguimiento' => $pedido->codigo_seguimiento ?? '—',
            'vendedor' => $pedido->recepcionista
                ? trim($pedido->recepcionista->nombre_completo)
                : '',
        ];

        $pdf = Pdf::loadView('administrador.ticket_venta', $data)
            ->setPaper([0, 0, 227, 600], 'portrait')
            ->setOption('isRemoteEnabled', true);

        $nombreArchivo = "Ticket-{$c->serie}-{$c->numero_correlativo}.pdf";

        return $pdf->download($nombreArchivo);
    }

    private function montoEnLetras(float $monto): string
    {
        $entero = (int) floor($monto);
        $decimal = round(($monto - $entero) * 100);

        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($entero === 0) {
            $texto = 'CERO';
        } elseif ($entero === 100) {
            $texto = 'CIEN';
        } else {
            $texto = '';
            $c = (int) ($entero / 100);
            $r = $entero % 100;

            if ($c > 0) {
                $texto .= $centenas[$c] . ' ';
            }

            if ($r > 0) {
                if ($r < 10) {
                    $texto .= $unidades[$r];
                } elseif ($r < 20) {
                    $texto .= $especiales[$r - 10];
                } else {
                    $d = (int) ($r / 10);
                    $u = $r % 10;
                    $texto .= $decenas[$d];
                    if ($u > 0) {
                        $texto .= ' Y ' . $unidades[$u];
                    }
                }
            }
        }

        return "SON {$texto} CON {$decimal}/100 SOLES";
    }
}
