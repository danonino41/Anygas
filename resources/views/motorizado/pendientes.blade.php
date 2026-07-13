@extends('layouts.motorizado')

@section('titulo', 'Pendientes de Recojo')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam text-warning me-2"></i> Pendientes de Recojo</h3>
            <p class="text-secondary small mb-0">Pedidos con balones vacíos por recoger</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Pedidos Pendientes</small>
                    <h3 class="fw-bold text-warning mb-0">{{ $totalPedidos }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger bg-white h-100">
                <div class="card-body py-3 px-3">
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.65rem;">Vacíos Pendientes</small>
                    <h3 class="fw-bold text-danger mb-0">{{ $totalEnvasesPendientes }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if($pedidos->count())
        <div class="row g-3">
            @foreach($pedidos as $p)
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-warning bg-white h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $p->codigo_seguimiento ?? '#'.$p->id }}</h6>
                                <small class="text-muted">{{ $p->cliente->nombre_completo }}</small>
                            </div>
                            <span class="badge bg-warning text-dark fw-normal px-2">{{ $p->envases_pendientes }} pendiente(s)</span>
                        </div>

                        <div class="small mb-2">
                            <div><i class="bi bi-telephone me-1 text-secondary"></i> {{ $p->cliente->telefono ?? '—' }}</div>
                            <div><i class="bi bi-geo-alt me-1 text-secondary"></i> {{ $p->direccion_entrega ?? '—' }}</div>
                            <div><i class="bi bi-calendar me-1 text-secondary"></i> Entregado {{ $p->fecha_entrega ? \Carbon\Carbon::parse($p->fecha_entrega)->format('d/m H:i') : '—' }}</div>
                        </div>

                        <div class="table-responsive mt-2">
                            <table class="table table-sm small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Entregado</th>
                                        <th class="text-center">Recibido</th>
                                        <th class="text-center">Pendiente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p->detalles as $d)
                                    @php $pend = $d->cantidad - $d->envases_devueltos; @endphp
                                    @if($pend > 0)
                                    <tr>
                                        <td class="fw-medium">{{ $d->producto->nombre ?? 'Producto' }} @if($d->producto->marca) <small class="text-muted">({{ $d->producto->marca }})</small> @endif</td>
                                        <td class="text-center">{{ $d->cantidad }}</td>
                                        <td class="text-center">{{ $d->envases_devueltos }}</td>
                                        <td class="text-center fw-bold text-danger">{{ $pend }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-primary btn-sm fw-semibold flex-fill btn-recoger"
                                    data-pedido-id="{{ $p->id }}"
                                    data-codigo="{{ $p->codigo_seguimiento }}"
                                    data-cliente="{{ $p->cliente->nombre_completo }}"
                                    data-detalles='@json($p->detalles->where('cantidad', '>', 0)->values(), JSON_HEX_APOS)'>
                                <i class="bi bi-box me-1"></i> Recoger Vacíos
                            </button>
                            @if($p->cliente->telefono)
                            @php
                                $mensaje = "Hola {$p->cliente->nombre_completo}, recuerda que tienes {$p->envases_pendientes} balón(es) vacío(s) pendiente(s) de tu pedido {$p->codigo_seguimiento}. Pasaremos pronto a recogerlo(s). ¡Gracias!";
                                $url = 'https://wa.me/51' . preg_replace('/[^0-9]/', '', $p->cliente->telefono) . '?text=' . urlencode($mensaje);
                            @endphp
                            <a href="{{ $url }}" target="_blank" class="btn btn-success btn-sm fw-semibold flex-fill">
                                <i class="bi bi-whatsapp me-1"></i> Recordar
                            </a>
                            @endif
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
                <h5 class="fw-bold text-dark">¡Todos los vacíos han sido recogidos!</h5>
                <p class="text-muted small mb-0">No hay pedidos con balones vacíos pendientes de recojo.</p>
            </div>
        </div>
    @endif
</div>

{{-- Modal Recoger Vacíos --}}
<div class="modal fade" id="modalRecoger" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-box me-2"></i> Recoger Vacíos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRecoger" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <span class="text-muted small">Cliente:</span>
                        <span class="fw-semibold" id="recogerCliente"></span><br>
                        <span class="text-muted small">Pedido:</span>
                        <span class="fw-semibold" id="recogerCodigo"></span>
                    </div>

                    <div id="recogerDetalles">
                        {{-- JS llena esto --}}
                    </div>

                    <div class="alert alert-info small mb-0 py-2">
                        <i class="bi bi-info-circle me-1"></i> Confirma cuántos balones vacíos estás recogiendo hoy.
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4"><i class="bi bi-check-lg me-1"></i> Confirmar Recojo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('modalRecoger');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('formRecoger');
    const container = document.getElementById('recogerDetalles');
    const clienteSpan = document.getElementById('recogerCliente');
    const codigoSpan = document.getElementById('recogerCodigo');

    document.querySelectorAll('.btn-recoger').forEach(btn => {
        btn.addEventListener('click', () => {
            const pedidoId = btn.dataset.pedidoId;
            const codigo = btn.dataset.codigo;
            const cliente = btn.dataset.cliente;
            const detalles = JSON.parse(btn.dataset.detalles || '[]');

            form.action = '/motorizado/pendientes/' + pedidoId + '/recoger';
            clienteSpan.textContent = cliente;
            codigoSpan.textContent = codigo;

            let html = '<table class="table table-sm small mb-3"><thead class="table-light"><tr><th>Producto</th><th class="text-center">Pendiente</th><th class="text-center">Recogiendo</th></tr></thead><tbody>';
            detalles.forEach(d => {
                const pend = d.cantidad - (d.envases_devueltos || 0);
                if (pend <= 0) return;
                html += `<tr>
                    <td class="fw-medium">${d.producto ? d.producto.nombre : 'Producto'}${d.producto && d.producto.marca ? ' <small class="text-muted">(' + d.producto.marca + ')</small>' : ''}</td>
                    <td class="text-center fw-bold text-danger">${pend}</td>
                    <td class="text-center" style="width:100px;">
                        <input type="number" name="detalles[${d.id}][recogido]" class="form-control form-control-sm text-center recogido-input" min="0" max="${pend}" value="${pend}">
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;

            container.querySelectorAll('.recogido-input').forEach(inp => {
                inp.addEventListener('input', function () {
                    const max = parseInt(this.max) || 0;
                    let val = parseInt(this.value) || 0;
                    if (val > max) this.value = max;
                    if (val < 0) this.value = 0;
                });
            });

            modal.show();
        });
    });
});
</script>
@endpush
