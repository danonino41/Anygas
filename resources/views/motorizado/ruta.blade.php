@extends('layouts.motorizado')

@section('titulo', 'Panel de Ruta')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-map text-warning me-2"></i> Panel de Ruta</h3>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} &mdash; Mis entregas del turno</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('cobro_exitoso'))
        <div class="alert alert-success rounded-4 border-0 mb-3 p-3" style="background:#d1fae5;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    <strong>¡Cobro registrado!</strong>
                    <span class="text-muted small ms-2">{{ session('codigo') }} — S/ {{ session('monto_total') }}</span>
                    @if(session('envases_pendientes'))
                    <span class="badge bg-warning text-dark ms-2">{{ session('envases_pendientes') }} vacío(s) pendiente(s)</span>
                    @endif
                </div>
                <div class="d-flex gap-1">
                    @if(session('envases_pendientes'))
                    <button type="button" class="btn btn-warning btn-sm fw-bold rounded-pill px-3" id="btn-enviar-whatsapp-envases">
                        <i class="bi bi-whatsapp me-1"></i> Notificar recojo
                    </button>
                    @endif
                    <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-3" id="btn-enviar-whatsapp">
                        <i class="bi bi-whatsapp me-1"></i> Enviar comprobante
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Asignados</small>
                    <h3 class="fw-bold text-warning mb-0">{{ $totalAsignados }}</h3>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info bg-white">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">En Ruta</small>
                    <h3 class="fw-bold text-info mb-0">{{ $totalEnRuta }}</h3>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success bg-white">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Entregados Hoy</small>
                    <h3 class="fw-bold text-success mb-0">{{ $entregadosHoy }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if($asignados->count())
        <div class="row g-3">
            @foreach($asignados as $p)
            @php
                $estadoCls = $p->estado === 'en_ruta' ? 'border-info' : 'border-warning';
                $totalProd = $p->detalles->sum('cantidad');
                $primerProducto = $p->detalles->first()?->producto;
            @endphp
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 {{ $estadoCls }} bg-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $p->codigo_seguimiento ?? '#' . $p->id }}</h6>
                                <small class="text-muted">{{ $p->cliente->nombre_completo }}</small>
                            </div>
                            <span class="badge {{ $p->estado === 'en_ruta' ? 'bg-info' : 'bg-warning text-dark' }} fw-normal px-2">
                                {{ $p->estado === 'en_ruta' ? 'En ruta' : 'Asignado' }}
                            </span>
                        </div>

                        <div class="small mb-2">
                            <div><i class="bi bi-telephone me-1 text-secondary"></i> {{ $p->cliente->telefono ?? '—' }}</div>
                            <div><i class="bi bi-geo-alt me-1 text-secondary"></i> {{ $p->direccion_entrega ?? '—' }}</div>
                            <div><i class="bi bi-box-seam me-1 text-secondary"></i> {{ $totalProd }} productos ({{ $p->detalles->count() }} ítems)</div>
                            @if($primerProducto)
                            <div><span class="badge bg-light text-dark border fw-normal mt-1">{{ $primerProducto->nombre }} {{ $primerProducto->marca ? '('.$primerProducto->marca.')' : '' }}</span></div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                            <div>
                                <span class="fw-bold text-dark">S/ {{ number_format($p->monto_total, 2) }}</span>
                                @if($p->pagos->count())
                                <div class="small text-muted">
                                    @foreach($p->pagos as $pg)
                                    <span class="badge bg-light text-dark border fw-normal me-1">{{ $pg->tipoPago->nombre }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="d-flex gap-1">
                                @if($p->estado === 'asignado')
                                <form action="{{ route('motorizado.ruta.iniciar', $p->id) }}" method="POST" class="d-inline form-iniciar-ruta">
                                    @csrf
                                    <button class="btn btn-warning btn-sm fw-semibold text-dark">
                                        <i class="bi bi-play-fill me-1"></i> Iniciar
                                    </button>
                                </form>
                                @endif
                                @if($p->estado === 'en_ruta')
                                <form action="{{ route('motorizado.ruta.notificar', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-info btn-sm fw-semibold text-white">
                                        <i class="bi bi-bell me-1"></i> Llegada
                                    </button>
                                </form>
                                <a href="{{ route('motorizado.ruta.cobrar', $p->id) }}" class="btn btn-success btn-sm fw-semibold">
                                    <i class="bi bi-cash-coin me-1"></i> Cobrar
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="bi bi-emoji-smile fs-1 d-block mb-2 text-success"></i>
                <h5 class="fw-bold text-dark">¡Sin pedidos pendientes!</h5>
                <p class="text-muted small mb-0">No tienes pedidos asignados en este momento.</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.form-iniciar-ruta').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            Swal.fire({
                title: '¿Iniciar ruta?',
                text: 'Marcarás este pedido como "En camino" hacia el cliente.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, iniciar',
                cancelButtonText: 'Cancelar'
            }).then(r => { if (r.isConfirmed) form.submit(); });
        });
    });

    @if(session('cobro_exitoso'))
    document.getElementById('btn-enviar-whatsapp')?.addEventListener('click', () => {
        const telefono = '{{ session('cliente_telefono') }}';
        const nombre = '{{ session('cliente_nombre') }}';
        const codigo = '{{ session('codigo') }}';
        const monto = '{{ session('monto_total') }}';
        const mensaje = 'Hola ' + nombre + ', gracias por tu compra en AnyGas. Aquí el detalle de tu pedido ' + codigo + ' por S/ ' + monto + '. ¡Vuelve pronto!';
        const url = 'https://wa.me/51' + telefono.replace(/[^0-9]/g, '') + '?text=' + encodeURIComponent(mensaje);
        window.open(url, '_blank');
    });
    @if(session('envases_pendientes'))
    document.getElementById('btn-enviar-whatsapp-envases')?.addEventListener('click', () => {
        const telefono = '{{ session('cliente_telefono') }}';
        const mensaje = '{{ session('mensaje_whatsapp_envases') }}';
        const url = 'https://wa.me/51' + telefono.replace(/[^0-9]/g, '') + '?text=' + encodeURIComponent(mensaje);
        window.open(url, '_blank');
    });
    @endif
    @endif
});
</script>
@endpush
