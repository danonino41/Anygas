@extends('layouts.recepcionista')

@section('titulo', 'Historial General de Pedidos')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-warning"></i> Historial General de Pedidos
            </h2>
            <p class="text-secondary small mb-0">
                <i class="bi bi-info-circle me-1"></i> Busca órdenes anteriores, reimprime comprobantes y revisa el timeline de cada pedido.
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
        <form action="{{ route('recepcionista.historial') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">
                    <i class="bi bi-upc-scan me-1"></i>Código
                </label>
                <input type="text" name="codigo" class="form-control form-control-sm text-uppercase" placeholder="Ej: ANG-1042" value="{{ request('codigo') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">
                    <i class="bi bi-person-badge me-1"></i>DNI Cliente
                </label>
                <input type="text" name="dni" class="form-control form-control-sm" placeholder="Buscar DNI..." value="{{ request('dni') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">
                    <i class="bi bi-calendar3 me-1"></i>Fecha
                </label>
                <input type="date" name="fecha" class="form-control form-control-sm" value="{{ request('fecha') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">
                    <i class="bi bi-truck me-1"></i>Motorizado
                </label>
                <select name="motorizado" class="form-select form-select-sm">
                    <option value="">Cualquier motorizado</option>
                    @foreach($motorizados as $m)
                        <option value="{{ $m->nombre_completo }}" {{ request('motorizado') == $m->nombre_completo ? 'selected' : '' }}>{{ $m->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-secondary mb-1">
                    <i class="bi bi-flag me-1"></i>Estado
                </label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="asignado" {{ request('estado') == 'asignado' ? 'selected' : '' }}>Asignado</option>
                    <option value="en_camino" {{ request('estado') == 'en_camino' ? 'selected' : '' }}>En Camino</option>
                    <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="col-md-2 d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('recepcionista.historial') }}" class="btn btn-light btn-sm fw-bold border">
                    <i class="bi bi-x-circle me-1"></i>Limpiar
                </a>
                <button type="submit" class="btn btn-dark btn-sm fw-bold px-3">
                    <i class="bi bi-funnel me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="table-responsive p-3">
            <table class="table align-middle mb-0" style="font-size:0.9rem;">
                <thead class="table-dark">
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Orden</th>
                        <th>Fecha / Hora</th>
                        <th>Cliente</th>
                        <th>Dirección</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-center" style="width:150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr class="table-row-click" data-target="detalle-{{ $pedido->id }}" style="cursor:pointer;">
                            <td class="text-center">
                                <i class="bi bi-chevron-down text-muted" id="icon-{{ $pedido->id }}"></i>
                            </td>
                            <td class="fw-bold text-dark">{{ $pedido->codigo_seguimiento }}</td>
                            <td class="text-muted small">
                                <div>{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y') }}</div>
                                <div>{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('H:i') }}</div>
                            </td>
                            <td>
                                <strong class="d-block text-dark">{{ $pedido->cliente->nombres }}</strong>
                                <small class="text-secondary">{{ $pedido->cliente->documento_identidad }}</small>
                            </td>
                            <td>
                                @if($pedido->tipo_despacho == 'recojo_tienda')
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-shop me-1"></i> Retiro
                                    </span>
                                @else
                                    <span class="d-block text-truncate" style="max-width:170px;" title="{{ $pedido->direccion_entrega }}">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_entrega }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $pedido->detalles->sum('cantidad') }} und.</span>
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
                                    $color = $colores[$pedido->estado] ?? 'bg-secondary';
                                    $icono = $iconos[$pedido->estado] ?? 'bi-question';
                                @endphp
                                <span class="badge {{ $color }} text-uppercase" style="font-size:0.7rem;">
                                    <i class="bi {{ $icono }} me-1"></i>{{ str_replace('_', ' ', $pedido->estado) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-light border text-primary shadow-sm" title="Imprimir Ticket" onclick="imprimirTicket('{{ $pedido->codigo_seguimiento }}')">
                                        <i class="bi bi-receipt"></i>
                                    </button>
                                    @if($pedido->motorizado)
                                        <button type="button" class="btn btn-light border text-info shadow-sm" title="Ver ruta realizada" onclick="verRuta('{{ $pedido->codigo_seguimiento }}')">
                                            <i class="bi bi-map"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-light border text-success shadow-sm" title="Historial de tiempos" onclick="toggleTiempos('tiempos-{{ $pedido->id }}', this)">
                                        <i class="bi bi-clock"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="detalle-fila d-none" id="detalle-{{ $pedido->id }}">
                            <td colspan="9" class="p-0 bg-light">
                                <div class="p-4 border-top border-2 border-warning">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="bi bi-cart-check me-2 text-warning"></i>Detalle de Compra
                                            </h6>
                                            <table class="table table-sm table-borderless mb-0" style="font-size:0.85rem;">
                                                <thead class="text-secondary" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.5px;">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Producto</th>
                                                        <th class="text-end">P.Unit</th>
                                                        <th class="text-end">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pedido->detalles as $i => $det)
                                                        <tr>
                                                            <td class="text-muted">{{ $i + 1 }}</td>
                                                            <td>
                                                                <span class="fw-bold text-dark">{{ $det->cantidad }}x</span>
                                                                {{ $det->producto->marca }} {{ $det->producto->nombre }}
                                                            </td>
                                                            <td class="text-end">S/. {{ number_format($det->precio_unitario, 2) }}</td>
                                                            <td class="text-end fw-bold">S/. {{ number_format($det->subtotal, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="border-top">
                                                    <tr>
                                                        <th colspan="3" class="text-end fw-bold text-dark pt-2">TOTAL</th>
                                                        <th class="text-end fw-bold text-success fs-6 pt-2">S/. {{ number_format($pedido->monto_total, 2) }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            @if($pedido->pagos->isNotEmpty())
                                                <hr class="my-3">
                                                <h6 class="fw-bold text-dark mb-2">
                                                    <i class="bi bi-credit-card me-2 text-warning"></i>Información de Pago
                                                </h6>
                                                @foreach($pedido->pagos as $pago)
                                                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:0.85rem;">
                                                        <span class="text-muted">
                                                            <i class="bi bi-dot me-1"></i>{{ $pago->tipoPago->nombre ?? 'N/A' }}
                                                        </span>
                                                        <span class="fw-bold text-dark">S/. {{ number_format($pago->monto, 2) }}</span>
                                                    </div>
                                                    @if($pago->monto_recibido)
                                                        <div class="d-flex justify-content-between align-items-center mb-1 ps-4" style="font-size:0.85rem;">
                                                            <span class="text-muted">Recibido</span>
                                                            <span class="fw-bold text-dark">S/. {{ number_format($pago->monto_recibido, 2) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mb-1 ps-4" style="font-size:0.85rem;">
                                                            <span class="text-muted">Vuelto</span>
                                                            <span class="fw-bold text-success">S/. {{ number_format($pago->monto_recibido - $pago->monto, 2) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex flex-column gap-3">
                                                @if($pedido->comprobante)
                                                    <div class="bg-white rounded-3 p-3 border">
                                                        <h6 class="fw-bold text-dark mb-2">
                                                            <i class="bi bi-receipt me-2 text-warning"></i>Comprobante
                                                        </h6>
                                                        <div style="font-size:0.85rem;">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted">Tipo</span>
                                                                <span class="fw-bold text-uppercase">{{ $pedido->comprobante->tipo_comprobante }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted">Serie</span>
                                                                <span class="fw-bold">{{ $pedido->comprobante->serie }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted">Número</span>
                                                                <span class="fw-bold">{{ $pedido->comprobante->numero_correlativo }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="text-muted">Emisión</span>
                                                                <span class="fw-bold">{{ \Carbon\Carbon::parse($pedido->comprobante->fecha_emision)->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($pedido->referencia_entrega)
                                                    <div class="bg-white rounded-3 p-3 border">
                                                        <h6 class="fw-bold text-dark mb-2">
                                                            <i class="bi bi-pin-map me-2 text-warning"></i>Referencia de Entrega
                                                        </h6>
                                                        <p class="mb-0 text-muted" style="font-size:0.85rem;">{{ $pedido->referencia_entrega }}</p>
                                                    </div>
                                                @endif

                                                <div class="bg-white rounded-3 p-3 border">
                                                    <h6 class="fw-bold text-dark mb-2">
                                                        <i class="bi bi-person me-2 text-warning"></i>Registrado por
                                                    </h6>
                                                    <p class="mb-0" style="font-size:0.85rem;">
                                                        @if($pedido->recepcionista)
                                                            <span class="fw-bold">{{ $pedido->recepcionista->nombre_completo }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </p>
                                                </div>

                                                @if($pedido->motorizado)
                                                    <div class="bg-white rounded-3 p-3 border">
                                                        <h6 class="fw-bold text-dark mb-2">
                                                            <i class="bi bi-truck me-2 text-warning"></i>Motorizado Asignado
                                                        </h6>
                                                        <p class="mb-0" style="font-size:0.85rem;">
                                                            <span class="fw-bold">{{ $pedido->motorizado->nombre_completo }}</span>
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="detalle-tiempos d-none" id="tiempos-{{ $pedido->id }}">
                            <td colspan="9" class="p-0">
                                <div class="p-4 bg-dark text-white rounded-bottom">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-clock-history me-2 text-warning"></i>Línea de Tiempo de la Orden
                                    </h6>
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-warning"></div>
                                            <div class="timeline-content">
                                                <strong>Recepción del Pedido</strong>
                                                <div class="small text-white-50">
                                                    {{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($pedido->fecha_salida)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-info"></div>
                                                <div class="timeline-content">
                                                    <strong>Salida a Reparto</strong>
                                                    <div class="small text-white-50">
                                                        {{ \Carbon\Carbon::parse($pedido->fecha_salida)->format('d/m/Y h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if(in_array($pedido->estado, ['en_camino', 'entregado']))
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-primary"></div>
                                                <div class="timeline-content">
                                                    <strong>En Camino</strong>
                                                    <div class="small text-white-50">
                                                        @if($pedido->fecha_salida)
                                                            Tiempo estimado de tránsito
                                                        @else
                                                            Pendiente de asignación de salida
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($pedido->fecha_entrega)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-success"></div>
                                                <div class="timeline-content">
                                                    <strong>Entregado al Cliente</strong>
                                                    <div class="small text-white-50">
                                                        {{ \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($pedido->estado == 'cancelado')
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-danger"></div>
                                                <div class="timeline-content">
                                                    <strong>Cancelado</strong>
                                                    <div class="small text-white-50">Pedido cancelado</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <div class="fs-1 mb-2">📋</div>
                                <p class="fw-bold">No se encontraron pedidos para los filtros seleccionados.</p>
                                <p class="small">Intenta cambiar los filtros o seleccionar otra fecha.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pedidos->hasPages())
            <div class="px-4 py-3 border-top d-flex justify-content-center">
                {{ $pedidos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<style>
    .table-row-click:hover {
        background-color: rgba(245, 158, 11, 0.05);
    }
    .detalle-fila td {
        animation: slideDown 0.25s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: rgba(255,255,255,0.2);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 20px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -24px;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #fff;
        z-index: 1;
    }
    .timeline-content strong {
        display: block;
        font-size: 0.9rem;
    }
    .timeline-content .small {
        font-size: 0.8rem;
    }
    @media print {
        .top-header, .sidebar, form, .btn-group, .pagination { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .app-main { overflow: visible !important; }
        .detalle-fila { display: table-row !important; }
    }
</style>

<script>
    document.querySelectorAll('.table-row-click').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.btn-group')) return;
            const targetId = this.dataset.target;
            const detalle = document.getElementById(targetId);
            const icon = document.getElementById('icon-' + targetId.replace('detalle-', ''));
            if (detalle) {
                detalle.classList.toggle('d-none');
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');
            }
        });
    });

    function toggleTiempos(id, btn) {
        const el = document.getElementById(id);
        el.classList.toggle('d-none');
        btn.classList.toggle('active');
        btn.classList.toggle('border-warning');
    }

    function imprimirTicket(codigo) {
        const url = '{{ route("recepcionista.historial") }}?codigo=' + codigo;
        window.open(url, '_blank');
    }

    function verRuta(codigo) {
        window.location.href = '/recepcionista/asignar?codigo=' + codigo;
    }
</script>
@endsection
