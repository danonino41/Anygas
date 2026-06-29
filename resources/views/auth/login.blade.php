@extends('layouts.app')

@section('titulo', 'Acceso para Empleados')

@section('contenido')
<div class="row justify-content-center my-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-dark text-white text-center py-4 rounded-top-4">
                <h3 class="fw-bold mb-0 text-warning">⚡ ANYGAS</h3>
                <small class="text-light">Portal Operativo</small>
            </div>
            <div class="card-body p-4 bg-white rounded-bottom-4">

                @if ($errors->has('error_login'))
                <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <small>{{ $errors->first('error_login') }}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('login.procesar') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold text-secondary">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control"
                            value="{{ old('correo') }}" placeholder="ejemplo@anygas.com" required autofocus>
                        @error('correo')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="contrasena" class="form-label fw-semibold text-secondary">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control"
                            placeholder="••••••••" required>
                        @error('contrasena')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg fw-bold shadow-sm">
                            Ingresar al Sistema
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="/" class="text-decoration-none text-muted"><small>← Volver a la página principal</small></a>
        </div>
    </div>
</div>
@endsection