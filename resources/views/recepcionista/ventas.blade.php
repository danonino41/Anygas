@extends('layouts.recepcionista')

@section('titulo', 'Ventas del Día')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-cash-coin text-warning"></i> Ventas del Día
            </h2>
            <p class="text-secondary small mb-0">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d/m/Y') }} — Todos los pedidos registrados hoy
            </p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-3 text-center">
                    <div class="fs-3 fw-bold text-dark">{{ $resumen->total_pedidos }}</div>
                    <small class="text-muted">Pedidos Totales</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-3 text-center">
                    <div class="fs-3 fw-bold text-warning">{{ $resumen->pendientes }}</div>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-3 text-center">
                    <div class="fs-3 fw-bold text-success">{{ $resumen->entregados }}</div>
                    <small class="text-muted">Entregados</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100 border-start border-success border-4">
                <div class="card-body p-3 text-center">
                    <div class="fs-3 fw-bold text-success">S/. {{ number_format($resumen->total_ingresos, 2) }}</div>
                    <small class="text-muted">Ingresos del Día</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark">
                <i class="bi bi-list-ol me-1"></i> Pedidos de Hoy
                <span class="badge bg-dark ms-1">{{ $pedidos->count() }}</span>
            </span>
            <small class="text-muted">ordenados del más reciente al más antiguo</small>
        </div>
        <div class="table-responsive px-3 pb-3">
            <table class="table align-middle mb-0" style="font-size:0.9rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Hora</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Motorizado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td class="fw-bold text-dark">{{ $pedido->codigo_seguimiento }}</td>
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('H:i') }}</td>
                            <td>
                                <span class="fw-bold text-dark d-block">{{ $pedido->cliente->nombres }}</span>
                                <small class="text-muted">{{ $pedido->cliente->documento_identidad }}</small>
                            </td>
                            <td><small class="text-muted">{{ $pedido->cliente->telefono }}</small></td>
                            <td>
                                @if($pedido->tipo_despacho == 'recojo_tienda')
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-shop me-1"></i>Retiro</span>
                                @else
                                    <span class="text-truncate d-block" style="max-width:160px;" title="{{ $pedido->direccion_entrega }}">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_entrega }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $pedido->detalles->sum('cantidad') }} und.</span>
                            </td>
                            <td class="fw-bold text-success">S/. {{ number_format($pedido->monto_total, 2) }}</td>
                            <td>
                                @php
                                    $colores = [
                                        'pendiente' => 'bg-warning text-dark',
                                        'asignado'  => 'bg-primary text-white',
                                        'en_camino' => 'bg-info text-dark',
                                        'entregado' => 'bg-success text-white',
                                        'cancelado' => 'bg-danger text-white'
                                    ];
                                    $iconos = [
                                        'pendiente' => 'bi-hourglass-split',
                                        'asignado'  => 'bi-person-check',
                                        'en_camino' => 'bi-truck',
                                        'entregado' => 'bi-check2-circle',
                                        'cancelado' => 'bi-x-circle'
                                    ];
                                @endphp
                                <span class="badge {{ $colores[$pedido->estado] ?? 'bg-secondary' }} text-uppercase" style="font-size:0.7rem;">
                                    <i class="bi {{ $iconos[$pedido->estado] ?? 'bi-question' }} me-1"></i>{{ str_replace('_', ' ', $pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                @if($pedido->motorizado)
                                    <small class="fw-bold text-dark">{{ $pedido->motorizado->nombre_completo }}</small>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2">🛒</div>
                                <p class="fw-bold mb-0">No hay ventas registradas hoy</p>
                                <small>Los pedidos que registres aparecerán aquí.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
