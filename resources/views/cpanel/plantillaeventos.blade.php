<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (NECESARIO para los íconos del calendario y menú) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados (DEBE ir al final para sobrescribir Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('estilos.css') }}">
</head>

<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <h3 class="text-white text-center mt-3 mb-4">Menú</h3>

    <form class="mb-3 text-center">
        <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='/menu'">
            Menu
        </button>
    </form>

    <form method="post" action="respaldo.html" class="mb-3 text-center">
        <button type="submit" class="btn btn-outline-light w-100">Respaldar</button>
    </form>

    <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='login'">
        Cerrar sesión
    </button>
</div>

<!-- HEADER -->
<header class="py-3 mb-4" id="header-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <span id="menu-toggle">&#9776;</span>
        <i class="bi bi-calendar-event text-white fs-4"></i>
    </div>
</header>

<!-- CONTENIDO -->
<main class="content" id="main-content">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="py-4 mt-auto content" id="footer-content">
    <div class="container">
        <p class="mb-0 text-center">© 2025 Calendario de Eventos - Todos los derechos reservados</p>
    </div>
</footer>

<!-- Script Toggle combinado -->
<script>
    const toggleBtn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('main-content');
    const footerContent = document.getElementById('footer-content');
    const headerBar = document.getElementById('header-bar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
        content.classList.toggle('full');
        footerContent?.classList.toggle('full');
        headerBar?.classList.toggle('full');

        sidebar.classList.toggle('active');
        content.classList.toggle('shifted');
    });
</script>

@yield('scripts')

</body>
</html>
