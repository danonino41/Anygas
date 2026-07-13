@extends('layouts.administrador')

@section('titulo', 'Compras y Reabastecimiento')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-truck text-warning me-2"></i> Compras y Reabastecimiento</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} — Registro de entrada de mercadería</p>
        </div>
        <button class="btn btn-dark shadow-sm fw-semibold" id="btnNuevaCompra">
            <i class="bi bi-plus-lg me-1"></i> Nueva Compra
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-cart me-1 text-warning"></i> Compras</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $totalCompras }}</h2>
                <small class="text-secondary">órdenes registradas</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-danger border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-cash-stack me-1 text-danger"></i> Gastado</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">S/ {{ number_format($totalGastado, 2) }}</h2>
                <small class="text-secondary">total acumulado</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-box me-1 text-success"></i> Ingresados</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $totalProductos }}</h2>
                <small class="text-secondary">unidades compradas</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-clock-history me-1 text-info"></i> Última</small>
                <h2 class="fw-bold text-dark mb-0 mt-1" style="font-size:1.1rem;">
                    {{ $compraMasReciente ? optional($compraMasReciente->proveedor)->nombre_empresa ?? '—' : 'Sin compras' }}
                </h2>
                <small class="text-secondary">
                    {{ $compraMasReciente ? \Carbon\Carbon::parse($compraMasReciente->fecha_compra)->format('d/m/Y') : '—' }}
                </small>
            </div>
        </div>
    </div>

    <div id="formularioCompra" class="card border-0 shadow-sm rounded-4 mb-4" style="display:none;">
        <div class="card-header bg-dark text-white rounded-top-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-truck me-2"></i> Registrar Nueva Compra</h5>
            <button type="button" class="btn btn-sm btn-outline-light" id="btnCerrarForm">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="card-body p-4">
            <form id="compraForm" method="POST" action="{{ route('admin.compras.guardar') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-dark small">Proveedor <span class="text-danger">*</span></label>
                        <select name="proveedor_id" class="form-select" id="proveedorSelect" required>
                            <option value="">— Todos los proveedores —</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre_empresa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark small">Fecha de compra <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_compra" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="bi bi-box me-2"></i> Productos</h6>
                        <small class="text-muted">Selecciona los productos recibidos en esta compra</small>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" id="filtroProductos" placeholder="Buscar producto..." style="width:200px;">
                        <button type="button" class="btn btn-sm btn-outline-success fw-semibold" id="agregarFilaBtn">
                            <i class="bi bi-plus-circle me-1"></i> Agregar
                        </button>
                    </div>
                </div>

                <div class="table-responsive rounded-3 border">
                    <table class="table table-bordered mb-0 bg-white" id="productosTable">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th style="width:38%;">Producto</th>
                                <th style="width:14%;">Cantidad</th>
                                <th style="width:18%;">Costo Unit. (S/)</th>
                                <th style="width:18%;">Subtotal</th>
                                <th style="width:12%;"></th>
                            </tr>
                        </thead>
                        <tbody id="tablaCuerpo"></tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="3" class="text-end text-uppercase">Total compra</td>
                                <td id="totalDisplay">S/ 0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary fw-semibold px-4" id="btnCancelarForm">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4" id="btnGuardar" disabled>
                        <i class="bi bi-check-lg me-1"></i> Guardar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0 px-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-secondary"></i> Historial de Compras</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark small">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Registrado por</th>
                            <th class="text-end pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compras as $compra)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $compra->id }}</td>
                            <td>
                                <span class="fw-semibold">{{ $compra->proveedor->nombre_empresa ?? '—' }}</span>
                                <br><small class="text-muted">{{ $compra->proveedor->ruc ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                            <td>
                                @php $limite = 3; @endphp
                                @foreach($compra->detalles->take($limite) as $detalle)
                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        {{ $detalle->producto->nombre ?? '—' }} <span class="text-muted">x{{ $detalle->cantidad_recibida }}</span>
                                    </span>
                                @endforeach
                                @if($compra->detalles->count() > $limite)
                                    <span class="badge bg-secondary me-1 mb-1">+{{ $compra->detalles->count() - $limite }} más</span>
                                @endif
                            </td>
                            <td class="fw-bold">S/ {{ number_format($compra->monto_total_compra, 2) }}</td>
                            <td><small>{{ $compra->usuario->nombre_completo ?? '—' }}</small></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#detalle{{ $compra->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="detalle{{ $compra->id }}">
                            <td colspan="7" class="bg-light p-3">
                                <table class="table table-sm table-borderless mb-0 small">
                                    <thead class="text-muted">
                                        <tr><th>Producto</th><th>Cant.</th><th>Costo Unit.</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($compra->detalles as $det)
                                        <tr>
                                            <td>{{ $det->producto->nombre ?? '—' }}</td>
                                            <td>{{ $det->cantidad_recibida }}</td>
                                            <td>S/ {{ number_format($det->costo_unitario_compra, 2) }}</td>
                                            <td>S/ {{ number_format($det->subtotal_compra, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold border-top">
                                            <td colspan="3" class="text-end">Total:</td>
                                            <td>S/ {{ number_format($compra->monto_total_compra, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No hay compras registradas. Haz clic en "Nueva Compra" para comenzar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const productosData = @json($productos);
const productosArray = Array.isArray(productosData) ? productosData : Object.values(productosData || {});

document.addEventListener('DOMContentLoaded', () => {
    let filaIndex = 0;
    const form = document.getElementById('formularioCompra');
    const btnNueva = document.getElementById('btnNuevaCompra');
    const btnCerrar = document.getElementById('btnCerrarForm');
    const btnCancelar = document.getElementById('btnCancelarForm');
    const proveedorSelect = document.getElementById('proveedorSelect');
    const tablaCuerpo = document.getElementById('tablaCuerpo');
    const totalDisplay = document.getElementById('totalDisplay');
    const btnGuardar = document.getElementById('btnGuardar');
    const filtroInput = document.getElementById('filtroProductos');

    function proveedorSeleccionado() {
        return proveedorSelect.value;
    }

    function opcionesProductos() {
        const provId = proveedorSeleccionado();
        const textoFiltro = (filtroInput.value || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        let html = '<option value="">— Seleccionar —</option>';
        productosArray.forEach(p => {
            if (provId && String(p.proveedor_id) !== provId) return;
            const nom = (p.nombre || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            if (textoFiltro && !nom.includes(textoFiltro)) return;
            const costo = p.costo ?? p.precio_compra ?? p.precio ?? '';
            const stock = p.stock_actual ?? p.stock ?? 0;
            html += `<option value="${p.id}" data-precio="${costo}">${p.nombre} — Stock: ${stock}</option>`;
        });
        return html;
    }

    function refrescarOpciones() {
        document.querySelectorAll('.producto-select').forEach(sel => {
            const val = sel.value;
            sel.innerHTML = opcionesProductos();
            if (![...sel.options].some(o => o.value === val)) {
                sel.value = '';
            } else {
                sel.value = val;
            }
        });
    }

    function agregarFila(focus = false) {
        const idx = filaIndex++;
        const tr = document.createElement('tr');
        tr.className = 'fila-producto';
        tr.innerHTML = `
            <td>
                <select name="items[${idx}][producto_id]" class="form-select form-select-sm producto-select" required>${opcionesProductos()}</select>
            </td>
            <td>
                <input type="number" name="items[${idx}][cantidad]" class="form-control form-control-sm cantidad-input" min="1" step="1" placeholder="0" required>
            </td>
            <td>
                <input type="number" name="items[${idx}][costo_unitario]" class="form-control form-control-sm costo-input" min="0" step="0.01" placeholder="0.00" required>
            </td>
            <td class="text-center align-middle">
                <span class="subtotal-display fw-semibold">S/ 0.00</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger eliminar-fila px-2" title="Quitar"><i class="bi bi-trash"></i></button>
            </td>
        `;
        tablaCuerpo.appendChild(tr);
        btnGuardar.disabled = false;
        estadoEliminar();
        if (focus) tr.querySelector('.producto-select').focus();
    }

    function recalcular() {
        let total = 0;
        document.querySelectorAll('.fila-producto').forEach(r => {
            const c = parseFloat(r.querySelector('.cantidad-input').value) || 0;
            const u = parseFloat(r.querySelector('.costo-input').value) || 0;
            const s = c * u;
            r.querySelector('.subtotal-display').textContent = 'S/ ' + s.toFixed(2);
            total += s;
        });
        totalDisplay.textContent = 'S/ ' + total.toFixed(2);
    }

    function estadoEliminar() {
        const fs = document.querySelectorAll('.fila-producto');
        fs.forEach((f, i) => { const b = f.querySelector('.eliminar-fila'); if (b) b.disabled = fs.length <= 1; });
    }

    function mostrarForm() { form.style.display = 'block'; btnNueva.style.display = 'none'; if (!tablaCuerpo.children.length) agregarFila(); }
    function ocultarForm() { form.style.display = 'none'; btnNueva.style.display = ''; }

    btnNueva.addEventListener('click', mostrarForm);
    btnCerrar.addEventListener('click', ocultarForm);
    btnCancelar.addEventListener('click', ocultarForm);
    document.getElementById('agregarFilaBtn').addEventListener('click', () => agregarFila(true));
    proveedorSelect.addEventListener('change', refrescarOpciones);
    filtroInput.addEventListener('input', refrescarOpciones);

    tablaCuerpo.addEventListener('change', e => {
        if (e.target.classList.contains('producto-select') && e.target.selectedOptions[0]?.dataset.precio) {
            const r = e.target.closest('.fila-producto');
            const ci = r.querySelector('.costo-input');
            if (!ci.value) {
                ci.value = e.target.selectedOptions[0].dataset.precio;
                recalcular();
            }
        }
    });

    tablaCuerpo.addEventListener('input', e => {
        if (e.target.classList.contains('cantidad-input') || e.target.classList.contains('costo-input')) recalcular();
    });

    tablaCuerpo.addEventListener('click', e => {
        const b = e.target.closest('.eliminar-fila');
        if (b && document.querySelectorAll('.fila-producto').length > 1) {
            b.closest('.fila-producto').remove();
            recalcular();
            estadoEliminar();
        }
    });
});
</script>
@endpush
