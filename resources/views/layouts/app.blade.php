<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <title>@yield('title', 'HOME')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('estilos.css') }}">

    <style>
        :root {
            --sidebar-width: 265px;
            --topbar-height: 60px;
            --primary: #1a3c4e;
            --primary-dark: #102530;
            --primary-light: #2a5870;
            --accent: #3aafce;
            --accent-hover: #2b94b0;
            --text-sidebar: #cfe8f0;
            --sidebar-item-hover: rgba(58, 175, 206, 0.15);
            --sidebar-item-active: rgba(58, 175, 206, 0.25);
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #1e293b;
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 900;
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.active { display: block; }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 60%, var(--primary-light) 100%);
            z-index: 1000;
            transform: translateX(calc(-1 * var(--sidebar-width)));
            transition: transform var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.25);
        }
        .sidebar.active { transform: translateX(0); }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: white; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(58,175,206,0.4);
        }
        .sidebar-brand-text { color: white; font-weight: 700; font-size: 1.1rem; line-height: 1.2; }
        .sidebar-brand-sub  { color: var(--text-sidebar); font-size: 0.72rem; opacity: 0.75; }

        .sidebar-section { padding: 16px 12px 8px; flex: 1; }
        .sidebar-section-label {
            color: rgba(255,255,255,0.35);
            font-size: 0.68rem; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 0 8px 8px;
        }

        .nav-item-sidebar {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: var(--text-sidebar); font-size: 0.9rem; font-weight: 500;
            cursor: pointer; border: none; background: transparent;
            width: 100%; text-align: left;
            transition: background var(--transition), color var(--transition), transform 0.15s ease;
            margin-bottom: 3px; position: relative;
        }
        .nav-item-sidebar:hover {
            background: var(--sidebar-item-hover);
            color: white; transform: translateX(3px);
        }
        .nav-item-sidebar.active-route {
            background: var(--sidebar-item-active);
            color: var(--accent); font-weight: 600;
        }
        .nav-item-sidebar.active-route::before {
            content: ''; position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px; background: var(--accent);
            border-radius: 0 3px 3px 0;
        }
        .nav-item-sidebar .nav-icon { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: auto;
        }
        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 11px; border-radius: 10px;
            background: rgba(239,68,68,0.12); color: #fca5a5;
            border: 1px solid rgba(239,68,68,0.25);
            font-size: 0.9rem; font-weight: 500; cursor: pointer;
            transition: all var(--transition);
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.28);
            color: #fee2e2; border-color: rgba(239,68,68,0.5);
        }

        .content {
            margin-left: 0;
            transition: margin-left var(--transition);
            min-height: 100vh; width: 100%;
            display: flex; flex-direction: column;
        }
        .content.shifted { margin-left: var(--sidebar-width); }

        .top-bar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky; top: 0; z-index: 100;
            height: var(--topbar-height);
            padding: 0 20px 0 64px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        .top-bar-title { font-size: 1rem; font-weight: 600; color: #334155; }
        .top-bar-right  { display: flex; align-items: center; gap: 12px; }

        #btn-perfil {
            width: 36px; height: 36px;
            border-radius: 50%; overflow: hidden;
            border: 2px solid #e2e8f0;
            padding: 0; cursor: pointer;
            background: none;
            transition: border-color 0.2s;
        }
        #btn-perfil:hover { border-color: var(--accent); }
        #btn-perfil img { width: 100%; height: 100%; object-fit: cover; }

        #menu-toggle {
            position: fixed; top: 10px; left: 10px; z-index: 2000;
            background: var(--primary); color: white;
            width: 40px; height: 40px; border-radius: 10px; border: none;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            transition: left var(--transition), background 0.2s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        #menu-toggle:hover { background: var(--primary-light); }

        .content-body { padding: 28px 24px; flex: 1; }

        @media (min-width: 992px) { #sidebar-overlay { display: none !important; } }
        @media (max-width: 991px) {
            .content.shifted { margin-left: 0; }
            #menu-toggle { left: 10px !important; }
        }
        @media (max-width: 576px) {
            .content-body { padding: 16px 12px; }
            .top-bar { padding-left: 60px; }
        }
    </style>

    @yield('styles')
</head>
<body>

{{-- OVERLAY --}}
<div id="sidebar-overlay"></div>

{{-- TOGGLE --}}
<button id="menu-toggle" aria-label="Abrir menú">
    <i class="bi bi-list"></i>
</button>

{{-- SIDEBAR --}}
<div id="sidebar" class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <div class="sidebar-brand-text">Ubicatec</div>
            <div class="sidebar-brand-sub">Panel de navegación</div>
        </div>
    </div>

    @php $ruta = request()->route() ? request()->route()->getName() : ''; @endphp

    <div class="sidebar-section">
        <div class="sidebar-section-label">Navegación</div>

        <button type="button"
                class="nav-item-sidebar {{ $ruta === 'home' ? 'active-route' : '' }}"
                onclick="window.location.href='{{ url('/') }}'">
            <i class="bi bi-house-fill nav-icon"></i><span>Inicio</span>
        </button>

        @if(session('usuario')?->tieneRol(['administrador', 'jefe_departamento']))
            <button type="button"
                    class="nav-item-sidebar {{ $ruta === 'usuarios.index' ? 'active-route' : '' }}"
                    {{ $ruta === 'usuarios.index' ? 'disabled' : '' }}
                    onclick="window.location.href='{{ url('usuarios') }}'">
                <i class="bi bi-people-fill nav-icon"></i><span>Usuarios</span>
            </button>
        @endif

        <button type="button"
                class="nav-item-sidebar {{ $ruta === 'eventos.index' ? 'active-route' : '' }}"
                {{ $ruta === 'eventos.index' ? 'disabled' : '' }}
                onclick="window.location.href='{{ url('eventos') }}'">
            <i class="bi bi-calendar-event-fill nav-icon"></i><span>Eventos</span>
        </button>

        <button type="button"
                class="nav-item-sidebar {{ $ruta === 'mapa.index' ? 'active-route' : '' }}"
                {{ $ruta === 'mapa.index' ? 'disabled' : '' }}
                onclick="window.location.href='{{ url('mapa') }}'">
            <i class="bi bi-map-fill nav-icon"></i><span>Mapa Campus</span>
        </button>

        <button type="button"
                class="nav-item-sidebar {{ $ruta === 'aulas.index' ? 'active-route' : '' }}"
                {{ $ruta === 'aulas.index' ? 'disabled' : '' }}
                onclick="window.location.href='{{ url('aulas') }}'">
            <i class="bi bi-building nav-icon"></i><span>Edifico A</span>
        </button>

        <button type="button"
            class="nav-item-sidebar {{ $ruta === 'aulas.edificioB' ? 'active-route' : '' }}"
            {{ $ruta === 'aulas.edificioB' ? 'disabled' : '' }}
            onclick="window.location.href='{{ route('aulas.edificioB') }}'">
            <i class="bi bi-building nav-icon"></i><span>Edificio B</span>
        </button>
        <button type="button"
            class="nav-item-sidebar {{ $ruta === 'aulas.edificioC' ? 'active-route' : '' }}"
            {{ $ruta === 'aulas.edificioC' ? 'disabled' : '' }}
            onclick="window.location.href='{{ route('aulas.edificioC') }}'">
            <i class="bi bi-building nav-icon"></i><span>Edificio C</span>
        </button>
        <button type="button"
            class="nav-item-sidebar {{ $ruta === 'aulas.salonesPracticas' ? 'active-route' : '' }}"
            {{ $ruta === 'aulas.salonesPracticas' ? 'disabled' : '' }}
            onclick="window.location.href='{{ route('aulas.salonesPracticas') }}'">
            <i class="bi bi-easel2 nav-icon"></i><span>Salones de prácticas</span>
        </button>
        <button type="button"
            class="nav-item-sidebar {{ $ruta === 'aulas.manufactura' ? 'active-route' : '' }}"
            {{ $ruta === 'aulas.manufactura' ? 'disabled' : '' }}
            onclick="window.location.href='{{ route('aulas.manufactura') }}'">
            <i class="bi bi-easel2 nav-icon"></i><span>Salon de Manufactura</span>
        </button>
        <div class="sidebar-section-label mt-3">Sistema</div>

                @if(session('usuario')?->esAdmin())
            <button type="button"
                    class="nav-item-sidebar"
                    onclick="window.location.href='{{ route('backup.index') }}'">
                <i class="bi bi-cloud-arrow-up-fill nav-icon"></i><span>Respaldar BD</span>
            </button>
        @endif
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</div>

{{-- CONTENIDO PRINCIPAL --}}
<div class="content" id="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <span class="top-bar-title">@yield('title', 'Inicio')</span>
        <div class="top-bar-right">
            <span class="text-muted small d-none d-sm-inline">
                {{ now()->format('d M Y') }}
            </span>
            {{-- Avatar / botón perfil --}}
            <button id="btn-perfil" title="Mi perfil">
                <img id="avatar-topbar"
                     src="{{ session('usuario')?->foto_url ?? 'https://ui-avatars.com/api/?name=U&background=1a3c4e&color=fff' }}"
                     alt="Foto perfil">
            </button>
        </div>
    </div>

    {{-- MODAL PERFIL --}}
    <div class="modal fade" id="modalPerfil" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">

                {{-- Header --}}
                <div class="modal-header border-0 text-white"
                     style="background: linear-gradient(135deg,#102530,#1a3c4e); padding: 24px 24px 16px;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="position:relative; width:64px; height:64px;">
                            <img id="preview-foto"
                                src="{{ session('usuario')?->foto_url ?? 'https://ui-avatars.com/api/?name=U&background=1a3c4e&color=fff' }}"
                                alt="Foto"
                                style="width:64px;height:64px;object-fit:cover;border-radius:50%;border:3px solid rgba(255,255,255,0.4);">
                            {{-- Botón cámara visible para TODOS --}}
                            <label for="input-foto"
                                style="position:absolute;bottom:0;right:0;width:22px;height:22px;
                                        background:#3aafce;border-radius:50%;cursor:pointer;
                                        display:flex;align-items:center;justify-content:center;
                                        border:2px solid white;">
                                <i class="bi bi-camera-fill" style="font-size:.6rem;color:white;"></i>
                            </label>
                            <input type="file" id="input-foto" accept="image/*" class="d-none">
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="modal-nombre">
                                {{ session('usuario')?->nombre ?? 'Usuario' }}
                            </h5>
                            <span id="modal-rol-badge" class="badge mt-1" style="font-size:.75rem;">
                                {{ session('usuario')?->rol_label ?? '' }}
                            </span>
                        </div>
                    </div>
                    <button type="button"
                            class="btn-close btn-close-white ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4">
                    <form id="form-perfil" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">
                                <i class="bi bi-person-lines-fill me-1"></i>Descripción
                            </label>
                            <textarea id="input-descripcion" name="descripcion"
                                      class="form-control" rows="3" maxlength="300"
                                      placeholder="Escribe una breve descripción..."
                                      style="resize:none; border-radius:10px;">{{ session('usuario')?->descripcion }}</textarea>
                            <div class="text-end mt-1">
                                <span id="char-count" class="text-muted" style="font-size:.75rem;">0/300</span>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-semibold text-secondary small">
                                <i class="bi bi-envelope-fill me-1"></i>Correo electrónico
                            </label>
                            <input type="email" class="form-control"
                                   value="{{ session('usuario')?->correo }}"
                                   disabled
                                   style="border-radius:10px; background:#f8fafc;">
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between align-items-center">
                    @if(session('usuario')?->esAdmin())
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                id="btn-eliminar-usuario"
                                style="border-radius:8px; display:none;">
                            <i class="bi bi-trash3-fill me-1"></i>Eliminar usuario
                        </button>
                    @else
                        <div></div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal"
                                style="border-radius:8px;">
                            Cancelar
                        </button>
                        <button type="button" id="btn-guardar-perfil"
                                class="btn text-white fw-semibold"
                                style="border-radius:8px; background:linear-gradient(135deg,#1a3c4e,#3aafce);">
                            <i class="bi bi-check2-circle me-1"></i>Guardar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="content-body">
        @yield('content')
    </div>
</div>

<!-- Bootstrap JS — SIEMPRE antes de los scripts del modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ── Sidebar toggle ──
    const toggleBtn = document.getElementById('menu-toggle');
    const sidebar   = document.getElementById('sidebar');
    const content   = document.getElementById('main-content');
    const overlay   = document.getElementById('sidebar-overlay');
    const SIDEBAR_W = 265;
    const isDesktop = () => window.innerWidth >= 992;

    function openSidebar() {
        sidebar.classList.add('active');
        content.classList.add('shifted');
        toggleBtn.style.left = isDesktop() ? (SIDEBAR_W + 10) + 'px' : '10px';
        if (!isDesktop()) overlay.classList.add('active');
    }
    function closeSidebar() {
        sidebar.classList.remove('active');
        content.classList.remove('shifted');
        toggleBtn.style.left = '10px';
        overlay.classList.remove('active');
    }

    openSidebar();
    toggleBtn.addEventListener('click', () => sidebar.classList.contains('active') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => {
        if (isDesktop()) {
            overlay.classList.remove('active');
            if (sidebar.classList.contains('active')) {
                content.classList.add('shifted');
                toggleBtn.style.left = (SIDEBAR_W + 10) + 'px';
            }
        } else {
            content.classList.remove('shifted');
            if (sidebar.classList.contains('active')) toggleBtn.style.left = '10px';
        }
    });

    // ── Modal perfil ──
    document.addEventListener('DOMContentLoaded', () => {
        const modalEl     = document.getElementById('modalPerfil');
        const modal       = new bootstrap.Modal(modalEl);
        const btnPerfil   = document.getElementById('btn-perfil');
        const inputFoto   = document.getElementById('input-foto');
        const previewFoto = document.getElementById('preview-foto');
        const avatarTopbar= document.getElementById('avatar-topbar');
        const textarea    = document.getElementById('input-descripcion');
        const charCount   = document.getElementById('char-count');
        const btnGuardar  = document.getElementById('btn-guardar-perfil');
        const selectRol   = document.getElementById('input-rol');

        const rolColors = {
            administrador:     '#ef4444',
            docente:           '#3b82f6',
            jefe_departamento: '#8b5cf6',
            estudiante:        '#10b981',
        };
        const rolLabels = {
            administrador:     'Administrador',
            docente:           'Docente',
            jefe_departamento: 'Jefe de Departamento',
            estudiante:        'Estudiante / Visitante',
        };

        function actualizarBadge(rol) {
            const badge = document.getElementById('modal-rol-badge');
            if (!badge) return;
            badge.textContent       = rolLabels[rol] || rol;
            badge.style.background  = rolColors[rol] || '#64748b';
        }
        actualizarBadge("{{ session('usuario')?->rol ?? 'estudiante' }}");

        // Contador caracteres
        if (textarea) {
            charCount.textContent = textarea.value.length + '/300';
            textarea.addEventListener('input', () => {
                charCount.textContent = textarea.value.length + '/300';
            });
        }

        // Preview foto
        if (inputFoto) {
            inputFoto.addEventListener('change', e => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = ev => previewFoto.src = ev.target.result;
                reader.readAsDataURL(file);
            });
        }

        // Abrir modal
        if (btnPerfil) btnPerfil.addEventListener('click', () => modal.show());

        // Cambio de rol
        if (selectRol) selectRol.addEventListener('change', () => actualizarBadge(selectRol.value));

        // Guardar perfil
        if (btnGuardar) {
            btnGuardar.addEventListener('click', () => {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('descripcion', textarea ? textarea.value : '');
                if (selectRol) formData.append('rol', selectRol.value);
                if (inputFoto && inputFoto.files[0]) formData.append('foto', inputFoto.files[0]);

                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';

                fetch('{{ url("/perfil/update") }}', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        avatarTopbar.src = data.foto_url + '?t=' + Date.now();
                        previewFoto.src  = avatarTopbar.src;
                        actualizarBadge(data.rol);
                        modal.hide();
                        Swal.fire({ icon:'success', title:'¡Listo!', text: data.message, timer:2000, showConfirmButton:false });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo guardar.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Ocurrió un problema de red.', 'error'))
                .finally(() => {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Guardar';
                });
            });
        }

        // Eliminar usuario (admin)
        const btnEliminar = document.getElementById('btn-eliminar-usuario');
        if (btnEliminar) {
            btnEliminar.addEventListener('click', () => {
                const userId = btnEliminar.dataset.userId;
                Swal.fire({
                    title: '¿Eliminar usuario?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, eliminar',
                }).then(result => {
                    if (!result.isConfirmed) return;
                    fetch(`/perfil/${userId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            Swal.fire('Eliminado', data.message, 'success')
                                .then(() => window.location.href = '{{ url("usuarios") }}');
                        }
                    });
                });
            });
        }
    });
</script>

@yield('scripts')
@stack('scripts')

@include('components.chatbox')
</body>
</html>