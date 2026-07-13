<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 112px; object-fit: contain;">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuAnygas" aria-controls="menuAnygas" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuAnygas">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                @auth
                    @if(Auth::user()->rol === 'administrador')
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">📊 Inteligencia de Negocio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/almacen">📦 Almacén y Compras</a>
                        </li>
                    @elseif(Auth::user()->rol === 'recepcionista')
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-primary" href="/recepcionista/punto-venta">🖥️ Punto de Venta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/recepcionista/dashboard">📋 Panel Diario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/recepcionista/asignar">🏍️ Asignar Rutas</a>
                        </li>
                    @elseif(Auth::user()->rol === 'motorizado')
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-success" href="/motorizado/ruta">🗺️ Mis Rutas de Hoy</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/seguimiento">🔍 Seguimiento de mi Balón</a>
                    </li>
                @endauth
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm fw-semibold px-3">
                        Acceso Empleados
                    </a>
                @else
                    <span class="text-light d-none d-sm-inline">
                        <small class="text-secondary">Conectado como:</small> 
                        <strong class="text-white">{{ Auth::user()->nombre_completo }}</strong>
                        <span class="badge bg-secondary ms-1 text-uppercase" style="font-size: 0.65rem;">{{ Auth::user()->rol }}</span>
                    </span>
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Salir</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>