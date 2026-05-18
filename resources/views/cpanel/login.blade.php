<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | UbicaTec</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-body: 'Inter', system-ui, sans-serif;
            --text-xs:   clamp(0.75rem, 0.7rem + 0.25vw, 0.875rem);
            --text-sm:   clamp(0.875rem, 0.8rem + 0.35vw, 1rem);
            --text-base: clamp(1rem, 0.95rem + 0.25vw, 1.125rem);
            --space-1: 0.25rem; --space-2: 0.5rem; --space-3: 0.75rem;
            --space-4: 1rem;    --space-5: 1.25rem; --space-6: 1.5rem;
            --space-8: 2rem;
            --radius-sm: 0.375rem; --radius-md: 0.5rem;
            --radius-lg: 0.75rem;  --radius-xl: 1rem; --radius-full: 9999px;
            --transition-interactive: 180ms cubic-bezier(0.16, 1, 0.3, 1);
            --color-bg: #f4f6f9;
            --color-surface: #ffffff;
            --color-surface-2: #f8f9fb;
            --color-divider: #e4e8f0;
            --color-border: #dde2ec;
            --color-text: #0f1623;
            --color-text-muted: #5a6480;
            --color-text-faint: #9aa3bb;
            --color-text-inverse: #ffffff;
            --color-primary: #2563eb;
            --color-primary-hover: #1d4ed8;
            --color-primary-active: #1e40af;
            --color-error: #dc2626;
            --color-error-highlight: #fef2f2;
            --color-success: #16a34a;
            --color-success-highlight: #f0fdf4;
            --shadow-sm: 0 1px 3px oklch(0.2 0.02 260 / 0.06), 0 1px 2px oklch(0.2 0.02 260 / 0.04);
            --shadow-md: 0 4px 12px oklch(0.2 0.02 260 / 0.08), 0 2px 6px oklch(0.2 0.02 260 / 0.06);
            --shadow-lg: 0 20px 60px oklch(0.2 0.02 260 / 0.14), 0 8px 24px oklch(0.2 0.02 260 / 0.08);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility; }
        body {
            min-height: 100dvh; font-family: var(--font-body); font-size: var(--text-sm);
            color: var(--color-text); background: var(--color-bg);
            display: flex; align-items: center; justify-content: center;
            padding: var(--space-4); position: relative; overflow: hidden;
        }
        input, button, select { font: inherit; color: inherit; }
        button { cursor: pointer; background: none; border: none; }

        /* Background */
        body::before {
            content: ""; position: fixed; inset: 0; z-index: 0;
            background: url('{{ asset("assets/images/logofinal.jpeg") }}') no-repeat center center / cover;
        }
        body::after {
            content: ""; position: fixed; inset: 0; z-index: 1;
            background: linear-gradient(135deg, oklch(0.18 0.08 260 / 0.75) 0%, oklch(0.16 0.06 230 / 0.65) 50%, oklch(0.20 0.05 200 / 0.70) 100%);
            backdrop-filter: blur(10px) saturate(0.8);
        }

        /* Floating ambient shapes */
        .bg-shapes { position: fixed; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
        .bg-shape {
            position: absolute; border-radius: var(--radius-full);
            background: oklch(0.55 0.12 220 / 0.08);
            animation: float-shape 20s ease-in-out infinite;
        }
        .bg-shape:nth-child(1) { width: 500px; height: 500px; top: -200px; right: -100px; animation-delay: 0s; }
        .bg-shape:nth-child(2) { width: 300px; height: 300px; bottom: -100px; left: -80px; animation-delay: -8s; background: oklch(0.60 0.10 200 / 0.06); }
        .bg-shape:nth-child(3) { width: 200px; height: 200px; top: 50%; left: 30%; animation-delay: -14s; background: oklch(0.65 0.08 240 / 0.05); }
        @keyframes float-shape {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -30px) scale(1.04); }
            66% { transform: translate(-15px, 15px) scale(0.97); }
        }

        /* Card */
        .login-wrap { position: relative; z-index: 10; width: 100%; max-width: 420px; }
        .card-box {
            background: rgba(255, 255, 255, 0.97);
            border-radius: var(--radius-xl); box-shadow: var(--shadow-lg);
            border: 1px solid oklch(1 0 0 / 0.8); overflow: hidden;
            animation: card-enter 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes card-enter {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Tabs */
        .tab-nav { display: flex; background: var(--color-surface-2); border-bottom: 1px solid var(--color-divider); padding: var(--space-2); gap: var(--space-1); }
        .tab-btn {
            flex: 1; padding: var(--space-2) var(--space-3); border-radius: var(--radius-md);
            font-size: var(--text-xs); font-weight: 500; color: var(--color-text-muted);
            cursor: pointer; border: none; background: transparent;
            transition: color var(--transition-interactive), background var(--transition-interactive), box-shadow var(--transition-interactive);
            letter-spacing: 0.01em;
        }
        .tab-btn.active { color: var(--color-text); background: var(--color-surface); box-shadow: var(--shadow-sm); font-weight: 600; }
        .tab-btn:hover:not(.active) { color: var(--color-text); background: oklch(0 0 0 / 0.04); }

        /* Panels */
        .tab-panel { display: none; padding: var(--space-8) var(--space-8) var(--space-6); }
        .tab-panel.active {
            display: block;
            animation: panel-enter 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes panel-enter {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand */
        .brand-header { text-align: center; margin-bottom: var(--space-6); }
        .brand-logo {
            display: inline-flex; align-items: center; justify-content: center;
            width: 48px; height: 48px; background: var(--color-primary);
            border-radius: var(--radius-lg); margin-bottom: var(--space-3);
            box-shadow: 0 4px 14px oklch(0.45 0.22 260 / 0.35);
            transition: transform var(--transition-interactive), box-shadow var(--transition-interactive);
        }
        .brand-logo:hover { transform: scale(1.05); box-shadow: 0 6px 20px oklch(0.45 0.22 260 / 0.45); }
        .brand-logo svg { width: 22px; height: 22px; fill: none; stroke: white; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .brand-name { font-size: 1.2rem; font-weight: 700; color: var(--color-text); letter-spacing: -0.02em; margin-bottom: var(--space-1); }
        .brand-sub { font-size: var(--text-xs); color: var(--color-text-muted); }

        /* Fields */
        .field { margin-bottom: var(--space-4); }
        .form-label { display: block; font-size: var(--text-xs); font-weight: 500; color: var(--color-text-muted); margin-bottom: var(--space-1); letter-spacing: 0.02em; }
        .form-control, .form-select {
            width: 100%; padding: 0.625rem var(--space-3);
            background: var(--color-surface-2); border: 1px solid var(--color-border);
            border-radius: var(--radius-md); font-size: var(--text-sm); color: var(--color-text);
            transition: border-color var(--transition-interactive), box-shadow var(--transition-interactive), background var(--transition-interactive);
            -webkit-appearance: none; appearance: none;
        }
        .form-control::placeholder { color: var(--color-text-faint); }
        .form-control:hover, .form-select:hover { border-color: oklch(0.6 0.10 260 / 0.5); }
        .form-control:focus, .form-select:focus {
            outline: none; background: var(--color-surface);
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px oklch(0.55 0.22 260 / 0.12);
        }
        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235a6480' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.75rem center; padding-right: 2.25rem;
        }

        /* Button */
        .btn-main {
            width: 100%; padding: 0.65rem var(--space-4); background: var(--color-primary);
            color: var(--color-text-inverse); border: none; border-radius: var(--radius-md);
            font-size: var(--text-sm); font-weight: 600; letter-spacing: 0.01em; cursor: pointer;
            transition: background var(--transition-interactive), transform var(--transition-interactive), box-shadow var(--transition-interactive);
            box-shadow: 0 2px 8px oklch(0.45 0.22 260 / 0.30);
            display: flex; align-items: center; justify-content: center; gap: var(--space-2);
        }
        .btn-main:hover { background: var(--color-primary-hover); transform: translateY(-1px); box-shadow: 0 4px 16px oklch(0.45 0.22 260 / 0.40); }
        .btn-main:active { background: var(--color-primary-active); transform: translateY(0); box-shadow: 0 2px 6px oklch(0.45 0.22 260 / 0.25); }
        .btn-main .btn-arrow { transition: transform var(--transition-interactive); flex-shrink: 0; }
        .btn-main:hover .btn-arrow { transform: translateX(3px); }

        /* Code inputs */
        .code-inputs { display: flex; gap: var(--space-2); justify-content: center; margin: var(--space-2) 0; }
        .code-inputs input {
            width: 44px; height: 52px; text-align: center; font-size: 1.3rem; font-weight: 700;
            border-radius: var(--radius-md); border: 1.5px solid var(--color-border);
            background: var(--color-surface-2); color: var(--color-primary);
            transition: border-color var(--transition-interactive), box-shadow var(--transition-interactive), transform var(--transition-interactive), background var(--transition-interactive);
        }
        .code-inputs input:focus {
            outline: none; background: var(--color-surface); border-color: var(--color-primary);
            box-shadow: 0 0 0 3px oklch(0.55 0.22 260 / 0.12);
            transform: translateY(-2px) scale(1.05);
        }
        .code-inputs input.filled { border-color: var(--color-primary); background: oklch(0.95 0.03 260); }

        /* Alerts */
        .alert-custom {
            padding: var(--space-2) var(--space-3); border-radius: var(--radius-md); font-size: var(--text-xs);
            margin-top: var(--space-3); display: flex; align-items: center; gap: var(--space-2);
            animation: alert-enter 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes alert-enter { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
        .alert-err { background: var(--color-error-highlight); color: var(--color-error); border: 1px solid oklch(0.5 0.18 20 / 0.15); }
        .alert-ok  { background: var(--color-success-highlight); color: var(--color-success); border: 1px solid oklch(0.5 0.14 145 / 0.15); }
        .alert-icon { flex-shrink: 0; width: 14px; height: 14px; }

        /* Expira */
        .expira-badge {
            display: inline-flex; align-items: center; gap: var(--space-2);
            background: oklch(0.97 0.03 60); border: 1px solid oklch(0.85 0.08 60 / 0.6);
            border-radius: var(--radius-full); padding: var(--space-1) var(--space-3);
            font-size: var(--text-xs); color: #92400e; font-weight: 500; margin-bottom: var(--space-4);
        }

        /* Links */
        .link-sec { font-size: var(--text-xs); color: var(--color-text-muted); text-align: center; margin-top: var(--space-4); }
        .link-sec a { color: var(--color-primary); font-weight: 500; text-decoration: none; transition: color var(--transition-interactive); }
        .link-sec a:hover { color: var(--color-primary-hover); text-decoration: underline; }

        .divider { height: 1px; background: var(--color-divider); margin: var(--space-4) 0; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-3); }
        .submit-row { margin-top: var(--space-5); }

        @media (max-width: 480px) {
            .tab-panel { padding: var(--space-6) var(--space-5) var(--space-5); }
            .code-inputs input { width: 36px; height: 44px; font-size: 1.1rem; }
            .two-col { grid-template-columns: 1fr; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>

<div class="bg-shapes" aria-hidden="true">
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
</div>

<div class="login-wrap">
    <div class="card-box">

        {{-- TABS --}}
        <div class="tab-nav" role="tablist">
            <button class="tab-btn active" role="tab" aria-selected="true" onclick="switchTab('login')">Iniciar sesión</button>
            <button class="tab-btn" role="tab" aria-selected="false" onclick="switchTab('registro')">Crear cuenta</button>
        </div>

        {{-- PANEL LOGIN --}}
        <div class="tab-panel active" id="panel-login" role="tabpanel">
            <div class="brand-header">
                <div class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="white" stroke="none"/>
                        <circle cx="12" cy="9" r="2.5" fill="rgba(37,99,235,0.9)" stroke="none"/>
                    </svg>
                </div>
                <div class="brand-name">UbicaTec</div>
                <div class="brand-sub">Sistema de gestión del campus</div>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="field">
                    <label class="form-label" for="no_control_login">No. de Control</label>
                    <input type="text" id="no_control_login" name="no_control" class="form-control" placeholder="20260001" required autofocus autocomplete="username">
                </div>
                <div class="field">
                    <label class="form-label" for="password_login">Contraseña</label>
                    <input type="password" id="password_login" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                </div>
                <div class="submit-row">
                    <button type="submit" class="btn-main">
                        Entrar
                        <svg class="btn-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if(session('error'))
                    <div class="alert-custom alert-err">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert-custom alert-ok">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
            </form>
            <div class="link-sec">
                ¿No tienes cuenta? <a href="#" onclick="switchTab('registro'); return false;">Regístrate aquí</a>
            </div>
        </div>

        {{-- PANEL REGISTRO --}}
        <div class="tab-panel" id="panel-registro" role="tabpanel">
            <div class="brand-header">
                <div class="brand-name" style="font-size:1.05rem;">Crear cuenta</div>
                <div class="brand-sub">Recibirás un código de verificación en tu correo</div>
            </div>
            <form method="POST" action="{{ route('registro.post') }}">
                @csrf
                <div class="field">
                    <label class="form-label" for="no_control_reg">No. de Control</label>
                    <input type="text" id="no_control_reg" name="no_control" class="form-control" placeholder="20260001" required value="{{ old('no_control') }}">
                </div>
                <div class="field">
                    <label class="form-label" for="nombre_reg">Nombre</label>
                    <input type="text" id="nombre_reg" name="nombre" class="form-control" placeholder="Juan" required value="{{ old('nombre') }}">
                </div>
                <div class="two-col field">
                    <div>
                        <label class="form-label" for="ape_pat">Ap. Paterno</label>
                        <input type="text" id="ape_pat" name="ape_paterno" class="form-control" placeholder="García" value="{{ old('ape_paterno') }}">
                    </div>
                    <div>
                        <label class="form-label" for="ape_mat">Ap. Materno</label>
                        <input type="text" id="ape_mat" name="ape_materno" class="form-control" placeholder="López" value="{{ old('ape_materno') }}">
                    </div>
                </div>
                <div class="field">
                    <label class="form-label" for="carrera_reg">Carrera</label>
                    <select id="carrera_reg" name="carrera" class="form-select" required>
                        <option value="" disabled selected>Selecciona tu carrera</option>
                        <option value="A"  {{ old('carrera') == 'A'  ? 'selected' : '' }}>Ambiental</option>
                        <option value="C"  {{ old('carrera') == 'C'  ? 'selected' : '' }}>Contador Público</option>
                        <option value="E"  {{ old('carrera') == 'E'  ? 'selected' : '' }}>Electromecánica</option>
                        <option value="G"  {{ old('carrera') == 'G'  ? 'selected' : '' }}>Gestión Empresarial</option>
                        <option value="I"  {{ old('carrera') == 'I'  ? 'selected' : '' }}>Industrial</option>
                        <option value="S"  {{ old('carrera') == 'S'  ? 'selected' : '' }}>Sistemas Computacionales</option>
                        <option value="T"  {{ old('carrera') == 'T'  ? 'selected' : '' }}>Turismo</option>
                    </select>
                </div>
                <div class="field">
                    <label class="form-label" for="correo_reg">Correo electrónico</label>
                    <input type="email" id="correo_reg" name="correo" class="form-control" placeholder="correo@tectepic.edu.mx" required value="{{ old('correo') }}">
                </div>
                <div class="field">
                    <label class="form-label" for="password_reg">Contraseña</label>
                    <input type="password" id="password_reg" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="submit-row">
                    <button type="submit" class="btn-main">
                        Enviar código de verificación
                        <svg class="btn-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if(session('error') && session('registro_step') === 'registro')
                    <div class="alert-custom alert-err">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert-custom alert-err">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif
            </form>
            <div class="link-sec">
                ¿Ya tienes cuenta? <a href="#" onclick="switchTab('login'); return false;">Inicia sesión</a>
            </div>
        </div>

        {{-- PANEL VERIFICAR REGISTRO --}}
        <div class="tab-panel" id="panel-verificar-registro" role="tabpanel">
            <div class="brand-header">
                <div class="brand-logo" style="background:oklch(0.45 0.14 145);" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <div class="brand-name" style="font-size:1.05rem;">Verifica tu correo</div>
                <div class="brand-sub">
                    Código enviado a
                    <strong style="color:var(--color-primary);">{{ session('registro_correo') }}</strong>
                </div>
            </div>
            <div style="text-align:center;">
                <div class="expira-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Expira en 5 minutos
                </div>
            </div>
            <form method="POST" action="{{ route('registro.verificar') }}">
                @csrf
                <div class="code-inputs" id="code-boxes-reg">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 1">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 2">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 3">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 4">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 5">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="Dígito 6">
                </div>
                <input type="hidden" name="codigo" id="codigo-reg-hidden">
                <div class="submit-row">
                    <button type="submit" class="btn-main">
                        Verificar y crear cuenta
                        <svg class="btn-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                @if(session('error') && !session('registro_step'))
                    <div class="alert-custom alert-err">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert-custom alert-ok">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
            </form>
            <div class="divider"></div>
            <div class="link-sec">
                ¿No recibiste el correo?
                <a href="{{ route('registro.reenviar') }}">Reenviar código</a>
                &nbsp;·&nbsp;
                <a href="#" onclick="switchTab('registro'); return false;">Cambiar datos</a>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.remove('active');
        p.setAttribute('aria-hidden', 'true');
    });
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
    });
    const panel = document.getElementById('panel-' + tab);
    if (panel) { panel.classList.add('active'); panel.setAttribute('aria-hidden', 'false'); }
    const idx = { login: 0, registro: 1 };
    if (idx[tab] !== undefined) {
        const btn = document.querySelectorAll('.tab-btn')[idx[tab]];
        if (btn) { btn.classList.add('active'); btn.setAttribute('aria-selected', 'true'); }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    @if(session('registro_correo'))
        switchTab('verificar-registro');
    @elseif($errors->any() || session('registro_step') === 'registro')
        switchTab('registro');
    @endif
});

document.addEventListener('DOMContentLoaded', () => {
    const boxes  = document.querySelectorAll('#code-boxes-reg input');
    const hidden = document.getElementById('codigo-reg-hidden');

    function sync() { hidden.value = [...boxes].map(b => b.value).join(''); }
    function updateFilled() { boxes.forEach(b => b.classList.toggle('filled', !!b.value)); }

    boxes.forEach((box, i) => {
        box.addEventListener('input', () => {
            box.value = box.value.replace(/\D/g, '');
            updateFilled();
            if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
            sync();
        });
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
        });
        box.addEventListener('paste', e => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            [...text].slice(0, 6).forEach((c, j) => { if (boxes[j]) boxes[j].value = c; });
            boxes[Math.min(text.length, 5)].focus();
            updateFilled();
            sync();
        });
    });
});
</script>
</body>
</html>