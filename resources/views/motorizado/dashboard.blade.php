@extends('layouts.motorizado')

@section('titulo', 'Dashboard')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-speedometer2 text-warning me-2"></i> Dashboard</h3>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Asignados</small>
                    <h3 class="fw-bold text-warning mb-0">{{ $asignados }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">En Ruta</small>
                    <h3 class="fw-bold text-info mb-0">{{ $enRuta }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Entregados Hoy</small>
                    <h3 class="fw-bold text-success mb-0">{{ $entregadosHoy->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Total Cobrado</small>
                    <h4 class="fw-bold text-primary mb-0">S/ {{ number_format($totalCobrado, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                        <i class="bi bi-cash text-success"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.65rem;">EFECTIVO</small>
                        <span class="fw-bold fs-6">S/ {{ number_format($totalEfectivo, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                        <i class="bi bi-phone text-info"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.65rem;">YAPE</small>
                        <span class="fw-bold fs-6">S/ {{ number_format($totalYape, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                        <i class="bi bi-phone text-primary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.65rem;">PLIN</small>
                        <span class="fw-bold fs-6">S/ {{ number_format($totalPlin, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                        <i class="bi bi-credit-card text-secondary"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size:0.65rem;">TARJETA</small>
                        <span class="fw-bold fs-6">S/ {{ number_format($totalTarjeta, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-task me-2"></i> Pendientes</h6>
            <a href="{{ route('motorizado.ruta') }}" class="btn btn-sm btn-outline-warning fw-semibold">Ver todos</a>
        </div>
        <div class="card-body px-0 pt-0">
            @if($pendientes->count())
            <ul class="list-group list-group-flush">
                @foreach($pendientes as $p)
                <li class="list-group-item border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                        <span class="fw-semibold small">{{ $p->codigo_seguimiento }}</span>
                        <span class="text-muted small ms-2">{{ $p->cliente->nombre_completo }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $p->estado === 'en_ruta' ? 'bg-info' : 'bg-warning text-dark' }} fw-normal">{{ $p->estado === 'en_ruta' ? 'En ruta' : 'Asignado' }}</span>
                        <span class="fw-bold small text-dark">S/ {{ number_format($p->monto_total, 2) }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-center text-muted py-3 mb-0"><i class="bi bi-emoji-smile me-2"></i>Sin pedidos pendientes</p>
            @endif
        </div>
    </div>
</div>
@endsection
