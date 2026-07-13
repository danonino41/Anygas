@extends('layouts.administrador')

@section('titulo', 'Directorio de Clientes')

@section('contenido')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-person-lines-fill text-warning me-2"></i> Directorio de Clientes</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} &mdash; Fichas de clientes y coordenadas geográficas</p>
        </div>
        <button class="btn btn-dark shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Cliente
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-primary bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#eef2ff;"><i class="bi bi-people text-primary"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Total</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $total }}</h3>
                <small class="text-secondary">clientes registrados</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-success bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#ecfdf5;"><i class="bi bi-check-circle text-success"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Activos</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $activos }}</h3>
                <small class="text-secondary">clientes activos</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-danger bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#fef2f2;"><i class="bi bi-exclamation-triangle text-danger"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Morosos</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $morosos }}</h3>
                <small class="text-secondary">con deuda</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-warning bg-white h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="rounded-3 p-2 lh-1" style="background:#fffbeb;"><i class="bi bi-box-seam text-warning"></i></div>
                    <small class="text-muted fw-semibold text-uppercase">Deuda Envases</small>
                </div>
                <h3 class="fw-bold text-dark mb-0 mt-2">{{ $deudaEnvases }}</h3>
                <small class="text-secondary">envases en deuda</small>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body px-4 pt-4 pb-0 border-bottom">
            <form method="GET" action="{{ route('admin.clientes') }}" class="row g-2 align-items-end pb-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-search me-1"></i>Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Nombre, documento o teléfono..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="moroso" {{ request('estado') == 'moroso' ? 'selected' : '' }}>Moroso</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark btn-sm fw-semibold px-3" title="Filtrar"><i class="bi bi-search"></i></button>
                    <a href="{{ route('admin.clientes') }}" class="btn btn-outline-secondary btn-sm px-3" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark small text-nowrap">
                    <tr>
                        <th class="ps-3">Cliente</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Pedidos</th>
                        <th>Total Gastado</th>
                        <th>Deuda Env.</th>
                        <th>Estado</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($clientes as $c)
                    <tr class="align-middle">
                        <td class="ps-3 fw-semibold">{{ $c->nombre_completo }}</td>
                        <td><span class="text-secondary">{{ $c->documento_identidad ?? '—' }}</span></td>
                        <td>
                            <a href="tel:{{ $c->telefono }}" class="text-decoration-none">{{ $c->telefono ?? '—' }}</a>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-normal">{{ $c->cantidad_pedidos }}</span></td>
                        <td class="fw-semibold">S/ {{ number_format($c->total_gastado, 2) }}</td>
                        <td>
                            @if($c->deuda_envases > 0)
                                <span class="badge bg-danger fw-normal px-2">{{ $c->deuda_envases }}</span>
                            @else
                                <span class="badge bg-light text-dark border fw-normal px-2">0</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $c->estado_badge }} fw-normal px-2">{{ $c->estado_label }}</span></td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.clientes.ver', $c->id) }}" class="btn btn-outline-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-outline-primary editar-btn" title="Editar"
                                        data-id="{{ $c->id }}"
                                        data-nombres="{{ $c->nombres }}"
                                        data-apellidos="{{ $c->apellidos }}"
                                        data-documento="{{ $c->documento_identidad }}"
                                        data-telefono="{{ $c->telefono }}"
                                        data-direccion="{{ $c->direccion_principal }}"
                                        data-referencia="{{ $c->referencia_direccion }}"
                                        data-correo="{{ $c->correo }}"
                                        data-deuda="{{ $c->deuda_envases }}"
                                        data-notas="{{ $c->notas_internas }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.clientes.estado', $c->id) }}" method="POST" class="d-inline form-toggle-estado">
                                    @csrf @method('PUT')
                                    <button class="btn {{ $c->toggle_btn }}"
                                            title="{{ $c->toggle_label }}"
                                            data-nombre="{{ $c->nombre_completo }}"
                                            data-accion="{{ $c->toggle_accion }}">
                                        <i class="bi bi-{{ $c->toggle_icon }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-person-lines-fill fs-1 d-block mb-2 text-muted"></i>
                            <span class="text-muted">No hay clientes registrados.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clientes->hasPages())
            <div class="card-footer bg-transparent py-3 border-top">
                {{ $clientes->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i> Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.clientes.guardar') }}">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Documento Identidad</label>
                            <input type="text" name="documento_identidad" class="form-control" placeholder="DNI / RUC / CE">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="telefono" class="form-control" placeholder="999 999 999" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" placeholder="cliente@correo.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dirección Principal</label>
                        <input type="text" name="direccion_principal" class="form-control" placeholder="Av./Jr./Calle ...">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Referencia</label>
                            <input type="text" name="referencia_direccion" class="form-control" placeholder="Cerca de ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Deuda de Envases</label>
                            <input type="number" name="deuda_envases" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Notas Internas</label>
                        <textarea name="notas_internas" class="form-control" rows="2" maxlength="500" placeholder="Observaciones..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 border-top-0 px-4 py-3">
                    <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4"><i class="bi bi-check-lg me-1"></i> Crear Cliente</button>
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
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i> Editar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditar" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" id="edit_nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" id="edit_apellidos" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Documento Identidad</label>
                            <input type="text" name="documento_identidad" id="edit_documento" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Correo Electrónico</label>
                            <input type="email" name="correo" id="edit_correo" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dirección Principal</label>
                        <input type="text" name="direccion_principal" id="edit_direccion" class="form-control">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Referencia</label>
                            <input type="text" name="referencia_direccion" id="edit_referencia" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Deuda de Envases</label>
                            <input type="number" name="deuda_envases" id="edit_deuda" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-semibold small">Notas Internas</label>
                        <textarea name="notas_internas" id="edit_notas" class="form-control" rows="2" maxlength="500"></textarea>
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

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- EDITAR MODAL ---
        const formEditar = document.getElementById('formEditar');

        document.querySelectorAll('.editar-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                formEditar.action = '{{ url("admin/clientes") }}/' + btn.dataset.id;
                document.getElementById('edit_nombres').value = btn.dataset.nombres;
                document.getElementById('edit_apellidos').value = btn.dataset.apellidos;
                document.getElementById('edit_documento').value = btn.dataset.documento || '';
                document.getElementById('edit_telefono').value = btn.dataset.telefono;
                document.getElementById('edit_direccion').value = btn.dataset.direccion || '';
                document.getElementById('edit_referencia').value = btn.dataset.referencia || '';
                document.getElementById('edit_correo').value = btn.dataset.correo || '';
                document.getElementById('edit_deuda').value = btn.dataset.deuda || 0;
                document.getElementById('edit_notas').value = btn.dataset.notas || '';
                new bootstrap.Modal(document.getElementById('modalEditar')).show();
            });
        });

        // --- TOGGLE ESTADO -- SweetAlert2 ---
        document.querySelectorAll('.form-toggle-estado').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                const btn = form.querySelector('button');
                const nombre = btn.dataset.nombre;
                const accion = btn.dataset.accion;
                Swal.fire({
                    title: '¿' + (accion === 'activar' ? 'Activar' : 'Desactivar') + ' cliente?',
                    text: nombre + ' pasará a estado «' + (accion === 'activar' ? 'activo' : 'inactivo') + '»',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: accion === 'activar' ? '#28a745' : '#6c757d',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Sí, ' + (accion === 'activar' ? 'activar' : 'desactivar'),
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // --- ELIMINAR TELÉFONO -- SweetAlert2 (delegación dinámica) ---
        document.addEventListener('submit', e => {
            if (e.target.classList.contains('form-eliminar-tel')) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar teléfono?',
                    text: 'Se eliminará este número de la lista de teléfonos del cliente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) e.target.submit();
                });
            }
        });
    });
</script>
@endpush
