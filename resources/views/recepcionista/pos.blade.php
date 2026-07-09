@extends('layouts.recepcionista')

@section('titulo', 'Toma de Pedido (POS)')

@section('contenido')
<div class="container-fluid p-0">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error operativo:</strong> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('recepcionista.pos.guardar') }}" method="POST" id="formPos">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam me-2 text-warning"></i> Catálogo AnyGas</h4>
                        <div style="max-width: 260px;" class="w-50">
                            <input type="text" id="buscadorCatalogo" class="form-control form-control-sm" placeholder="Buscar producto...">
                        </div>
                    </div>

                    <div class="row g-3" id="contenedorCatalogo" style="max-height: 76vh; overflow-y: auto;">
                        @foreach($productos as $prod)
                            <div class="col-sm-6 col-md-4 col-xl-3 producto-item" data-search="{{ strtolower($prod->nombre . ' ' . $prod->marca) }}">
                                <div class="card h-100 border rounded-4 p-0 tarjeta-producto" role="button" style="transition: all 0.2s; background-color: #fcfcfd; overflow: hidden;" data-prod='{!! json_encode(['id' => $prod->id, 'nombre' => $prod->nombre, 'marca' => $prod->marca, 'precio' => $prod->precio_venta, 'stock' => $prod->stock_actual]) !!}' data-imagen="{{ $prod->imagen_url }}">
                                    <div style="height: 130px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <img src="{{ $prod->imagen_url }}" alt="{{ $prod->nombre }}" style="height: 100%; width: 100%; object-fit: cover;" loading="lazy">
                                    </div>
                                    <div class="p-2 text-center">
                                        <span class="badge bg-dark mb-1" style="font-size: 0.6rem;">{{ $prod->marca }}</span>
                                        <h6 class="fw-bold text-dark mb-0 small" style="font-size: 0.75rem; line-height: 1.2;">{{ $prod->nombre }}</h6>
                                        <div class="d-flex justify-content-between align-items-center mt-1 px-1">
                                            <small class="text-muted" style="font-size: 0.6rem;">Stock: {{ $prod->stock_actual }}</small>
                                            <span class="fw-bold text-primary" style="font-size: 0.85rem;">S/ {{ number_format($prod->precio_venta, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="card border-0 shadow rounded-4 p-4 bg-white d-flex flex-column" style="min-height: 82vh;">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-receipt me-2"></i> Orden de Despacho</h5>

                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Buscar cliente por DNI, nombre o teléfono</label>
                            <input type="text" id="buscadorCliente" class="form-control form-control-sm" placeholder="Escriba DNI, nombre o teléfono...">
                            <div id="clienteEncontrado" class="mt-1" style="display:none;">
                                <small class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Cliente encontrado</small>
                            </div>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-bold mb-1">DNI / RUC</label>
                            <input type="text" name="documento_identidad" id="dniCliente" class="form-control form-control-sm" placeholder="71234567" required>
                        </div>
                        <div class="col-7">
                            <label class="form-label small fw-bold mb-1">Teléfono</label>
                            <input type="text" name="telefono" id="inputTelefono" class="form-control form-control-sm" placeholder="988111222" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Nombre o Razón Social</label>
                            <input type="text" name="nombres" id="inputNombres" class="form-control form-control-sm" placeholder="Cliente General" required>
                        </div>
                        <div class="col-12" id="direccionesContainer" style="display:none;">
                            <label class="form-label small fw-bold mb-1">Direcciones guardadas</label>
                            <select id="selectDirecciones" class="form-select form-select-sm">
                                <option value="">-- Seleccionar una dirección --</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Dirección Exacta de Entrega</label>
                            <input type="text" name="direccion" id="inputDireccion" class="form-control form-control-sm" placeholder="Av. Principal 123" required>
                        </div>
                    </div>

                    <label class="form-label small fw-bold text-secondary mb-1">Carrito de Compra:</label>
                    <div class="flex-grow-1 border rounded-3 p-2 mb-3 bg-light" style="max-height: 260px; overflow-y: auto;" id="contenedorCarrito">
                        <p class="text-center text-muted small my-4" id="carritoVacio">Ningún producto seleccionado</p>
                    </div>

                    <div class="row g-2 pt-2 border-top">
                        <div class="col-6">
                            <label class="form-label small fw-bold mb-1">Tipo Despacho</label>
                            <select name="tipo_despacho" class="form-select form-select-sm">
                                <option value="domicilio"> Delivery</option>
                                <option value="recojo_tienda"> En Tienda</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold mb-1">Asignar Motorizado</label>
                            <select name="motorizado_id" class="form-select form-select-sm">
                                <option value="">(Pendiente)</option>
                                @foreach($motorizados as $mot)
                                    <option value="{{ $mot->id }}">{{ $mot->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small fw-bold mb-1">Método de Pago</label>
                            <select name="tipo_pago_id" class="form-select form-select-sm" required>
                                @foreach($tiposPago as $pago)
                                    <option value="{{ $pago->id }}">{{ $pago->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">Total a Cobrar:</span>
                        <span class="fs-3 fw-bolder text-dark" id="textoTotal">S/. 0.00</span>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold shadow" id="btnCobrar" disabled>
                            ⚡ Confirmar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let carrito = {};
    let direccionesCliente = [];
    let clienteEncontrado = false;

    // ─── Mostrar modal de pedido creado ───
    @if(session('pedido_creado'))
    document.addEventListener('DOMContentLoaded', function() {
        const items = @json(session('items', []));
        let htmlResumen = '<div style="text-align: left; max-width: 360px; margin: 0 auto;">';
        htmlResumen += '<table style="width:100%; font-size:0.85rem;">';
        items.forEach(item => {
            htmlResumen += `<tr>
                <td style="padding:2px 4px;">${item.cantidad}x ${item.nombre}</td>
                <td style="padding:2px 4px; text-align:right;">S/ ${parseFloat(item.subtotal).toFixed(2)}</td>
            </tr>`;
        });
        htmlResumen += `<tr><td style="border-top:2px solid #ddd; padding-top:6px; font-weight:700;">Total</td>
            <td style="border-top:2px solid #ddd; padding-top:6px; text-align:right; font-weight:700;">S/ {{ number_format(session('total'), 2) }}</td></tr>`;
        htmlResumen += '</table></div>';

        Swal.fire({
            icon: 'success',
            title: 'Pedido creado',
            html: `
                <div style="text-align: center;">
                    <div style="font-size:2.5rem; margin-bottom:8px;">✅</div>
                    <p style="font-size:1.1rem; font-weight:600; margin-bottom:4px;">Código: <span style="color:#F59E0B;">{{ session('codigo') }}</span></p>
                    <p style="color:#6B7280; margin-bottom:12px;">Cliente: {{ session('cliente') }}</p>
                    ${htmlResumen}
                </div>
            `,
            confirmButtonText: 'Nuevo Pedido',
            confirmButtonColor: '#F59E0B',
            showCancelButton: false,
        });
    });
    @endif

    // ─── Catálogo: agregar producto al carrito ───
    document.querySelectorAll('.tarjeta-producto').forEach(card => {
        card.addEventListener('click', function() {
            const prod = JSON.parse(this.dataset.prod);
            const imagenUrl = this.dataset.imagen;
            const enCarrito = carrito[prod.id];
            const cantidadInicial = enCarrito ? enCarrito.cantidad : 1;
            const precioInicial = enCarrito ? enCarrito.precio : prod.precio;

            Swal.fire({
                title: prod.nombre,
                html: `
                    <div style="text-align: center;">
                        <img src="${imagenUrl}" alt="${prod.nombre}" style="width: 140px; height: 140px; object-fit: cover; border-radius: 12px; margin-bottom: 10px; background: #f0f0f0;">
                        <div><span class="badge bg-dark" style="font-size: 0.7rem;">${prod.marca}</span></div>
                        <p class="text-muted small mb-2" style="margin-top: 6px;">Stock disponible: <strong>${prod.stock}</strong></p>
                        <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin: 10px 0;">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnQtyMenos" style="width: 36px; height: 36px; font-size: 1.2rem; font-weight: bold;">−</button>
                            <span id="cantidadModal" style="font-size: 1.5rem; font-weight: 700; min-width: 40px;">${cantidadInicial}</span>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnQtyMas" style="width: 36px; height: 36px; font-size: 1.2rem; font-weight: bold;">+</button>
                        </div>
                        <div style="text-align: left; max-width: 200px; margin: 0 auto;">
                            <label class="form-label small fw-bold mb-1">Precio unitario (S/)</label>
                            <input type="number" id="precioModal" class="form-control form-control-lg text-center fw-bold" step="0.10" min="0.10" value="${precioInicial}">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: enCarrito ? 'Actualizar carrito' : 'Agregar al carrito',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#F59E0B',
                cancelButtonColor: '#6B7280',
                didOpen: () => {
                    const btnMas = document.getElementById('btnQtyMas');
                    const btnMenos = document.getElementById('btnQtyMenos');
                    const spanCantidad = document.getElementById('cantidadModal');
                    const inputPrecio = document.getElementById('precioModal');
                    let cantidad = cantidadInicial;

                    btnMas.addEventListener('click', () => {
                        if (cantidad < prod.stock) {
                            cantidad++;
                            spanCantidad.textContent = cantidad;
                        }
                    });
                    btnMenos.addEventListener('click', () => {
                        if (cantidad > 1) {
                            cantidad--;
                            spanCantidad.textContent = cantidad;
                        }
                    });
                    inputPrecio.addEventListener('click', function() {
                        this.select();
                    });
                },
                preConfirm: () => {
                    const cantidad = parseInt(document.getElementById('cantidadModal').textContent);
                    const precio = parseFloat(document.getElementById('precioModal').value);
                    if (!precio || precio <= 0) {
                        Swal.showValidationMessage('Ingrese un precio válido');
                        return false;
                    }
                    return { cantidad, precio };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { cantidad, precio } = result.value;
                    if (enCarrito) {
                        carrito[prod.id].cantidad = cantidad;
                        carrito[prod.id].precio = precio;
                    } else {
                        carrito[prod.id] = { id: prod.id, nombre: prod.nombre, marca: prod.marca, precio, cantidad, imagenUrl };
                    }
                    renderizarCarrito();
                }
            });
        });
    });

    // ─── Funciones del carrito ───
    function cambiarCantidad(id, delta) {
        if (carrito[id]) {
            carrito[id].cantidad += delta;
            if (carrito[id].cantidad <= 0) delete carrito[id];
            renderizarCarrito();
        }
    }

    function actualizarPrecioItem(id, nuevoPrecio) {
        if (carrito[id]) {
            const precio = parseFloat(nuevoPrecio);
            if (!isNaN(precio) && precio > 0) {
                carrito[id].precio = precio;
                actualizarTotal();
            }
        }
    }

    function eliminarItem(id) {
        delete carrito[id];
        renderizarCarrito();
    }

    function renderizarCarrito() {
        const contenedor = document.getElementById('contenedorCarrito');
        const textoTotal = document.getElementById('textoTotal');
        const btnCobrar = document.getElementById('btnCobrar');

        contenedor.innerHTML = '';
        let total = 0;
        let cantItems = 0;

        for (let id in carrito) {
            let item = carrito[id];
            let subtotal = item.cantidad * item.precio;
            total += subtotal;
            cantItems++;

            contenedor.innerHTML += `
                <div class="d-flex justify-content-between align-items-center bg-white border rounded p-2 mb-1 small">
                    <div style="line-height: 1.2; flex: 1; min-width: 0;">
                        <strong class="text-dark d-block" style="font-size: 0.75rem;">${item.nombre}</strong>
                        <span class="text-muted" style="font-size:0.6rem;">${item.marca}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" class="form-control form-control-sm text-center precio-item" style="width: 68px; font-size: 0.75rem;" step="0.10" min="0.10" value="${item.precio.toFixed(2)}" data-id="${item.id}" onchange="actualizarPrecioItem(${item.id}, this.value)">
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                        <span class="fw-bold" style="font-size:0.85rem; min-width: 20px; text-align: center;">${item.cantidad}</span>
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0" onclick="cambiarCantidad(${item.id}, 1)">+</button>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 px-1 py-0" onclick="eliminarItem(${item.id})" title="Quitar">&times;</button>
                        <input type="hidden" name="productos[${item.id}][cantidad]" value="${item.cantidad}">
                        <input type="hidden" name="productos[${item.id}][precio]" value="${item.precio}">
                    </div>
                </div>
            `;
        }

        if (cantItems === 0) {
            contenedor.innerHTML = '<p class="text-center text-muted small my-4">Ningún producto seleccionado</p>';
            btnCobrar.disabled = true;
        } else {
            btnCobrar.disabled = false;
        }

        textoTotal.innerText = 'S/. ' + total.toFixed(2);
    }

    function actualizarTotal() {
        let total = 0;
        for (let id in carrito) {
            total += carrito[id].cantidad * carrito[id].precio;
        }
        document.getElementById('textoTotal').innerText = 'S/. ' + total.toFixed(2);
        document.getElementById('btnCobrar').disabled = Object.keys(carrito).length === 0;
    }

    // ─── Búsqueda de catálogo ───
    document.getElementById('buscadorCatalogo')?.addEventListener('input', function() {
        const termino = this.value.toLowerCase().trim();
        document.querySelectorAll('.producto-item').forEach(el => {
            el.style.display = (!termino || el.dataset.search.includes(termino)) ? '' : 'none';
        });
    });

    // ─── Búsqueda de cliente por DNI, nombre o teléfono (solo con Enter) ───
    function seleccionarCliente(cliente) {
        clienteEncontrado = true;
        document.getElementById('dniCliente').value = cliente.documento_identidad || '';
        document.getElementById('inputNombres').value = cliente.nombre_completo;
        document.getElementById('inputTelefono').value = cliente.telefono;
        document.getElementById('inputDireccion').value = cliente.direccion;
        document.getElementById('clienteEncontrado').style.display = 'block';

        direccionesCliente = cliente.direcciones || [];
        const container = document.getElementById('direccionesContainer');
        container.style.display = 'none';
        if (direccionesCliente.length > 0) {
            const select = document.getElementById('selectDirecciones');
            select.innerHTML = '<option value="">-- Seleccionar una dirección --</option>';
            direccionesCliente.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.etiqueta + ': ' + d.direccion + (d.referencia ? ' (' + d.referencia + ')' : '');
                if (d.es_principal) opt.selected = true;
                select.appendChild(opt);
            });
            container.style.display = 'block';
            const principal = direccionesCliente.find(d => d.es_principal);
            if (principal) {
                document.getElementById('inputDireccion').value = principal.direccion;
            }
        }
    }

    document.getElementById('buscadorCliente')?.addEventListener('keydown', function(e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        document.getElementById('direccionesContainer').style.display = 'none';
        document.getElementById('clienteEncontrado').style.display = 'none';

        const q = this.value.trim();
        if (q.length < 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Búsqueda muy corta',
                text: 'Escriba al menos 3 caracteres para buscar.',
                confirmButtonColor: '#F59E0B',
            });
            return;
        }

        fetch('/recepcionista/buscar-cliente?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (!data.encontrado || data.clientes.length === 0) {
                    clienteEncontrado = false;
                    Swal.fire({
                        icon: 'info',
                        title: 'Cliente nuevo',
                        text: 'No se encontró ningún cliente. Complete los datos manualmente.',
                        confirmButtonColor: '#F59E0B',
                    });
                    return;
                }

                if (data.clientes.length === 1) {
                    const c = data.clientes[0];
                    seleccionarCliente(c);
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente encontrado',
                        text: c.nombre_completo + ' — ' + c.telefono,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    return;
                }

                let opciones = '';
                data.clientes.forEach((c, i) => {
                    opciones += `<div class="cliente-opcion" data-index="${i}" style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor=''">
                        <div>
                            <strong>${c.nombre_completo}</strong><br>
                            <small style="color:#6B7280;">${c.documento_identidad} — ${c.telefono}</small>
                        </div>
                        <span style="color:#F59E0B; font-size:1.2rem;">›</span>
                    </div>`;
                });

                Swal.fire({
                    title: 'Seleccione un cliente',
                    html: `<div style="max-height:300px; overflow-y:auto; text-align:left;">${opciones}</div>`,
                    showCancelButton: false,
                    showConfirmButton: false,
                    showCloseButton: true,
                    didOpen: () => {
                        document.querySelectorAll('.cliente-opcion').forEach(el => {
                            el.addEventListener('click', function() {
                                const idx = parseInt(this.dataset.index);
                                const c = data.clientes[idx];
                                seleccionarCliente(c);
                                Swal.close();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cliente seleccionado',
                                    text: c.nombre_completo,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            });
                        });
                    },
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de búsqueda',
                    text: 'No se pudo conectar con el servidor.',
                    confirmButtonColor: '#F59E0B',
                });
            });
    });

    document.getElementById('selectDirecciones')?.addEventListener('change', function() {
        const id = parseInt(this.value);
        const dir = direccionesCliente.find(d => d.id === id);
        if (dir) {
            document.getElementById('inputDireccion').value = dir.direccion;
        }
    });

    document.getElementById('inputDireccion')?.addEventListener('input', function() {
        document.getElementById('direccionesContainer').style.display = 'none';
    });
</script>

<style>
.tarjeta-producto:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
.tarjeta-producto:active { transform: scale(0.97); }
.precio-item:focus { border-color: #F59E0B; box-shadow: 0 0 0 2px rgba(245,158,11,0.2); }
</style>
@endsection
