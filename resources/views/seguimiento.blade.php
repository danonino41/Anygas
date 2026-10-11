<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — Seguimiento de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png">
    <style>
        :root {
            --anygas-dark: #111827;
            --anygas-amber: #F59E0B;
            --anygas-amber-hover: #D97706;
            --anygas-light: #FFFBEB;
            --anygas-green: #10B981;
            --anygas-green-dark: #059669;
            --anygas-red: #EF4444;
            --anygas-blue: #3B82F6;
        }
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        body { background: #F9FAFB; min-height: 100vh; }

        /* ─── NAVBAR ─── */
        .nav-seguimiento {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #E5E7EB;
        }

        /* ─── HERO ─── */
        .hero {
            background: var(--anygas-dark);
            position: relative;
            overflow: hidden;
            padding: 3rem 0 4rem;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(245,158,11,0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59,130,246,0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(255,255,255,0.02) 0%, transparent 60%);
        }
        .hero-content { position: relative; z-index: 1; max-width: 560px; margin: 0 auto; text-align: center; }

        .hero-title {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }
        .hero-title .amber { color: var(--anygas-amber); }
        .hero-sub { color: #94A3B8; font-size: 0.95rem; margin-bottom: 2rem; }

        /* ─── SEARCH ─── */
        .search-wrap {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 6px;
            display: flex;
            gap: 6px;
            max-width: 480px;
            margin: 0 auto;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .search-wrap:focus-within {
            border-color: rgba(245,158,11,0.5);
            box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
        }
        .search-wrap input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.85rem 1rem;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            outline: none;
        }
        .search-wrap input::placeholder { color: #64748B; text-transform: none; letter-spacing: 0; font-weight: 400; }
        .search-wrap .btn-search {
            background: var(--anygas-amber);
            border: none;
            border-radius: 14px;
            padding: 0.85rem 1.8rem;
            color: var(--anygas-dark);
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .search-wrap .btn-search:hover { background: var(--anygas-amber-hover); color: white; transform: scale(1.02); }

        .hero-steps {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
        }
        .hero-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748B;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .hero-step .num {
            width: 22px; height: 22px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: #94A3B8;
        }
        .hero-step .arrow { color: #475569; margin: 0 -0.5rem; }

        .error-msg {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #FCA5A5;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ─── RESULTS ─── */
        .results-area { margin-top: -2.5rem; position: relative; z-index: 2; padding-bottom: 3rem; }

        .card-order {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.06);
            overflow: hidden;
            border: 1px solid #F3F4F6;
            animation: slideUp 0.4s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .order-header {
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #FFFBEB 0%, #FFFFFF 100%);
            border-bottom: 1px solid #FEF3C7;
        }
        .order-code { font-size: 1.3rem; font-weight: 900; color: var(--anygas-dark); letter-spacing: -0.5px; }
        .order-date { font-size: 0.8rem; color: #9CA3AF; font-weight: 500; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .status-pill .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: currentColor;
        }
        .status-pendiente  { background: #FEF3C7; color: #92400E; }
        .status-asignado   { background: #DBEAFE; color: #1E40AF; }
        .status-en_camino  { background: #E0F2FE; color: #0369A1; }
        .status-en_ruta    { background: #E0F2FE; color: #0369A1; }
        .status-entregado  { background: #D1FAE5; color: #065F46; }
        .status-cancelado  { background: #FEE2E2; color: #991B1B; }
        .status-entregado .dot { animation: pulseDot 2s infinite; }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .order-body { padding: 1.5rem 1.75rem; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .info-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }
        .info-item .icon-box {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }
        .info-item .icon-box.amber  { background: #FEF3C7; color: #D97706; }
        .info-item .icon-box.blue   { background: #DBEAFE; color: #2563EB; }
        .info-item .icon-box.green  { background: #D1FAE5; color: #059669; }
        .info-item .icon-box.gray   { background: #F3F4F6; color: #6B7280; }
        .info-label { font-size: 0.7rem; color: #9CA3AF; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .info-value { font-size: 0.88rem; color: #111827; font-weight: 600; }

        /* ─── DRIVER CARD ─── */
        .driver-strip {
            background: linear-gradient(135deg, #111827 0%, #1F2937 100%);
            border-radius: 16px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .driver-avatar {
            width: 48px; height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--anygas-amber), var(--anygas-amber-hover));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1rem;
        }
        .driver-name { color: white; font-weight: 700; font-size: 0.9rem; }
        .driver-role { color: #9CA3AF; font-size: 0.75rem; }
        .btn-whatsapp {
            background: #25D366;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            margin-left: auto;
            text-decoration: none;
        }
        .btn-whatsapp:hover { background: #128C7E; color: white; transform: scale(1.05); }

        /* ─── PRODUCTS ─── */
        .products-section {
            background: #F9FAFB;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }
        .products-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .product-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
        }
        .product-row + .product-row { border-top: 1px solid #E5E7EB; }
        .product-name { font-weight: 600; color: #374151; font-size: 0.9rem; }
        .product-qty { color: #9CA3AF; font-size: 0.8rem; }
        .product-price { font-weight: 700; color: #111827; }
        .product-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            margin-top: 0.5rem;
            border-top: 2px solid var(--anygas-amber);
        }
        .product-total .label { font-weight: 800; color: #111827; font-size: 0.9rem; }
        .product-total .amount { font-weight: 900; font-size: 1.3rem; color: var(--anygas-green-dark); }

        /* ─── TIMELINE ─── */
        .timeline-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #F3F4F6;
            margin-top: 1rem;
            padding: 1.75rem;
            animation: slideUp 0.5s ease;
        }
        .timeline-header {
            font-weight: 800;
            color: #111827;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .timeline-header i { color: var(--anygas-amber); }

        .tl { position: relative; padding-left: 40px; }
        .tl::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: #E5E7EB;
        }

        .tl-item {
            position: relative;
            padding-bottom: 1.75rem;
        }
        .tl-item:last-child { padding-bottom: 0; }

        .tl-dot {
            position: absolute;
            left: -32px;
            top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.55rem;
            z-index: 1;
            border: 2px solid #E5E7EB;
            background: white;
            color: #D1D5DB;
        }

        .tl-item.done .tl-dot    { background: var(--anygas-green); border-color: var(--anygas-green); color: white; }
        .tl-item.active .tl-dot  { background: var(--anygas-amber); border-color: var(--anygas-amber); color: white; box-shadow: 0 0 0 4px rgba(245,158,11,0.2); animation: pulseRing 2s infinite; }
        .tl-item.cancel .tl-dot  { background: var(--anygas-red); border-color: var(--anygas-red); color: white; }

        @keyframes pulseRing {
            0%, 100% { box-shadow: 0 0 0 4px rgba(245,158,11,0.2); }
            50%      { box-shadow: 0 0 0 8px rgba(245,158,11,0.08); }
        }

        .tl-item.done + .tl-item.done::before,
        .tl-item.done + .tl-item.active::before { background: var(--anygas-green); }

        .tl-title { font-weight: 700; font-size: 0.88rem; color: #9CA3AF; }
        .tl-sub { font-size: 0.78rem; color: #D1D5DB; margin-top: 2px; }
        .tl-item.done .tl-title   { color: #065F46; }
        .tl-item.done .tl-sub    { color: #6B7280; }
        .tl-item.active .tl-title { color: #92400E; }
        .tl-item.active .tl-sub  { color: #92400E; }
        .tl-item.cancel .tl-title { color: #991B1B; }
        .tl-item.cancel .tl-sub  { color: #DC2626; }

        /* ─── STATUS BANNER ─── */
        .banner-entregado {
            margin-top: 1rem;
            background: linear-gradient(135deg, #D1FAE5 0%, #ECFDF5 100%);
            border: 1px solid #A7F3D0;
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            animation: slideUp 0.5s ease;
        }
        .banner-cancelado {
            margin-top: 1rem;
            background: linear-gradient(135deg, #FEE2E2 0%, #FEF2F2 100%);
            border: 1px solid #FECACA;
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            animation: slideUp 0.5s ease;
        }

        /* ─── EMPTY STATE ─── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            animation: slideUp 0.4s ease;
        }
        .empty-icon {
            width: 80px; height: 80px;
            background: #F3F4F6;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #D1D5DB;
            margin-bottom: 1.25rem;
        }

        /* ─── FOOTER ─── */
        .footer-track {
            text-align: center;
            padding: 2rem 1rem;
            color: #9CA3AF;
            font-size: 0.8rem;
        }
        .footer-track a { color: var(--anygas-amber); text-decoration: none; font-weight: 600; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .hero { padding: 2rem 0 3rem; }
            .hero-title { font-size: 1.5rem; }
            .search-wrap { flex-direction: column; border-radius: 16px; padding: 4px; }
            .search-wrap input { text-align: center; padding: 0.75rem; }
            .search-wrap .btn-search { justify-content: center; border-radius: 12px; padding: 0.75rem; }
            .hero-steps { gap: 1rem; font-size: 0.7rem; }
            .info-grid { grid-template-columns: 1fr; }
            .order-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .order-body { padding: 1.25rem; }
            .driver-strip { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar nav-seguimiento py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 38px;">
                <span class="fw-bold text-dark" style="font-size:1.1rem;">Any<span style="color:var(--anygas-amber);">Gas</span></span>
            </a>
            <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" style="font-size:0.82rem;">
                <i class="bi bi-house me-1"></i> Inicio
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="bi bi-droplet-fill amber me-2"></i>Seguimiento de <span class="amber">Pedido</span>
                </h1>
                <p class="hero-sub">Consulta el estado de tu entrega en tiempo real</p>

                <form action="{{ route('cliente.seguimiento.consultar') }}" method="GET" class="search-wrap">
                    <input type="text" name="codigo" placeholder="Ej: ANG-4521"
                           value="{{ request('codigo') }}" required autocomplete="off" autofocus>
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </form>

                @error('codigo')
                    <div class="error-msg"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
                @enderror

                <div class="hero-steps">
                    <div class="hero-step"><span class="num">1</span> Registrado</div>
                    <span class="arrow"><i class="bi bi-chevron-right"></i></span>
                    <div class="hero-step"><span class="num">2</span> Asignado</div>
                    <span class="arrow"><i class="bi bi-chevron-right"></i></span>
                    <div class="hero-step"><span class="num">3</span> En Camino</div>
                    <span class="arrow"><i class="bi bi-chevron-right"></i></span>
                    <div class="hero-step"><span class="num">4</span> Entregado</div>
                </div>
            </div>
        </div>
    </section>

    <!-- RESULTS -->
    <div class="container results-area">

        @if(isset($pedido))
            @php
                $est = match($pedido->estado) {
                    'pendiente' => ['label' => 'Pendiente',  'css' => 'status-pendiente',  'icon' => 'bi-hourglass-split'],
                    'asignado'  => ['label' => 'Asignado',   'css' => 'status-asignado',   'icon' => 'bi-person-check'],
                    'en_camino' => ['label' => 'En Camino',  'css' => 'status-en_camino',  'icon' => 'bi-truck'],
                    'en_ruta'   => ['label' => 'En Ruta',    'css' => 'status-en_ruta',    'icon' => 'bi-geo-alt'],
                    'entregado' => ['label' => 'Entregado',  'css' => 'status-entregado',  'icon' => 'bi-check2-circle'],
                    'cancelado' => ['label' => 'Cancelado',  'css' => 'status-cancelado',  'icon' => 'bi-x-circle'],
                    default     => ['label' => $pedido->estado, 'css' => 'status-pendiente', 'icon' => 'bi-question'],
                };
            @endphp

            {{-- ORDER CARD --}}
            <div class="card-order">
                <div class="order-header">
                    <div>
                        <div class="order-code">
                            <i class="bi bi-receipt-cutoff me-1" style="color:var(--anygas-amber);"></i>
                            {{ $pedido->codigo_seguimiento }}
                        </div>
                        <div class="order-date">Registrado {{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y \a\l\s H:i') }}</div>
                    </div>
                    <span class="status-pill {{ $est['css'] }}">
                        <span class="dot"></span> {{ $est['label'] }}
                    </span>
                </div>

                <div class="order-body">
                    {{-- INFO GRID --}}
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="icon-box amber"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="info-label">Cliente</div>
                                <div class="info-value">{{ $pedido->cliente->nombre_completo }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon-box blue"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="info-label">Dirección</div>
                                <div class="info-value">{{ $pedido->direccion_entrega }}</div>
                            </div>
                        </div>
                        @if($pedido->referencia_entrega)
                        <div class="info-item">
                            <div class="icon-box gray"><i class="bi bi-signpost-2-fill"></i></div>
                            <div>
                                <div class="info-label">Referencia</div>
                                <div class="info-value">{{ $pedido->referencia_entrega }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="info-item">
                            <div class="icon-box green"><i class="bi bi-box-seam-fill"></i></div>
                            <div>
                                <div class="info-label">Productos</div>
                                <div class="info-value">{{ $pedido->detalles->sum('cantidad') }} items</div>
                            </div>
                        </div>
                    </div>

                    {{-- DRIVER --}}
                    @if($pedido->motorizado)
                    <div class="driver-strip">
                        <div class="driver-avatar">
                            @php
                                $iniciales = collect(explode(' ', $pedido->motorizado->nombre_completo ?? 'R R'))
                                    ->take(2)->map(fn($p) => strtoupper(substr($p,0,1)))->implode('');
                            @endphp
                            {{ $iniciales ?: 'M' }}
                        </div>
                        <div>
                            <div class="driver-name">{{ $pedido->motorizado->nombre_completo }}</div>
                            <div class="driver-role"><i class="bi bi-shield-check me-1"></i>Repartidor autorizado</div>
                        </div>
                        @if($pedido->cliente->telefono)
                        <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $pedido->cliente->telefono) }}?text=Hola,%20soy%20el%20repartidor%20de%20AnyGas.%20Voy%20en%20camino%20con%20tu%20pedido%20{{ $pedido->codigo_seguimiento }}."
                           target="_blank" class="btn-whatsapp">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        @endif
                    </div>
                    @endif

                    {{-- PRODUCTS --}}
                    <div class="products-section">
                        <div class="products-title"><i class="bi bi-box-seam"></i> Productos del Pedido</div>
                        @foreach($pedido->detalles as $d)
                        <div class="product-row">
                            <div>
                                <span class="product-name">{{ $d->producto->nombre ?? 'Producto' }}</span>
                                @if($d->producto->marca)
                                    <span class="product-qty ms-1">{{ $d->producto->marca }}</span>
                                @endif
                                <div class="product-qty">x{{ $d->cantidad }}</div>
                            </div>
                            <span class="product-price">S/ {{ number_format($d->subtotal, 2) }}</span>
                        </div>
                        @endforeach
                        <div class="product-total">
                            <span class="label">Total a Pagar</span>
                            <span class="amount">S/ {{ number_format($pedido->monto_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TIMELINE --}}
            @php
                $isCancel = $pedido->estado === 'cancelado';
                $isEntregado = $pedido->estado === 'entregado';
                $isAsignado = in_array($pedido->estado, ['asignado','en_camino','en_ruta','entregado']);
                $isEnCamino = in_array($pedido->estado, ['en_camino','en_ruta','entregado']);
                $isEnRuta = in_array($pedido->estado, ['en_ruta','entregado']);
            @endphp
            <div class="timeline-card">
                <div class="timeline-header"><i class="bi bi-clock-history"></i> Historial del Pedido</div>
                <div class="tl">
                    {{-- 1. Registrado --}}
                    <div class="tl-item done">
                        <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                        <div class="tl-title">Pedido Registrado</div>
                        <div class="tl-sub">{{ \Carbon\Carbon::parse($pedido->fecha_registro)->format('d/m/Y \a\l\s H:i') }}</div>
                    </div>

                    {{-- 2. Asignado / Cancelado --}}
                    <div class="tl-item {{ $isCancel ? 'cancel' : ($isAsignado ? 'done' : '') }}">
                        <div class="tl-dot">
                            <i class="bi {{ $isCancel ? 'bi-x-lg' : 'bi-person-check' }}"></i>
                        </div>
                        <div class="tl-title">{{ $isCancel ? 'Pedido Cancelado' : 'Asignado a Repartidor' }}</div>
                        <div class="tl-sub">{{ $isCancel ? 'El pedido fue cancelado' : ($pedido->motorizado->nombre_completo ?? 'Pendiente de asignación') }}</div>
                    </div>

                    @if(!$isCancel)
                    {{-- 3. En camino --}}
                    <div class="tl-item {{ $isEnCamino ? 'done' : ($pedido->estado === 'en_camino' ? 'active' : '') }}">
                        <div class="tl-dot"><i class="bi bi-truck"></i></div>
                        <div class="tl-title">En Camino</div>
                        <div class="tl-sub">{{ $isEnCamino ? 'El repartidor va hacia tu dirección' : 'Esperando salida del repartidor' }}</div>
                    </div>

                    {{-- 4. Cerca / En ruta --}}
                    <div class="tl-item {{ $isEnRuta ? 'done' : ($pedido->estado === 'en_ruta' ? 'active' : '') }}">
                        <div class="tl-dot"><i class="bi bi-geo-alt"></i></div>
                        <div class="tl-title">Repartidor en tu Zona</div>
                        <div class="tl-sub">{{ $isEnRuta ? 'El repartidor está cerca de tu dirección' : 'Próximamente' }}</div>
                    </div>

                    {{-- 5. Entregado --}}
                    <div class="tl-item {{ $isEntregado ? 'done' : '' }}">
                        <div class="tl-dot"><i class="bi bi-check2-circle"></i></div>
                        <div class="tl-title">Entregado</div>
                        <div class="tl-sub">{{ $isEntregado && $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y \a\l\s H:i') : 'Pendiente de entrega' }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- STATUS BANNER --}}
            @if($isEntregado)
            <div class="banner-entregado">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#34D399;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-circle-fill text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#065F46;">¡Pedido Entregado con Éxito!</div>
                        <small style="color:#6B7280;font-size:0.82rem;">Gracias por confiar en AnyGas</small>
                    </div>
                </div>
                @if($pedido->cliente->telefono)
                <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $pedido->cliente->telefono) }}?text=Hola%20AnyGas!%20Quiero%20consultar%20sobre%20mi%20pedido%20{{ $pedido->codigo_seguimiento }}."
                   target="_blank" class="btn-whatsapp" style="flex-shrink:0;">
                    <i class="bi bi-whatsapp"></i> Contactar
                </a>
                @endif
            </div>
            @elseif($isCancel)
            <div class="banner-cancelado">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;background:#F87171;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-x-circle-fill text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#991B1B;">Pedido Cancelado</div>
                        <small style="color:#DC2626;font-size:0.82rem;">Si tienes dudas, contáctanos por WhatsApp</small>
                    </div>
                </div>
            </div>
            @endif

        @else
            {{-- EMPTY STATE --}}
            <div class="card-order">
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-search"></i></div>
                    <h5 class="fw-bold text-dark mb-2">Busca tu Pedido</h5>
                    <p class="text-secondary mx-auto" style="max-width:360px;font-size:0.9rem;">
                        Ingresa tu código de seguimiento para ver el estado actual de tu entrega.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer-track">
        <i class="bi bi-droplet-fill" style="color:var(--anygas-amber);"></i>
        <a href="/">AnyGas</a> &mdash; Distribuidora de Gas GLP &mdash; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
