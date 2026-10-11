<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — Distribuidora de Gas GLP</title>
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
            --anygas-warm: #78350F;
        }

        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        body { background: #ffffff; }

        /* ─── TOP BAR ─── */
        .top-bar {
            background: var(--anygas-dark);
            color: #9CA3AF;
            font-size: 0.78rem;
        }
        .top-bar a { color: var(--anygas-amber); text-decoration: none; }

        /* ─── NAVBAR ─── */
        .navbar-landing {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #f3f4f6;
            transition: box-shadow 0.3s;
        }
        .navbar-landing.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,0.08); }
        .navbar-brand-text { font-weight: 900; font-size: 1.5rem; color: var(--anygas-dark); letter-spacing: -0.5px; }
        .navbar-brand-text span { color: var(--anygas-amber); }
        .nav-link-landing {
            font-weight: 600;
            color: #374151 !important;
            transition: color 0.2s;
            font-size: 0.9rem;
        }
        .nav-link-landing:hover { color: var(--anygas-amber) !important; }

        /* ─── HERO ─── */
        .hero {
            background: linear-gradient(165deg, #FFFBEB 0%, #FEF3C7 30%, #ffffff 100%);
            min-height: 92vh;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -150px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(120,53,15,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--anygas-dark);
        }
        .hero-title .highlight {
            background: linear-gradient(135deg, var(--anygas-amber), var(--anygas-amber-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 1.15rem;
            color: #6B7280;
            line-height: 1.7;
            max-width: 500px;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 100px;
            padding: 6px 16px 6px 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .hero-badge .dot {
            width: 8px; height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .btn-hero-primary {
            background: var(--anygas-dark);
            color: white;
            border: none;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-hero-primary:hover {
            background: #1F2937;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17,24,39,0.25);
        }
        .btn-hero-outline {
            border: 2px solid #D1D5DB;
            color: #374151;
            background: white;
            padding: 14px 32px;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-hero-outline:hover {
            border-color: var(--anygas-amber);
            color: var(--anygas-amber-hover);
            transform: translateY(-2px);
        }
        .hero-visual {
            position: relative;
            z-index: 2;
        }
        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            border: 1px solid #F3F4F6;
        }
        .hero-stat {
            text-align: center;
            padding: 12px;
        }
        .hero-stat-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--anygas-dark);
        }
        .hero-stat-label {
            font-size: 0.75rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ─── FEATURES ─── */
        .features { padding: 100px 0; background: #ffffff; }
        .feature-card {
            background: white;
            border: 1px solid #F3F4F6;
            border-radius: 16px;
            padding: 32px;
            transition: all 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            border-color: var(--anygas-amber);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(245,158,11,0.12);
        }
        .feature-icon {
            width: 56px; height: 56px;
            background: var(--anygas-light);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--anygas-amber);
            margin-bottom: 20px;
        }
        .feature-title { font-weight: 800; color: var(--anygas-dark); margin-bottom: 10px; font-size: 1.05rem; }
        .feature-text { color: #6B7280; font-size: 0.9rem; line-height: 1.6; }

        /* ─── HOW IT WORKS ─── */
        .how-it-works {
            background: var(--anygas-dark);
            padding: 100px 0;
            color: white;
        }
        .step-number {
            width: 48px; height: 48px;
            background: var(--anygas-amber);
            color: var(--anygas-dark);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.2rem;
            margin-bottom: 16px;
        }
        .step-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 8px; }
        .step-text { color: #9CA3AF; font-size: 0.9rem; line-height: 1.6; }
        .step-connector {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 40px;
        }
        .step-connector-line {
            width: 60px; height: 2px;
            background: rgba(245,158,11,0.3);
        }
        @media (max-width: 991.98px) {
            .step-connector { display: none; }
        }

        /* ─── TRACKING CTA ─── */
        .tracking-section {
            background: linear-gradient(180deg, #ffffff 0%, var(--anygas-light) 100%);
            padding: 100px 0;
        }
        .tracking-card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.06);
            border: 1px solid #F3F4F6;
        }
        .tracking-input {
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: border-color 0.3s;
        }
        .tracking-input:focus {
            border-color: var(--anygas-amber);
            box-shadow: 0 0 0 4px rgba(245,158,11,0.1);
            outline: none;
        }
        .btn-track {
            background: var(--anygas-amber);
            color: var(--anygas-dark);
            border: none;
            padding: 16px 40px;
            font-weight: 800;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .btn-track:hover {
            background: var(--anygas-amber-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.3);
        }

        /* ─── TRUST / STATS ─── */
        .trust-bar {
            background: white;
            border-top: 1px solid #F3F4F6;
            border-bottom: 1px solid #F3F4F6;
            padding: 48px 0;
        }
        .trust-item {
            text-align: center;
            padding: 16px;
        }
        .trust-value {
            font-size: 2.2rem;
            font-weight: 900;
            color: var(--anygas-dark);
        }
        .trust-label {
            font-size: 0.8rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .trust-icon {
            font-size: 1.8rem;
            color: var(--anygas-amber);
            margin-bottom: 8px;
        }

        /* ─── CONTACT / FOOTER ─── */
        .footer-landing {
            background: var(--anygas-dark);
            color: #9CA3AF;
            padding: 64px 0 32px;
        }
        .footer-brand { font-weight: 900; font-size: 1.3rem; color: white; }
        .footer-brand span { color: var(--anygas-amber); }
        .footer-link {
            color: #9CA3AF;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
            display: block;
            padding: 4px 0;
        }
        .footer-link:hover { color: var(--anygas-amber); }
        .footer-bottom {
            border-top: 1px solid #1F2937;
            padding-top: 24px;
            margin-top: 40px;
            font-size: 0.8rem;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 767.98px) {
            .hero-title { font-size: 2.2rem; }
            .hero { min-height: auto; padding: 60px 0; }
            .hero-card { margin-top: 40px; }
            .trust-value { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <!-- TOP BAR -->
    <div class="top-bar py-2 d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-clock me-1"></i> Horario: Lunes a Domingo — 6:00 AM a 10:00 PM
            </div>
            <div>
                <i class="bi bi-telephone me-1"></i> Central de Pedidos:
                <a href="tel:+511555269" class="fw-bold">(01) 555-ANYGAS</a>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-landing sticky-top py-3" id="mainNav">
        <div class="container">
            <a class="d-flex align-items-center gap-2 text-decoration-none" href="/">
                <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 42px;">
                <span class="navbar-brand-text d-none d-sm-inline">Any<span>Gas</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="bi bi-list fs-4 text-dark"></i>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navContent">
                <ul class="navbar-nav align-items-lg-center gap-2 gap-lg-3">
                    <li class="nav-item"><a class="nav-link nav-link-landing" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-landing" href="#como-funciona">Cómo Funciona</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-landing" href="#seguimiento-section">Rastrear Pedido</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-hero-primary btn-sm" href="/login">
                            <i class="bi bi-lock me-1"></i> Portal Empleados
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero d-flex align-items-center">
        <div class="container position-relative" style="z-index: 3;">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <span class="hero-badge">
                            <span class="dot"></span>
                            Despachos activos hoy
                        </span>
                    </div>
                    <h1 class="hero-title mt-3">
                        El gas que necesitas, <span class="highlight">más rápido que nunca</span>
                    </h1>
                    <p class="hero-subtitle mt-4 mb-4">
                        Distribuidora de Gas GLP con despacho express a domicilio. Seguridad garantizada, peso exacto y servicio confiable para tu hogar o negocio.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('cliente.seguimiento') }}" class="btn btn-hero-primary">
                            <i class="bi bi-search me-2"></i>Rastrear mi Pedido
                        </a>
                        <a href="#como-funciona" class="btn btn-hero-outline">
                            <i class="bi bi-play-circle me-2"></i>Cómo Funciona
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-5">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-shield-check text-success"></i>
                            </div>
                            <span class="fw-semibold text-dark" style="font-size:0.85rem;">Válvulas Certificadas</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-truck text-primary"></i>
                            </div>
                            <span class="fw-semibold text-dark" style="font-size:0.85rem;">Despacho en &lt;2h</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="hero-card">
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center gap-2 bg-light rounded-pill px-3 py-2 mb-3">
                                    <i class="bi bi-fire text-danger fs-5"></i>
                                    <span class="fw-bold text-dark" style="font-size:0.9rem;">Resumen del Día</span>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="hero-card bg-light rounded-3 p-3 text-center">
                                        <div class="hero-stat-value text-warning">24/7</div>
                                        <div class="hero-stat-label">Disponible</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="hero-card bg-light rounded-3 p-3 text-center">
                                        <div class="hero-stat-value text-success">< 2h</div>
                                        <div class="hero-stat-label">Despacho</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="hero-card bg-light rounded-3 p-3 text-center">
                                        <div class="hero-stat-value text-primary">100%</div>
                                        <div class="hero-stat-label">Seguro</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning bg-opacity-25 rounded-circle p-3">
                                        <i class="bi bi-geo-alt-fill text-warning fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.9rem;">Cobertura en Lima y Callao</div>
                                        <div class="text-muted" style="font-size:0.8rem;">San Martín de Porres, Comas, Los Olivos, SMP y más</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST BAR / STATS -->
    <section class="trust-bar">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="trust-value">5,000+</div>
                        <div class="trust-label">Clientes Atendidos</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="bi bi-truck"></i></div>
                        <div class="trust-value">15,000+</div>
                        <div class="trust-label">Despachos Realizados</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="bi bi-star-fill"></i></div>
                        <div class="trust-value">4.9</div>
                        <div class="trust-label">Calificación Promedio</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="bi bi-clock-history"></i></div>
                        <div class="trust-value">8+</div>
                        <div class="trust-label">Años de Experiencia</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section class="features" id="servicios">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3" style="font-size:0.75rem; letter-spacing:0.5px;">NUESTROS SERVICIOS</span>
                <h2 class="fw-bold text-dark" style="font-size: 2.2rem;">¿Por qué elegir <span class="text-warning">AnyGas</span>?</h2>
                <p class="text-muted mx-auto" style="max-width: 550px;">Ofrecemos soluciones integrales de distribución de gas GLP con los más altos estándares de calidad y seguridad.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h5 class="feature-title">Despacho Express</h5>
                        <p class="feature-text">Recibe tu gas en menos de 2 horas. Nuestros motorizados están preparados para llegar a tu ubicación con rapidez y seguridad.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="feature-title">Garantía de Calidad</h5>
                        <p class="feature-text">Balones con válvulas certificadas INDECOPI. Peso exacto garantizado en cada recarga, sin excusas.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h5 class="feature-title">Seguimiento en Tiempo Real</h5>
                        <p class="feature-text">Consulta el estado de tu pedido desde tu celular. Sabrás exactamente cuándo llega tu gas a domicilio.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h5 class="feature-title">Múltiples Medios de Pago</h5>
                        <p class="feature-text">Paga en efectivo, con tarjeta, Yape o Plin. La comodidad de elegir cómo pagar.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h5 class="feature-title">Control de Envases</h5>
                        <p class="feature-text">Sistema inteligente de seguimiento de balones vacíos. Recogemos tus envases en tu próximo pedido.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h5 class="feature-title">Atención Personalizada</h5>
                        <p class="feature-text">Equipo capacitado para atenderte. Soporte por teléfono, WhatsApp y en tienda de lunes a domingo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how-it-works" id="como-funciona">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 mb-3" style="font-size:0.75rem; letter-spacing:0.5px;">PASO A PASO</span>
                <h2 class="fw-bold text-white" style="font-size: 2.2rem;">¿Cómo funciona?</h2>
                <p class="text-secondary mx-auto" style="max-width: 500px;">Obtener tu gas nunca fue tan fácil. Solo 3 pasos simples.</p>
            </div>

            <div class="row align-items-start gy-4">
                <div class="col-lg-3 col-md-4 text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row flex-lg-column align-items-center align-items-md-start align-items-lg-center gap-3">
                        <div class="step-number">1</div>
                        <div>
                            <h5 class="step-title">Realiza tu Pedido</h5>
                            <p class="step-text">Llámanos o acude a nuestra tienda. Nuestro personal tomará tu pedido al instante.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-1 d-none d-lg-flex step-connector">
                    <div class="step-connector-line"></div>
                    <i class="bi bi-arrow-right text-warning mx-1"></i>
                    <div class="step-connector-line"></div>
                </div>

                <div class="col-lg-3 col-md-4 text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row flex-lg-column align-items-center align-items-md-start align-items-lg-center gap-3">
                        <div class="step-number">2</div>
                        <div>
                            <h5 class="step-title">Preparamos tu Despacho</h5>
                            <p class="step-text">Un motorizado sale con tu pedido. Puedes rastrear tu entrega en tiempo real.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-1 d-none d-lg-flex step-connector">
                    <div class="step-connector-line"></div>
                    <i class="bi bi-arrow-right text-warning mx-1"></i>
                    <div class="step-connector-line"></div>
                </div>

                <div class="col-lg-3 col-md-4 text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row flex-lg-column align-items-center align-items-md-start align-items-lg-center gap-3">
                        <div class="step-number">3</div>
                        <div>
                            <h5 class="step-title">Recibe en tu Hogar</h5>
                            <p class="step-text">Paga con el medio que prefieras. Recogemos el balón vacío en tu próximo pedido.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRACKING CTA -->
    <section class="tracking-section" id="seguimiento-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="tracking-card text-center">
                        <div class="d-inline-flex align-items-center gap-2 bg-warning bg-opacity-10 rounded-pill px-3 py-2 mb-4">
                            <i class="bi bi-geo-alt text-warning"></i>
                            <span class="fw-bold text-dark" style="font-size:0.8rem;">SEGUIMIENTO EN TIEMPO REAL</span>
                        </div>
                        <h2 class="fw-bold text-dark mb-3" style="font-size: 1.8rem;">¿Dónde está tu pedido?</h2>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">Ingresa tu código de seguimiento (ej: ANG-1234) y conoce el estado actual de tu entrega.</p>

                        <form action="{{ route('cliente.seguimiento.consultar') }}" method="GET" class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                            <input type="text" name="codigo" class="form-control tracking-input text-center flex-grow-1" style="max-width: 350px;" placeholder="ANG-1234" required>
                            <button type="submit" class="btn btn-track d-flex align-items-center gap-2">
                                <i class="bi bi-search"></i>
                                <span>Rastrear</span>
                            </button>
                        </form>

                        <div class="mt-4 d-flex flex-wrap justify-content-center gap-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-clock text-muted"></i>
                                <small class="text-muted fw-semibold">Recepción</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-arrow-right text-muted"></i>
                                <small class="text-muted fw-semibold">Asignado</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-arrow-right text-muted"></i>
                                <small class="text-muted fw-semibold">En Camino</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle text-success"></i>
                                <small class="text-success fw-semibold">Entregado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-landing">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 36px; filter: brightness(0) invert(1);">
                        <span class="footer-brand">Any<span>Gas</span></span>
                    </div>
                    <p style="font-size: 0.9rem; line-height: 1.7;">
                        Distribuidora de Gas GLP comprometida con la seguridad, calidad y rapidez. Llevamos energía a tu hogar desde 2017.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">EMPRESA</h6>
                    <a href="#servicios" class="footer-link">Servicios</a>
                    <a href="#como-funciona" class="footer-link">Cómo Funciona</a>
                    <a href="#seguimiento-section" class="footer-link">Rastrear Pedido</a>
                    <a href="/login" class="footer-link">Portal Empleados</a>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">SERVICIO AL CLIENTE</h6>
                    <a href="{{ route('cliente.seguimiento') }}" class="footer-link">Seguimiento de Pedidos</a>
                    <a href="#" class="footer-link">Preguntas Frecuentes</a>
                    <a href="#" class="footer-link">Términos y Condiciones</a>
                    <a href="#" class="footer-link">Política de Privacidad</a>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">CONTACTO</h6>
                    <div class="d-flex gap-2 align-items-start mb-2">
                        <i class="bi bi-geo-alt text-warning mt-1"></i>
                        <span style="font-size:0.88rem;">Av. Universitaria 4295, San Martín de Porres, Lima</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start mb-2">
                        <i class="bi bi-telephone text-warning mt-1"></i>
                        <span style="font-size:0.88rem;">(01) 555-ANYGAS</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start mb-2">
                        <i class="bi bi-envelope text-warning mt-1"></i>
                        <span style="font-size:0.88rem;">ventas@anygas.pe</span>
                    </div>
                    <div class="d-flex gap-2 align-items-start">
                        <i class="bi bi-clock text-warning mt-1"></i>
                        <span style="font-size:0.88rem;">Lun - Dom: 6:00 AM a 10:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} AnyGas — INVERSIONES CORRALES ESQUIVEL S.A.C.</span>
                <span>RUC: 20513813512 | Todos los derechos reservados</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar shadow on scroll
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        });
    </script>
</body>
</html>
