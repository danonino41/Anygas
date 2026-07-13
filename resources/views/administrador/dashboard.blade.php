@extends('layouts.administrador')

@section('titulo', 'Dashboard Financiero')

@section('contenido')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up-arrow text-warning me-2"></i> Dashboard
                Financiero</h2>
            <p class="text-secondary small mb-0">{{ now()->format('d/m/Y H:i') }} — Resumen operativo del negocio</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase">Ingresos Hoy</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">S/ {{ number_format($ingresosHoy, 2) }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-info border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase">Pedidos Hoy</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $pedidosHoy }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase">Pendientes</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $pedidosPendientes }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4 bg-white h-100">
                <small class="text-muted fw-bold text-uppercase">Motorizados</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $motorizadosActivos }}</h3>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-trophy me-2 text-warning"></i> Top Productos</h5>
                <p class="text-muted small mb-2">Más vendidos (30 días)</p>
                <div class="d-flex flex-column gap-2">
                    @forelse($topProductos as $item)
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light">
                        <span class="fw-bold small text-dark">{{ $item->nombre }}</span>
                        <span class="badge bg-dark">{{ $item->total_vendido }} und.</span>
                    </div>
                    @empty
                    <p class="text-muted small text-center mb-0">Sin ventas en los últimos 30 días</p>
                    @endforelse
                </div>
            </div>
        </div>    
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-server me-2 text-warning"></i> Servidor</h5>
                <p class="text-muted small mb-2">Estado técnico del sistema</p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">PHP</span>
                        <span class="fw-bold small">{{ $servidor['php_version'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Base de Datos</span>
                        <span class="fw-bold small">{{ $servidor['base_datos'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Uptime</span>
                        <span class="fw-bold small">{{ $servidor['uptime'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Memoria</span>
                        <span class="fw-bold small">{{ $servidor['memoria_usada'] }}</span>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Uso de memoria</small>
                            <small class="fw-bold">{{ $servidor['memoria_usada'] }}</small>
                        </div>
                        @php
                        $memPercent = is_numeric(str_replace('%', '', $servidor['memoria_usada'])) ? (float)
                        str_replace('%', '', $servidor['memoria_usada']) : 0;
                        @endphp
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $memPercent > 80 ? 'bg-danger' : ($memPercent > 50 ? 'bg-warning' : 'bg-success') }}"
                                style="width: {{ $memPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection