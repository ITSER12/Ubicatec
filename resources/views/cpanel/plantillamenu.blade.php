<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HOME</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- TU CSS (NO SE TOCA) -->
    <link rel="stylesheet" href="{{ asset('estilos.css') }}">

</head>
<body>

<!-- Sidebar ORIGINAL (exacto como tú lo pasaste) -->
<div id="sidebar" class="sidebar active p-3">
    <h3 class="text-white text-center mt-3 mb-4">Menú</h3>

    <form class="mb-3 text-center">
        <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='{{ url('usuarios') }}'">
            Usuarios
        </button>
    </form>

    <form class="mb-3 text-center">
        <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='{{ url('eventos') }}'">
            Eventos
        </button>
    </form>

    <form class="mb-3 text-center">
        <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='{{ url('aulas') }}'">
             Aulas
        </button>
    </form>

    <!-- Después del botón Eventos, antes de Aulas -->
    <form class="mb-3 text-center">
        <button type="button" class="btn btn-outline-light w-100" onclick="window.location.href='{{ url('mapa') }}'">
            Mapa Campus
        </button>
    </form>

       <form method="POST" action="{{ route('logout') }}" class="mb-3 text-center">
    @csrf
    <button type="submit" class="btn btn-outline-light w-100">
        Cerrar sesión
    </button>
</form>


    <form method="post" action="respaldo.html" class="mb-3 text-center">
        <button type="submit" class="btn btn-outline-light w-100">Respaldar</button>
    </form>

     <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-outline-light w-100">Cerrar sesión</button>
</form>

<!-- Contenedor principal -->
<div class="content shifted" id="main-content">
    <span id="menu-toggle">&#9776;</span>

    <div class="container py-5 text-center">
        <h1 class="mb-4">BIENVENIDO</h1>

        <!-- Aquí va tu contenido real -->
        <p class="text-white">Aquí irá la sección de eventos.</p>
    </div>
</div>

<!-- Script para mostrar/ocultar sidebar (el tuyo) -->
<script>
    const toggleBtn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('main-content');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        content.classList.toggle('shifted');
    });
</script>

</body>
</html>
