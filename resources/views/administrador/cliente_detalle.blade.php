@extends('layouts.administrador')

@section('titulo', $cliente->nombre_completo)

@section('contenido')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.clientes') }}" class="text-decoration-none text-secondary mb-1 d-inline-block small">
                <i class="bi bi-arrow-left me-1"></i> Directorio de Clientes
            </a>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-person-vcard text-warning me-2"></i> {{ $cliente->nombre_completo }}</h3>
        </div>
        <div class="d-flex gap-2">
            @php $waPhone = preg_replace('/[^0-9]/', '', $cliente->telefono ?? ''); @endphp
            @if($waPhone)
            <a href="https://wa.me/51{{ $waPhone }}" target="_blank" class="btn btn-success btn-sm fw-semibold">
                <i class="bi bi-whatsapp me-1"></i> WhatsApp
            </a>
            @endif
            <button class="btn btn-dark btn-sm fw-semibold" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
        </div>
    </div>

    {{-- Info cliente + Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Información General</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-secondary" style="width:140px;">Documento</td>
                            <td class="fw-medium">{{ $cliente->documento_identidad ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Teléfono</td>
                            <td class="fw-medium"><a href="tel:{{ $cliente->telefono }}" class="text-decoration-none">{{ $cliente->telefono ?? '—' }}</a></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Correo</td>
                            <td class="fw-medium">{{ $cliente->correo ? '<a href="mailto:'.$cliente->correo.'" class="text-decoration-none">'.$cliente->correo.'</a>' : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Dirección</td>
                            <td class="fw-medium">{{ $cliente->direccion_principal ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Referencia</td>
                            <td class="text-secondary">{{ $cliente->referencia_direccion ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Estado</td>
                            <td><span class="badge {{ $cliente->estado_badge }} fw-normal px-2">{{ $cliente->estado_label }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart me-2 text-success"></i>Estadísticas</h6>
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="rounded-3 p-2" style="background:#eff6ff;">
                                <small class="text-muted fw-semibold d-block" style="font-size:0.65rem;">Pedidos</small>
                                <h4 class="fw-bold text-primary mb-0">{{ $cliente->cantidad_pedidos }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-2" style="background:#ecfdf5;">
                                <small class="text-muted fw-semibold d-block" style="font-size:0.65rem;">Total Gastado</small>
                                <h4 class="fw-bold text-success mb-0">S/{{ number_format($cliente->total_gastado, 0) }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="rounded-3 p-2" style="background:#fffbeb;">
                                <small class="text-muted fw-semibold d-block" style="font-size:0.65rem;">Deuda Env.</small>
                                <h4 class="fw-bold text-warning mb-0">{{ $cliente->deuda_envases }}</h4>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-borderless mt-3 mb-0 small">
                        <tr>
                            <td class="text-secondary" style="width:140px;">Último pedido</td>
                            <td class="fw-medium">{{ $cliente->ultimo_pedido ? \Carbon\Carbon::parse($cliente->ultimo_pedido->fecha_registro)->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Pedidos (30d)</td>
                            <td class="fw-medium">{{ $cliente->pedidos_30dias }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Direcciones --}}
    @if($cliente->direcciones->count())
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt me-2 text-danger"></i>Direcciones</h6>
            <div class="table-responsive">
                <table class="table table-sm small mb-0">
                    <thead class="table-light">
                        <tr><th>Dirección</th><th>Referencia</th><th>Etiqueta</th><th>Principal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($cliente->direcciones as $d)
                        <tr>
                            <td class="fw-medium">{{ $d->direccion }}</td>
                            <td class="text-secondary">{{ $d->referencia ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark border fw-normal">{{ $d->etiqueta ?? '—' }}</span></td>
                            <td>@if($d->es_principal)<i class="bi bi-check-circle-fill text-success"></i>@endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Teléfonos adicionales --}}
    @if($cliente->telefonos->count())
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-telephone me-2 text-info"></i>Teléfonos Adicionales</h6>
            <div class="table-responsive">
                <table class="table table-sm small mb-0">
                    <thead class="table-light">
                        <tr><th>Teléfono</th><th>Etiqueta</th><th>Principal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($cliente->telefonos as $t)
                        <tr>
                            <td class="fw-medium"><a href="tel:{{ $t->telefono }}" class="text-decoration-none">{{ $t->telefono }}</a></td>
                            <td><span class="badge bg-light text-dark border fw-normal">{{ $t->etiqueta ?? '—' }}</span></td>
                            <td>@if($t->es_principal)<i class="bi bi-check-circle-fill text-success"></i>@endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Notas internas --}}
    @if($cliente->notas_internas)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-sticky me-2 text-secondary"></i>Notas Internas</h6>
            <p class="small text-secondary mb-0">{{ $cliente->notas_internas }}</p>
        </div>
    </div>
    @endif

    {{-- ============================================ --}}
    {{-- HISTORIAL DE PEDIDOS                        --}}
    {{-- ============================================ --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Historial de Pedidos</h5>
        </div>
        <div class="card-body px-0">
            @if($pedidos->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-dark text-nowrap">
                        <tr>
                            <th class="ps-3">Código</th>
                            <th>Fecha</th>
                            <th>Dirección</th>
                            <th class="text-center">Productos</th>
                            <th class="text-end">Monto</th>
                            <th>Estado</th>
                            <th>Motorizado</th>
                            <th class="text-end pe-3">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $p)
                        <tr>
                            <td class="ps-3 fw-medium text-nowrap">{{ $p->codigo_seguimiento ?? '#'.$p->id }}</td>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($p->fecha_registro)->format('d/m/Y H:i') }}</td>
                            <td class="text-secondary" style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->direccion_entrega }}">{{ $p->direccion_entrega ?? '—' }}</td>
                            <td class="text-center">{{ $p->detalles->sum('cantidad') }} und.</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($p->monto_total, 2) }}</td>
                            <td>
                                @php
                                    $estadoCls = match($p->estado) {
                                        'pendiente' => 'bg-warning text-dark',
                                        'asignado', 'en_ruta', 'en_camino' => 'bg-info text-dark',
                                        'entregado' => 'bg-success',
                                        'cancelado' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $estadoCls }} fw-normal px-2">{{ $p->estado }}</span>
                            </td>
                            <td class="text-secondary">{{ $p->motorizado->nombre_completo ?? '—' }}</td>
                            <td class="text-end pe-3">
                                <button class="btn btn-outline-info btn-sm pedido-detalle-btn"
                                        title="Ver detalle del pedido"
                                        data-pedido='@json($p)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-muted py-4 mb-0"><i class="bi bi-inbox me-2"></i>No hay pedidos registrados para este cliente.</p>
            @endif
        </div>
    </div>
</div>

{{-- MODAL DETALLE DE PEDIDO --}}
<div class="modal fade" id="modalPedidoDetalle" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i> <span id="pedidoDetalleTitulo">Detalle del Pedido</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="pedidoDetalleBody">
                <div class="text-center py-4" id="pedidoDetalleLoading">
                    <div class="spinner-border text-warning mb-3" role="status"></div>
                    <p class="text-muted">Cargando detalle...</p>
                </div>
                <div id="pedidoDetalleContent" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Abrir modal con detalle del pedido
    document.querySelectorAll('.pedido-detalle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const pedido = JSON.parse(btn.dataset.pedido);
            const loading = document.getElementById('pedidoDetalleLoading');
            const content = document.getElementById('pedidoDetalleContent');
            const titulo = document.getElementById('pedidoDetalleTitulo');

            titulo.textContent = 'Pedido ' + (pedido.codigo_seguimiento || '#' + pedido.id);
            loading.style.display = 'block';
            content.style.display = 'none';

            // Info general
            const estadoCls = ({
                'pendiente': 'bg-warning text-dark', 'asignado': 'bg-info text-dark',
                'en_ruta': 'bg-primary', 'en_camino': 'bg-info text-dark',
                'entregado': 'bg-success', 'cancelado': 'bg-danger'
            })[pedido.estado] || 'bg-secondary';

            let html = `
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr><td class="text-secondary" style="width:110px;">Código</td><td class="fw-medium">${pedido.codigo_seguimiento || '#' + pedido.id}</td></tr>
                            <tr><td class="text-secondary">Fecha</td><td class="fw-medium">${pedido.fecha_registro ? new Date(pedido.fecha_registro).toLocaleDateString('es-PE') + ' ' + new Date(pedido.fecha_registro).toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'}) : '—'}</td></tr>
                            <tr><td class="text-secondary">Despacho</td><td class="fw-medium">${pedido.tipo_despacho === 'domicilio' ? 'Delivery' : 'Recojo Tienda'}</td></tr>
                            <tr><td class="text-secondary">Dirección</td><td class="fw-medium">${pedido.direccion_entrega || '—'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr><td class="text-secondary" style="width:110px;">Estado</td><td><span class="badge ${estadoCls} fw-normal px-2">${pedido.estado}</span></td></tr>
                            <tr><td class="text-secondary">Recepcionista</td><td class="fw-medium">${pedido.recepcionista ? pedido.recepcionista.nombre_completo : '—'}</td></tr>
                            <tr><td class="text-secondary">Motorizado</td><td class="fw-medium">${pedido.motorizado ? pedido.motorizado.nombre_completo : '—'}</td></tr>
                            <tr><td class="text-secondary">Entrega</td><td class="fw-medium">${pedido.fecha_entrega ? new Date(pedido.fecha_entrega).toLocaleDateString('es-PE') : '—'}</td></tr>
                        </table>
                    </div>
                </div>
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-list-check me-2"></i>Productos</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm small mb-0">
                        <thead class="table-light">
                            <tr><th>Producto</th><th>Marca</th><th class="text-center">Cant.</th><th class="text-end">P.Unit</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>`;

            (pedido.detalles || []).forEach(d => {
                const esObsequio = parseFloat(d.precio_unitario) === 0;
                html += `<tr>
                    <td class="fw-medium">${d.producto ? d.producto.nombre : 'Producto'} ${esObsequio ? '<span class="badge bg-info fw-normal" style="font-size:0.6rem;">Obsequio</span>' : ''}</td>
                    <td class="text-secondary">${d.producto && d.producto.marca ? d.producto.marca : '—'}</td>
                    <td class="text-center">${d.cantidad}</td>
                    <td class="text-end">S/ ${parseFloat(d.precio_unitario).toFixed(2)}</td>
                    <td class="text-end fw-semibold">S/ ${parseFloat(d.subtotal).toFixed(2)}</td>
                </tr>`;
            });

            html += `</tbody></table></div>`;

            // Pagos
            if (pedido.pagos && pedido.pagos.length > 0) {
                let totalPagado = 0;
                html += `<h6 class="fw-bold text-dark mb-2"><i class="bi bi-credit-card me-2"></i>Pagos</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm small mb-0">
                            <thead class="table-light">
                                <tr><th>Método</th><th class="text-end">Monto</th><th class="text-end">Recibido</th></tr>
                            </thead>
                            <tbody>`;
                pedido.pagos.forEach(p => {
                    totalPagado += parseFloat(p.monto);
                    html += `<tr>
                        <td class="fw-medium">${p.tipo_pago ? p.tipo_pago.nombre : 'Efectivo'}</td>
                        <td class="text-end">S/ ${parseFloat(p.monto).toFixed(2)}</td>
                        <td class="text-end">S/ ${parseFloat(p.monto_recibido || p.monto).toFixed(2)}</td>
                    </tr>`;
                });
                html += `</tbody><tfoot class="table-light fw-semibold"><tr><td>TOTAL</td><td class="text-end">S/ ${totalPagado.toFixed(2)}</td><td class="text-end"></td></tr></tfoot></table></div>`;
            }

            html += `<div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                <span class="fw-bold text-secondary">Total Pedido</span>
                <span class="fs-5 fw-bold text-dark">S/ ${parseFloat(pedido.monto_total).toFixed(2)}</span>
            </div>`;

            loading.style.display = 'none';
            content.style.display = 'block';
            content.innerHTML = html;

            new bootstrap.Modal(document.getElementById('modalPedidoDetalle')).show();
        });
    });
});
</script>
@endpush
