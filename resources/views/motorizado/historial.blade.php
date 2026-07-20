@extends('layouts.motorizado')

@section('titulo', 'Historial de Reparto')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-warning me-2"></i> Historial de Reparto</h3>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} &mdash; Mi rendición del turno</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Total Vendido</small>
                    <h4 class="fw-bold text-success mb-0">S/ {{ number_format($totalVendido, 2) }}</h4>
                    <small class="text-secondary">{{ $entregados->count() }} entregas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Efectivo</small>
                    <h4 class="fw-bold text-primary mb-0">S/ {{ number_format($totalEfectivo, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Yape / Plin</small>
                    <h4 class="fw-bold text-info mb-0">S/ {{ number_format($totalYape + $totalPlin, 2) }}</h4>
                    <small class="text-secondary">Y: S/{{ number_format($totalYape, 2) }} &middot; P: S/{{ number_format($totalPlin, 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Envases</small>
                    <h4 class="fw-bold text-warning mb-0">{{ $totalEnvasesRecolectados }}</h4>
                    <small class="text-secondary">balones recolectados</small>
                </div>
            </div>
        </div>
    </div>

    @if($entregados->count() || $cancelados->count())
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-check me-2"></i> Pedidos del turno</h6>
            <div>
                <span class="badge bg-success fw-normal me-1">{{ $entregados->count() }} entregados</span>
                <span class="badge bg-danger fw-normal">{{ $cancelados->count() }} cancelados</span>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-dark text-nowrap">
                        <tr>
                            <th class="ps-3">Código</th>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th class="text-center">Prod.</th>
                            <th class="text-end">Total</th>
                            <th>Pagos</th>
                            <th>Estado</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $p)
                        <tr>
                            <td class="ps-3 fw-medium text-nowrap">{{ $p->codigo_seguimiento ?? '#'.$p->id }}</td>
                            <td class="fw-medium">{{ $p->cliente->nombre_completo }}</td>
                            <td class="text-secondary" style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->direccion_entrega ?? '—' }}</td>
                            <td class="text-center">{{ $p->detalles->sum('cantidad') }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($p->monto_total, 2) }}</td>
                            <td>
                                @forelse($p->pagos as $pg)
                                <span class="badge bg-light text-dark border fw-normal me-1" title="S/ {{ number_format($pg->monto, 2) }}">
                                    {{ $pg->tipoPago->nombre ?? '—' }}
                                    <span class="text-success">S/{{ number_format($pg->monto, 2) }}</span>
                                </span>
                                @empty
                                <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge {{ $p->estado === 'entregado' ? 'bg-success' : 'bg-danger' }} fw-normal px-2">
                                    {{ $p->estado === 'entregado' ? 'Entregado' : 'Cancelado' }}
                                </span>
                            </td>
                            <td class="text-nowrap text-secondary">
                                {{ $p->fecha_entrega ? \Carbon\Carbon::parse($p->fecha_entrega)->format('H:i') : ($p->fecha_registro ? \Carbon\Carbon::parse($p->fecha_registro)->format('H:i') : '—') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-3 pb-3">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
            <h5 class="fw-bold text-dark">Sin movimientos hoy</h5>
            <p class="text-muted small mb-0">Aún no has registrado entregas ni cancelaciones en el turno de hoy.</p>
        </div>
    </div>
    @endif
</div>
@endsection
