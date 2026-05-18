<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Necesario para formularios y JS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('estilos.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<!-- BOTÓN PARA MOSTRAR/OCULTAR EL SIDEBAR -->
<div id="menu-toggle">☰</div>

<div class="d-flex">

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar p-3">
        <h3 class="text-white text-center mt-3 mb-4">Menú</h3>

        <button type="button" class="btn btn-outline-light w-100 mb-3" onclick="window.location.href='menu'">
            Menu
        </button>

        <form method="post" action="respaldo.html" class="mb-3 text-center">
            <button type="submit" class="btn btn-outline-light w-100">Respaldar</button>
        </form> 

           <form method="POST" action="{{ route('logout') }}" class="mb-3 text-center">
    @csrf
    <button type="submit" class="btn btn-outline-light w-100">
        Cerrar sesión
    </button>
</form>

    <!-- Contenido principal -->
    <div id="main-content" class="content p-4 flex-grow-1">
        @yield('content')
    </div>

</div>

<script>
    const toggleBtn = document.getElementById('menu-toggle');
    const sidebar  = document.getElementById('sidebar');
    const content  = document.getElementById('main-content');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        content.classList.toggle('shifted');
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Aquí se insertan los scripts de cada vista -->
@stack('scripts')

</body>
</html>
