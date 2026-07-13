@extends('layouts.administrador')

@section('titulo', 'Gestión de Personal')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-people text-warning me-2"></i> Gestión de Personal</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} — Administración de usuarios del sistema</p>
        </div>
        <button class="btn btn-dark shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-people me-1 text-primary"></i> Total</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $total }}</h2>
                <small class="text-secondary">usuarios registrados</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-check-circle me-1 text-success"></i> Activos</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $activos }}</h2>
                <small class="text-secondary">en el sistema</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-headset me-1 text-info"></i> Recepcionistas</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $recepcionistas }}</h2>
                <small class="text-secondary">en mostrador</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase d-block"><i class="bi bi-bicycle me-1 text-warning"></i> Motorizados</small>
                <h2 class="fw-bold text-dark mb-0 mt-1">{{ $motorizados }}</h2>
                <small class="text-secondary">en reparto</small>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark small">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $u)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">{{ $u->id }}</td>
                            <td><small>{{ $u->documento_identidad }}</small></td>
                            <td class="fw-semibold">{{ $u->nombre_completo }}</td>
                            <td><small class="text-muted">{{ $u->correo }}</small></td>
                            <td><small>{{ $u->telefono }}</small></td>
                            <td>
                                @php
                                    $roles = ['administrador' => 'Admin', 'recepcionista' => 'Recepcionista', 'motorizado' => 'Motorizado'];
                                    $badges = ['administrador' => 'danger', 'recepcionista' => 'info', 'motorizado' => 'warning'];
                                @endphp
                                <span class="badge bg-{{ $badges[$u->rol] ?? 'secondary' }}">{{ $roles[$u->rol] ?? $u->rol }}</span>
                            </td>
                            <td>
                                @if($u->estado === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($u->estado === 'inactivo')
                                    <span class="badge bg-secondary">Inactivo</span>
                                @else
                                    <span class="badge bg-danger">Suspendido</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button class="btn btn-sm btn-outline-primary editar-btn" title="Editar"
                                            data-id="{{ $u->id }}"
                                            data-documento="{{ $u->documento_identidad }}"
                                            data-nombre="{{ $u->nombre_completo }}"
                                            data-correo="{{ $u->correo }}"
                                            data-telefono="{{ $u->telefono }}"
                                            data-rol="{{ $u->rol }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning password-btn" title="Cambiar contraseña"
                                            data-id="{{ $u->id }}"
                                            data-nombre="{{ $u->nombre_completo }}">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <form action="{{ route('admin.personal.estado', $u->id) }}" method="POST" class="d-inline form-toggle-estado">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-outline-{{ $u->estado === 'activo' ? 'secondary' : 'success' }}"
                                                title="{{ $u->estado === 'activo' ? 'Desactivar' : 'Activar' }}"
                                                data-nombre="{{ $u->nombre_completo }}"
                                                data-accion="{{ $u->estado === 'activo' ? 'desactivar' : 'activar' }}">
                                            <i class="bi bi-{{ $u->estado === 'activo' ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                No hay usuarios registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i> Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.personal.guardar') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Documento de Identidad</label>
                        <input type="text" name="documento_identidad" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre Completo</label>
                        <input type="text" name="nombre_completo" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="recepcionista">Recepcionista</option>
                                <option value="motorizado">Motorizado</option>
                                <option value="administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" minlength="6" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i> Editar Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditar" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Documento de Identidad</label>
                        <input type="text" name="documento_identidad" id="edit_documento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre Completo</label>
                        <input type="text" name="nombre_completo" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Correo Electrónico</label>
                            <input type="email" name="correo" id="edit_correo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Rol</label>
                        <select name="rol" id="edit_rol" class="form-select" required>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="motorizado">Motorizado</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL CAMBIAR CONTRASEÑA --}}
<div class="modal fade" id="modalPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-key me-2"></i> Cambiar Contraseña</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formPassword" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3" id="passwordUsuario"></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nueva Contraseña</label>
                        <input type="password" name="contrasena" class="form-control" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEditar = document.getElementById('modalEditar');
    const formEditar = document.getElementById('formEditar');
    const modalPassword = document.getElementById('modalPassword');
    const formPassword = document.getElementById('formPassword');
    const passwordUsuario = document.getElementById('passwordUsuario');

    document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            formEditar.action = '{{ url("admin/personal") }}/' + id;
            document.getElementById('edit_documento').value = btn.dataset.documento;
            document.getElementById('edit_nombre').value = btn.dataset.nombre;
            document.getElementById('edit_correo').value = btn.dataset.correo;
            document.getElementById('edit_telefono').value = btn.dataset.telefono;
            document.getElementById('edit_rol').value = btn.dataset.rol;
            new bootstrap.Modal(modalEditar).show();
        });
    });

    document.querySelectorAll('.password-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            formPassword.action = '{{ url("admin/personal") }}/' + id + '/password';
            passwordUsuario.textContent = 'Cambiando contraseña de: ' + nombre;
            new bootstrap.Modal(modalPassword).show();
        });
    });

    document.querySelectorAll('.form-toggle-estado').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const btn = form.querySelector('button');
            const nombre = btn.dataset.nombre;
            const accion = btn.dataset.accion;
            Swal.fire({
                title: '¿' + accion.charAt(0).toUpperCase() + accion.slice(1) + ' usuario?',
                text: 'Se cambiará el estado de ' + nombre,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: accion === 'activar' ? '#28a745' : '#6c757d',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Sí, ' + accion,
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endpush
