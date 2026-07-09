@extends('layouts.recepcionista')

@section('titulo', 'Despacho y Asignación de Rutas')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-truck text-warning"></i> Despacho y Logística de Última Milla
            </h2>
            <p class="text-secondary small mb-0">
                <i class="bi bi-info-circle me-1"></i> Asigna conductores a los pedidos entrantes y monitorea las entregas en calle.
            </p>
        </div>
    </div>

    @if(session('exito'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('exito') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <ul class="nav nav-tabs border-bottom mb-4" id="tabLogistica" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark fw-semibold active position-relative px-4" id="por-asignar-tab" data-bs-toggle="tab" data-bs-target="#por-asignar" type="button" role="tab">
                <i class="bi bi-inbox me-1"></i> Por Asignar
                <span class="badge bg-warning text-dark rounded-pill ms-2" style="font-size: 0.75rem;">{{ $pendientes->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-dark fw-semibold px-4" id="en-ruta-tab" data-bs-toggle="tab" data-bs-target="#en-ruta" type="button" role="tab">
                <i class="bi bi-truck me-1"></i> Flota en Calle
                <span class="badge bg-secondary text-white rounded-pill ms-2" style="font-size: 0.75rem;">{{ $enRuta->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="contenidoTabLogistica">

        <div class="tab-pane fade show active" id="por-asignar" role="tabpanel">

            <div class="row g-4 mb-4" id="motorizadosPanel">
                @forelse($motorizados as $mot)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 motorizado-card {{ $mot->carga_actual > 0 ? 'border-start border-warning border-4' : '' }}"
                             data-motorizado-id="{{ $mot->id }}"
                             ondragover="event.preventDefault()"
                             ondrop="asignarDrop(event, {{ $mot->id }}, '{{ $mot->nombre_completo }}')">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $mot->nombre_completo }}</h6>
                                        <span class="small {{ $mot->carga_actual > 0 ? 'text-warning' : 'text-success' }}">
                                            <i class="bi bi-{{ $mot->carga_actual > 0 ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                                            {{ $mot->carga_actual > 0 ? "{$mot->carga_actual} pedido(s) en ruta" : 'Disponible' }}
                                        </span>
                                    </div>
                                </div>

                                @if($mot->pedidos_asignados->isNotEmpty())
                                    <div class="bg-light rounded-3 p-2" style="font-size:0.8rem;">
                                        <small class="text-secondary fw-semibold d-block mb-1">
                                            <i class="bi bi-journal-text me-1"></i>Ruta actual:
                                        </small>
                                        @foreach($mot->pedidos_asignados as $asignado)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark">{{ $asignado->codigo_seguimiento }}</span>
                                                <small class="text-muted">{{ $asignado->cliente->nombres }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted small py-2">
                                        <i class="bi bi-dash-circle me-1"></i>Sin pedidos asignados
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                            <div class="fs-1 mb-3">🏍️</div>
                            <h5 class="fw-bold text-secondary">No hay motorizados activos</h5>
                            <p class="text-muted small">Registra conductores en el panel de administración.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-transparent border-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">
                        <i class="bi bi-inbox me-1"></i> Pedidos Pendientes
                        <span class="badge bg-dark ms-1">{{ $pendientes->count() }}</span>
                    </span>
                    <small class="text-muted">
                        <i class="bi bi-arrow-repeat me-1"></i>Arrastra un pedido al motorizado
                    </small>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-middle mb-0" style="font-size:0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th style="min-width:200px;">Asignar a</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendientes as $pedido)
                                <tr class="pendiente-row {{ $codigoFiltro === $pedido->codigo_seguimiento ? 'table-warning' : '' }}"
                                    draggable="true"
                                    data-pedido-id="{{ $pedido->id }}"
                                    data-codigo="{{ $pedido->codigo_seguimiento }}"
                                    data-cliente="{{ $pedido->cliente->nombres }}"
                                    data-direccion="{{ $pedido->direccion_entrega }}"
                                    ondragstart="asignarDragStart(event, {{ $pedido->id }}, '{{ $pedido->codigo_seguimiento }}')">
                                    <td class="fw-bold text-dark">{{ $pedido->codigo_seguimiento }}</td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('H:i') }}</td>
                                    <td>
                                        <strong class="d-block text-dark">{{ $pedido->cliente->nombres }}</strong>
                                        <small class="text-muted">{{ $pedido->cliente->telefono }}</small>
                                    </td>
                                    <td>
                                        <span class="d-block text-truncate" style="max-width: 200px;" title="{{ $pedido->direccion_entrega }}">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_entrega }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-box me-1"></i>{{ $pedido->detalles->sum('cantidad') }} und.
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">S/. {{ number_format($pedido->monto_total, 2) }}</td>
                                    <td>
                                        <form action="{{ route('recepcionista.asignar.procesar', $pedido->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <select name="motorizado_id" class="form-select form-select-sm" required>
                                                    <option value="" disabled selected>Chofer...</option>
                                                    @foreach($motorizados as $mot)
                                                        <option value="{{ $mot->id }}">{{ $mot->nombre_completo }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-warning fw-bold text-dark shadow-sm">
                                                    <i class="bi bi-send me-1"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="fs-1 mb-2">✅</div>
                                        <h5 class="fw-bold mb-0">¡Todo al día! No hay pedidos pendientes.</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="en-ruta" role="tabpanel">
            <div class="row g-4">
                @forelse($motorizados->filter(fn($m) => $m->carga_actual > 0) as $mot)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-transparent border-0 pt-3 px-3 d-flex align-items-center gap-3">
                                <div class="bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $mot->nombre_completo }}</h6>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-truck me-1"></i>{{ $mot->carga_actual }} pedido(s)
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-3 pt-0">
                                <table class="table table-sm table-borderless mb-0" style="font-size:0.85rem;">
                                    <thead class="text-secondary" style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.5px;">
                                        <tr>
                                            <th>Orden</th>
                                            <th>Cliente</th>
                                            <th>Dirección</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mot->pedidos_asignados as $ruta)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $ruta->codigo_seguimiento }}</td>
                                                <td>{{ $ruta->cliente->nombres }}</td>
                                                <td class="text-truncate" style="max-width:150px;" title="{{ $ruta->direccion_entrega }}">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $ruta->direccion_entrega }}
                                                </td>
                                                <td class="fw-bold">S/. {{ number_format($ruta->monto_total, 2) }}</td>
                                                <td>
                                                    @if($ruta->estado === 'asignado')
                                                        <span class="badge bg-primary text-uppercase" style="font-size:0.65rem;">
                                                            <i class="bi bi-clipboard me-1"></i>Planta
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info text-dark text-uppercase" style="font-size:0.65rem;">
                                                            <i class="bi bi-truck me-1"></i>En Camino
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                            <div class="fs-1 mb-3">🗺️</div>
                            <h5 class="fw-bold mb-0 text-secondary">No hay unidades en calle</h5>
                            <p class="text-muted small mt-1">Asigna pedidos a los motorizados para verlos aquí.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<form id="formAsignarDrop" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="motorizado_id" id="dropMotorizadoId">
</form>

<style>
    .motorizado-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .motorizado-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    .motorizado-card.drag-over {
        border-color: #F59E0B !important;
        background-color: rgba(245, 158, 11, 0.08);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3) !important;
    }
    .pendiente-row {
        cursor: grab;
        transition: opacity 0.2s ease;
    }
    .pendiente-row:active {
        cursor: grabbing;
        opacity: 0.6;
    }
    .pendiente-row.dragging {
        opacity: 0.4;
    }
    .table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    @media (max-width: 767.98px) {
        #motorizadosPanel .col-md-6 { margin-bottom: 0.5rem; }
    }
</style>

<script>
    let pedidoDragId = null;
    let pedidoDragCodigo = null;

    function asignarDragStart(event, pedidoId, codigo) {
        pedidoDragId = pedidoId;
        pedidoDragCodigo = codigo;
        event.dataTransfer.effectAllowed = 'move';
        event.target.classList.add('dragging');

        document.querySelectorAll('.motorizado-card').forEach(c => {
            c.addEventListener('dragenter', function() { this.classList.add('drag-over'); });
            c.addEventListener('dragleave', function() { this.classList.remove('drag-over'); });
        });
    }

    document.addEventListener('dragend', () => {
        document.querySelectorAll('.motorizado-card').forEach(c => c.classList.remove('drag-over'));
        document.querySelectorAll('.pendiente-row').forEach(r => r.classList.remove('dragging'));
    });

    function asignarDrop(event, motorizadoId, nombre) {
        event.preventDefault();
        document.querySelectorAll('.motorizado-card').forEach(c => c.classList.remove('drag-over'));

        if (!pedidoDragId) return;

        Swal.fire({
            title: '¿Asignar pedido?',
            html: `Asignar <strong>${pedidoDragCodigo}</strong> a <strong>${nombre}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Asignar',
            cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Cancelar',
            confirmButtonColor: '#F59E0B',
            cancelButtonColor: '#6B7280',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('formAsignarDrop');
                form.action = `/recepcionista/asignar/${pedidoDragId}`;
                document.getElementById('dropMotorizadoId').value = motorizadoId;
                form.submit();
            }
        });
    }
</script>
@endsection
