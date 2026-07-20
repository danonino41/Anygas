<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas - Distribuidora de Gas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            /* TRUCO PRO: Flexbox para obligar al footer a pegarse al fondo */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .hero-section {
            padding: 80px 0;
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
        }
        .top-bar {
            background-color: #e9ecef;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="top-bar py-1 text-center text-muted d-none d-md-block">
        ⚡ Horario de Despacho: Lunes a Domingo de 6:00 AM a 10:00 PM | Central de Pedidos: (01) 555-ANYGAS
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-3" href="/">
                🔥 AnyGas
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-3 mt-3 mt-lg-0">
                    
                    <li class="nav-item">
                        <a class="btn btn-outline-primary w-100" href="{{ route('cliente.seguimiento') }}">
                            📍 Rastrear Pedido
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="btn btn-primary w-100" href="/login">
                            🔒 Login Empleados
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center flex-grow-1 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    
                    <div class="mb-3 d-flex justify-content-center gap-2 flex-wrap">
                        <span class="badge bg-light text-secondary border">⏱️ Despacho Rápido</span>
                        <span class="badge bg-light text-secondary border">⚖️ Peso Exacto Garantizado</span>
                        <span class="badge bg-light text-secondary border">🛡️ Válvulas Seguras</span>
                    </div>

                    <h1 class="display-4 fw-bold text-dark mb-4">El gas que necesitas, <span class="text-primary">más rápido que nunca</span></h1>
                    <p class="lead text-muted mb-5">
                        En AnyGas modernizamos nuestra atención. Confía en nosotros para llevar la energía a tu hogar con total seguridad y rapidez.
                    </p>
                    
                    <div class="card shadow-sm border-0 bg-light mx-auto" style="max-width: 400px;">
                        <div class="card-body p-4">
                            <h5 class="card-title text-dark">¿Hiciste un pedido?</h5>
                            <p class="card-text text-muted small">
                                Ingresa tu código de seguimiento para conocer el estado de tu pedido en tiempo real.
                            </p>
                            
                            <div class="d-grid">
                                <a href="{{ route('cliente.seguimiento') }}" class="btn btn-primary d-flex justify-content-center align-items-center gap-2">
                                    <i class="bi bi-search"></i>
                                    <span>Rastrear mi Pedido</span>
                                </a>
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.7rem;">⚡ Arquitectura Laravel enlazada a MySQL</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white py-4 mt-auto text-center border-top">
        <div class="container text-muted small">
            &copy; {{ date('Y') }} AnyGas - Sistema de Gestión Logística. Todos los derechos reservados.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>