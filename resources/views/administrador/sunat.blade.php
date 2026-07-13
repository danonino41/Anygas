@extends('layouts.administrador')

@section('titulo', 'Historial y SUNAT')

@section('contenido')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-journal-check text-warning me-2"></i> Historial y SUNAT</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} &mdash; Auditoría contable y fiscal de comprobantes emitidos</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- 1. PANEL DE RESUMEN FISCAL (TARJETAS)        --}}
    {{-- ============================================= --}}
    <div class="row g-3 mb-4">
        {{-- Total Ventas Declaradas --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-primary bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#eef2ff;"><i class="bi bi-cash-stack text-primary"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Ventas del Mes</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">S/ {{ number_format($totalVentasMes, 2) }}</h3>
                <div class="d-flex gap-3 mt-1">
                    <small class="text-secondary">Base: <span class="fw-semibold">S/ {{ number_format($totalVentasMesBase, 2) }}</span></small>
                    <small class="text-secondary">IGV: <span class="fw-semibold">S/ {{ number_format($totalIgvMes, 2) }}</span></small>
                </div>
            </div>
        </div>

        {{-- Pendientes de Envío --}}
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-warning bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#fffbeb;"><i class="bi bi-hourglass-split text-warning"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Pendientes</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $pendientes }}</h3>
                <small class="text-secondary">comprobantes</small>
            </div>
        </div>

        {{-- Aceptados --}}
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-success bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#ecfdf5;"><i class="bi bi-check-circle text-success"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Aceptados</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $aceptados }}</h3>
                <small class="text-secondary">comprobantes</small>
            </div>
        </div>

        {{-- Rechazados --}}
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-danger bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#fef2f2;"><i class="bi bi-x-circle text-danger"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Rechazados</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $rechazados }}</h3>
                <small class="text-secondary">comprobantes</small>
            </div>
        </div>

        {{-- Ratio Comprobantes --}}
        <div class="col-6 col-lg-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-info bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#eff6ff;"><i class="bi bi-pie-chart text-info"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Ratio</small>
                </div>
                <div class="mt-2">
                    @if($totalGeneral > 0)
                        @php
                            $pctBoleta = round(($totalBoletas / $totalGeneral) * 100);
                            $pctFactura = round(($totalFacturas / $totalGeneral) * 100);
                        @endphp
                        <div class="progress rounded-pill mb-2" style="height:8px;">
                            <div class="progress-bar bg-primary" style="width:{{ $pctBoleta }}%" title="Boletas: {{ $pctBoleta }}%"></div>
                            <div class="progress-bar bg-success" style="width:{{ $pctFactura }}%" title="Facturas: {{ $pctFactura }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-square-fill text-primary me-1" style="font-size:0.5rem;"></i>Boletas {{ $pctBoleta }}%</span>
                            <span><i class="bi bi-square-fill text-success me-1" style="font-size:0.5rem;"></i>Facturas {{ $pctFactura }}%</span>
                        </div>
                    @else
                        <p class="text-muted small mb-0">Sin datos</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- 2. BANDEJA DE COMPROBANTES (Filtros)          --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body px-4 pt-4 pb-0 border-bottom">
            <form method="GET" action="{{ route('admin.sunat') }}" class="row g-2 align-items-end pb-4">
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-calendar me-1"></i>Desde</label>
                    <input type="date" name="desde" class="form-control form-control-sm" value="{{ request('desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">Hasta</label>
                    <input type="date" name="hasta" class="form-control form-control-sm" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-file-earmark me-1"></i>Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="boleta" {{ request('tipo') == 'boleta' ? 'selected' : '' }}>Boleta</option>
                        <option value="factura" {{ request('tipo') == 'factura' ? 'selected' : '' }}>Factura</option>
                        <option value="ticket" {{ request('tipo') == 'ticket' ? 'selected' : '' }}>Ticket Interno</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-cloud-check me-1"></i>Estado SUNAT</label>
                    <select name="estado_sinc" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado_sinc') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aceptado" {{ request('estado_sinc') == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                        <option value="rechazado" {{ request('estado_sinc') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-search me-1"></i>Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Serie, DNI, nombre..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-dark btn-sm fw-semibold px-3" title="Filtrar"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.sunat') }}" class="btn btn-outline-secondary btn-sm px-3" title="Limpiar"><i class="bi bi-x-lg"></i></a>
                </div>
            </form>
        </div>

        {{-- ============================================= --}}
        {{-- 3. TABLA PRINCIPAL DE AUDITORÍA CONTABLE      --}}
        {{-- ============================================= --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark small text-nowrap">
                    <tr>
                        <th class="ps-3">Comprobante</th>
                        <th>Pedido</th>
                        <th>Cliente / Doc.</th>
                        <th class="text-end">Base Imp.</th>
                        <th class="text-end">IGV</th>
                        <th class="text-end">Total</th>
                        <th>Estado</th>
                        <th>Emisión</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($comprobantes as $c)
                    <tr class="align-middle">
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $c->tipo_comprobante === 'factura' ? 'bg-success' : ($c->tipo_comprobante === 'boleta' ? 'bg-primary' : 'bg-secondary') }} fw-normal px-2" style="font-size:0.7rem;">
                                    {{ $c->tipo_label_txt }}
                                </span>
                                <span class="fw-bold text-dark">{{ $c->serie_correlativo }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="text-decoration-none fw-medium text-primary">#{{ $c->pedido->id }}</a>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $c->cliente_nombre }}</div>
                            <small class="text-secondary">{{ $c->cliente_doc }}</small>
                        </td>
                        <td class="text-end text-secondary">S/ {{ number_format($c->base_imponible_val, 2) }}</td>
                        <td class="text-end text-secondary">S/ {{ number_format($c->igv_val, 2) }}</td>
                        <td class="text-end fw-bold text-dark">S/ {{ number_format($c->monto_total_val, 2) }}</td>
                        <td><span class="badge {{ $c->estado_badge_cls }} fw-normal px-2">{{ $c->estado_label_txt }}</span></td>
                        <td class="text-nowrap text-secondary">{{ \Carbon\Carbon::parse($c->fecha_emision)->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info detalle-btn" title="Ver detalle" data-id="{{ $c->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('admin.sunat.pdf', $c->id) }}" class="btn btn-outline-secondary" title="Descargar PDF" target="_blank">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('admin.sunat.ticket', $c->id) }}" class="btn btn-outline-dark" title="Ticket de venta" target="_blank">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                @if($c->estado_sincronizacion === 'pendiente' || $c->estado_sincronizacion === 'rechazado')
                                <form action="{{ route('admin.sunat.enviar', $c->id) }}" method="POST" class="d-inline form-enviar-sunat">
                                    @csrf
                                    <button class="btn btn-outline-success" title="Enviar a SUNAT"
                                            data-serie="{{ $c->serie_correlativo }}">
                                        <i class="bi bi-cloud-upload"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-journal-check fs-1 d-block mb-2 text-muted"></i>
                            <span class="text-muted">No se encontraron comprobantes con los filtros seleccionados.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($comprobantes->hasPages())
            <div class="card-footer bg-transparent py-3 border-top">
                {{ $comprobantes->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL DETALLE DEL COMPROBANTE                 --}}
{{-- ============================================= --}}
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-receipt me-2"></i> <span id="detalleTitulo">Comprobante</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-4" id="detalleContent">
                    <div class="text-center py-5" id="detalleLoading">
                        <div class="spinner-border text-warning mb-3" role="status"></div>
                        <p class="text-muted">Cargando comprobante...</p>
                    </div>
                    <div id="detalleBody" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- DETALLE MODAL ---
    const detalleModal = document.getElementById('modalDetalle');
    const detalleTitulo = document.getElementById('detalleTitulo');
    const detalleLoading = document.getElementById('detalleLoading');
    const detalleBody = document.getElementById('detalleBody');

    document.querySelectorAll('.detalle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            detalleTitulo.textContent = 'Cargando...';
            detalleLoading.style.display = 'block';
            detalleBody.style.display = 'none';

            const modal = new bootstrap.Modal(detalleModal);
            modal.show();

            fetch('{{ url("admin/sunat") }}/' + id)
                .then(r => r.json())
                .then(c => {
                    detalleTitulo.textContent = c.serie_correlativo + ' — ' + c.tipo_label;
                    detalleLoading.style.display = 'none';
                    detalleBody.style.display = 'block';
                    renderDetalle(c);
                })
                .catch(() => {
                    detalleLoading.innerHTML = `
                        <i class="bi bi-exclamation-circle text-danger fs-1 d-block mb-2"></i>
                        <p class="text-danger">Error al cargar comprobante.</p>`;
                });
        });
    });

    function renderDetalle(c) {
        const estadoBadge = `<span class="badge ${c.estado_badge} fw-normal px-2">${c.estado_label}</span>`;

        // Info fiscal
        let infoHtml = `
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-receipt me-2"></i>Datos del Comprobante</h6>
                            <table class="table table-sm table-borderless mb-0 small">
                                <tr><td class="text-secondary pe-3" style="width:140px;">Serie-Correlativo</td><td class="fw-bold">${c.serie_correlativo}</td></tr>
                                <tr><td class="text-secondary">Tipo</td><td class="fw-medium">${c.tipo_label}</td></tr>
                                <tr><td class="text-secondary">Fecha Emisión</td><td class="fw-medium">${new Date(c.fecha_emision).toLocaleDateString('es-PE')} ${new Date(c.fecha_emision).toLocaleTimeString('es-PE', {hour:'2-digit', minute:'2-digit'})}</td></tr>
                                <tr><td class="text-secondary">Estado SUNAT</td><td>${estadoBadge}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person me-2"></i>Cliente</h6>
                            <table class="table table-sm table-borderless mb-0 small">
                                <tr><td class="text-secondary pe-3" style="width:140px;">Nombre</td><td class="fw-medium">${c.cliente ? c.cliente.nombre : '—'}</td></tr>
                                <tr><td class="text-secondary">Documento</td><td class="fw-medium">${c.cliente ? c.cliente.documento : '—'}</td></tr>
                                <tr><td class="text-secondary">Teléfono</td><td class="fw-medium">${c.cliente ? c.cliente.telefono : '—'}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`;

        // Pedido de origen
        let pedidoHtml = `
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam me-2"></i>Pedido de Origen</h6>
                    <div class="row g-3 small">
                        <div class="col-md-3"><span class="text-secondary">Código:</span> <strong>${c.pedido.codigo_seguimiento || '#' + c.pedido.id}</strong></div>
                        <div class="col-md-3"><span class="text-secondary">Estado:</span> <strong>${c.pedido.estado}</strong></div>
                        <div class="col-md-3"><span class="text-secondary">Recepcionista:</span> <strong>${c.pedido.recepcionista}</strong></div>
                        <div class="col-md-3"><span class="text-secondary">Motorizado:</span> <strong>${c.pedido.motorizado}</strong></div>
                        <div class="col-md-6"><span class="text-secondary">Dirección:</span> <strong>${c.pedido.direccion_entrega || '—'}</strong></div>
                        <div class="col-md-3"><span class="text-secondary">Registro:</span> <strong>${c.pedido.fecha_registro ? new Date(c.pedido.fecha_registro).toLocaleDateString('es-PE') : '—'}</strong></div>
                        <div class="col-md-3"><span class="text-secondary">Entrega:</span> <strong>${c.pedido.fecha_entrega ? new Date(c.pedido.fecha_entrega).toLocaleDateString('es-PE') : '—'}</strong></div>
                    </div>
                </div>
            </div>`;

        // Detalle de productos
        let prodHtml = `
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-check me-2"></i>Productos / Detalle</h6>
                    <div class="table-responsive">
                        <table class="table table-sm small mb-0">
                            <thead class="table-light">
                                <tr><th>Producto</th><th>Marca</th><th class="text-center">Cant.</th><th class="text-end">P. Unit.</th><th class="text-end">Subtotal</th></tr>
                            </thead>
                            <tbody>`;

        c.detalles.forEach(d => {
            const obsequioTag = d.es_obsequio ? ' <span class="badge bg-info fw-normal" style="font-size:0.6rem;">Obsequio S/ 0.00</span>' : '';
            prodHtml += `<tr>
                <td class="fw-medium">${d.producto}${obsequioTag}</td>
                <td class="text-secondary">${d.marca || '—'}</td>
                <td class="text-center">${d.cantidad}</td>
                <td class="text-end">S/ ${Number(d.precio_unitario).toFixed(2)}</td>
                <td class="text-end">S/ ${Number(d.subtotal).toFixed(2)}</td>
            </tr>`;
        });

        prodHtml += `</tbody></table></div></div></div>`;

        // Resumen fiscal
        let fiscalHtml = `
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calculator me-2"></i>Resumen Fiscal</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-4" style="background:#eff6ff;">
                                <small class="text-secondary fw-semibold d-block mb-1">Base Imponible</small>
                                <h5 class="fw-bold text-primary mb-0">S/ ${Number(c.base_imponible).toFixed(2)}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-4" style="background:#fef3c7;">
                                <small class="text-secondary fw-semibold d-block mb-1">IGV (18%)</small>
                                <h5 class="fw-bold text-warning mb-0">S/ ${Number(c.igv).toFixed(2)}</h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded-4" style="background:#ecfdf5;">
                                <small class="text-secondary fw-semibold d-block mb-1">Importe Total</small>
                                <h5 class="fw-bold text-success mb-0">S/ ${Number(c.monto_total).toFixed(2)}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        // Pagos
        let pagosHtml = `
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card me-2"></i>Pagos Registrados</h6>`;

        if (c.pagos && c.pagos.length > 0) {
            let totalMonto = 0, totalRecibido = 0;
            pagosHtml += '<div class="table-responsive"><table class="table table-sm small mb-0"><thead class="table-light"><tr><th>Método</th><th class="text-end">Monto</th><th class="text-end">Recibido</th></tr></thead><tbody>';
            c.pagos.forEach(p => {
                totalMonto += Number(p.monto);
                totalRecibido += Number(p.monto_recibido);
                pagosHtml += `<tr><td class="fw-medium">${p.tipo}</td><td class="text-end">S/ ${Number(p.monto).toFixed(2)}</td><td class="text-end">S/ ${Number(p.monto_recibido).toFixed(2)}</td></tr>`;
            });
            pagosHtml += `</tbody><tfoot class="table-light fw-semibold"><tr><td>TOTAL</td><td class="text-end">S/ ${totalMonto.toFixed(2)}</td><td class="text-end">S/ ${totalRecibido.toFixed(2)}</td></tr></tfoot></table></div>`;
        } else {
            pagosHtml += '<p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Sin pagos registrados.</p>';
        }
        pagosHtml += '</div></div>';

        // Acciones
        let accionesHtml = `
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ url("admin/sunat") }}/${c.id}/pdf" target="_blank" class="btn btn-outline-secondary btn-sm fw-semibold">
                    <i class="bi bi-file-pdf me-1"></i> Descargar PDF
                </a>
                <a href="{{ url("admin/sunat") }}/${c.id}/ticket" target="_blank" class="btn btn-outline-dark btn-sm fw-semibold">
                    <i class="bi bi-receipt me-1"></i> Ticket
                </a>
                <a href="https://wa.me/51${(c.cliente && c.cliente.telefono) ? c.cliente.telefono.replace(/[^0-9]/g, '') : ''}" target="_blank" class="btn btn-success btn-sm fw-semibold" ${!c.cliente || !c.cliente.telefono ? 'disabled' : ''}>
                    <i class="bi bi-whatsapp me-1"></i> Enviar por WhatsApp
                </a>
            </div>`;

        detalleBody.innerHTML = infoHtml + pedidoHtml + prodHtml + fiscalHtml + pagosHtml + accionesHtml;
    }

    // --- ENVIAR A SUNAT - SweetAlert2 ---
    document.querySelectorAll('.form-enviar-sunat').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const serie = form.querySelector('button').dataset.serie;
            Swal.fire({
                title: '¿Enviar a SUNAT?',
                text: 'Se generará el XML, se firmará y se enviará al servicio de SUNAT para el comprobante ' + serie,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

});
</script>
@endpush
