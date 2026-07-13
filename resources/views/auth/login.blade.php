<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyGas — Acceso para Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27120%27 height=%27120%27%3E%3Cdefs%3E%3Cpattern id=%27g%27 width=%27120%27 height=%27120%27 patternUnits=%27userSpaceOnUse%27 patternTransform=%27rotate(45)%27%3E%3Cg fill=%27%23F59E0B%27 fill-opacity=%270.06%27%3E%3Crect x=%2735%27 y=%2732%27 width=%2750%27 height=%2755%27 rx=%278%27/%3E%3Crect x=%2745%27 y=%2718%27 width=%2730%27 height=%2716%27 rx=%274%27/%3E%3Crect x=%2756%27 y=%278%27 width=%278%27 height=%2710%27 rx=%272%27/%3E%3C/g%3E%3C/pattern%3E%3C/defs%3E%3Crect width=%27120%27 height=%27120%27 fill=%27url(%23g)%27/%3E%3C/svg%3E'), linear-gradient(135deg, #111827 0%, #451a03 50%, #78350f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            margin: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-dark text-white text-center py-4 rounded-top-4 border-0">
                <img src="https://lirp.cdn-website.com/2cdc2e47/dms3rep/multi/opt/LOGO+ANY+GAS+LETRAS+REDONDAS-cad4bd93-1920w.png" alt="AnyGas" style="height: 140px; object-fit: contain; margin-bottom: 6px;">
                <small class="text-light d-block">Portal Operativo</small>
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
                        <button type="submit" class="btn btn-warning fw-bold shadow-sm py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar al Sistema
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="text-center mt-3">
            <a href="/" class="text-white text-decoration-none opacity-75 small">
                <i class="bi bi-arrow-left me-1"></i> Volver a la página principal
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>