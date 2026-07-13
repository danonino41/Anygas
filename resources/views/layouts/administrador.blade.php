<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — @yield('titulo', 'Panel Administrativo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --anygas-dark: #111827;
            --anygas-amber: #F59E0B;
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
        .sidebar {
            background: linear-gradient(180deg, #111827 0%, #451a03 50%, #78350f 100%);
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
        .sidebar:hover { width: 260px; }
        .sidebar a { text-decoration: none; }
        .logo-collapsed {
            display: block;
            text-align: center;
        }
        .logo-expanded {
            display: none;
            font-weight: 800;
            color: var(--anygas-amber);
        }
        .sidebar:hover .logo-collapsed { display: none; }
        .sidebar:hover .logo-expanded { display: block; }
        .sidebar .nav-link {
            color: #9CA3AF;
            border-radius: 0;
            padding: 14px 20px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
        }
        .sidebar .nav-link i {
            font-size: 1.25rem;
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
            font-size: 0.85rem;
        }
        .sidebar:hover .nav-link .menu-text {
            opacity: 1;
            visibility: visible;
        }
        .sidebar .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255,255,255,0.05);
            border-left: 4px solid var(--anygas-amber);
        }
        .sidebar .nav-link:hover i { color: var(--anygas-amber); }
        .sidebar .nav-link.active {
            color: var(--anygas-amber) !important;
            background-color: rgba(245,158,11,0.1) !important;
            border-left: 4px solid var(--anygas-amber);
        }
        .sidebar .nav-link.active i { color: var(--anygas-amber); }
        .app-content {
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: url('data:image/svg+xml,%3Csvg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%235a187f" fill-opacity="0.03" fill-rule="evenodd"%3E%3Ccircle cx="3" cy="3" r="3"/%3E%3Ccircle cx="13" cy="13" r="3"/%3E%3C/g%3E%3C/svg%3E');
        }
        .top-header {
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 20;
            background-color: #fff;
        }
        .app-main {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        @media (max-width: 991.98px) {
            .layout-shell { flex-direction: column; }
            .sidebar {
                position: fixed; left: 0; top: 0; bottom: 0; z-index: 1050;
                width: 260px; transform: translateX(-100%);
                transition: transform 0.25s ease;
                box-shadow: 0 18px 50px rgba(0,0,0,0.3);
            }
            .sidebar.sidebar-open { transform: translateX(0); }
            .sidebar .logo-collapsed { display: none; }
            .sidebar .logo-expanded { display: block; margin-top: 8px; }
            .sidebar .nav-link .menu-text { opacity: 1; visibility: visible; }
            .app-content { width: 100%; }
            .mobile-sidebar-backdrop {
                display: block !important; position: fixed; inset: 0;
                background: rgba(0,0,0,0.5); z-index: 1040;
                opacity: 0; visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
            }
            .mobile-sidebar-backdrop.show { opacity: 1; visibility: visible; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex flex-nowrap layout-shell">

        <aside id="adminSidebar" class="sidebar d-flex flex-column flex-shrink-0 pt-3 shadow">
            <a href="/admin/dashboard" class="d-flex justify-content-center align-items-center mb-3 py-2 text-decoration-none">
                <span class="logo-collapsed">
                    <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 108px; width: 108px; object-fit: contain; border-radius: 8px;">
                </span>
                <span class="logo-expanded">
                    <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 120px; object-fit: contain;">
                </span>
            </a>
            <hr class="text-secondary mx-3 my-1 opacity-25">

            <ul class="nav nav-pills flex-column mb-auto mt-2 px-0">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span class="menu-text">Dashboard Financiero</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/kpis" class="nav-link {{ request()->is('admin/kpis*') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-text">KPIs y Métricas</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/kardex" class="nav-link {{ request()->is('admin/kardex*') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span class="menu-text">Kárdex de Almacén</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/compras" class="nav-link {{ request()->is('admin/compras*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span class="menu-text">Compras y Reabastecimiento</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/personal" class="nav-link {{ request()->is('admin/personal*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Gestión de Personal</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/productos" class="nav-link {{ request()->is('admin/productos*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span class="menu-text">Productos y Precios</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/clientes" class="nav-link {{ request()->is('admin/clientes*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        <span class="menu-text">Directorio de Clientes</span>
                    </a>
                </li>
                <li class="nav-item mt-1">
                    <a href="/admin/sunat" class="nav-link {{ request()->is('admin/sunat*') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i>
                        <span class="menu-text">Historial y SUNAT</span>
                    </a>
                </li>
            </ul>
        </aside>

        <div class="flex-grow-1 d-flex flex-column app-content">
            <header class="top-header px-4 py-3 border-bottom shadow-sm d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <button id="sidebarToggle" type="button" class="btn btn-dark d-lg-none shadow-sm">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-dark text-warning me-2 px-2 py-1"><i class="bi bi-shield-lock me-1"></i> Admin</span>
                        <span class="text-secondary d-none d-md-inline me-1">Bienvenido,</span>
                        <span class="fw-bold fs-6 text-dark">{{ Auth::user()->nombre_completo ?? 'Admin' }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="/recepcionista/dashboard" class="btn btn-outline-secondary btn-sm shadow-sm fw-semibold" title="Ir a Mostrador">
                        <i class="bi bi-headset me-1"></i> Mostrador
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm fw-semibold px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Salir
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
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            const close = () => { sidebar?.classList.remove('sidebar-open'); backdrop?.classList.remove('show'); };
            const open = () => { sidebar?.classList.add('sidebar-open'); backdrop?.classList.add('show'); };
            if (toggle && sidebar && backdrop) {
                backdrop.classList.remove('d-none');
                toggle.addEventListener('click', () => sidebar.classList.contains('sidebar-open') ? close() : open());
                backdrop.addEventListener('click', close);
                sidebar.querySelectorAll('a').forEach(l => l.addEventListener('click', () => { if (window.innerWidth < 992) close(); }));
                window.addEventListener('resize', () => { if (window.innerWidth >= 992) close(); });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>