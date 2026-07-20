<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — Seguimiento de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f5f7fa; min-height: 100vh; }
        .hero {
            background: linear-gradient(135deg, #111827 0%, #1e293b 50%, #451a03 100%);
            padding: 3rem 1.5rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(245,158,11,0.08) 0%, transparent 60%),
                        radial-gradient(circle at 70% 30%, rgba(251,191,36,0.05) 0%, transparent 50%);
        }
        .hero-content { position: relative; z-index: 1; max-width: 520px; margin: 0 auto; }
        .hero-logo { height: 52px; margin-bottom: 0.75rem; }
        .hero h1 { color: #fbbf24; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .hero p { color: #94a3b8; font-size: 0.85rem; margin-bottom: 1.5rem; }
        .search-box {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 60px;
            padding: 4px;
            display: flex;
            gap: 4px;
        }
        .search-box input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.7rem 1.2rem;
            color: #fff;
            font-size: 0.95rem;
            outline: none;
        }
        .search-box input::placeholder { color: #64748b; }
        .search-box button {
            background: #f59e0b;
            border: none;
            border-radius: 40px;
            padding: 0.55rem 1.5rem;
            color: #111827;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: background 0.2s;
        }
        .search-box button:hover { background: #d97706; }
        .tracking-wrap { max-width: 560px; margin: -1rem auto 2rem; position: relative; z-index: 2; padding: 0 1rem; }
        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            color: #dc2626;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .result-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-top: 1.25rem;
        }
        .result-card-header {
            background: linear-gradient(135deg, #fff 0%, #fffbeb 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .badge-status {
            padding: 0.35rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .detail-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; }
        .detail-row i { color: #94a3b8; font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        .timeline { padding: 1.25rem 0 0.5rem; }
        .timeline-step {
            display: flex;
            gap: 1rem;
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-step:last-child { padding-bottom: 0; }
        .timeline-step::before {
            content: '';
            position: absolute;
            left: 17px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        .timeline-step:last-child::before { display: none; }
        .timeline-step.active::before { background: #f59e0b; }
        .timeline-step.done::before { background: #22c55e; }
        .timeline-step.cancel::before { background: #ef4444; }
        .step-icon {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; z-index: 1; position: relative;
            border: 2px solid #e2e8f0; background: #fff; color: #94a3b8;
        }
        .timeline-step.done .step-icon { background: #22c55e; border-color: #22c55e; color: #fff; }
        .timeline-step.active .step-icon { background: #f59e0b; border-color: #f59e0b; color: #fff; box-shadow: 0 0 0 4px rgba(245,158,11,0.15); }
        .timeline-step.cancelled .step-icon { background: #ef4444; border-color: #ef4444; color: #fff; }
        .step-label .title { font-weight: 600; color: #0f172a; font-size: 0.9rem; }
        .step-label .sub { font-size: 0.78rem; color: #64748b; margin-top: 1px; }
        .timeline-step.done .step-label .title { color: #16a34a; }
        .timeline-step.active .step-label .title { color: #d97706; }
        .timeline-step.cancelled .step-label .title { color: #dc2626; }
        .timeline-step.pending .step-label .title { color: #94a3b8; }
        .driver-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
        }
        .driver-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 1.1rem;
            flex-shrink: 0;
        }
        .product-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.5rem 0;
        }
        .product-item + .product-item { border-top: 1px solid #f1f5f9; }
        .footer { text-align: center; padding: 2rem 1rem 1.5rem; color: #94a3b8; font-size: 0.8rem; }
        .footer i { color: #f59e0b; }

        @media (max-width: 480px) {
            .hero { padding: 2rem 1rem 2rem; }
            .search-box { border-radius: 40px; }
            .search-box input { font-size: 0.85rem; padding: 0.6rem 1rem; }
            .search-box button { padding: 0.5rem 1.2rem; font-size: 0.8rem; }
            .result-card-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .hero h1 { font-size: 1.25rem; }
        }
    </style>
</head>
<body>

    <div class="hero">
        <div class="hero-content">
            <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png"
                 alt="AnyGas" class="hero-logo">
            <h1><i class="bi bi-droplet-half me-2"></i>Seguimiento de Pedido</h1>
            <p>Ingresa tu código de seguimiento para conocer el estado de tu pedido</p>
            <form action="{{ route('cliente.seguimiento.consultar') }}" method="GET" class="search-box">
                <input type="text" name="codigo" placeholder="Ej: ANG-4521"
                       value="{{ request('codigo') }}" required autocomplete="off">
                <button type="submit"><i class="bi bi-search"></i> Buscar</button>
            </form>
            @error('codigo')
                <div class="error-msg"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
            @enderror
        </div>
    </div>

    @if(isset($pedido))
    <div class="tracking-wrap">

        {{-- Orden info + estado --}}
        <div class="result-card">
            <div class="result-card-header">
                <div>
                    <h5 class="fw-bold mb-0" style="color:#0f172a;">
                        <i class="bi bi-receipt me-1" style="color:#f59e0b;"></i>
                        {{ $pedido->codigo_seguimiento }}
                    </h5>
                    <small class="text-secondary">Registrado {{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y H:i') }}</small>
                </div>
                @php
                    $estados = [
                        'pendiente' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-hourglass-split', 'label' => 'Pendiente'],
                        'asignado'  => ['class' => 'bg-primary text-white', 'icon' => 'bi-person-check', 'label' => 'Asignado'],
                        'en_camino' => ['class' => 'bg-info text-white', 'icon' => 'bi-truck', 'label' => 'En Camino'],
                        'en_ruta'   => ['class' => 'bg-info text-white', 'icon' => 'bi-geo-alt', 'label' => 'En Ruta'],
                        'entregado' => ['class' => 'bg-success text-white', 'icon' => 'bi-check2-circle', 'label' => 'Entregado'],
                        'cancelado' => ['class' => 'bg-danger text-white', 'icon' => 'bi-x-circle', 'label' => 'Cancelado'],
                    ];
                    $est = $estados[$pedido->estado] ?? ['class' => 'bg-secondary text-white', 'icon' => 'bi-question', 'label' => $pedido->estado];
                @endphp
                <span class="badge-status {{ $est['class'] }}">
                    <i class="bi {{ $est['icon'] }} me-1"></i> {{ $est['label'] }}
                </span>
            </div>

            <div style="padding:1.25rem 1.5rem;">

                {{-- Cliente --}}
                <div class="detail-row">
                    <i class="bi bi-person-circle"></i>
                    <span><span class="text-secondary">Cliente:</span> <span class="fw-medium">{{ $pedido->cliente->nombre_completo }}</span></span>
                </div>
                <div class="detail-row">
                    <i class="bi bi-geo-alt"></i>
                    <span><span class="text-secondary">Dirección:</span> <span class="fw-medium">{{ $pedido->direccion_entrega }}</span></span>
                </div>
                @if($pedido->referencia_entrega)
                <div class="detail-row">
                    <i class="bi bi-signpost-2"></i>
                    <span><span class="text-secondary">Referencia:</span> <span class="fw-medium">{{ $pedido->referencia_entrega }}</span></span>
                </div>
                @endif

                {{-- Conductor --}}
                @if($pedido->motorizado)
                <div class="driver-card">
                    <div class="driver-avatar">
                        @php $iniciales = collect(explode(' ', $pedido->motorizado->nombre_completo ?? 'R R'))->take(2)->map(fn($p) => strtoupper(substr($p,0,1)))->implode(''); @endphp
                        {{ $iniciales ?: 'M' }}
                    </div>
                    <div style="flex:1;">
                        <div class="fw-semibold" style="color:#0f172a;">{{ $pedido->motorizado->nombre_completo }}</div>
                        <small class="text-secondary">Tu repartidor</small>
                    </div>
                    @if($pedido->cliente->telefono)
                    <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $pedido->cliente->telefono) }}?text=Hola,%20soy%20repartidor%20de%20AnyGas.%20Estoy%20en%20camino%20con%20tu%20pedido%20{{ $pedido->codigo_seguimiento }}."
                       target="_blank" class="btn btn-success btn-sm rounded-pill px-3" style="background:#25D366;border-color:#25D366;">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    @endif
                </div>
                @endif

                {{-- Productos y total --}}
                <div style="margin-top:1rem;">
                    <small class="text-secondary fw-semibold text-uppercase" style="font-size:0.65rem;letter-spacing:0.05em;">
                        <i class="bi bi-box-seam me-1"></i> Productos
                    </small>
                    @foreach($pedido->detalles as $d)
                    <div class="product-item">
                        <span class="fw-medium">{{ $d->cantidad }}x {{ $d->producto->nombre ?? 'Producto' }}
                            @if($d->producto->marca) <small class="text-secondary">{{ $d->producto->marca }}</small> @endif
                        </span>
                        <span class="fw-semibold" style="color:#0f172a;">S/ {{ number_format($d->subtotal, 2) }}</span>
                    </div>
                    @endforeach
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-top:0.75rem;margin-top:0.5rem;border-top:2px solid #f59e0b;">
                        <span class="fw-bold" style="color:#0f172a;">Total</span>
                        <span class="fw-bold fs-5" style="color:#16a34a;">S/ {{ number_format($pedido->monto_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline de estados --}}
        <div class="result-card" style="margin-top:1rem;">
            <div style="padding:1.25rem 1.5rem;">
                <h6 class="fw-bold mb-0" style="color:#0f172a;">
                    <i class="bi bi-clock-history me-1" style="color:#f59e0b;"></i> Estado del pedido
                </h6>
                <div class="timeline">

                    {{-- 1. Registrado --}}
                    @php
                        $registradoDone = true;
                        $asignadoDone = in_array($pedido->estado, ['asignado','en_camino','en_ruta','entregado']);
                        $enCaminoDone = in_array($pedido->estado, ['en_camino','en_ruta','entregado']);
                        $enRutaDone = in_array($pedido->estado, ['en_ruta','entregado']);
                        $entregadoDone = $pedido->estado === 'entregado';
                        $cancelado = $pedido->estado === 'cancelado';
                    @endphp
                    <div class="timeline-step done">
                        <div class="step-icon"><i class="bi bi-check-lg"></i></div>
                        <div class="step-label">
                            <div class="title">Pedido registrado</div>
                            <div class="sub">{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    {{-- 2. Asignado --}}
                    <div class="timeline-step {{ $cancelado ? 'cancelled' : ($asignadoDone ? 'done' : '') }}">
                        <div class="step-icon"><i class="bi bi-person-check"></i></div>
                        <div class="step-label">
                            <div class="title">{{ $cancelado ? 'Cancelado' : 'Asignado a repartidor' }}</div>
                            <div class="sub">{{ $cancelado ? 'El pedido fue cancelado' : ($pedido->motorizado->nombre_completo ?? '—') }}</div>
                        </div>
                    </div>

                    @if(!$cancelado)
                    {{-- 3. En camino --}}
                    <div class="timeline-step {{ $enCaminoDone ? 'done' : ($pedido->estado === 'en_camino' ? 'active' : '') }}">
                        <div class="step-icon"><i class="bi bi-truck"></i></div>
                        <div class="step-label">
                            <div class="title">En camino</div>
                            <div class="sub">{{ $enCaminoDone ? 'El repartidor va hacia tu dirección' : 'Próximo paso' }}</div>
                        </div>
                    </div>

                    {{-- 4. En ruta / Llegada --}}
                    <div class="timeline-step {{ $enRutaDone ? 'done' : ($pedido->estado === 'en_ruta' ? 'active' : '') }}">
                        <div class="step-icon"><i class="bi bi-geo-alt"></i></div>
                        <div class="step-label">
                            <div class="title">Repartidor cerca</div>
                            <div class="sub">{{ $enRutaDone ? 'El repartidor está en tu zona' : 'Próximo paso' }}</div>
                        </div>
                    </div>

                    {{-- 5. Entregado --}}
                    <div class="timeline-step {{ $entregadoDone ? 'done' : '' }}">
                        <div class="step-icon"><i class="bi bi-check2-circle"></i></div>
                        <div class="step-label">
                            <div class="title">Entregado</div>
                            <div class="sub">{{ $entregadoDone && $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y H:i') : '—' }}</div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- WhatsApp si está entregado con envases pendientes --}}
        @if($pedido->estado === 'entregado')
        <div class="result-card" style="margin-top:1rem;background:#f0fdf4;border:1px solid #bbf7d0;">
            <div style="padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                <div>
                    <span class="fw-semibold" style="color:#166534;"><i class="bi bi-hand-thumbs-up me-1"></i> ¡Pedido entregado!</span>
                    <small class="d-block text-secondary" style="font-size:0.8rem;">Gracias por confiar en AnyGas.</small>
                </div>
                @if($pedido->cliente->telefono)
                <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $pedido->cliente->telefono) }}?text=Hola%20AnyGas!%20Quiero%20consultar%20sobre%20mi%20pedido%20{{ $pedido->codigo_seguimiento }}."
                   target="_blank" class="btn btn-success rounded-pill px-3" style="background:#25D366;border-color:#25D366;font-size:0.85rem;white-space:nowrap;">
                    <i class="bi bi-whatsapp me-1"></i> Contactar
                </a>
                @endif
            </div>
        </div>
        @elseif($pedido->estado === 'cancelado')
        <div class="result-card" style="margin-top:1rem;background:#fef2f2;border:1px solid #fecaca;">
            <div style="padding:1rem 1.5rem;">
                <span class="fw-semibold" style="color:#dc2626;"><i class="bi bi-x-circle me-1"></i> Pedido cancelado</span>
                <small class="d-block text-secondary" style="font-size:0.8rem;">Si tienes dudas, contáctanos por WhatsApp.</small>
            </div>
        </div>
        @endif

    </div>
    @else
    <div class="tracking-wrap">
        <div class="result-card" style="text-align:center;padding:3rem 2rem;">
            <div style="font-size:3rem;color:#e2e8f0;margin-bottom:1rem;">
                <i class="bi bi-droplet-half"></i>
            </div>
            <h5 class="fw-bold" style="color:#0f172a;">Busca tu pedido</h5>
            <p class="text-secondary" style="font-size:0.9rem;max-width:320px;margin:0 auto;">
                Ingresa el código de seguimiento que recibiste al realizar tu pedido para ver su estado en tiempo real.
            </p>
        </div>
    </div>
    @endif

    <div class="footer">
        <i class="bi bi-droplet-half me-1"></i> AnyGas &mdash; {{ date('Y') }}
    </div>

</body>
</html>
