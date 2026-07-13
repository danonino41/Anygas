@extends('layouts.administrador')

@section('titulo', 'Indicadores KPIs')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-speedometer2 text-warning me-2"></i> Indicadores KPIs</h3>
            <p class="text-secondary small mb-0">Métricas clave de negocio &mdash; actualizado {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Fila 1: Ingresos --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-2 d-inline-flex mb-2">
                        <i class="bi bi-cash-stack text-warning fs-5"></i>
                    </div>
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.7rem;">Ingresos Hoy</small>
                    <h4 class="fw-bold text-dark mb-0">S/ {{ number_format($ingresosHoy, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 d-inline-flex mb-2">
                        <i class="bi bi-calendar-week text-primary fs-5"></i>
                    </div>
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.7rem;">Semana</small>
                    <h4 class="fw-bold text-dark mb-0">S/ {{ number_format($ingresosSemana, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 d-inline-flex mb-2">
                        <i class="bi bi-graph-up-arrow text-success fs-5"></i>
                    </div>
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.7rem;">Mes</small>
                    <h4 class="fw-bold text-dark mb-0">S/ {{ number_format($ingresosMes, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-2 d-inline-flex mb-2">
                        <i class="bi bi-box-seam text-info fs-5"></i>
                    </div>
                    <small class="text-muted fw-semibold text-uppercase d-block" style="font-size:0.7rem;">Valor Inventario</small>
                    <h4 class="fw-bold text-dark mb-0">S/ {{ number_format($valorInventario, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Fila 2: Pedidos, Clientes, Motorizados --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-warning">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold text-uppercase" style="font-size:0.65rem;">Pedidos Hoy</small>
                            <h3 class="fw-bold text-dark mb-0">{{ $pedidosHoy }}</h3>
                        </div>
                        <i class="bi bi-receipt text-warning fs-2 opacity-50"></i>
                    </div>
                    <small class="text-secondary">Entregados hoy: <strong class="text-success">{{ $pedidosEntregadosHoy }}</strong></small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-primary">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold text-uppercase" style="font-size:0.65rem;">Pendientes</small>
                            <h3 class="fw-bold text-dark mb-0">{{ $pedidosPendientes }}</h3>
                        </div>
                        <i class="bi bi-hourglass-split text-primary fs-2 opacity-50"></i>
                    </div>
                    <small class="text-secondary">En proceso: <strong class="text-info">{{ $pedidosEnProceso }}</strong></small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold text-uppercase" style="font-size:0.65rem;">Clientes</small>
                            <h3 class="fw-bold text-dark mb-0">{{ $totalClientes }}</h3>
                        </div>
                        <i class="bi bi-people text-success fs-2 opacity-50"></i>
                    </div>
                    <small class="text-secondary">Clientes con compras: <strong>{{ $clientesConPedidos }}</strong></small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-dark">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold text-uppercase" style="font-size:0.65rem;">Motorizados</small>
                            <h3 class="fw-bold text-dark mb-0">{{ $motorizadosActivos }}</h3>
                        </div>
                        <i class="bi bi-bicycle text-dark fs-2 opacity-50"></i>
                    </div>
                    <small class="text-secondary">Productos c/stock: <strong>{{ $productosConStock }}</strong> &middot; Agotados: <strong class="text-danger">{{ $productosAgotados }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    {{-- Fila 3: Gráficos --}}
    <div class="row g-3 mb-4">

        {{-- Ingresos últimos 15 días --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i> Ingresos Diarios (últimos 15 días)</h6>
                </div>
                <div class="card-body px-4 pt-2">
                    @php $maxIngreso = $dias->max('total') ?: 1; @endphp
                    <div class="d-flex align-items-end gap-1" style="height:140px;">
                        @foreach($dias as $d)
                            @php
                                $altura = $maxIngreso > 0 ? max(round(($d['total'] / $maxIngreso) * 120), $d['total'] > 0 ? 6 : 2) : 2;
                                $diaLabel = \Carbon\Carbon::parse($d['fecha'])->format('d');
                                $esHoy = \Carbon\Carbon::parse($d['fecha'])->isToday();
                            @endphp
                            <div class="flex-fill d-flex flex-column align-items-center">
                                <small class="fw-bold {{ $esHoy ? 'text-warning' : 'text-dark' }}" style="font-size:0.65rem;">S/{{ number_format($d['total'], 0) }}</small>
                                <div class="w-100 rounded-1 {{ $esHoy ? 'bg-warning' : 'bg-primary' }}" style="height:{{ $altura }}px; transition: height 0.3s;"></div>
                                <small class="text-muted" style="font-size:0.6rem; margin-top:2px;">{{ $diaLabel }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Distribución pagos / despacho --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-credit-card me-2 text-success"></i> Ingresos por Pago (Mes)</h6>
                </div>
                <div class="card-body px-4 pt-2">
                    @if($pagosMes->count())
                        @foreach($pagosMes as $p)
                            @php $pct = $ingresosMes > 0 ? round(($p->total / $ingresosMes) * 100) : 0; @endphp
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small">
                                    <span class="fw-semibold">{{ $p->tipo }}</span>
                                    <span>S/ {{ number_format($p->total, 2) }} <span class="text-muted">({{ $pct }}%)</span></span>
                                </div>
                                <div class="progress rounded-pill" style="height:6px;">
                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small text-center py-3 mb-0">Sin datos este mes</p>
                    @endif

                    <hr class="my-2">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-truck me-2 text-info"></i> Despachos (Mes)</h6>
                    @if($despachoMes->count())
                        @foreach($despachoMes as $d)
                            @php
                                $label = $d->tipo_despacho === 'domicilio' ? 'Delivery' : 'Recojo Tienda';
                                $pct = $ingresosMes > 0 ? round(($d->total_ingreso / $ingresosMes) * 100) : 0;
                            @endphp
                            <div class="mb-1">
                                <div class="d-flex justify-content-between small">
                                    <span class="fw-semibold">{{ $label }}</span>
                                    <span>{{ $d->total_pedidos }} ped. / S/{{ number_format($d->total_ingreso, 0) }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height:6px;">
                                    <div class="progress-bar bg-info" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">Sin datos este mes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Fila 4: Top productos + SUNAT --}}
    <div class="row g-3 mb-4">

        {{-- Top 5 productos --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-trophy me-2 text-warning"></i> Top 5 Productos (30 días)</h6>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 small">
                            <thead class="table-light">
                                <tr><th class="ps-4">#</th><th>Producto</th><th>Marca</th><th class="text-center">Vendidos</th><th class="text-end pe-4">Ingreso</th></tr>
                            </thead>
                            <tbody>
                                @forelse($topProductos as $i => $p)
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">{{ $i + 1 }}</td>
                                    <td class="fw-medium">{{ $p->nombre }}</td>
                                    <td class="text-secondary">{{ $p->marca }}</td>
                                    <td class="text-center fw-bold">{{ $p->total_vendido }}</td>
                                    <td class="text-end pe-4 fw-semibold">S/ {{ number_format($p->total_ingreso, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">No hay ventas en los últimos 30 días</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comprobantes SUNAT --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-cloud-check me-2 text-secondary"></i> SUNAT (Mes)</h6>
                </div>
                <div class="card-body px-4">
                    @php
                        $totalComp = $comprobantesAceptados + $comprobantesPendientes + $comprobantesRechazados;
                        $pctAcep = $totalComp > 0 ? round(($comprobantesAceptados / $totalComp) * 100) : 0;
                        $pctPend = $totalComp > 0 ? round(($comprobantesPendientes / $totalComp) * 100) : 0;
                        $pctRech = $totalComp > 0 ? round(($comprobantesRechazados / $totalComp) * 100) : 0;
                    @endphp
                    @if($totalComp > 0)
                        <div class="mb-2">
                            <div class="progress rounded-pill mb-3" style="height:10px;">
                                <div class="progress-bar bg-success" style="width:{{ $pctAcep }}%">{{ $comprobantesAceptados > 0 ? $pctAcep.'%' : '' }}</div>
                                <div class="progress-bar bg-warning" style="width:{{ $pctPend }}%">{{ $comprobantesPendientes > 0 ? $pctPend.'%' : '' }}</div>
                                <div class="progress-bar bg-danger" style="width:{{ $pctRech }}%">{{ $comprobantesRechazados > 0 ? $pctRech.'%' : '' }}</div>
                            </div>
                            <div class="d-flex justify-content-around text-center small">
                                <div><span class="d-block fw-bold text-success">{{ $comprobantesAceptados }}</span><span class="text-muted" style="font-size:0.65rem;">Aceptados</span></div>
                                <div><span class="d-block fw-bold text-warning">{{ $comprobantesPendientes }}</span><span class="text-muted" style="font-size:0.65rem;">Pendientes</span></div>
                                <div><span class="d-block fw-bold text-danger">{{ $comprobantesRechazados }}</span><span class="text-muted" style="font-size:0.65rem;">Rechazados</span></div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted small text-center py-4 mb-0">Sin comprobantes este mes</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stock status --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3 px-4">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-boxes me-2 text-primary"></i> Inventario Rápido</h6>
                </div>
                <div class="card-body px-4">
                    @php $totalProd = $productosConStock + $productosAgotados; @endphp
                    @if($totalProd > 0)
                        <div class="text-center mb-3">
                            <h2 class="fw-bold text-dark mb-0">{{ $totalProd }}</h2>
                            <small class="text-muted">Total productos activos</small>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height:10px;">
                            <div class="progress-bar bg-primary" style="width:{{ $totalProd > 0 ? round(($productosConStock / $totalProd)*100) : 0 }}%"></div>
                            <div class="progress-bar bg-danger" style="width:{{ $totalProd > 0 ? round(($productosAgotados / $totalProd)*100) : 0 }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="bi bi-circle-fill text-primary me-1" style="font-size:0.5rem;"></i> Con stock: <strong>{{ $productosConStock }}</strong></span>
                            <span><i class="bi bi-circle-fill text-danger me-1" style="font-size:0.5rem;"></i> Agotados: <strong>{{ $productosAgotados }}</strong></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Valor inventario</span>
                            <span class="fw-bold">S/ {{ number_format($valorInventario, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mt-1">
                            <span class="text-muted">Clientes nuevos</span>
                            <span class="fw-bold text-success">{{ $clientesConPedidos }} con compras</span>
                        </div>
                    @else
                        <p class="text-muted small text-center py-4 mb-0">Sin productos registrados</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
