@extends('layouts.app')

@section('title', 'Eventos — Ubicatec')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root {
    --teal: #376073; --teal-dark: #2c4f60; --teal-light: #5a8fa3;
    --text: #1e3040; --text-muted: #5a7a8a; --text-faint: #9ab3c0;
    --surface: #fff; --bg: #eef4f8; --border: rgba(55,96,115,.13);
    --radius: 14px;
    --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
    --shadow-md: 0 6px 24px rgba(0,0,0,.10);
    --shadow-lg: 0 20px 60px rgba(0,0,0,.18);
    --ease: cubic-bezier(.34,1.56,.64,1);
    --ease-smooth: cubic-bezier(.16,1,.3,1);
}

/* ── PAGE ── */
.ev-page { display:flex; flex-direction:column; gap:24px; padding:4px 0 32px; }

/* ── HERO ── */
.ev-hero {
    background: linear-gradient(135deg, #0f2535 0%, var(--teal-dark) 40%, var(--teal) 75%, var(--teal-light) 100%);
    border-radius: var(--radius); padding: 24px 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    box-shadow: var(--shadow-md); position: relative; overflow: hidden;
}
.ev-hero::before {
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse at 0% 100%, rgba(74,156,109,.15) 0%, transparent 55%),
                radial-gradient(ellipse at 100% 0%, rgba(90,143,163,.12) 0%, transparent 50%);
    pointer-events:none;
}
.hero-left { position:relative; z-index:1; }
.hero-title { font-family:'Instrument Serif',serif; font-size:1.65rem; color:#fff; line-height:1.1; }
.hero-sub   { font-size:.75rem; color:rgba(255,255,255,.6); margin-top:4px; }
.hero-right { position:relative; z-index:1; display:flex; gap:8px; flex-wrap:wrap; }

/* ── BOTONES ── */
.btn-ev {
    display:inline-flex; align-items:center; gap:7px; padding:9px 18px;
    border-radius:20px; font-size:.78rem; font-weight:700; border:none;
    cursor:pointer; text-decoration:none; transition:opacity .2s, transform .15s var(--ease);
    white-space:nowrap;
}
.btn-ev:hover { opacity:.88; transform:translateY(-1px); }
.btn-ev-primary  { background:rgba(255,255,255,.18); color:#fff; border:1.5px solid rgba(255,255,255,.3); backdrop-filter:blur(6px); }
.btn-ev-danger   { background:rgba(231,76,60,.85); color:#fff; }
.btn-ev-success  { background:rgba(39,174,96,.85); color:#fff; }

/* ── SECTION LABEL ── */
.section-label {
    display:flex; align-items:center; gap:10px;
    font-family:'Instrument Serif',serif; font-size:1.1rem; color:var(--teal); margin-bottom:4px;
}
.section-label svg { width:17px; height:17px; stroke:var(--teal); fill:none; stroke-width:2; }
.section-label::after { content:''; flex:1; height:1px; background:linear-gradient(90deg,rgba(55,96,115,.2),transparent); }

/* ── CHARTS ── */
.charts-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
@media(max-width:768px){ .charts-grid { grid-template-columns:1fr; } }
.chart-card {
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--radius); padding:20px; box-shadow:var(--shadow-sm);
    cursor:pointer; transition:transform .2s var(--ease), box-shadow .2s;
}
.chart-card:hover { transform:translateY(-3px); box-shadow:var(--shadow-md); }
.chart-card-title {
    font-size:.72rem; font-weight:700; color:var(--text-faint);
    text-transform:uppercase; letter-spacing:.6px; margin-bottom:12px;
    display:flex; align-items:center; gap:6px;
}
.chart-card-title::before { content:''; width:8px; height:8px; border-radius:50%; background:var(--teal); display:block; }
.chart-card canvas { width:100%!important; height:260px!important; }

/* ── CALENDARIO ── */
.cal-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow-sm); }
.cal-header {
    background:linear-gradient(135deg,#0f2535,var(--teal)); color:#fff;
    padding:14px 20px; display:flex; align-items:center; justify-content:space-between;
}
.cal-header h4 { font-family:'Instrument Serif',serif; font-size:1.1rem; margin:0; }
.cal-nav { font-size:1.5rem; cursor:pointer; opacity:.8; transition:opacity .2s; user-select:none; padding:0 8px; }
.cal-nav:hover { opacity:1; }
.cal-body { padding:16px; }
.cal-body table { width:100%; border-collapse:collapse; }
.cal-body thead th { padding:8px 4px; font-size:.7rem; font-weight:700; color:var(--text-faint); text-transform:uppercase; letter-spacing:.5px; text-align:center; }
.cal-body tbody td { padding:4px; text-align:center; }
.cal-day {
    width:36px; height:36px; margin:auto; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:.82rem; cursor:pointer; transition:all .2s var(--ease); color:var(--text);
}
.cal-day:hover  { background:rgba(55,96,115,.1); color:var(--teal); }
.cal-day.today  { border:2px solid var(--teal); color:var(--teal); font-weight:700; }
.cal-day.has-event { background:linear-gradient(135deg,#e74c3c,#c0392b); color:#fff; font-weight:700; box-shadow:0 3px 10px rgba(231,76,60,.35); }
.cal-day.selected  { background:linear-gradient(135deg,var(--teal-dark),var(--teal)); color:#fff; box-shadow:0 3px 10px rgba(55,96,115,.35); }
.cal-day.today.has-event { border:2px solid #fff; }

/* ── PANEL EVENTOS DEL DÍA ── */
.cal-detail { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:20px; box-shadow:var(--shadow-sm); min-height:160px; }
.cal-detail-title { font-family:'Instrument Serif',serif; font-size:1rem; color:var(--teal); margin-bottom:12px; display:flex; align-items:center; gap:8px; }
.ev-day-card { background:#f5f9fc; border:1px solid var(--border); border-radius:9px; padding:12px 14px; margin-bottom:8px; border-left:3px solid var(--teal); }
.ev-day-card strong { font-size:.85rem; color:var(--text); }
.ev-day-card p { font-size:.75rem; color:var(--text-muted); margin:3px 0 0; }
.ev-day-empty { text-align:center; color:var(--text-faint); font-size:.82rem; padding:24px 0; }

/* ── BANNER CARTELERA ── */
.cartelera-banner {
    background:linear-gradient(135deg,#0f2535 0%,var(--teal-dark) 40%,var(--teal) 75%,#5a8fa3 100%);
    border-radius:var(--radius); padding:32px; text-align:center;
    position:relative; overflow:hidden; box-shadow:var(--shadow-md);
}
.cartelera-banner::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at 50% 100%,rgba(255,255,255,.06) 0%,transparent 70%); }
.cartelera-banner-text { font-family:'Instrument Serif',serif; font-size:clamp(2rem,5vw,3.5rem); color:#fff; letter-spacing:8px; position:relative; z-index:1; text-shadow:0 4px 20px rgba(0,0,0,.3); }
.cartelera-banner-sub  { font-size:.75rem; color:rgba(255,255,255,.55); margin-top:8px; letter-spacing:3px; text-transform:uppercase; position:relative; z-index:1; }

/* ── TARJETAS ── */
.events-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:20px; }
.ev-card { border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow-sm); transition:transform .3s var(--ease),box-shadow .3s; display:flex; flex-direction:column; }
.ev-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-lg); }
.ev-card-img-wrap { overflow:hidden; position:relative; }
.ev-card-img { width:100%; height:185px; object-fit:cover; cursor:pointer; transition:transform .4s var(--ease-smooth); display:block; }
.ev-card:hover .ev-card-img { transform:scale(1.05); }
.ev-card-body { flex:1; padding:14px 16px 10px; }
.ev-card-title { font-weight:700; line-height:1.3; margin-bottom:6px; }
.ev-card-desc  { font-size:.78rem; line-height:1.6; opacity:.75; }
.ev-card-footer { display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid rgba(0,0,0,.08); }
.ev-btn {
    padding:10px 4px; border:none; background:none; cursor:pointer; font-size:.72rem;
    font-weight:600; display:flex; flex-direction:column; align-items:center; gap:3px;
    transition:background .2s; border-right:1px solid rgba(0,0,0,.07);
}
.ev-btn:last-child { border-right:none; }
.ev-btn:hover { background:rgba(0,0,0,.06); }
.ev-btn .ev-btn-icon  { font-size:1rem; }
.ev-btn .ev-btn-count { font-size:.65rem; opacity:.7; }
/* Like activo */
.ev-btn.liked { color:var(--teal); }
.ev-btn.liked .ev-btn-icon { filter:drop-shadow(0 0 4px rgba(55,96,115,.5)); }

.ev-card-actions { display:grid; grid-template-columns:1fr 1fr; border-top:1px solid rgba(0,0,0,.06); }
.ev-action { padding:8px; border:none; background:none; cursor:pointer; font-size:.72rem; font-weight:700; display:flex; align-items:center; justify-content:center; gap:5px; transition:background .2s; color:var(--text-muted); text-decoration:none; border-right:1px solid rgba(0,0,0,.06); }
.ev-action:last-child { border-right:none; }
.ev-action:hover   { background:rgba(0,0,0,.05); }
.ev-action.edit:hover { background:#fff8e1; color:#f39c12; }
.ev-action.del:hover  { background:#fde8e8; color:#e74c3c; }

/* ── MODALES ── */
.modal-content { border:none; border-radius:16px; overflow:hidden; box-shadow:var(--shadow-lg); }
.modal-header-custom { background:linear-gradient(135deg,#0f2535,var(--teal)); padding:18px 24px; display:flex; align-items:center; justify-content:space-between; }
.modal-header-title { font-family:'Instrument Serif',serif; font-size:1.15rem; color:#fff; display:flex; align-items:center; gap:10px; }
.modal-body-custom { padding:20px 24px; }

/* ── COMENTARIOS ── */
.comment-item { padding:10px 12px; border-radius:9px; background:#f5f9fc; border:1px solid var(--border); margin-bottom:8px; font-size:.8rem; color:var(--text); line-height:1.5; }
.comment-item small { display:block; color:var(--text-faint); font-size:.68rem; margin-top:4px; }
.comment-textarea { width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:9px; font-size:.82rem; font-family:'Inter',sans-serif; color:var(--text); resize:none; outline:none; transition:border-color .2s; }
.comment-textarea:focus { border-color:var(--teal); }
.btn-send { width:100%; padding:10px; background:linear-gradient(135deg,var(--teal-dark),var(--teal)); color:#fff; border:none; border-radius:9px; font-size:.82rem; font-weight:700; cursor:pointer; transition:opacity .2s; box-shadow:0 4px 12px rgba(55,96,115,.25); }
.btn-send:hover { opacity:.88; }

/* ── MODAL IMAGEN GRANDE ── */
.modal-grande .modal-content { background:rgba(0,0,0,.88); border-radius:16px; }
.modal-grande .modal-body { min-height:70vh; display:flex; align-items:center; justify-content:center; padding:30px; }
.modal-grande .btn-close { position:absolute; top:16px; right:16px; z-index:10; background:#fff; border-radius:50%; padding:6px; opacity:.9; }
.modal-img-grande { max-width:90vw; max-height:80vh; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,.4); }
.grafica-grande-wrap { background:#fff; border-radius:16px; padding:28px; max-width:90vw; }
.grafica-grande-wrap h3 { font-family:'Instrument Serif',serif; color:var(--teal); text-align:center; margin-bottom:16px; }

/* ── OVERLAY TARJETA EXPANDIDA ── */
.expand-overlay {
    position:fixed; inset:0; background:rgba(15,37,53,.82); backdrop-filter:blur(8px);
    z-index:1050; display:flex; align-items:center; justify-content:center; padding:20px;
    opacity:0; pointer-events:none; transition:opacity .3s var(--ease-smooth);
}
.expand-overlay.visible { opacity:1; pointer-events:all; }
.ev-card-expanded {
    background:var(--surface); border-radius:20px; max-width:680px; width:100%;
    max-height:90vh; overflow-y:auto; box-shadow:var(--shadow-lg);
    transform:scale(.95) translateY(20px); transition:transform .35s var(--ease);
}
.expand-overlay.visible .ev-card-expanded { transform:scale(1) translateY(0); }
.ev-card-expanded-img { width:100%; max-height:320px; object-fit:cover; border-radius:20px 20px 0 0; }
.ev-card-expanded-noimg { width:100%; height:160px; background:var(--bg); border-radius:20px 20px 0 0; display:flex; align-items:center; justify-content:center; }
.ev-card-expanded-body { padding:24px; }
.ev-card-expanded-fecha { font-size:.75rem; color:var(--text-faint); margin-bottom:8px; }
.ev-card-expanded-titulo { font-family:'Instrument Serif',serif; font-size:1.6rem; color:var(--text); margin-bottom:10px; line-height:1.2; }
.ev-card-expanded-desc { font-size:.88rem; color:var(--text-muted); line-height:1.7; }
.expand-close-btn {
    position:absolute; top:16px; right:16px; background:rgba(255,255,255,.15);
    border:none; border-radius:50%; width:36px; height:36px; cursor:pointer;
    color:#fff; font-size:1.2rem; display:flex; align-items:center; justify-content:center;
    backdrop-filter:blur(4px); transition:background .2s;
}
.expand-close-btn:hover { background:rgba(255,255,255,.3); }
.expand-footer { border-top:1px solid var(--border); display:grid; grid-template-columns:repeat(3,1fr); }
</style>
@endsection

@section('content')
@section('content')
@php
    $rolActual = session('usuario')['rol']       ?? '';
    $uidActual = session('usuario')['no_control'] ?? null;
@endphp
<div class="ev-page"></div>
<div class="ev-page">

{{-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ --}}

<div class="ev-hero">
    <div class="hero-left">
        <div class="hero-title">Gestión de Eventos</div>
        <div class="hero-sub">Cartelera y actividades del campus universitario</div>
    </div>
    <div class="hero-right">

        @if(in_array($rolActual, ['administrador', 'jefe_departamento']))
            <a href="{{ route('eventos.create') }}" class="btn-ev btn-ev-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Crear Evento
            </a>
            <a href="{{ route('solicitudes.index') }}" class="btn-ev" 
       style="background:rgba(243,156,18,.85);color:#fff">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" 
             stroke="currentColor" stroke-width="2">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
        </svg>
        Solicitudes
        {{-- Badge con conteo de pendientes --}}
        @php
            $pendientes = \Illuminate\Support\Facades\DB::table('solicitudes_evento')
                ->where('estado','pendiente')->count();
        @endphp
        @if($pendientes > 0)
            <span style="background:#e74c3c;color:#fff;border-radius:50%;
                         width:18px;height:18px;font-size:.65rem;
                         display:inline-flex;align-items:center;justify-content:center">
                {{ $pendientes }}
            </span>
        @endif
    </a>
        @elseif(in_array($rolActual, ['docente', 'estudiante']))
            <a href="{{ route('solicitudes.create') }}" class="btn-ev btn-ev-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Solicitar Evento
            </a>
        @endif

        @if(in_array($rolActual, ['administrador', 'jefe_departamento']))
            <a href="{{ route('eventos.pdf') }}" class="btn-ev btn-ev-danger" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                PDF
            </a>
            <a href="{{ route('eventos.excel') }}" class="btn-ev btn-ev-success">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M3 9h18M9 21V9"/>
                </svg>
                Excel
            </a>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════
     GRÁFICAS
══════════════════════════════════════ --}}
<div>
    <div class="section-label">
        <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Estadísticas de eventos
    </div>
    <div class="charts-grid">
        <div class="chart-card" data-tipo="grafica" data-nombre="Eventos por mes">
            <div class="chart-card-title">Eventos por mes</div>
            <canvas id="chartAnual"></canvas>
        </div>
        <div class="chart-card" data-tipo="grafica" data-nombre="Eventos por semana">
            <div class="chart-card-title">Eventos por semana</div>
            <canvas id="chartMensual"></canvas>
        </div>
        <div class="chart-card" data-tipo="grafica" data-nombre="Eventos diarios">
            <div class="chart-card-title">Eventos diarios</div>
            <canvas id="chartSemanal"></canvas>
        </div>
        <div class="chart-card" data-tipo="grafica" data-nombre="Top eventos">
            <div class="chart-card-title">Top eventos — likes / favoritos</div>
            <canvas id="chartTop"></canvas>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     CALENDARIO
══════════════════════════════════════ --}}
<div>
    <div class="section-label">
        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Calendario de eventos
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="cal-card">
                <div class="cal-header">
                    <span class="cal-nav prev-month">&#8249;</span>
                    <h4 id="current-month"></h4>
                    <span class="cal-nav next-month">&#8250;</span>
                </div>
                <div class="cal-body">
                    <table>
                        <thead>
                            <tr><th>Dom</th><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th></tr>
                        </thead>
                        <tbody id="calendar-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="cal-detail">
                <div class="cal-detail-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Eventos del día</span>
                    <span style="font-size:.72rem;color:var(--text-faint);font-family:'Inter',sans-serif" id="selected-date"> — Selecciona una fecha</span>
                </div>
                <div id="event-list">
                    <div class="ev-day-empty">Selecciona un día para ver sus eventos</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     BANNER CARTELERA
══════════════════════════════════════ --}}
<div class="cartelera-banner">
    <div class="cartelera-banner-text">CARTELERA</div>
    <div class="cartelera-banner-sub">Eventos y actividades del campus</div>
</div>

{{-- ══════════════════════════════════════
     TARJETAS EVENTOS
══════════════════════════════════════ --}}
<div>
    <div class="section-label">
        <svg viewBox="0 0 24 24"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
        Todos los eventos
    </div>
    <div class="events-grid" id="contenedor-eventos">
        @foreach($eventos as $evento)
        <div data-id="{{ $evento->id_evento }}">
            <div class="ev-card"
                 style="background:{{ $evento->color_fondo }};
                        color:{{ $evento->color_texto }};
                        border:{{ $evento->borde }};
                        font-family:{{ $evento->font_family }}">

                <div class="ev-card-img-wrap">
                    <img src="{{ asset('storage/' . $evento->poster) }}"
                         class="ev-card-img img-card"
                         data-tipo="evento"
                         data-titulo="{{ $evento->titulo }}"
                         alt="{{ $evento->titulo }}">
                </div>

                <div class="ev-card-body">
                    <div class="ev-card-title" style="font-size:{{ $evento->size_titulo }}px">
                        {{ $evento->titulo }}
                    </div>
                    <div class="ev-card-desc" style="font-size:{{ $evento->size_desc }}px">
                        {{ $evento->descripcion }}
                    </div>
                </div>

                <div class="ev-card-footer">
                    {{-- LIKE — 1 por usuario, toggle --}}
                    @php
                        $yaLiked = in_array($uidActual, json_decode($evento->likes_usuarios ?? '[]', true));
                    @endphp
                    <button class="ev-btn btn-like {{ $yaLiked ? 'liked' : '' }}"
                            data-id="{{ $evento->id_evento }}">
                        <span class="ev-btn-icon">👍</span>
                        <span class="ev-btn-count" id="like-{{ $evento->id_evento }}">{{ $evento->likes }}</span>
                    </button>

                    {{-- FAVORITO --}}
                    <button class="ev-btn btn-fav" data-id="{{ $evento->id_evento }}">
                        <span class="ev-btn-icon">⭐</span>
                        <span class="ev-btn-count" id="fav-{{ $evento->id_evento }}">{{ $evento->favoritos }}</span>
                    </button>

                    {{-- COMENTARIOS --}}
                    <button class="ev-btn btn-comentarios" data-id="{{ $evento->id_evento }}">
                        <span class="ev-btn-icon">💬</span>
                        <span class="ev-btn-count">Ver</span>
                    </button>
                </div>

                {{-- Acciones solo para admin/jefe --}}
                @if(in_array($rolActual, ['administrador', 'jefe_departamento']))
                <div class="ev-card-actions">
                    <a href="{{ route('eventos.edit', $evento->id_evento) }}" class="ev-action edit">✏️ Editar</a>
                    <button class="ev-action del btn-borrar" data-id="{{ $evento->id_evento }}">🗑️ Eliminar</button>
                </div>
                @endif

            </div>
        </div>
        @endforeach
    </div>
</div>

</div>{{-- /ev-page --}}

{{-- ══════════════════════════════════════
     OVERLAY TARJETA EXPANDIDA
══════════════════════════════════════ --}}
<div class="expand-overlay" id="expandOverlay" style="position:fixed">
    <div class="ev-card-expanded" style="position:relative">
        <button class="expand-close-btn" id="expandClose">✕</button>
        <div id="expandImgWrap"></div>
        <div class="ev-card-expanded-body">
            <div class="ev-card-expanded-fecha" id="expandFecha"></div>
            <div class="ev-card-expanded-titulo" id="expandTitulo"></div>
            <div class="ev-card-expanded-desc"   id="expandDesc"></div>
        </div>
        <div class="expand-footer" id="expandFooter"></div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL COMENTARIOS
══════════════════════════════════════ --}}
<div class="modal fade" id="modalComentarios" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-title">
                    <span style="font-size:1.2rem">💬</span> Comentarios
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom">
                <div id="lista-comentarios" style="max-height:280px;overflow-y:auto;margin-bottom:14px"></div>
                <textarea id="nuevo-comentario" class="comment-textarea" rows="3" placeholder="Escribe un comentario..."></textarea>
                <button id="btn-guardar-comentario" class="btn-send mt-2">Enviar comentario</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL GRÁFICA GRANDE
══════════════════════════════════════ --}}
<div class="modal fade modal-grande" id="modalImagenGrande" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body" style="position:relative">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <div id="contenedor-imagen-grande" class="text-center"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let eventoActual = null;
const token = '{{ csrf_token() }}';
const usuarioActualId = {{ $uidActual ?? 'null' }};

// ── Datos del controlador ──
const eventosPorFecha  = @json($eventosPorFecha);
const eventos          = @json($eventos);
eventos.sort((a, b) => b.id_evento - a.id_evento);
const meses            = @json($meses);
const cantidadesMes    = @json($cantidadesMes);
const diasSemana       = @json($diasSemana);
const cantidadesSemana = @json($cantidadesSemana);
const dias             = @json($dias);
const cantidadesDia    = @json($cantidadesDia);
const topLike          = @json($topLike);
const topFav           = @json($topFav);

// ════════════════════════════════
// GRÁFICAS
// ════════════════════════════════
const paleta = ['#376073','#27ae60','#2980b9','#8b5cf6','#f39c12','#e74c3c','#14b8a6','#f97316'];

new Chart(document.getElementById('chartAnual'), {
    type:'line',
    data:{ labels:meses, datasets:[{ label:'Eventos', data:cantidadesMes, borderColor:'#376073', backgroundColor:'rgba(55,96,115,.12)', borderWidth:2.5, pointRadius:4, pointBackgroundColor:'#376073', fill:true, tension:.4 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
});
new Chart(document.getElementById('chartMensual'), {
    type:'bar',
    data:{ labels:diasSemana, datasets:[{ label:'Eventos', data:cantidadesSemana, backgroundColor:paleta, borderRadius:8, borderSkipped:false }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
});
new Chart(document.getElementById('chartSemanal'), {
    type:'line',
    data:{ labels:dias, datasets:[{ label:'Eventos', data:cantidadesDia, borderColor:'#27ae60', backgroundColor:'rgba(39,174,96,.1)', borderWidth:2.5, pointRadius:4, fill:true, tension:.4 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
});
new Chart(document.getElementById('chartTop'), {
    type:'bar',
    data:{
        labels:[ topLike?.titulo||'N/A', topFav?.titulo||'N/A' ],
        datasets:[{ label:'Interacciones', data:[ topLike?.likes||0, topFav?.favoritos||0 ], backgroundColor:['#376073','#f39c12'], borderRadius:8, borderSkipped:false }]
    },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
});

// Click en gráfica → modal grande
document.querySelectorAll('.chart-card').forEach(card => {
    card.addEventListener('click', function() {
        const canvas = this.querySelector('canvas');
        const nombre = this.getAttribute('data-nombre') + ' — Gráfica';
        if (!canvas) return;
        const wrap = document.createElement('div');
        wrap.className = 'grafica-grande-wrap';
        const h = document.createElement('h3'); h.textContent = nombre;
        const tmp = document.createElement('canvas');
        tmp.width = canvas.width * 2; tmp.height = canvas.height * 2;
        const ctx = tmp.getContext('2d');
        ctx.scale(2,2); ctx.fillStyle='#fff';
        ctx.fillRect(0,0,tmp.width,tmp.height);
        ctx.drawImage(canvas,0,0);
        const img = document.createElement('img');
        img.src = tmp.toDataURL('image/png',1.0);
        img.style.cssText = 'max-width:100%;height:auto;border-radius:8px';
        wrap.append(h, img);
        const contenedor = document.getElementById('contenedor-imagen-grande');
        contenedor.innerHTML = '';
        contenedor.appendChild(wrap);
        new bootstrap.Modal(document.getElementById('modalImagenGrande')).show();
    });
});

// ════════════════════════════════
// CALENDARIO
// ════════════════════════════════
let currentDate = new Date();
currentDate.setDate(1);

const today = new Date();
const todayY = today.getFullYear();
const todayM = today.getMonth();
const todayD = today.getDate();

function renderCalendar() {
    const body  = document.getElementById('calendar-body');
    const title = document.getElementById('current-month');
    body.innerHTML = '';
    const months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    title.textContent = months[currentDate.getMonth()] + ' ' + currentDate.getFullYear();

    const firstDay    = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
    const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
    let date = 1;

    for (let i = 0; i < 6; i++) {
        let row = document.createElement('tr');
        for (let j = 0; j < 7; j++) {
            let cell = document.createElement('td');
            if (i === 0 && j < firstDay || date > daysInMonth) {
                cell.innerHTML = '';
            } else {
                let div = document.createElement('div');
                div.className = 'cal-day';
                div.textContent = date;

                // Marcar hoy
                if (currentDate.getFullYear() === todayY &&
                    currentDate.getMonth() === todayM &&
                    date === todayD) {
                    div.classList.add('today');
                }

                let month  = String(currentDate.getMonth() + 1).padStart(2,'0');
                let dayStr = String(date).padStart(2,'0');
                let key    = `${currentDate.getFullYear()}-${month}-${dayStr}`;

                if (eventosPorFecha[key] && eventosPorFecha[key].length > 0) {
                    div.classList.add('has-event');
                }

                const capturedDate = date;
                div.onclick = () => selectDate(div, capturedDate);
                cell.appendChild(div);
                date++;
            }
            row.appendChild(cell);
        }
        body.appendChild(row);
        if (date > daysInMonth) break;
    }
}

function selectDate(el, day) {
    document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');

    let month  = String(currentDate.getMonth() + 1).padStart(2,'0');
    let dayStr = String(day).padStart(2,'0');
    let key    = `${currentDate.getFullYear()}-${month}-${dayStr}`;

    const fechaBonita = new Date(currentDate.getFullYear(), currentDate.getMonth(), day)
        .toLocaleDateString('es-MX', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    document.getElementById('selected-date').innerText = ' — ' + fechaBonita;

    let lista = eventosPorFecha[key] || [];
    const MAX_VISIBLE = 3;
    let html = '';

    if (!lista.length) {
        html = '<div class="ev-day-empty">No hay eventos este día</div>';
    } else {
        lista.slice(0, MAX_VISIBLE).forEach(e => {
            const hora = e.fecha_evento ? e.fecha_evento.substring(11,16) : '';
            html += `<div class="ev-day-card">
                        <strong>${e.titulo}</strong>
                        ${hora ? `<p>🕐 ${hora} hrs</p>` : ''}
                        <p>${e.descripcion ?? ''}</p>
                     </div>`;
        });
        if (lista.length > MAX_VISIBLE) {
            html += `<button onclick="verMasEventos(this)" data-key="${key}" data-offset="${MAX_VISIBLE}"
                             style="width:100%;padding:8px;background:none;border:1.5px dashed var(--border);
                                    border-radius:9px;cursor:pointer;font-size:.78rem;color:var(--text-muted);
                                    margin-top:4px">
                        Ver ${lista.length - MAX_VISIBLE} evento(s) más ▼
                     </button>`;
        }
    }
    document.getElementById('event-list').innerHTML = html;
}

function verMasEventos(btn) {
    const key    = btn.dataset.key;
    const offset = parseInt(btn.dataset.offset);
    const lista  = eventosPorFecha[key] || [];
    const STEP   = 3;
    let html     = '';

    lista.slice(offset, offset + STEP).forEach(e => {
        const hora = e.fecha_evento ? e.fecha_evento.substring(11,16) : '';
        html += `<div class="ev-day-card">
                    <strong>${e.titulo}</strong>
                    ${hora ? `<p>🕐 ${hora} hrs</p>` : ''}
                    <p>${e.descripcion ?? ''}</p>
                 </div>`;
    });

    btn.insertAdjacentHTML('beforebegin', html);

    const newOffset = offset + STEP;
    if (newOffset >= lista.length) {
        btn.remove();
    } else {
        btn.dataset.offset = newOffset;
        btn.textContent    = `Ver ${lista.length - newOffset} evento(s) más ▼`;
    }
}

document.querySelector('.prev-month').onclick = () => { currentDate.setMonth(currentDate.getMonth()-1); renderCalendar(); };
document.querySelector('.next-month').onclick = () => { currentDate.setMonth(currentDate.getMonth()+1); renderCalendar(); };
renderCalendar();

// ════════════════════════════════
// TARJETA EXPANDIDA
// ════════════════════════════════
const expandOverlay = document.getElementById('expandOverlay');
const expandClose   = document.getElementById('expandClose');

function abrirTarjeta(evento) {
    const fecha = evento.fecha_evento
        ? new Date(evento.fecha_evento).toLocaleDateString('es-MX',
            { weekday:'long', year:'numeric', month:'long', day:'numeric' })
        : '';

    const imgWrap = document.getElementById('expandImgWrap');
    if (evento.poster) {
        imgWrap.innerHTML = `<img src="/storage/${evento.poster}" class="ev-card-expanded-img" alt="${evento.titulo}">`;
    } else {
        imgWrap.innerHTML = `<div class="ev-card-expanded-noimg">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" opacity=".3">
                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
            </svg></div>`;
    }

    document.getElementById('expandFecha').textContent  = fecha;
    document.getElementById('expandTitulo').textContent = evento.titulo;
    document.getElementById('expandDesc').textContent   = evento.descripcion ?? '';

    // Likes con estado
    const yaLiked = evento.likes_usuarios
        ? (Array.isArray(evento.likes_usuarios)
            ? evento.likes_usuarios
            : JSON.parse(evento.likes_usuarios))
          .includes(usuarioActualId)
        : false;

    document.getElementById('expandFooter').innerHTML = `
        <button class="ev-btn btn-like ${yaLiked ? 'liked' : ''}" data-id="${evento.id_evento}">
            <span class="ev-btn-icon">👍</span>
            <span class="ev-btn-count" id="like-exp-${evento.id_evento}">${evento.likes}</span>
        </button>
        <button class="ev-btn btn-fav" data-id="${evento.id_evento}">
            <span class="ev-btn-icon">⭐</span>
            <span class="ev-btn-count" id="fav-exp-${evento.id_evento}">${evento.favoritos}</span>
        </button>
        <button class="ev-btn btn-comentarios" data-id="${evento.id_evento}">
            <span class="ev-btn-icon">💬</span>
            <span class="ev-btn-count">Ver</span>
        </button>`;

    expandOverlay.classList.add('visible');
    document.body.style.overflow = 'hidden';
}

function cerrarTarjeta() {
    expandOverlay.classList.remove('visible');
    document.body.style.overflow = '';
}

expandClose.addEventListener('click', cerrarTarjeta);
expandOverlay.addEventListener('click', e => { if (e.target === expandOverlay) cerrarTarjeta(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarTarjeta(); });

// Click en tarjeta la abre expandida
document.querySelectorAll('.ev-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (e.target.closest('.ev-btn') || e.target.closest('.ev-action')) return;
        const wrapper = this.closest('[data-id]');
        if (!wrapper) return;
        const id = parseInt(wrapper.dataset.id);
        const ev = eventos.find(e => e.id_evento == id);
        if (ev) abrirTarjeta(ev);
    });
});

// ════════════════════════════════
// INTERACCIONES
// ════════════════════════════════
document.addEventListener('click', async function(e) {

    // LIKE (toggle, 1 por usuario)
    const like = e.target.closest('.btn-like');
    if (like) {
        const id  = like.dataset.id;
        const res = await fetch(`/evento/like/${id}`, {
            method:'POST', headers:{'X-CSRF-TOKEN': token}
        });
        const data = await res.json();
        document.querySelectorAll(`#like-${id}, #like-exp-${id}`)
            .forEach(el => el.innerText = data.likes);
        document.querySelectorAll(`.btn-like[data-id="${id}"]`)
            .forEach(btn => btn.classList.toggle('liked', data.liked));
        // Actualizar en array local
        const ev = eventos.find(e => e.id_evento == id);
        if (ev) {
            ev.likes = data.likes;
            if (data.liked) {
                if (!ev.likes_usuarios) ev.likes_usuarios = [];
                if (!ev.likes_usuarios.includes(usuarioActualId)) ev.likes_usuarios.push(usuarioActualId);
            } else {
                ev.likes_usuarios = (ev.likes_usuarios || []).filter(u => u != usuarioActualId);
            }
        }
        return;
    }

    // FAVORITO
    const fav = e.target.closest('.btn-fav');
    if (fav) {
        const id = fav.dataset.id;
        await fetch(`/evento/favorito/${id}`, { method:'POST', headers:{'X-CSRF-TOKEN': token} });
        document.querySelectorAll(`#fav-${id}, #fav-exp-${id}`)
            .forEach(el => el.innerText = parseInt(el.innerText) + 1);
        return;
    }

    // COMENTARIOS
    const comentBtn = e.target.closest('.btn-comentarios');
    if (comentBtn) {
        eventoActual = comentBtn.dataset.id;
        await cargarComentarios(eventoActual);
        new bootstrap.Modal(document.getElementById('modalComentarios')).show();
        return;
    }

    // BORRAR (solo admin/jefe — botón solo aparece para ellos en blade)
    const borrar = e.target.closest('.btn-borrar');
    if (borrar) {
        const id = borrar.dataset.id;
        Swal.fire({
            title: '¿Eliminar evento?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#376073',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
        }).then(async r => {
            if (!r.isConfirmed) return;
            await fetch(`/eventos/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN': token} });
            document.querySelector(`[data-id="${id}"]`).remove();
            cerrarTarjeta();
            Swal.fire({ icon:'success', title:'Eliminado', timer:1500, showConfirmButton:false });
        });
        return;
    }
});

// Guardar comentario
document.getElementById('btn-guardar-comentario').addEventListener('click', async function() {
    const texto = document.getElementById('nuevo-comentario').value;
    if (!texto.trim()) return;
    await fetch(`/evento/comentar/${eventoActual}`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token},
        body: JSON.stringify({ comentario: texto })
    });
    document.getElementById('nuevo-comentario').value = '';
    await cargarComentarios(eventoActual);
});

async function cargarComentarios(id) {
    const res  = await fetch(`/evento/comentarios/${id}`);
    const data = await res.json();
    const lista = document.getElementById('lista-comentarios');
    if (!data.length) {
        lista.innerHTML = '<p style="color:var(--text-faint);text-align:center;font-size:.8rem">Sin comentarios aún</p>';
        return;
    }
    lista.innerHTML = data.map(c => `
        <div class="comment-item">
            ${c.comentario}
            <small>${c.created_at}</small>
        </div>`).join('');
}
</script>
@endsection