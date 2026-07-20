@extends('layouts.administrador')

@section('titulo', 'Auditoría')
@section('page_title', 'Registro de Auditoría')
@section('page_subtitle', 'Todas las acciones de creación, modificación y eliminación')

@section('contenido')
<div class="container-fluid px-0">

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body px-4 py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Usuario</label>
                    <input type="text" name="usuario" class="form-control form-control-sm" value="{{ request('usuario') }}" placeholder="Nombre o correo">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Acción</label>
                    <select name="accion" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="created" {{ request('accion') === 'created' ? 'selected' : '' }}>Creación</option>
                        <option value="updated" {{ request('accion') === 'updated' ? 'selected' : '' }}>Modificación</option>
                        <option value="deleted" {{ request('accion') === 'deleted' ? 'selected' : '' }}>Eliminación</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Modelo</label>
                    <input type="text" name="modelo" class="form-control form-control-sm" value="{{ request('modelo') }}" placeholder="Ej: Pedido">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Desde</label>
                    <input type="date" name="desde" class="form-control form-control-sm" value="{{ request('desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">Hasta</label>
                    <input type="date" name="hasta" class="form-control form-control-sm" value="{{ request('hasta') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark btn-sm w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3 px-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark"><i class="bi bi-list-check me-2"></i> Historial de Cambios</span>
            <span class="badge bg-secondary">{{ $logs->total() }} registros</span>
        </div>
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-dark text-nowrap">
                        <tr>
                            <th class="ps-3">Fecha/Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Modelo</th>
                            <th>ID</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-3 text-nowrap text-secondary">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="fw-medium">{{ $log->usuario_nombre ?? '—' }}</td>
                            <td><span class="badge {{ $log->accion_badge }} fw-normal px-2">{{ $log->accion_label }}</span></td>
                            <td>{{ $log->modelo_label }}</td>
                            <td>{{ $log->modelo_id ?? '—' }}</td>
                            <td class="text-secondary">{{ $log->ip ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p class="fw-bold mb-0">Sin registros de auditoría</p>
                                <small>Las acciones sobre los modelos empezarán a registrarse aquí.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
