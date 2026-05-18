@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
:root {
    --teal: #376073; --teal-dark: #2c4f60; --teal-light: #5a8fa3;
    --text: #1e3040; --text-muted: #5a7a8a; --text-faint: #9ab3c0;
    --surface: #fff; --bg: #eef4f8; --border: rgba(55,96,115,.13);
    --radius: 14px;
    --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
    --shadow-md: 0 6px 24px rgba(0,0,0,.10);
    --ease: cubic-bezier(.34,1.56,.64,1);
}

/* ── PAGE ── */
.sol-page { display: flex; flex-direction: column; gap: 20px; padding: 4px 0 40px; }

/* ── HERO ── */
.sol-hero {
    background: linear-gradient(135deg, #0f2535 0%, var(--teal-dark) 40%, var(--teal) 75%, var(--teal-light) 100%);
    border-radius: var(--radius); padding: 24px 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    box-shadow: var(--shadow-md); position: relative; overflow: hidden;
}
.sol-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse at 0% 100%, rgba(74,156,109,.15) 0%, transparent 55%),
                radial-gradient(ellipse at 100% 0%, rgba(90,143,163,.12) 0%, transparent 50%);
    pointer-events: none;
}
.sol-hero-left { position: relative; z-index: 1; }
.sol-hero-title { font-family: 'Instrument Serif', serif; font-size: 1.65rem; color: #fff; line-height: 1.1; }
.sol-hero-sub   { font-size: .75rem; color: rgba(255,255,255,.6); margin-top: 4px; }
.sol-hero-badge {
    position: relative; z-index: 1;
    background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
    backdrop-filter: blur(6px); border-radius: 50px;
    padding: 10px 22px; text-align: center;
}
.sol-hero-badge-num  { font-family: 'Instrument Serif', serif; font-size: 2rem; color: #fff; line-height: 1; }
.sol-hero-badge-text { font-size: .68rem; color: rgba(255,255,255,.65); text-transform: uppercase; letter-spacing: .5px; }

/* ── FILTROS ── */
.sol-filters {
    display: flex; gap: 8px; flex-wrap: wrap;
}
.sol-filter-btn {
    padding: 7px 18px; border-radius: 50px; font-size: .78rem; font-weight: 700;
    border: 1.5px solid var(--border); background: var(--surface); color: var(--text-muted);
    cursor: pointer; transition: all .2s var(--ease);
}
.sol-filter-btn:hover { border-color: var(--teal); color: var(--teal); }
.sol-filter-btn.active { background: var(--teal); color: #fff; border-color: var(--teal); }

/* ── TARJETAS SOLICITUD ── */
.sol-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform .25s var(--ease), box-shadow .25s;
}
.sol-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

.sol-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
    gap: 12px; flex-wrap: wrap;
}
.sol-card-titulo {
    font-family: 'Instrument Serif', serif;
    font-size: 1.1rem; color: var(--text); font-weight: 600;
    display: flex; align-items: center; gap: 10px;
}
.sol-card-titulo-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--teal-dark), var(--teal));
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sol-card-titulo-icon svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 2; }

/* Badges de estado */
.sol-badge {
    padding: 5px 14px; border-radius: 50px; font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px; white-space: nowrap;
}
.sol-badge.pendiente { background: rgba(243,156,18,.12); color: #d68910; border: 1px solid rgba(243,156,18,.3); }
.sol-badge.aprobado  { background: rgba(39,174,96,.12);  color: #1e8449; border: 1px solid rgba(39,174,96,.3); }
.sol-badge.rechazado { background: rgba(231,76,60,.12);  color: #cb4335; border: 1px solid rgba(231,76,60,.3); }

/* Cuerpo */
.sol-card-body { padding: 16px 20px; display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: start; }
@media(max-width: 600px) { .sol-card-body { grid-template-columns: 1fr; } }

.sol-card-info { display: flex; flex-direction: column; gap: 10px; }

.sol-info-row { display: flex; align-items: flex-start; gap: 10px; font-size: .83rem; color: var(--text-muted); }
.sol-info-row svg { width: 15px; height: 15px; stroke: var(--teal-light); fill: none; stroke-width: 2; flex-shrink: 0; margin-top: 2px; }
.sol-info-row strong { color: var(--text); font-weight: 600; }

.sol-desc {
    background: #f5f9fc; border: 1px solid var(--border);
    border-radius: 9px; padding: 10px 14px;
    font-size: .8rem; color: var(--text-muted); line-height: 1.6;
    font-style: italic;
}

/* Poster preview */
.sol-poster-wrap {
    width: 110px; height: 110px; border-radius: 10px; overflow: hidden;
    border: 1px solid var(--border); flex-shrink: 0;
    background: var(--bg); display: flex; align-items: center; justify-content: center;
}
.sol-poster-wrap img { width: 100%; height: 100%; object-fit: cover; }
.sol-poster-wrap svg { width: 32px; height: 32px; stroke: var(--text-faint); fill: none; stroke-width: 1.5; opacity: .5; }

/* Footer acciones */
.sol-card-footer {
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap;
    background: #f9fbfc;
}
.sol-card-footer-meta { font-size: .73rem; color: var(--text-faint); display: flex; align-items: center; gap: 6px; }
.sol-card-footer-meta svg { width: 13px; height: 13px; stroke: var(--text-faint); fill: none; stroke-width: 2; }

.sol-actions { display: flex; gap: 8px; }
.sol-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 50px; font-size: .78rem; font-weight: 700;
    border: none; cursor: pointer; transition: opacity .2s, transform .15s var(--ease);
    white-space: nowrap;
}
.sol-btn:hover { opacity: .88; transform: translateY(-1px); }
.sol-btn-aprobar  { background: linear-gradient(135deg, #1e8449, #27ae60); color: #fff; box-shadow: 0 3px 10px rgba(39,174,96,.3); }
.sol-btn-rechazar { background: linear-gradient(135deg, #cb4335, #e74c3c); color: #fff; box-shadow: 0 3px 10px rgba(231,76,60,.3); }

/* Estado no-pendiente */
.sol-estado-label {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .78rem; font-weight: 700; color: var(--text-faint);
}

/* ── EMPTY STATE ── */
.sol-empty {
    text-align: center; padding: 60px 20px;
    background: var(--surface); border-radius: var(--radius);
    border: 1px solid var(--border);
}
.sol-empty-icon { font-size: 3rem; margin-bottom: 12px; }
.sol-empty-title { font-family: 'Instrument Serif', serif; font-size: 1.3rem; color: var(--text); margin-bottom: 6px; }
.sol-empty-sub   { font-size: .82rem; color: var(--text-faint); }

/* ── SECTION LABEL ── */
.section-label {
    display: flex; align-items: center; gap: 10px;
    font-family: 'Instrument Serif', serif; font-size: 1.1rem; color: var(--teal); margin-bottom: 4px;
}
.section-label svg { width: 17px; height: 17px; stroke: var(--teal); fill: none; stroke-width: 2; }
.section-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(55,96,115,.2), transparent); }
</style>
@endsection

@section('content')
@php
    $pendientes = $solicitudes->where('estado', 'pendiente')->count();
    $aprobadas  = $solicitudes->where('estado', 'aprobado')->count();
    $rechazadas = $solicitudes->where('estado', 'rechazado')->count();
@endphp

<div class="sol-page">

{{-- ── HERO ── --}}
<div class="sol-hero">
    <div class="sol-hero-left">
        <div class="sol-hero-title">Solicitudes de Eventos</div>
        <div class="sol-hero-sub">Revisa y gestiona las peticiones enviadas por docentes y estudiantes</div>
    </div>
    <div class="sol-hero-badge">
        <div class="sol-hero-badge-num">{{ $pendientes }}</div>
        <div class="sol-hero-badge-text">Pendiente{{ $pendientes !== 1 ? 's' : '' }}</div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<div class="sol-filters">
    <button class="sol-filter-btn active" onclick="filtrar('todos', this)">
        Todas <span style="opacity:.6">({{ $solicitudes->count() }})</span>
    </button>
    <button class="sol-filter-btn" onclick="filtrar('pendiente', this)">
        ⏳ Pendientes <span style="opacity:.6">({{ $pendientes }})</span>
    </button>
    <button class="sol-filter-btn" onclick="filtrar('aprobado', this)">
        ✅ Aprobadas <span style="opacity:.6">({{ $aprobadas }})</span>
    </button>
    <button class="sol-filter-btn" onclick="filtrar('rechazado', this)">
        ❌ Rechazadas <span style="opacity:.6">({{ $rechazadas }})</span>
    </button>
</div>

{{-- ── LISTA ── --}}
<div>
    <div class="section-label">
        <svg viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
        </svg>
        Solicitudes recibidas
    </div>

    @if($solicitudes->isEmpty())
        <div class="sol-empty">
            <div class="sol-empty-icon">📭</div>
            <div class="sol-empty-title">Sin solicitudes aún</div>
            <div class="sol-empty-sub">Cuando docentes o estudiantes envíen una petición de evento, aparecerá aquí.</div>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px" id="lista-solicitudes">
            @foreach($solicitudes as $sol)
            <div class="sol-card" data-estado="{{ $sol->estado }}">

                {{-- Header --}}
                <div class="sol-card-header">
                    <div class="sol-card-titulo">
                        <div class="sol-card-titulo-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        {{ $sol->titulo }}
                    </div>
                    <span class="sol-badge {{ $sol->estado }}">
                        @if($sol->estado === 'pendiente') ⏳
                        @elseif($sol->estado === 'aprobado') ✅
                        @else ❌
                        @endif
                        {{ ucfirst($sol->estado) }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="sol-card-body">
                    <div class="sol-card-info">

                        {{-- Solicitante --}}
                        <div class="sol-info-row">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            <span>
                                <strong>Solicitante:</strong>
                                {{ $sol->usuario->nombre ?? '' }}
                                {{ $sol->usuario->ape_paterno ?? '' }}
                                —
                                <span style="font-size:.75rem;color:var(--teal)">
                                    {{ $sol->usuario->rol ?? 'Sin rol' }}
                                </span>
                            </span>
                        </div>

                        {{-- Fecha propuesta --}}
                        <div class="sol-info-row">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>
                                <strong>Fecha propuesta:</strong>
                                {{ $sol->fechaevento
                                    ? \Carbon\Carbon::parse($sol->fechaevento)->format('d \d\e F Y, H:i') . ' hrs'
                                    : 'Sin fecha específica' }}
                            </span>
                        </div>

                        {{-- Descripción --}}
                        @if($sol->descripcion)
                        <div class="sol-desc">
                            "{{ $sol->descripcion }}"
                        </div>
                        @endif

                    </div>

                    {{-- Poster preview --}}
                    <div class="sol-poster-wrap">
                        @if($sol->poster)
                            <img src="{{ asset('storage/' . $sol->poster) }}" alt="Poster">
                        @else
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="sol-card-footer">
                    <div class="sol-card-footer-meta">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Enviada {{ $sol->created_at->diffForHumans() }}
                        &nbsp;·&nbsp;
                        {{ $sol->created_at->format('d M Y, H:i') }}
                    </div>

                    @if($sol->estado === 'pendiente')
                    <div class="sol-actions">
                        <form action="{{ route('solicitudes.rechazar', $sol->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="sol-btn sol-btn-rechazar">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Rechazar
                            </button>
                        </form>
                        <form action="{{ route('solicitudes.aprobar', $sol->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="sol-btn sol-btn-aprobar">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Aprobar y publicar
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="sol-estado-label">
                        @if($sol->estado === 'aprobado')
                            ✅ Aprobada y publicada
                        @else
                            ❌ Rechazada
                        @endif
                    </div>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>

</div>{{-- /sol-page --}}
@endsection

@section('scripts')
<script>
function filtrar(estado, btn) {
    // Actualizar botones activos
    document.querySelectorAll('.sol-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Filtrar tarjetas
    document.querySelectorAll('.sol-card').forEach(card => {
        if (estado === 'todos' || card.dataset.estado === estado) {
            card.style.display = '';
            card.style.animation = 'fadeIn .3s ease';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
<style>
@keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
</style>
@endsection