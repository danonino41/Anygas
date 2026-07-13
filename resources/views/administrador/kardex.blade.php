@extends('layouts.administrador')

@section('titulo', 'Kárdex de Almacén')

@section('contenido')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-boxes text-warning me-2"></i> Kárdex de Almacén</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} — Balance de inventario de balones de gas</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-arrow-up-circle me-1 text-success"></i> Llenos en Local</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $totalLlenos }}</h2>
                <small class="text-secondary">balones disponibles para despacho</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-secondary border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-arrow-down-circle me-1 text-secondary"></i> Vacíos en Circulación</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $totalVacios }}</h2>
                <small class="text-secondary">balones entregados (pendientes de retorno)</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-danger border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-coin me-1 text-danger"></i> Deuda Total Clientes</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">S/ {{ number_format($totalDeudaClientes, 2) }}</h2>
                <small class="text-secondary">{{ $deudas->count() }} pedidos con adeudo</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-box-seam text-success me-2"></i> Llenos en Local</h5>
                <p class="text-muted small mb-3">Stock actual por presentación</p>

                @forelse($llenosAgrupados as $grupo)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="small">{{ $grupo['presentacion'] }}</strong>
                            <span class="badge bg-success">{{ $grupo['total'] }} und.</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalLlenos > 0 ? ($grupo['total'] / $totalLlenos * 100) : 0 }}%"></div>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            @foreach($grupo['detalles'] as $item)
                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size:0.7rem;">
                                    {{ $item->marca ?? 'Genérico' }}: {{ $item->stock_actual }} und.
                                </span>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-muted small text-center mb-0">No hay balones en stock</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-arrow-return-left text-secondary me-2"></i> Vacíos en Circulación</h5>
                <p class="text-muted small mb-3">Entregados — por presentación</p>

                @forelse($vaciosPorPresentacion as $presentacion => $grupo)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3 bg-light">
                        <span class="small fw-bold">{{ $presentacion }}</span>
                        <span class="badge bg-secondary">{{ $grupo['total'] }} und.</span>
                    </div>
                @empty
                    <p class="text-muted small text-center mb-0">Sin entregas registradas</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-coin text-danger me-2"></i> Deudas de Clientes</h5>
                <p class="text-muted small mb-3">Pedidos con pago pendiente</p>

                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse($deudas as $item)
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light mb-2">
                            <div class="min-w-0">
                                <strong class="d-block text-dark small text-truncate">{{ $item['cliente']->nombres ?? '—' }}</strong>
                                <small class="text-muted" style="font-size:0.7rem;">{{ $item['pedido']->codigo_seguimiento }}</small>
                            </div>
                            <span class="badge bg-danger flex-shrink-0">S/ {{ number_format($item['adeudo'], 2) }}</span>
                        </div>
                    @empty
                        <p class="text-muted small text-center mb-0">Sin deudas pendientes</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
