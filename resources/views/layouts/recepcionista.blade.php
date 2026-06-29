<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — @yield('titulo', 'Mostrador y Recepción')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --anygas-dark: #111827;     /* Gris casi negro corporativo */
            --anygas-amber: #F59E0B;    /* Amarillo/Dorado GLP */
            --anygas-light: #F9FAFB;
        }

        body, html {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background-color: var(--anygas-light);
        }

        .layout-shell {
            height: 100vh;
            overflow: hidden;
        }

        /* --- BARRA LATERAL (SIDEBAR) --- */
        .sidebar {
            background-color: var(--anygas-dark);
            min-height: 100vh;
            width: 85px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            overflow-y: auto;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .sidebar:hover {
            width: 260px;
        }

        .sidebar a {
            text-decoration: none;
        }

        /* Representación limpia del logo colapsado vs expandido */
        .logo-collapsed {
            display: block;
            font-size: 1.8rem;
            text-align: center;
        }

        .logo-expanded {
            display: none;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--anygas-amber);
            letter-spacing: 1px;
        }

        .sidebar:hover .logo-collapsed {
            display: none;
        }

        .sidebar:hover .logo-expanded {
            display: block;
        }

        .sidebar .nav-link {
            color: #9CA3AF;
            border-radius: 0;
            padding: 16px 20px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link i {
            font-size: 1.3rem;
            min-width: 35px;
            text-align: center;
            color: #D1D5DB;
        }

        .sidebar .nav-link .menu-text {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease;
            margin-left: 12px;
            font-weight: 500;
        }

        .sidebar:hover .nav-link .menu-text {
            opacity: 1;
            visibility: visible;
        }

        .sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--anygas-amber);
        }

        .sidebar .nav-link:hover i {
            color: var(--anygas-amber);
        }

        .sidebar .nav-link.active {
            color: var(--anygas-amber) !important;
            background-color: rgba(245, 158, 11, 0.1) !important;
            border-left: 4px solid var(--anygas-amber);
            font-weight: 700;
        }

        .sidebar .nav-link.active i {
            color: var(--anygas-amber);
        }

        /* --- CONTENIDO PRINCIPAL --- */
        .app-content {
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .top-header {
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 20;
            background-color: #ffffff;
        }

        .app-main {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* --- MODO MÓVIL / DRAWER --- */
        @media (max-width: 991.98px) {
            .layout-shell {
                flex-direction: column;
            }

            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 1050;
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                box-shadow: 0 18px 50px rgba(0, 0, 0, 0.3);
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .sidebar .logo-collapsed {
                display: none;
            }

            .sidebar .logo-expanded {
                display: block;
                margin-top: 8px;
            }

            .sidebar .nav-link .menu-text {
                opacity: 1;
                visibility: visible;
            }

            .app-content {
                width: 100%;
            }

            .mobile-sidebar-backdrop {
                display: block !important;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
            }

            .mobile-sidebar-backdrop.show {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex flex-nowrap layout-shell">

        <aside id="recepcionSidebar" class="sidebar d-flex flex-column flex-shrink-0 pt-3 shadow">

            <a href="/recepcionista/dashboard" class="d-flex justify-content-center align-items-center mb-3 py-2 text-decoration-none">
                <span class="logo-collapsed">🔥</span>
                <span class="logo-expanded">⚡ ANYGAS</span>
            </a>

            <hr class="text-secondary mx-3 my-1 opacity-25">

            <ul class="nav nav-pills flex-column mb-auto mt-2 px-0">
                
                <li class="nav-item">
                    <a href="/recepcionista/dashboard" class="nav-link {{ request()->is('recepcionista/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-text">Panel Principal</span>
                    </a>
                </li>

                <li class="nav-item mt-1">
                    <a href="/recepcionista/punto-venta" class="nav-link {{ request()->is('recepcionista/punto-venta*') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i>
                        <span class="menu-text">Toma de Pedido</span>
                    </a>
                </li>

                <li class="nav-item mt-1">
                    <a href="/recepcionista/catalogo" class="nav-link {{ request()->is('recepcionista/catalogo*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span class="menu-text">Catálogo GLP</span>
                    </a>
                </li>

                <li class="nav-item mt-1">
                    <a href="/recepcionista/asignar" class="nav-link {{ request()->is('recepcionista/asignar*') ? 'active' : '' }}">
                        <i class="bi bi-motorcycle"></i>
                        <span class="menu-text">Despacho y Rutas</span>
                    </a>
                </li>

                <li class="nav-item mt-1">
                    <a href="/recepcionista/historial" class="nav-link {{ request()->is('recepcionista/historial*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span class="menu-text">Historial General</span>
                    </a>
                </li>

            </ul>

            <div class="p-3 text-center border-top border-secondary border-opacity-25">
                <small class="text-secondary d-none d-sm-block" style="font-size: 0.65rem;">🟢 Sistema Activo</small>
            </div>
        </aside>

        <div class="w-100 d-flex flex-column app-content">

            <header class="top-header px-4 py-3 border-bottom shadow-sm d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3 text-dark">
                    <button id="sidebarToggle" type="button" class="btn btn-dark d-lg-none shadow-sm">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning text-dark me-2 px-2 py-1"><i class="bi bi-headset me-1"></i> Mostrador</span>
                        <span class="text-secondary d-none d-md-inline me-1">Operador:</span>
                        <span class="fw-bold fs-6 text-dark">{{ Auth::user()->nombre_completo ?? 'Recepcionista' }}</span>
                    </div>
                </div>

                <div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-semibold px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Cerrar Turno
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-4 app-main">
                @yield('contenido')
            </main>

        </div>
    </div>

    <div id="mobileSidebarBackdrop" class="mobile-sidebar-backdrop d-none"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('recepcionSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('mobileSidebarBackdrop');

            const closeSidebar = () => {
                sidebar?.classList.remove('sidebar-open');
                sidebarBackdrop?.classList.remove('show');
            };

            const openSidebar = () => {
                sidebar?.classList.add('sidebar-open');
                sidebarBackdrop?.classList.add('show');
            };

            if (sidebarToggle && sidebar && sidebarBackdrop) {
                sidebarBackdrop.classList.remove('d-none');

                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
                });

                sidebarBackdrop.addEventListener('click', closeSidebar);

                sidebar.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 992) closeSidebar();
                    });
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 992) closeSidebar();
                });
            }
        });
    </script>
</body>
</html>