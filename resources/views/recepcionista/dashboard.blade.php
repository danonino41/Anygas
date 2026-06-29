@extends('layouts.recepcionista')

@section('titulo', 'Panel Diario - Recepción')

@section('contenido')
<div class="container-fluid p-0">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">📋 Control de Mostrador del Día</h2>
            <p class="text-secondary small mb-0">Monitoreo de balones en ruta y despacho en tiempo real.</p>
        </div>
        <div>
            <a href="/recepcionista/punto-venta" class="btn btn-warning fw-bold px-4 shadow-sm">
                <i class="bi bi-cart-plus me-1"></i> Nueva Venta Rápidas
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Pendientes</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">4</h3>
                    </div>
                    <div class="fs-1 text-warning bg-warning bg-opacity-10 rounded-circle px-3 py-1">🕒</div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Por asignar motorizado</small>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Asignados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">2</h3>
                    </div>
                    <div class="fs-1 text-primary bg-primary bg-opacity-10 rounded-circle px-3 py-1">🏍️</div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Esperando despacho</small>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">En Camino</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">5</h3>
                    </div>
                    <div class="fs-1 text-info bg-info bg-opacity-10 rounded-circle px-3 py-1">🗺️</div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Balones en calle</small>
            </div>
        </div>

        <div class="col-6 col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Entregados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">18</h3>
                    </div>
                    <div class="fs-1 text-success bg-success bg-opacity-10 rounded-circle px-3 py-1">✅</div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Cobrados hoy</small>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-danger border-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block">Cancelados</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1">1</h3>
                    </div>
                    <div class="fs-1 text-danger bg-danger bg-opacity-10 rounded-circle px-3 py-1">❌</div>
                </div>
                <small class="text-secondary mt-2 d-block" style="font-size: 0.75rem;">Rechazados hoy</small>
            </div>
        </div>

    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">🔥 Últimos Pedidos Registrados Hoy</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Despacho</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-uppercase">ANG-1042</td>
                                <td>Fiorella Retamozo</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">Domicilio</span></td>
                                <td class="fw-bold">S/. 52.00</td>
                                <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                                <td>
                                    <a href="/recepcionista/asignar" class="btn btn-sm btn-outline-dark">Despachar</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-uppercase">ANG-1041</td>
                                <td>Carlos Neuhaus</td>
                                <td><span class="badge bg-secondary-subtle text-secondary">Domicilio</span></td>
                                <td class="fw-bold">S/. 110.00</td>
                                <td><span class="badge bg-info text-dark">En Camino</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light disabled">En calle</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3">🏍️ Motorizados Activos Hoy</h5>
                <p class="text-muted small mb-3">Disponibilidad para nuevos despachos en la zona.</p>

                <div class="d-flex flex-column gap-3">
                    
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-circle p-2"> </span>
                            <div>
                                <strong class="d-block text-dark small">Walter García</strong>
                                <small class="text-secondary" style="font-size: 0.7rem;">Placa: 3456-1A</small>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary">3 En Ruta</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-circle p-2"> </span>
                            <div>
                                <strong class="d-block text-dark small">Juan Mendoza</strong>
                                <small class="text-secondary" style="font-size: 0.7rem;">Placa: 8891-2B</small>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success">Disponible</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection