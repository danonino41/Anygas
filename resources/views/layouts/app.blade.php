<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AnyGas — @yield('titulo', 'Sistema Operativo')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f4f6f9; /* Gris muy suave, cansancio visual cero */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .contenedor-principal {
            flex: 1;
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <main class="contenedor-principal container mt-4 mb-5">
        @yield('contenido')
    </main>

    <footer class="bg-dark text-center text-secondary py-3 mt-auto border-top border-secondary">
        <div class="container">
            <small class="text-light">⚡ AnyGas v1.0</small> <small>— Sistema de Distribución y Logística</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>