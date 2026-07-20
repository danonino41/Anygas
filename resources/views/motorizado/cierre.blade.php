@extends('layouts.motorizado')

@section('titulo', 'Cierre de Turno')

@section('contenido')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-success me-2"></i> Cierre de Turno</h3>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm no-print" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Imprimir
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Total Vendido</small>
                    <h4 class="fw-bold text-success mb-0">S/ {{ number_format($totalVendido, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Total Cobrado</small>
                    <h4 class="fw-bold text-primary mb-0">S/ {{ number_format($totalCobrado, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Entregados</small>
                    <h4 class="fw-bold text-info mb-0">{{ $entregados->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Cancelados</small>
                    <h4 class="fw-bold text-danger mb-0">{{ $cancelados->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Vacíos Recogidos</small>
                    <h4 class="fw-bold text-warning mb-0">{{ $vaciosRecogidos }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-secondary bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Vacíos Pendientes</small>
                    <h4 class="fw-bold text-secondary mb-0">{{ $vaciosPendientes }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack me-2"></i>Cobros por Método</h6>
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td><span class="badge bg-primary fw-normal px-2">Efectivo</span></td>
                                <td class="text-end fw-medium">S/ {{ number_format($totalEfectivo, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info fw-normal px-2">Yape</span></td>
                                <td class="text-end fw-medium">S/ {{ number_format($totalYape, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info fw-normal px-2">Plin</span></td>
                                <td class="text-end fw-medium">S/ {{ number_format($totalPlin, 2) }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary fw-normal px-2">Tarjeta</span></td>
                                <td class="text-end fw-medium">S/ {{ number_format($totalTarjeta, 2) }}</td>
                            </tr>
                            @if($totalOtros > 0)
                            <tr>
                                <td><span class="badge bg-secondary fw-normal px-2">Otros</span></td>
                                <td class="text-end fw-medium">S/ {{ number_format($totalOtros, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td class="fw-bold">Total Cobrado</td>
                                <td class="text-end fw-bold text-success">S/ {{ number_format($totalCobrado, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-boxes me-2"></i>Vacíos (Balones)</h6>
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Recogidos hoy</td>
                                <td class="text-end fw-medium text-success">{{ $vaciosRecogidos }} und.</td>
                            </tr>
                            <tr>
                                <td>Pendientes de recojo</td>
                                <td class="text-end fw-medium text-warning">{{ $vaciosPendientes }} und.</td>
                            </tr>
                            <tr class="table-active">
                                <td class="fw-bold">Total entregados</td>
                                <td class="text-end fw-bold">{{ ($vaciosRecogidos + $vaciosPendientes) }} und.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($entregados->count() || $cancelados->count())
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-check me-2"></i> Detalle de Pedidos</h6>
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
                        @foreach(collect($entregados)->concat($cancelados)->sortByDesc('fecha_entrega') as $p)
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
                                {{ $p->fecha_entrega ? \Carbon\Carbon::parse($p->fecha_entrega)->format('H:i') : \Carbon\Carbon::parse($p->fecha_registro)->format('H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
            <h5 class="fw-bold text-dark">Sin movimientos hoy</h5>
            <p class="text-muted small mb-0">No hay entregas ni cancelaciones registradas en este turno.</p>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .sidebar, nav, header, footer { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        body { background: white !important; }
    }
</style>
@endpush
