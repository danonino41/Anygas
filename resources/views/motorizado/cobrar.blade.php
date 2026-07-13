@extends('layouts.motorizado')

@section('titulo', 'Cobrar Pedido')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-cash-coin text-success me-2"></i> Cobrar Pedido</h4>
            <p class="text-secondary small mb-0">{{ $pedido->codigo_seguimiento }}</p>
        </div>
        <a href="{{ route('motorizado.ruta') }}" class="btn btn-outline-secondary btn-sm fw-semibold"><i class="bi bi-arrow-left me-1"></i> Volver</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small">Cliente</span>
                <span class="fw-semibold">{{ $pedido->cliente->nombre_completo }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small">Dirección</span>
                <span class="small text-end" style="max-width:60%;">{{ $pedido->direccion_entrega ?? '—' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small">Teléfono</span>
                <span class="small">{{ $pedido->cliente->telefono ?? '—' }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('motorizado.ruta.cobrar.guardar', $pedido->id) }}" method="POST" id="form-cobro">
        @csrf

        {{-- Productos entregados --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-header bg-transparent border-bottom-0 pt-3 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i> Productos entregados</h6>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-responsive">
                    <table class="table table-sm small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Producto</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-center">Vacíos recibidos</th>
                                <th class="text-center">Pendientes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $d)
                            @php
                                $maxDev = $d->cantidad - $d->envases_devueltos;
                            @endphp
                            <tr>
                                <td class="ps-3 fw-medium">{{ $d->producto->nombre ?? 'Producto' }} @if($d->producto->marca) <small class="text-muted">({{ $d->producto->marca }})</small> @endif</td>
                                <td class="text-center">{{ $d->cantidad }}</td>
                                <td class="text-center">
                                    <input type="hidden" name="detalles[{{ $d->id }}][producto_id]" value="{{ $d->producto_id }}">
                                    <input type="hidden" name="detalles[{{ $d->id }}][cantidad]" value="{{ $d->cantidad }}">
                                    <input type="number" name="detalles[{{ $d->id }}][envases_devueltos]"
                                           class="form-control form-control-sm text-center envase-input"
                                           style="width:80px;margin:0 auto;"
                                           min="0" max="{{ $d->cantidad }}"
                                           value="{{ $d->cantidad }}"
                                           data-cantidad="{{ $d->cantidad }}">
                                </td>
                                <td class="text-center envase-pendiente fw-bold text-danger">0</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="alerta-envases" class="alert alert-warning d-none rounded-0 mb-0 small text-center">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <span id="texto-alerta-envases"></span>
                </div>
            </div>
        </div>

        {{-- Pagos --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-header bg-transparent border-bottom-0 pt-3 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2"></i> Desglose de pago</h6>
            </div>
            <div class="card-body px-3 pt-0">

                <div class="border rounded-3 p-2 mb-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="fw-semibold mb-0 small"><i class="bi bi-cash me-1"></i> Efectivo</label>
                        <div class="input-group input-group-sm" style="width:150px;">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.10" name="pagos[1][monto]" id="monto-efectivo" class="form-control pago-input" value="0.00">
                        </div>
                    </div>
                    <div id="seccion-vuelto" class="d-none mt-2 ps-2 border-start border-3 border-warning">
                        <div class="row g-1">
                            <div class="col-6">
                                <small class="text-muted">Recibido</small>
                                <input type="number" step="0.10" name="pagos[1][monto_recibido]" id="monto-recibido" class="form-control form-control-sm" placeholder="S/ 0.00">
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Vuelto</small>
                                <input type="text" id="monto-vuelto" class="form-control form-control-sm bg-light fw-bold text-danger" readonly value="S/ 0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 p-2 mb-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="fw-semibold mb-0 small"><i class="bi bi-phone me-1 text-info"></i> Yape</label>
                        <div class="input-group input-group-sm" style="width:150px;">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.10" name="pagos[3][monto]" id="monto-yape" class="form-control pago-input" value="0.00">
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 p-2 mb-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="fw-semibold mb-0 small"><i class="bi bi-phone me-1 text-primary"></i> Plin</label>
                        <div class="input-group input-group-sm" style="width:150px;">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.10" name="pagos[4][monto]" id="monto-plin" class="form-control pago-input" value="0.00">
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 p-2 mb-2 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="fw-semibold mb-0 small"><i class="bi bi-credit-card me-1 text-secondary"></i> Tarjeta</label>
                        <div class="input-group input-group-sm" style="width:150px;">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.10" name="pagos[2][monto]" id="monto-tarjeta" class="form-control pago-input" value="0.00">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Resumen --}}
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body px-3 py-2">
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Ingresado:</span>
                    <span id="suma-ingresada" class="fw-bold">S/ 0.00</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Pendiente:</span>
                    <span id="diferencia-pendiente" class="fw-bold text-danger">S/ {{ number_format($pedido->monto_total, 2) }}</span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm rounded-4" id="btn-registrar" disabled>
            <i class="bi bi-check-circle me-2"></i> Confirmar Entrega y Cobro
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const total = {{ $pedido->monto_total }};
    const inputs = document.querySelectorAll('.pago-input');
    const sumaText = document.getElementById('suma-ingresada');
    const diffText = document.getElementById('diferencia-pendiente');
    const btn = document.getElementById('btn-registrar');
    const efec = document.getElementById('monto-efectivo');
    const secVuelto = document.getElementById('seccion-vuelto');
    const recibido = document.getElementById('monto-recibido');
    const vueltoText = document.getElementById('monto-vuelto');
    const alertaEnvases = document.getElementById('alerta-envases');
    const textoAlerta = document.getElementById('texto-alerta-envases');

    const envaseInputs = document.querySelectorAll('.envase-input');

    function actualizarEnvases() {
        let totalPendientes = 0;
        envaseInputs.forEach(function (inp) {
            const fila = inp.closest('tr');
            const pendienteTd = fila.querySelector('.envase-pendiente');
            const cantidad = parseInt(inp.dataset.cantidad) || 0;
            const devueltos = parseInt(inp.value) || 0;
            const clamped = Math.min(Math.max(devueltos, 0), cantidad);
            if (clamped !== devueltos) inp.value = clamped;
            const pendientes = cantidad - clamped;
            pendienteTd.textContent = pendientes;
            totalPendientes += pendientes;
        });

        if (totalPendientes > 0) {
            alertaEnvases.classList.remove('d-none');
            textoAlerta.textContent = totalPendientes + ' balón(es) pendiente(s) de recojo. Se le notificará al cliente.';
        } else {
            alertaEnvases.classList.add('d-none');
        }
    }

    envaseInputs.forEach(function (inp) {
        inp.addEventListener('input', actualizarEnvases);
    });

    function calcPago() {
        let s = 0;
        inputs.forEach(function (i) { let v = parseFloat(i.value) || 0; if (v < 0) v = 0; s += v; });

        const e = parseFloat(efec.value) || 0;
        if (e > 0) {
            secVuelto.classList.remove('d-none');
            const r = parseFloat(recibido.value) || 0;
            vueltoText.value = r > e ? 'S/ ' + (r - e).toFixed(2) : 'S/ 0.00';
        } else {
            secVuelto.classList.add('d-none');
            recibido.value = '';
            vueltoText.value = 'S/ 0.00';
        }

        sumaText.textContent = 'S/ ' + s.toFixed(2);
        const d = total - s;
        if (Math.abs(d) < 0.01) {
            diffText.textContent = 'S/ 0.00';
            diffText.className = 'fw-bold text-success';
            btn.removeAttribute('disabled');
        } else {
            diffText.textContent = 'S/ ' + d.toFixed(2);
            diffText.className = 'fw-bold text-danger';
            btn.setAttribute('disabled', 'disabled');
        }
    }

    inputs.forEach(function (i) {
        i.addEventListener('input', calcPago);
        i.addEventListener('focus', function () { if (this.value === '0.00') this.value = ''; });
        i.addEventListener('blur', function () { if (this.value === '') this.value = '0.00'; calcPago(); });
    });

    if (recibido) recibido.addEventListener('input', calcPago);

    actualizarEnvases();
});
</script>
@endpush
