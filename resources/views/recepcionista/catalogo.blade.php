@extends('layouts.recepcionista')

@section('titulo', 'Catálogo de Productos')

@section('contenido')
<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-box-seam text-warning"></i> Catálogo de Inventario
            </h2>
            <p class="text-secondary small mb-0">
                <i class="bi bi-info-circle me-1"></i> Consulta rápida de balones, accesorios y disponibilidad en planta.
            </p>
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
            <div class="btn-group shadow-sm" role="group">
                <button type="button" class="btn btn-dark active" id="btnGrid" onclick="cambiarVista('grid')">
                    <i class="bi bi-grid-fill me-1"></i> Tarjetas
                </button>
                <button type="button" class="btn btn-outline-dark" id="btnList" onclick="cambiarVista('list')">
                    <i class="bi bi-list-ul me-1"></i> Lista
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-search me-1"></i>Buscar producto
                </label>
                <input type="text" id="buscador" class="form-control" placeholder="Nombre, peso o referencia..." onkeyup="filtrarCatalogo()">
            </div>
            <div class="col-6 col-md">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-tag me-1"></i>Marca
                </label>
                <select id="filtroMarca" class="form-select" onchange="filtrarCatalogo()">
                    <option value="todas">Todas las Marcas</option>
                    @foreach($marcas as $marca)
                        <option value="{{ strtolower($marca) }}">{{ $marca }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-building me-1"></i>Proveedor
                </label>
                <select id="filtroProveedor" class="form-select" onchange="filtrarCatalogo()">
                    <option value="todos">Todos los Proveedores</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ strtolower($prov->nombre_empresa) }}">{{ $prov->nombre_empresa }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-box me-1"></i>Tipo Entrada
                </label>
                <select id="filtroEntrada" class="form-select" onchange="filtrarCatalogo()">
                    <option value="todas">Cualquier Entrada</option>
                    <option value="estandar">Estándar (Normal)</option>
                    <option value="premium">Premium</option>
                    <option value="ninguna">Accesorios (Ninguna)</option>
                </select>
            </div>
            <div class="col-6 col-md">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-currency-dollar me-1"></i>Precio máx.
                </label>
                <select id="filtroPrecio" class="form-select" onchange="filtrarCatalogo()">
                    <option value="todos">Todos los Precios</option>
                    <option value="50">Hasta S/. 50</option>
                    <option value="100">Hasta S/. 100</option>
                    <option value="150">Hasta S/. 150</option>
                    <option value="9999">S/. 150+</option>
                </select>
            </div>
            <div class="col-6 col-md-auto">
                <label class="form-label small text-secondary fw-semibold mb-1">
                    <i class="bi bi-eye me-1"></i>Ver
                </label>
                <select id="filtroPorPagina" class="form-select" onchange="filtrarCatalogo()">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                    <option value="9999">Todos</option>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="contadorProductos">
                    <i class="bi bi-box me-1"></i> {{ $productos->count() }} productos en catálogo
                </span>
                <span class="text-muted small" id="infoPagina"></span>
            </div>
        </div>
    </div>

    <div class="row g-4" id="vistaTarjetas">
        @foreach($productos as $prod)
            <div class="col-sm-6 col-md-4 col-xl-3 item-producto"
                 data-nombre="{{ strtolower($prod->nombre) }}"
                 data-marca="{{ strtolower($prod->marca) }}"
                 data-entrada="{{ strtolower($prod->tipo_entrada) }}"
                 data-proveedor="{{ strtolower($prod->proveedor?->nombre_empresa ?? 'sin proveedor') }}"
                 data-precio="{{ $prod->precio_venta }}">

                <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-0 {{ $prod->estado == 'agotado' ? 'opacity-50' : '' }} producto-card">
                    <div class="position-relative">
                        <span class="badge {{ $prod->estado == 'disponible' ? 'bg-success' : 'bg-danger' }} position-absolute top-0 end-0 m-2 z-1">
                            <i class="bi {{ $prod->estado == 'disponible' ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                            {{ ucfirst($prod->estado) }}
                        </span>
                        <img src="{{ $prod->imagen_url }}"
                             alt="{{ $prod->nombre }}"
                             class="w-100 rounded-top"
                             style="height: 180px; object-fit: cover;"
                             loading="lazy"
                             onclick="abrirZoom(this.src, '{{ $prod->nombre }}')"
                             role="button">
                    </div>

                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $prod->nombre }}">{{ $prod->nombre }}</h5>
                        <div class="d-flex justify-content-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-dark bg-opacity-10 text-dark">
                                <i class="bi bi-tag-fill me-1" style="font-size:0.6rem;"></i>{{ $prod->marca }}
                            </span>
                            <span class="badge bg-info bg-opacity-10 text-info-emphasis">
                                <i class="bi bi-box me-1" style="font-size:0.6rem;"></i>{{ ucfirst($prod->tipo_entrada) }}
                            </span>
                        </div>

                        @if($prod->descripcion)
                            <p class="text-muted small mb-1 text-truncate" title="{{ $prod->descripcion }}">
                                <i class="bi bi-info-circle me-1"></i>{{ $prod->descripcion }}
                            </p>
                        @endif

                        @if($prod->proveedor)
                            <p class="text-muted small mb-0">
                                <i class="bi bi-building me-1"></i>{{ $prod->proveedor->nombre_empresa }}
                            </p>
                        @endif

                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">
                                    <i class="bi bi-cube me-1"></i>Stock en Planta
                                </small>
                                <span class="fw-bold {{ $prod->stock_actual <= 10 ? 'text-danger' : 'text-dark' }}">
                                    {{ $prod->stock_actual }} und.
                                    @if($prod->stock_actual <= 5)
                                        <i class="bi bi-exclamation-triangle-fill text-danger" title="Stock crítico"></i>
                                    @endif
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Precio Venta</small>
                                <span class="fs-5 fw-bolder text-warning">S/. {{ number_format($prod->precio_venta, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white d-none" id="vistaLista">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px;"></th>
                        <th>Producto</th>
                        <th>Marca</th>
                        <th>Proveedor</th>
                        <th>Tipo Entrada</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody id="tablaProductos">
                    @foreach($productos as $prod)
                        <tr class="item-producto"
                            data-nombre="{{ strtolower($prod->nombre) }}"
                            data-marca="{{ strtolower($prod->marca) }}"
                            data-entrada="{{ strtolower($prod->tipo_entrada) }}"
                            data-proveedor="{{ strtolower($prod->proveedor?->nombre_empresa ?? 'sin proveedor') }}"
                            data-precio="{{ $prod->precio_venta }}">

                            <td>
                                <img src="{{ $prod->imagen_url }}"
                                     alt="{{ $prod->nombre }}"
                                     class="rounded-2"
                                     style="width: 48px; height: 48px; object-fit: cover;"
                                     loading="lazy"
                                     onclick="abrirZoom(this.src, '{{ $prod->nombre }}')"
                                     role="button">
                            </td>
                            <td class="fw-bold text-dark">{{ $prod->nombre }}</td>
                            <td><span class="badge bg-dark">{{ $prod->marca }}</span></td>
                            <td>
                                @if($prod->proveedor)
                                    <small class="text-muted">
                                        <i class="bi bi-building me-1"></i>{{ $prod->proveedor->nombre_empresa }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Sin proveedor</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($prod->tipo_entrada) }}</td>
                            <td class="fw-bold text-warning fs-6">S/. {{ number_format($prod->precio_venta, 2) }}</td>
                            <td>
                                <span class="fw-bold {{ $prod->stock_actual <= 10 ? 'text-danger' : 'text-dark' }}">
                                    {{ $prod->stock_actual }}
                                    @if($prod->stock_actual <= 5)
                                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $prod->estado == 'disponible' ? 'bg-success' : 'bg-danger' }}">
                                    <i class="bi {{ $prod->estado == 'disponible' ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                    {{ ucfirst($prod->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <nav id="paginacion" class="d-flex justify-content-center mt-4" aria-label="Navegación del catálogo">
        <ul class="pagination pagination-sm flex-wrap justify-content-center mb-0">
        </ul>
    </nav>

    <div id="mensajeVacio" class="text-center py-5 d-none">
        <div class="fs-1 text-muted mb-3">🔍</div>
        <h5 class="fw-bold text-secondary">No se encontraron productos</h5>
        <p class="text-muted">Intenta cambiar los filtros o el término de búsqueda.</p>
    </div>

</div>

<div id="modalImagen" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalImg" src="" alt="" class="img-fluid rounded-3" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<style>
    .producto-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
        overflow: hidden;
    }
    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .producto-card img[role="button"] {
        transition: transform 0.3s ease;
    }
    .producto-card:hover img[role="button"] {
        transform: scale(1.05);
    }
    .table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table td {
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background-color: rgba(245, 158, 11, 0.05);
    }
    .pagination .page-link {
        color: #111827;
        border-radius: 8px;
        margin: 0 2px;
        border: none;
        font-weight: 500;
        min-width: 36px;
        text-align: center;
    }
    .pagination .page-link:hover {
        background-color: #F59E0B;
        color: #fff;
    }
    .pagination .page-item.active .page-link {
        background-color: #111827;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: #9CA3AF;
        pointer-events: none;
    }
    @media print {
        .btn-group, #filtroMarca, #filtroProveedor, #filtroEntrada, #filtroPrecio, #filtroPorPagina, #buscador,
        .btn-outline-secondary, .top-header, .sidebar, #paginacion { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .app-main { overflow: visible !important; }
        .item-producto { display: block !important; opacity: 1 !important; }
    }
</style>

<script>
    let paginaActual = 1;
    let itemsPorPagina = 12;
    let totalPaginas = 1;
    let itemsVisibles = [];

    function cambiarVista(vista) {
        const btnGrid = document.getElementById('btnGrid');
        const btnList = document.getElementById('btnList');
        const vistaTarjetas = document.getElementById('vistaTarjetas');
        const vistaLista = document.getElementById('vistaLista');

        if (vista === 'grid') {
            btnGrid.classList.replace('btn-outline-dark', 'btn-dark');
            btnList.classList.replace('btn-dark', 'btn-outline-dark');
            vistaTarjetas.classList.remove('d-none');
            vistaLista.classList.add('d-none');
        } else {
            btnList.classList.replace('btn-outline-dark', 'btn-dark');
            btnGrid.classList.replace('btn-dark', 'btn-outline-dark');
            vistaLista.classList.remove('d-none');
            vistaTarjetas.classList.add('d-none');
        }
        paginaActual = 1;
        filtrarCatalogo();
    }

    function obtenerItems() {
        const vistaActiva = document.getElementById('vistaTarjetas').classList.contains('d-none') ? 'vistaLista' : 'vistaTarjetas';
        return document.querySelectorAll(`#${vistaActiva} .item-producto`);
    }

    function filtrarCatalogo() {
        const textoBusqueda = document.getElementById('buscador').value.toLowerCase();
        const marcaFiltro = document.getElementById('filtroMarca').value;
        const entradaFiltro = document.getElementById('filtroEntrada').value;
        const proveedorFiltro = document.getElementById('filtroProveedor').value;
        const precioFiltro = document.getElementById('filtroPrecio').value;
        itemsPorPagina = parseInt(document.getElementById('filtroPorPagina').value);

        const items = obtenerItems();
        itemsVisibles = [];

        items.forEach(item => {
            const nombre = item.getAttribute('data-nombre');
            const marca = item.getAttribute('data-marca');
            const entrada = item.getAttribute('data-entrada');
            const proveedor = item.getAttribute('data-proveedor');
            const precio = parseFloat(item.getAttribute('data-precio'));

            const coincideTexto = nombre.includes(textoBusqueda);
            const coincideMarca = marcaFiltro === 'todas' || marca === marcaFiltro;
            const coincideEntrada = entradaFiltro === 'todas' || entrada === entradaFiltro;
            const coincideProveedor = proveedorFiltro === 'todos' || proveedor === proveedorFiltro;
            let coincidePrecio = true;
            if (precioFiltro !== 'todos') {
                const maxPrecio = parseFloat(precioFiltro);
                coincidePrecio = precio <= maxPrecio;
            }

            const visible = coincideTexto && coincideMarca && coincideEntrada && coincideProveedor && coincidePrecio;
            if (visible) itemsVisibles.push(item);
        });

        totalPaginas = Math.ceil(itemsVisibles.length / itemsPorPagina) || 1;
        if (paginaActual > totalPaginas) paginaActual = totalPaginas;

        aplicarPagina();
        renderizarPaginacion();
        actualizarContador();
    }

    function aplicarPagina() {
        const inicio = (paginaActual - 1) * itemsPorPagina;
        const fin = inicio + itemsPorPagina;

        const items = obtenerItems();
        items.forEach(item => item.style.display = 'none');

        const paginaItems = itemsVisibles.slice(inicio, fin);
        paginaItems.forEach(item => {
            item.style.display = '';
            if (item.tagName === 'TR') item.style.display = 'table-row';
        });

        document.getElementById('mensajeVacio').classList.toggle('d-none', itemsVisibles.length > 0);

        const info = document.getElementById('infoPagina');
        if (itemsVisibles.length > 0) {
            info.textContent = `Pág. ${paginaActual} de ${totalPaginas} (mostrando ${paginaItems.length})`;
        } else {
            info.textContent = '';
        }
    }

    function renderizarPaginacion() {
        const ul = document.querySelector('#paginacion ul');
        ul.innerHTML = '';

        if (totalPaginas <= 1) return;

        const crearItem = (label, pagina, disabled = false, active = false) => {
            const li = document.createElement('li');
            li.className = `page-item${active ? ' active' : ''}${disabled ? ' disabled' : ''}`;
            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = label;
            if (!disabled) {
                a.addEventListener('click', e => {
                    e.preventDefault();
                    paginaActual = pagina;
                    aplicarPagina();
                    renderizarPaginacion();
                });
            }
            li.appendChild(a);
            ul.appendChild(li);
        };

        crearItem('‹', paginaActual - 1, paginaActual === 1);

        const maxBotones = 5;
        let inicioPag = Math.max(1, paginaActual - Math.floor(maxBotones / 2));
        let finPag = Math.min(totalPaginas, inicioPag + maxBotones - 1);
        if (finPag - inicioPag + 1 < maxBotones) {
            inicioPag = Math.max(1, finPag - maxBotones + 1);
        }

        if (inicioPag > 1) {
            crearItem('1', 1, false, 1 === paginaActual);
            if (inicioPag > 2) {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">…</span>';
                ul.appendChild(li);
            }
        }

        for (let i = inicioPag; i <= finPag; i++) {
            crearItem(String(i), i, false, i === paginaActual);
        }

        if (finPag < totalPaginas) {
            if (finPag < totalPaginas - 1) {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">…</span>';
                ul.appendChild(li);
            }
            crearItem(String(totalPaginas), totalPaginas, false, totalPaginas === paginaActual);
        }

        crearItem('›', paginaActual + 1, paginaActual === totalPaginas);
    }

    function actualizarContador() {
        const total = obtenerItems().length;
        document.getElementById('contadorProductos').innerHTML =
            `<i class="bi bi-box me-1"></i> ${itemsVisibles.length} de ${total} productos`;
    }

    function abrirZoom(src, titulo) {
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitulo').textContent = titulo;
        const modal = new bootstrap.Modal(document.getElementById('modalImagen'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', () => filtrarCatalogo());
</script>
@endsection
