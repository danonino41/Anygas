@extends('layouts.administrador')

@section('titulo', 'Productos y Precios')

@section('contenido')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-tags text-warning me-2"></i> Productos y Precios</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} &mdash; Catálogo completo de productos</p>
        </div>
        <button class="btn btn-dark shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-primary bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#eef2ff;"><i class="bi bi-box text-primary"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Total</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $total }}</h3>
                <small class="text-secondary">productos</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-success bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#ecfdf5;"><i class="bi bi-check-circle text-success"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Disponibles</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $disponibles }}</h3>
                <small class="text-secondary">en stock</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-info bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#f0f9ff;"><i class="bi bi-boxes text-info"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Stock Total</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $stockTotal }}</h3>
                <small class="text-secondary">unidades</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-danger bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#fef2f2;"><i class="bi bi-exclamation-triangle text-danger"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Stock Bajo</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $bajoStock }}</h3>
                <small class="text-secondary">&le; 5 unidades</small>
            </div>
        </div>
    </div>

    {{-- Tabla con filtros --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        {{-- Filtros --}}
        <div class="card-body px-4 pt-4 pb-0 border-bottom">
            <form method="GET" action="{{ route('admin.productos') }}" class="row g-2 align-items-end pb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-search me-1"></i>Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Nombre o marca..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted mb-1">Proveedor</label>
                    <select name="proveedor" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($proveedores as $prov)
                            <option value="{{ $prov->id }}" {{ request('proveedor') == $prov->id ? 'selected' : '' }}>{{ $prov->nombre_empresa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="estandar" {{ request('tipo') == 'estandar' ? 'selected' : '' }}>Estándar</option>
                        <option value="premium" {{ request('tipo') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="ninguna" {{ request('tipo') == 'ninguna' ? 'selected' : '' }}>Genérico</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="agotado" {{ request('estado') == 'agotado' ? 'selected' : '' }}>Agotado</option>
                        <option value="descontinuado" {{ request('estado') == 'descontinuado' ? 'selected' : '' }}>Descontinuado</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark btn-sm fw-semibold px-3" title="Filtrar"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.productos') }}" class="btn btn-outline-secondary btn-sm px-3" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark small text-nowrap">
                    <tr>
                        <th class="ps-3" style="width:48px;"></th>
                        <th class="w-25">Producto</th>
                        <th>Marca</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th class="text-end">Compra</th>
                        <th class="text-end">Venta</th>
                        <th class="text-center">Stock</th>
                        <th>Estado</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($productos as $p)
                    <tr class="align-middle">
                        <td class="ps-3">
                            <img src="{{ $p->imagen_url }}" alt="" class="rounded border" style="width:34px;height:34px;object-fit:contain;">
                        </td>
                        <td class="fw-semibold">{{ $p->nombre }}</td>
                        <td><span class="text-secondary">{{ $p->marca }}</span></td>
                        <td><span class="badge bg-light text-dark border fw-normal">{{ $p->proveedor->nombre_empresa ?? '—' }}</span></td>
                        <td><span class="badge {{ $p->tipo_badge }} fw-normal">{{ $p->tipo_label }}</span></td>
                        <td class="text-end text-secondary">{{ $p->precio_compra ? 'S/ '.number_format($p->precio_compra, 2) : '—' }}</td>
                        <td class="text-end fw-semibold">S/ {{ number_format($p->precio_venta, 2) }}</td>
                        <td class="text-center"><span class="badge {{ $p->stock_badge }} fw-normal px-2">{{ $p->stock_actual }}</span></td>
                        <td><span class="badge {{ $p->estado_badge }} fw-normal px-2">{{ $p->estado_label }}</span></td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary editar-btn" title="Editar"
                                        data-id="{{ $p->id }}"
                                        data-nombre="{{ $p->nombre }}"
                                        data-marca="{{ $p->marca }}"
                                        data-proveedor="{{ $p->proveedor_id }}"
                                        data-tipo="{{ $p->tipo_entrada }}"
                                        data-precio_compra="{{ $p->precio_compra }}"
                                        data-precio_venta="{{ $p->precio_venta }}"
                                        data-stock="{{ $p->stock_actual }}"
                                        data-descripcion="{{ $p->descripcion }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-info precio-btn" title="Ajustar precio"
                                        data-id="{{ $p->id }}"
                                        data-nombre="{{ $p->nombre }}"
                                        data-precio_venta="{{ $p->precio_venta }}"
                                        data-precio_compra="{{ $p->precio_compra }}">
                                    <i class="bi bi-currency-dollar"></i>
                                </button>
                                <form action="{{ route('admin.productos.estado', $p->id) }}" method="POST" class="d-inline form-toggle-estado">
                                    @csrf @method('PUT')
                                    <button class="btn {{ $p->toggle_btn }}"
                                            title="{{ $p->toggle_label }}"
                                            data-nombre="{{ $p->nombre }}"
                                            data-accion="{{ $p->toggle_accion }}">
                                        <i class="bi bi-{{ $p->toggle_icon }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-tags fs-1 d-block mb-2 text-muted"></i>
                            <span class="text-muted">No hay productos registrados.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="card-footer bg-transparent py-3 border-top">
                {{ $productos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.productos.guardar') }}">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="marca" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Proveedor <span class="text-danger">*</span></label>
                            <select name="proveedor_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre_empresa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tipo de Entrada <span class="text-danger">*</span></label>
                            <select name="tipo_entrada" class="form-select" required>
                                <option value="estandar">Estándar</option>
                                <option value="premium">Premium</option>
                                <option value="ninguna">Genérico</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Precio Compra (S/)</label>
                            <input type="number" name="precio_compra" class="form-control" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Precio Venta (S/) <span class="text-danger">*</span></label>
                            <input type="number" name="precio_venta" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stock Inicial <span class="text-danger">*</span></label>
                            <input type="number" name="stock_actual" class="form-control" min="0" value="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Descripci&oacute;n</label>
                        <textarea name="descripcion" class="form-control" rows="2" maxlength="500" placeholder="Opcional"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4"><i class="bi bi-check-lg me-1"></i> Crear Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i> Editar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditar" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="marca" id="edit_marca" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Proveedor <span class="text-danger">*</span></label>
                            <select name="proveedor_id" id="edit_proveedor" class="form-select" required>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nombre_empresa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tipo de Entrada <span class="text-danger">*</span></label>
                            <select name="tipo_entrada" id="edit_tipo" class="form-select" required>
                                <option value="estandar">Estándar</option>
                                <option value="premium">Premium</option>
                                <option value="ninguna">Genérico</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Precio Compra (S/)</label>
                            <input type="number" name="precio_compra" id="edit_precio_compra" class="form-control" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Precio Venta (S/) <span class="text-danger">*</span></label>
                            <input type="number" name="precio_venta" id="edit_precio_venta" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock_actual" id="edit_stock" class="form-control" min="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Descripci&oacute;n</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2" maxlength="500" placeholder="Opcional"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4"><i class="bi bi-check-lg me-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PRECIO --}}
<div class="modal fade" id="modalPrecio" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-currency-dollar me-2"></i> Ajustar Precio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPrecio" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4 py-3">
                    <p class="text-secondary small mb-3" id="precioProducto"></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Precio Compra (S/)</label>
                        <input type="number" name="precio_compra" id="precio_compra_input" class="form-control" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Precio Venta (S/) <span class="text-danger">*</span></label>
                        <input type="number" name="precio_venta" id="precio_venta_input" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4"><i class="bi bi-check-lg me-1"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formEditar = document.getElementById('formEditar');
        const formPrecio = document.getElementById('formPrecio');
        const precioProducto = document.getElementById('precioProducto');

        document.querySelectorAll('.editar-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                formEditar.action = '{{ url("admin/productos") }}/' + btn.dataset.id;
                document.getElementById('edit_nombre').value = btn.dataset.nombre;
                document.getElementById('edit_marca').value = btn.dataset.marca;
                document.getElementById('edit_proveedor').value = btn.dataset.proveedor;
                document.getElementById('edit_tipo').value = btn.dataset.tipo;
                document.getElementById('edit_precio_compra').value = btn.dataset.precio_compra;
                document.getElementById('edit_precio_venta').value = btn.dataset.precio_venta;
                document.getElementById('edit_stock').value = btn.dataset.stock;
                document.getElementById('edit_descripcion').value = btn.dataset.descripcion ?? '';
                new bootstrap.Modal(document.getElementById('modalEditar')).show();
            });
        });

        document.querySelectorAll('.precio-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                formPrecio.action = '{{ url("admin/productos") }}/' + btn.dataset.id + '/precio';
                precioProducto.textContent = 'Ajustando precio de: ' + btn.dataset.nombre;
                document.getElementById('precio_compra_input').value = btn.dataset.precio_compra || '';
                document.getElementById('precio_venta_input').value = btn.dataset.precio_venta;
                new bootstrap.Modal(document.getElementById('modalPrecio')).show();
            });
        });

        document.querySelectorAll('.form-toggle-estado').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                const btn = form.querySelector('button');
                const nombre = btn.dataset.nombre;
                const accion = btn.dataset.accion;
                Swal.fire({
                    title: '¿' + (accion === 'disponible' ? 'Activar' : 'Desactivar') + ' producto?',
                    text: nombre + ' pasará a estado «' + (accion === 'disponible' ? 'disponible' : 'agotado') + '»',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: accion === 'disponible' ? '#28a745' : '#6c757d',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Sí, ' + (accion === 'disponible' ? 'activar' : 'desactivar'),
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
@endpush
