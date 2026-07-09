@extends('layouts.recepcionista')

@section('titulo', 'Panel Diario - Recepción')

@section('contenido')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-clipboard-data me-2"></i> Control de Mostrador del Día</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y') }} — Monitoreo de pedidos y despacho en tiempo real.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/recepcionista/punto-venta" class="btn btn-warning fw-bold px-4 shadow-sm">
                <i class="bi bi-cart-plus me-1"></i> Nueva Venta
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Pendientes</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $pendientes }}</h3>
                    </div>
                    <div class="fs-1 text-warning bg-warning bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-hourglass-split"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Por asignar motorizado</small>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Asignados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $asignados }}</h3>
                    </div>
                    <div class="fs-1 text-primary bg-primary bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-person-badge"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Esperando despacho</small>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">En Camino</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $enCamino }}</h3>
                    </div>
                    <div class="fs-1 text-info bg-info bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-map"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Balones en calle</small>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Entregados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $entregados }}</h3>
                    </div>
                    <div class="fs-1 text-success bg-success bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-check2-circle"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Cobrados hoy</small>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-danger border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Cancelados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ $cancelados }}</h3>
                    </div>
                    <div class="fs-1 text-danger bg-danger bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-x-circle"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Rechazados hoy</small>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-dark border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Ingresos del Día</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">S/ {{ number_format($ingresos, 2) }}</h3>
                    </div>
                    <div class="fs-1 text-dark bg-dark bg-opacity-10 rounded-circle px-3 py-1"><i class="bi bi-cash-stack"></i></div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Entregados cobrados</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8 d-flex flex-column">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 flex-grow-1">
                <div class="d-flex flex-column h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-fire me-2"></i> Últimos Pedidos Registrados Hoy</h5>
                    <div class="table-responsive mb-0 flex-grow-1">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Cliente</th>
                                    <th>Despacho</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosPedidos as $pedido)
                                <tr>
                                    <td class="fw-bold text-uppercase">{{ $pedido->codigo_seguimiento }}</td>
                                    <td>{{ $pedido->cliente?->nombres ?? '—' }}</td>
                                    <td>
                                        @if($pedido->tipo_despacho === 'domicilio')
                                            <span class="badge bg-secondary-subtle text-secondary">Domicilio</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info">Recojo tienda</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">S/. {{ number_format($pedido->monto_total, 2) }}</td>
                                    <td>
                                        @switch($pedido->estado)
                                            @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                            @case('asignado') <span class="badge bg-primary">Asignado</span> @break
                                            @case('en_camino') <span class="badge bg-info text-dark">En Camino</span> @break
                                            @case('entregado') <span class="badge bg-success">Entregado</span> @break
                                            @case('cancelado') <span class="badge bg-danger">Cancelado</span> @break
                                            @default <span class="badge bg-secondary">{{ $pedido->estado }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($pedido->estado === 'pendiente')
                                            <a href="/recepcionista/asignar" class="btn btn-sm btn-outline-dark">Despachar</a>
                                        @elseif(in_array($pedido->estado, ['asignado', 'en_camino']))
                                            <span class="badge bg-light text-muted">En ruta</span>
                                        @else
                                            <span class="badge bg-light text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No hay pedidos registrados hoy</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($stockBajo->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white flex-shrink-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle text-danger me-2"></i> Stock Bajo</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($stockBajo as $prod)
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-3" style="font-size:0.8rem;">
                            {{ $prod->nombre }} — <strong>{{ $prod->stock_actual }} und.</strong>
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4 d-flex">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white w-100 d-flex flex-column">
                <div class="flex-shrink-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-truck me-2"></i> Motorizados Activos Hoy</h5>
                    <p class="text-muted small mb-3">Disponibilidad para nuevos despachos.</p>
                </div>

                <div class="d-flex flex-column gap-3 flex-grow-1 overflow-y-auto" style="min-height: 0px;">
                    @forelse($motorizados as $mot)
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border flex-shrink-0">
                        <div class="d-flex align-items-center gap-2">
                            @if($mot->carga_actual > 0)
                                <span class="badge bg-primary rounded-circle p-2"> </span>
                            @else
                                <span class="badge bg-success rounded-circle p-2"> </span>
                            @endif
                            <div>
                                <strong class="d-block text-dark small">{{ $mot->nombre_completo }}</strong>
                                <small class="text-secondary" style="font-size: 0.7rem;">{{ $mot->documento_identidad ?? '—' }}</small>
                            </div>
                        </div>
                        @if($mot->carga_actual > 0)
                            <span class="badge bg-primary-subtle text-primary">{{ $mot->carga_actual }} en ruta</span>
                        @else
                            <span class="badge bg-success-subtle text-success">Disponible</span>
                        @endif
                    </div>
                    @empty
                    <p class="text-muted small text-center mb-0">No hay motorizados activos hoy</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection