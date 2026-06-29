@extends('layouts.recepcionista')

@section('titulo', 'Punto de Venta (POS)')

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
                    <h4 class="fw-bold text-dark mb-3"><i class="bi bi-box-seam me-2 text-warning"></i> Catálogo Disponible AnyGas</h4>
                    <p class="text-secondary small mb-4">Haz clic sobre las tarjetas para añadir productos al ticket de venta.</p>

                    <div class="row g-3" style="max-height: 68vh; overflow-y: auto;">
                        @foreach($productos as $prod)
                            <div class="col-sm-6 col-md-4">
                                <div class="card h-100 border rounded-4 p-3 text-center tarjeta-producto" 
                                     role="button"
                                     onclick="agregarAlCarrito({{ $prod->id }}, '{{ addslashes($prod->nombre) }}', '{{ $prod->marca }}', {{ $prod->precio_venta }})"
                                     style="transition: all 0.2s; background-color: #fcfcfd;">
                                    
                                    <div class="fs-1 my-1">
                                        @if(str_contains(strtolower($prod->nombre), '45')) 🏭
                                        @elseif(str_contains(strtolower($prod->nombre), 'balón')) 🛢️
                                        @elseif(str_contains(strtolower($prod->nombre), 'manguera')) 〰️
                                        @else 🔧 @endif
                                    </div>

                                    <span class="badge bg-dark mx-auto mb-1">{{ $prod->marca }}</span>
                                    <h6 class="fw-bold text-dark mb-1 small">{{ $prod->nombre }}</h6>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Stock: {{ $prod->stock_actual }} und.</small>
                                    <span class="fs-5 fw-bold text-primary mt-2">S/. {{ number_format($prod->precio_venta, 2) }}</span>
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
                        <div class="col-5">
                            <label class="form-label small fw-bold mb-1">DNI / RUC</label>
                            <input type="text" name="documento_identidad" id="dniCliente" class="form-control form-control-sm" placeholder="71234567" required>
                        </div>
                        <div class="col-7">
                            <label class="form-label small fw-bold mb-1">Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-sm" placeholder="988111222" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Nombre o Razón Social</label>
                            <input type="text" name="nombres" class="form-control form-control-sm" placeholder="Cliente General" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Dirección Exacta de Entrega</label>
                            <input type="text" name="direccion" class="form-control form-control-sm" placeholder="Av. Principal 123" required>
                        </div>
                    </div>

                    <label class="form-label small fw-bold text-secondary mb-1">Carrito de Compra:</label>
                    <div class="flex-grow-1 border rounded-3 p-2 mb-3 bg-light" style="max-height: 220px; overflow-y: auto;" id="contenedorCarrito">
                        <p class="text-center text-muted small my-4" id="carritoVacio">Ningún producto seleccionado</p>
                    </div>

                    <div class="row g-2 pt-2 border-top">
                        <div class="col-6">
                            <label class="form-label small fw-bold mb-1">Tipo Despacho</label>
                            <select name="tipo_despacho" class="form-select form-select-sm">
                                <option value="domicilio">🚚 Delivery</option>
                                <option value="recojo_tienda">🏪 En Tienda</option>
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

    function agregarAlCarrito(id, nombre, marca, precio) {
        if (carrito[id]) {
            carrito[id].cantidad++;
        } else {
            carrito[id] = { id, nombre, marca, precio, cantidad: 1 };
        }
        renderizarCarrito();
    }

    function cambiarCantidad(id, delta) {
        if (carrito[id]) {
            carrito[id].cantidad += delta;
            if (carrito[id].cantidad <= 0) delete carrito[id];
            renderizarCarrito();
        }
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
                    <div style="line-height: 1.1;">
                        <strong class="text-dark d-block">${item.nombre}</strong>
                        <span class="text-muted" style="font-size:0.7rem;">S/. ${item.precio.toFixed(2)} und.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0" onclick="cambiarCantidad(${item.id}, -1)">-</button>
                        <span class="fw-bold">${item.cantidad}</span>
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0" onclick="cambiarCantidad(${item.id}, 1)">+</button>
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
</script>
@endsection