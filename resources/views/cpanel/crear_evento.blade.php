@extends('layouts.app')

@section('title', 'Crear Evento — Ubicatec')

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
    --ease-smooth: cubic-bezier(.16,1,.3,1);
}

/* ── PÁGINA ── */
.create-page { display: flex; flex-direction: column; gap: 20px; padding: 4px 0 48px; }

/* ── HERO ── */
.create-hero {
    background: linear-gradient(135deg, #0f2535 0%, #1a3d4f 35%, #376073 70%, #5a8fa3 100%);
    border-radius: var(--radius); padding: 22px 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    box-shadow: var(--shadow-md); position: relative; overflow: hidden;
}
.create-hero::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(ellipse at 0% 100%, rgba(55,96,115,.25) 0%, transparent 55%),
                radial-gradient(ellipse at 100% 0%, rgba(90,143,163,.15) 0%, transparent 50%);
}
.create-hero::after {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='20' fill='none' stroke='%23ffffff' stroke-width='0.3' opacity='0.06'/%3E%3C/svg%3E") repeat;
}
.create-hero-left   { position: relative; z-index: 1; }
.create-hero-title  { font-family: 'Instrument Serif', serif; font-size: 1.6rem; color: #fff; line-height: 1.1; }
.create-hero-sub    { font-size: .74rem; color: rgba(255,255,255,.55); margin-top: 4px; max-width: 360px; line-height: 1.5; }

/* Badge admin */
.hero-admin-badge {
    position: relative; z-index: 1;
    background: rgba(255,255,255,.14); border: 1.5px solid rgba(255,255,255,.28);
    backdrop-filter: blur(6px); border-radius: 50px;
    padding: 8px 18px; display: flex; align-items: center; gap: 8px;
    font-size: .73rem; color: rgba(255,255,255,.9); font-weight: 600; white-space: nowrap;
}
.hero-admin-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #4ecb71;
    box-shadow: 0 0 0 3px rgba(78,203,113,.3);
    animation: pulse-green 2s ease-in-out infinite;
}
@keyframes pulse-green {
    0%,100% { box-shadow: 0 0 0 3px rgba(78,203,113,.3); }
    50%      { box-shadow: 0 0 0 6px rgba(78,203,113,.12); }
}
.create-hero-steps { position: relative; z-index: 1; display: flex; gap: 6px; }
.hero-step {
    width: 32px; height: 32px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.3); background: rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; color: rgba(255,255,255,.5);
    transition: all .3s var(--ease); position: relative;
}
.hero-step.active { background: #fff; color: var(--teal-dark); border-color: #fff; box-shadow: 0 0 0 4px rgba(255,255,255,.2); }
.hero-step.done   { background: rgba(255,255,255,.25); color: #fff; border-color: rgba(255,255,255,.55); }
.hero-step::after {
    content: attr(data-label); position: absolute; bottom: -20px; left: 50%;
    transform: translateX(-50%); font-size: .6rem; color: rgba(255,255,255,.5); white-space: nowrap;
}
.hero-step.active::after { color: rgba(255,255,255,.9); }

/* ── LAYOUT ── */
.create-layout {
    display: grid; grid-template-columns: 1fr 410px;
    gap: 24px; align-items: start;
}
@media (max-width: 900px) {
    .create-layout { grid-template-columns: 1fr; }
    .preview-sticky { position: static !important; }
}

/* ── FORM CARD ── */
.form-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; }
.form-card-header {
    background: #f5f9fc; border-bottom: 1px solid var(--border);
    padding: 16px 24px; display: flex; align-items: center; gap: 12px;
}
.form-card-header-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, #1a3d4f, #376073);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.form-card-header-icon svg { width: 17px; height: 17px; stroke: #fff; fill: none; stroke-width: 2; }
.form-card-header-title { font-family: 'Instrument Serif', serif; font-size: 1.15rem; color: var(--text); }
.form-card-header-sub   { font-size: .72rem; color: var(--text-faint); margin-top: 1px; }
.form-card-body { padding: 24px; display: flex; flex-direction: column; gap: 18px; }

/* ── STEPS ── */
.form-step {
    display: block;
}
.step-progress { display: flex; gap: 6px; align-items: center; }
.step-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--border); transition: all .3s var(--ease); }
.step-dot.active { background: var(--teal); width: 22px; border-radius: 4px; }
.step-dot.done   { background: var(--teal-light); }

/* ── CAMPOS ── */
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: .72rem; font-weight: 700; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .6px;
    display: flex; align-items: center; gap: 6px;
}
.form-label svg { width: 12px; height: 12px; stroke: var(--teal-light); fill: none; stroke-width: 2; }
.required { color: #e74c3c; }
.form-input {
    width: 100%; padding: 11px 15px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-size: .87rem; color: var(--text); background: var(--surface);
    outline: none; transition: border-color .2s, box-shadow .2s;
    font-family: 'Inter', sans-serif;
}
.form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(55,96,115,.1); }
.form-input::placeholder { color: var(--text-faint); }
textarea.form-input { resize: none; line-height: 1.6; }

/* ── UPLOAD ── */
.upload-zone {
    border: 2px dashed rgba(55,96,115,.25); border-radius: 12px;
    padding: 28px 20px; text-align: center; cursor: pointer;
    transition: all .25s; background: #f7fbfd; position: relative;
}
.upload-zone:hover, .upload-zone.dragover { border-color: var(--teal); background: rgba(55,96,115,.04); }
.upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-icon  { font-size: 2.2rem; margin-bottom: 8px; }
.upload-title { font-size: .87rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
.upload-sub   { font-size: .73rem; color: var(--text-faint); }
.upload-preview { width: 100%; aspect-ratio: 16/9; border-radius: 10px; overflow: hidden; position: relative; background: #000; display: none; }
.upload-preview img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
.upload-preview-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.45);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; opacity: 0; transition: opacity .2s; cursor: pointer;
}
.upload-preview:hover .upload-preview-overlay { opacity: 1; }
.upload-preview-overlay span { color: #fff; font-size: .78rem; font-weight: 700; }
.upload-preview-overlay svg  { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 2; }
.img-hint {
    margin-top: 10px; padding: 10px 14px;
    background: rgba(55,96,115,.06); border: 1px solid rgba(55,96,115,.15);
    border-radius: 8px; font-size: .72rem; color: var(--text-muted);
    display: flex; align-items: flex-start; gap: 8px; line-height: 1.5;
}
.img-hint svg { width: 14px; height: 14px; stroke: var(--teal); fill: none; stroke-width: 2; flex-shrink: 0; margin-top: 1px; }

/* ── COLORES ── */
.color-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.color-pick { border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: border-color .2s; }
.color-pick:focus-within { border-color: var(--teal); }
.color-swatch { width: 36px; height: 36px; border-radius: 8px; border: 2px solid rgba(0,0,0,.08); overflow: hidden; position: relative; flex-shrink: 0; }
.color-swatch input[type="color"] { position: absolute; inset: -4px; width: calc(100% + 8px); height: calc(100% + 8px); border: none; padding: 0; cursor: pointer; opacity: 0; }
.color-swatch-preview { position: absolute; inset: 0; border-radius: 6px; pointer-events: none; }
.color-info label  { font-size: .7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; display: block; }
.color-hex { font-size: .82rem; font-weight: 600; color: var(--text); font-family: monospace; }

.borde-row { display: grid; grid-template-columns: 80px 1fr 80px; gap: 10px; align-items: end; }
.font-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.font-btn { padding: 10px; border: 1.5px solid var(--border); border-radius: 9px; background: var(--surface); cursor: pointer; text-align: center; transition: all .2s; font-size: .82rem; color: var(--text-muted); }
.font-btn:hover  { border-color: var(--teal); color: var(--teal); }
.font-btn.selected { border-color: var(--teal); background: rgba(55,96,115,.06); color: var(--teal); font-weight: 700; }
.size-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.pos-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }

/* ── NAVEGACIÓN ── */
.step-nav { display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--border); }
.btn-prev { padding: 10px 20px; border-radius: 50px; font-size: .8rem; font-weight: 700; border: 1.5px solid var(--border); background: var(--surface); color: var(--text-muted); cursor: pointer; transition: all .2s; display: flex; align-items: center; gap: 6px; }
.btn-prev:hover { border-color: var(--teal); color: var(--teal); }
.btn-next-step { padding: 10px 22px; border-radius: 50px; font-size: .8rem; font-weight: 700; border: none; cursor: pointer; background: linear-gradient(135deg, #1a3d4f, #376073); color: #fff; box-shadow: 0 4px 14px rgba(55,96,115,.3); transition: opacity .2s, transform .15s; display: flex; align-items: center; gap: 6px; }
.btn-next-step:hover { opacity: .88; transform: translateY(-1px); }

/* ── BOTÓN PUBLICAR ── */
.btn-publicar {
    padding: 13px 32px; border-radius: 50px; font-size: .88rem; font-weight: 700;
    border: none; cursor: pointer;
    background: linear-gradient(135deg, #1a3d4f, #376073);
    color: #fff; box-shadow: 0 4px 18px rgba(55,96,115,.35);
    transition: opacity .2s, transform .15s;
    display: flex; align-items: center; gap: 9px; letter-spacing: .2px;
}
.btn-publicar:hover { opacity: .88; transform: translateY(-2px); box-shadow: 0 6px 24px rgba(55,96,115,.45); }

/* ── PREVIEW ── */
.preview-sticky { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 16px; }
.preview-card-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; }
.preview-header { padding: 14px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; background: #f5f9fc; }
.preview-header-dot   { width: 8px; height: 8px; border-radius: 50%; background: var(--teal); }
.preview-header-title { font-size: .72rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
.preview-inner { padding: 16px; }
.ev-card-preview { border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md); transition: transform .3s; }
.ev-card-preview:hover { transform: translateY(-4px); }
.ev-card-preview-img-wrap { width: 100%; aspect-ratio: 16 / 9; overflow: hidden; position: relative; background: #1e2d38; }
.ev-card-preview-img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; transition: transform .4s; }
.ev-card-preview:hover .ev-card-preview-img { transform: scale(1.04); }
.ev-card-preview-placeholder { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: rgba(255,255,255,.3); font-size: .75rem; }
.ev-card-preview-placeholder svg { width: 36px; height: 36px; stroke: currentColor; fill: none; stroke-width: 1.5; }
.ev-card-preview-body { padding: 14px 16px 10px; }
.ev-card-preview-title { font-weight: 700; font-size: .97rem; line-height: 1.3; margin-bottom: 5px; }
.ev-card-preview-desc  { font-size: .78rem; line-height: 1.6; opacity: .75; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.ev-card-preview-fecha { margin-top: 8px; font-size: .72rem; opacity: .55; display: flex; align-items: center; gap: 5px; }
.ev-card-preview-fecha svg { width: 11px; height: 11px; stroke: currentColor; fill: none; stroke-width: 2; }
.ev-card-preview-footer { border-top: 1px solid rgba(0,0,0,.07); padding: 8px 16px; display: flex; gap: 14px; font-size: .7rem; opacity: .5; align-items: center; }
.ev-card-preview-actions { border-top: 1px solid rgba(0,0,0,.06); padding: 8px 16px; display: flex; }
.ev-card-preview-actions span { font-size: .68rem; color: var(--text-faint); }

/* Tip card */
.tip-card {
    background: rgba(55,96,115,.05); border: 1px solid rgba(55,96,115,.15);
    border-radius: 11px; padding: 14px 16px;
    font-size: .73rem; color: var(--text-muted); line-height: 1.6;
    display: flex; flex-direction: column; gap: 8px;
}
.tip-card-title { font-size: .72rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: .5px; display: flex; align-items: center; gap: 6px; }
.tip-card-title svg { width: 13px; height: 13px; stroke: var(--teal); fill: none; stroke-width: 2; }
.tip-item { display: flex; align-items: baseline; gap: 7px; }
.tip-item::before { content: '✓'; color: var(--teal); font-size: .75rem; flex-shrink: 0; }

/* ── RESUMEN ── */
.resumen-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.resumen-item { background: #f5f9fc; border: 1px solid var(--border); border-radius: 9px; padding: 12px 14px; }
.resumen-label { font-size: .68rem; font-weight: 700; color: var(--text-faint); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.resumen-val   { font-size: .85rem; color: var(--text); font-weight: 600; }
.resumen-item.full { grid-column: 1 / -1; }
.resumen-colores { display: flex; gap: 10px; align-items: center; }
.resumen-swatch { width: 28px; height: 28px; border-radius: 6px; border: 2px solid rgba(0,0,0,.08); flex-shrink: 0; }

/* ── SECTION LABEL ── */
.section-label { display: flex; align-items: center; gap: 10px; font-family: 'Instrument Serif', serif; font-size: 1.05rem; color: var(--teal); }
.section-label svg { width: 15px; height: 15px; stroke: var(--teal); fill: none; stroke-width: 2; }
.section-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(55,96,115,.2), transparent); }
</style>
@endsection

@section('content')
<div class="create-page">

    {{-- ── HERO ── --}}
    <div class="create-hero">
        <div class="create-hero-left">
            <div class="create-hero-title">Crear Nuevo Evento</div>
            <div class="create-hero-sub">
                Publica un evento directamente en la cartelera del campus universitario.
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;position:relative;z-index:1">
            <div class="hero-admin-badge">
                <div class="hero-admin-dot"></div>
                Publicación directa
            </div>
            <div class="create-hero-steps">
                <div class="hero-step active" id="hs-1" data-label="Datos">1</div>
                <div class="hero-step"        id="hs-2" data-label="Diseño">2</div>
                <div class="hero-step"        id="hs-3" data-label="Publicar">3</div>
            </div>
        </div>
    </div>

    {{-- ── LAYOUT ── --}}
    <div class="create-layout">

        {{-- ━━ FORMULARIO ━━ --}}
        <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data" id="form-crear">
            @csrf

            {{-- ════ PASO 1: Información ════ --}}
            <div class="form-card" id="step-1">
                <div class="form-card-header">
                    <div class="form-card-header-icon">
                        <svg viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <div class="form-card-header-title">Información del evento</div>
                        <div class="form-card-header-sub">Paso 1 de 3 — Datos generales</div>
                    </div>
                    <div class="step-progress" style="margin-left:auto">
                        <div class="step-dot active"></div>
                        <div class="step-dot"></div>
                        <div class="step-dot"></div>
                    </div>
                </div>
                <div class="form-card-body">

                    <div class="form-group">
                        <label class="form-label" for="titulo">
                            <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h8M4 18h8"/></svg>
                            Título del evento <span class="required">*</span>
                        </label>
                        <input type="text" name="titulo" id="titulo" class="form-input"
                               required placeholder="Ej. Conferencia de Inteligencia Artificial" maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="descripcion">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            Descripción
                        </label>
                        <textarea name="descripcion" id="descripcion" class="form-input" rows="4"
                                  placeholder="¿De qué trata el evento? ¿Quiénes participan? ¿Cuál es el objetivo?"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="fechaevento">
                            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Fecha y hora del evento <span class="required">*</span>
                        </label>
                        <input type="datetime-local" name="fechaevento" id="fechaevento" class="form-input" required>
                    </div>

                    <div class="step-nav">
                        <a href="{{ route('eventos.index') }}" class="btn-prev">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Cancelar
                        </a>
                        <button type="button" class="btn-next-step" onclick="irPaso(2)">
                            Siguiente
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ════ PASO 2: Diseño ════ --}}
            <div class="form-card hidden" id="step-2">
                <div class="form-card-header">
                    <div class="form-card-header-icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div>
                        <div class="form-card-header-title">Imagen y estilo visual</div>
                        <div class="form-card-header-sub">Paso 2 de 3 — Personaliza el cartel</div>
                    </div>
                    <div class="step-progress" style="margin-left:auto">
                        <div class="step-dot done"></div>
                        <div class="step-dot active"></div>
                        <div class="step-dot"></div>
                    </div>
                </div>
                <div class="form-card-body">

                    {{-- Upload poster --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            Imagen del cartel
                            <span style="color:var(--text-faint);font-size:.65rem;font-weight:400;text-transform:none;letter-spacing:0">(opcional · recomendado 1280×720 px)</span>
                        </label>
                        <div class="upload-zone" id="upload-zone">
                            <input type="file" name="poster" id="poster" accept="image/*">
                            <div class="upload-icon">🖼️</div>
                            <div class="upload-title">Arrastra o haz clic para subir</div>
                            <div class="upload-sub">PNG, JPG, WEBP · Máx 4 MB · Recomendado 16:9</div>
                        </div>
                        <div class="upload-preview" id="upload-preview">
                            <img src="" alt="Vista previa" id="upload-preview-img">
                            <div class="upload-preview-overlay" onclick="quitarImagen()">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                <span>Cambiar imagen</span>
                            </div>
                        </div>
                        <div class="img-hint">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            La imagen se muestra en proporción <strong>16:9</strong>. Imágenes horizontales quedan mejor.
                        </div>
                    </div>

                    {{-- Colores --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg viewBox="0 0 24 24"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>
                            Colores del cartel
                        </label>
                        <div class="color-grid">
                            <div class="color-pick">
                                <div class="color-swatch">
                                    <div class="color-swatch-preview" id="swatch-fondo" style="background:#ffffff"></div>
                                    <input type="color" name="colorfondo" id="colorfondo" value="#ffffff">
                                </div>
                                <div class="color-info">
                                    <label>Fondo</label>
                                    <div class="color-hex" id="hex-fondo">#ffffff</div>
                                </div>
                            </div>
                            <div class="color-pick">
                                <div class="color-swatch">
                                    <div class="color-swatch-preview" id="swatch-texto" style="background:#1e3040"></div>
                                    <input type="color" name="colortexto" id="colortexto" value="#1e3040">
                                </div>
                                <div class="color-info">
                                    <label>Texto</label>
                                    <div class="color-hex" id="hex-texto">#1e3040</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Borde --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                            Borde de la tarjeta
                        </label>
                        <div class="borde-row">
                            <div class="form-group" style="gap:4px">
                                <label class="form-label" style="font-size:.65rem">Grosor (px)</label>
                                <input type="number" name="grosor_borde" id="grosor_borde" class="form-input" value="3" min="0" max="10">
                            </div>
                            <div class="form-group" style="gap:4px">
                                <label class="form-label" style="font-size:.65rem">Estilo</label>
                                <select name="estilo_borde" id="estilo_borde" class="form-input">
                                    <option value="solid" selected>Sólido</option>
                                    <option value="dashed">Rayado</option>
                                    <option value="dotted">Punteado</option>
                                    <option value="double">Doble</option>
                                    <option value="none">Sin borde</option>
                                </select>
                            </div>
                            <div class="form-group" style="gap:4px">
                                <label class="form-label" style="font-size:.65rem">Color</label>
                                <div class="color-swatch" style="width:100%;border-radius:9px;height:44px">
                                    <div class="color-swatch-preview" id="swatch-borde" style="background:#376073;border-radius:8px"></div>
                                    <input type="color" name="color_borde" id="color_borde" value="#376073">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tipografía --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg viewBox="0 0 24 24"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
                            Tipografía
                        </label>
                        <div class="font-grid">
                            @foreach(['Inter' => 'Inter — Sans', 'Instrument Serif' => 'Serif', 'Georgia' => 'Georgia', 'Courier New' => 'Mono'] as $font => $label)
                            <button type="button" class="font-btn {{ $loop->first ? 'selected' : '' }}"
                                    onclick="seleccionarFuente('{{ $font }}', this)"
                                    style="font-family:'{{ $font }}'">{{ $label }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="fontfamily" id="fontfamily" value="Inter">
                    </div>

                    {{-- Tamaños --}}
                    <div class="form-group">
                        <label class="form-label">Tamaños de texto</label>
                        <div class="size-row">
                            <div class="form-group" style="gap:4px">
                                <label class="form-label" style="font-size:.65rem">Título (px)</label>
                                <input type="number" name="sizetitulo" id="sizetitulo" class="form-input" value="18" min="10" max="40">
                            </div>
                            <div class="form-group" style="gap:4px">
                                <label class="form-label" style="font-size:.65rem">Descripción (px)</label>
                                <input type="number" name="sizedesc" id="sizedesc" class="form-input" value="13" min="9" max="24">
                            </div>
                        </div>
                    </div>

                    {{-- Posición imagen --}}
                    <div class="form-group">
                        <label class="form-label">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Posición de imagen
                        </label>
                        <div class="pos-grid">
                            @foreach(['top' => '↑ Arriba', 'center' => '· Centro', 'bottom' => '↓ Abajo'] as $pos => $label)
                            <button type="button" class="font-btn {{ $pos === 'center' ? 'selected' : '' }}"
                                    onclick="seleccionarPos('posimg', '{{ $pos }}', this)"
                                    data-group="posimg">{{ $label }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="posimg" id="posimg" value="center">
                    </div>

                    <div class="step-nav">
                        <button type="button" class="btn-prev" onclick="irPaso(1)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                            Anterior
                        </button>
                        <button type="button" class="btn-next-step" onclick="irPaso(3)">
                            Revisar
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ════ PASO 3: Confirmar y PUBLICAR ════ --}}
            <div class="form-card hidden" id="step-3">
                <div class="form-card-header">
                    <div class="form-card-header-icon" style="background:linear-gradient(135deg,#27ae60,#2ecc71)">
                        <svg viewBox="0 0 24 24" stroke="#fff"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <div class="form-card-header-title">Confirmar y publicar</div>
                        <div class="form-card-header-sub">Paso 3 de 3 — El evento se publicará de inmediato</div>
                    </div>
                    <div class="step-progress" style="margin-left:auto">
                        <div class="step-dot done"></div>
                        <div class="step-dot done"></div>
                        <div class="step-dot active"></div>
                    </div>
                </div>
                <div class="form-card-body">

                    <div class="section-label" style="margin-bottom:4px">
                        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                        Resumen del evento
                    </div>

                    <div class="resumen-grid">
                        <div class="resumen-item full">
                            <div class="resumen-label">Título</div>
                            <div class="resumen-val" id="res-titulo">—</div>
                        </div>
                        <div class="resumen-item">
                            <div class="resumen-label">Fecha</div>
                            <div class="resumen-val" id="res-fecha">—</div>
                        </div>
                        <div class="resumen-item">
                            <div class="resumen-label">Tipografía</div>
                            <div class="resumen-val" id="res-font">Inter</div>
                        </div>
                        <div class="resumen-item full">
                            <div class="resumen-label">Descripción</div>
                            <div class="resumen-val" id="res-desc" style="font-weight:400;font-size:.82rem;opacity:.8">—</div>
                        </div>
                        <div class="resumen-item full">
                            <div class="resumen-label">Colores</div>
                            <div class="resumen-colores">
                                <div class="resumen-swatch" id="res-swatch-fondo" style="background:#fff"></div>
                                <span style="font-size:.72rem;color:var(--text-muted)" id="res-hex-fondo">#ffffff</span>
                                <div class="resumen-swatch" id="res-swatch-texto" style="background:#1e3040;margin-left:8px"></div>
                                <span style="font-size:.72rem;color:var(--text-muted)" id="res-hex-texto">#1e3040</span>
                            </div>
                        </div>
                    </div>

                    <div class="img-hint" style="background:rgba(39,174,96,.07);border-color:rgba(39,174,96,.2)">
                        <svg viewBox="0 0 24 24" style="stroke:#27ae60"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        El evento se <strong>publicará inmediatamente</strong> en la cartelera del campus.
                    </div>

                    <div class="step-nav">
                        <button type="button" class="btn-prev" onclick="irPaso(2)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                            Anterior
                        </button>
                        <button type="submit" class="btn-publicar">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Publicar evento
                        </button>
                    </div>
                </div>
            </div>

        </form>

        {{-- ━━ PREVIEW ━━ --}}
        <div class="preview-sticky">
            <div class="preview-card-wrap">
                <div class="preview-header">
                    <div class="preview-header-dot"></div>
                    <div class="preview-header-title">Vista previa del cartel</div>
                </div>
                <div class="preview-inner">
                    <div class="ev-card-preview" id="preview-card" style="background:#fff;color:#1e3040;border:3px solid #376073">
                        <div class="ev-card-preview-img-wrap">
                            <img src="" alt="" class="ev-card-preview-img" id="preview-img" style="display:none">
                            <div class="ev-card-preview-placeholder" id="preview-placeholder">
                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span>Imagen del evento</span>
                                <span style="font-size:.65rem;opacity:.5">16 : 9</span>
                            </div>
                        </div>
                        <div class="ev-card-preview-body">
                            <div class="ev-card-preview-title" id="preview-titulo">Título del evento</div>
                            <div class="ev-card-preview-desc"  id="preview-desc">Descripción del evento...</div>
                            <div class="ev-card-preview-fecha" id="preview-fecha">
                                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Sin fecha
                            </div>
                        </div>
                        <div class="ev-card-preview-footer">
                            <span>👍 0</span>
                            <span>⭐ 0</span>
                            <span>💬 0</span>
                        </div>
                        <div class="ev-card-preview-actions">
                            <span>Editar · Eliminar</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tip-card">
                <div class="tip-card-title">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Consejos para un buen cartel
                </div>
                <div class="tip-item">Usa imágenes en formato 16:9 (1280×720 px)</div>
                <div class="tip-item">Títulos cortos, máx. 8 palabras</div>
                <div class="tip-item">Contrasta bien el color de fondo y texto</div>
                <div class="tip-item">Incluye la fecha exacta del evento</div>
            </div>
        </div>

    </div>{{-- /create-layout --}}

</div>
@endsection

@section('scripts')
<script>
const heroSteps = [document.getElementById('hs-1'), document.getElementById('hs-2'), document.getElementById('hs-3')];

function irPaso(n) {
    [1, 2, 3].forEach(i => {
        const el = document.getElementById('step-' + i);
        if (!el) return;
        if (i === n) {
            el.classList.remove('hidden');   // ← quita la clase en vez de usar style
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            requestAnimationFrame(() => {
                el.style.transition = 'opacity .25s ease, transform .25s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });
        } else {
            el.classList.add('hidden');      // ← agrega la clase en vez de usar style
        }
    });

    // Actualizar pasos del hero (el resto de tu código queda igual)
    heroSteps.forEach((s, i) => {
        s.classList.remove('active', 'done');
        if (i < n - 1) s.classList.add('done');
        else if (i === n - 1) s.classList.add('active');
    });

    // Actualizar resumen si vas al paso 3
    if (n === 3) actualizarResumen();

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Preview en vivo ──
const previewCard  = document.getElementById('preview-card');
const previewTit   = document.getElementById('preview-titulo');
const previewDesc  = document.getElementById('preview-desc');
const previewFecha = document.getElementById('preview-fecha');
const previewImg   = document.getElementById('preview-img');
const previewPh    = document.getElementById('preview-placeholder');

document.getElementById('titulo').addEventListener('input', e =>
    previewTit.textContent = e.target.value || 'Título del evento');

document.getElementById('descripcion').addEventListener('input', e =>
    previewDesc.textContent = e.target.value || 'Descripción del evento...');

document.getElementById('fechaevento').addEventListener('change', e => {
    if (e.target.value) {
        const d = new Date(e.target.value);
        previewFecha.innerHTML = `
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            ${d.toLocaleDateString('es-MX', { weekday:'long', year:'numeric', month:'long', day:'numeric' })}
        `;
    }
});

function bindColor(inputId, swatchId, hexId) {
    const input  = document.getElementById(inputId);
    const swatch = document.getElementById(swatchId);
    const hex    = hexId ? document.getElementById(hexId) : null;
    input.addEventListener('input', e => {
        swatch.style.background = e.target.value;
        if (hex) hex.textContent = e.target.value;
        if (inputId === 'colorfondo') previewCard.style.background = e.target.value;
        if (inputId === 'colortexto') previewCard.style.color = e.target.value;
        if (inputId === 'color_borde') actualizarBordePreview();
    });
}
bindColor('colorfondo', 'swatch-fondo', 'hex-fondo');
bindColor('colortexto', 'swatch-texto', 'hex-texto');
bindColor('color_borde', 'swatch-borde', null);

function actualizarBordePreview() {
    const g = document.getElementById('grosor_borde').value || 3;
    const e = document.getElementById('estilo_borde').value || 'solid';
    const c = document.getElementById('color_borde').value  || '#376073';
    previewCard.style.border = `${g}px ${e} ${c}`;
}
document.getElementById('grosor_borde').addEventListener('input',  actualizarBordePreview);
document.getElementById('estilo_borde').addEventListener('change', actualizarBordePreview);

document.getElementById('sizetitulo').addEventListener('input', e =>
    previewTit.style.fontSize = (e.target.value || 18) + 'px');
document.getElementById('sizedesc').addEventListener('input', e =>
    previewDesc.style.fontSize = (e.target.value || 13) + 'px');

function seleccionarFuente(font, btn) {
    document.querySelectorAll('.font-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('fontfamily').value = font;
    previewCard.style.fontFamily = font;
}

function seleccionarPos(campo, valor, btn) {
    document.querySelectorAll(`[data-group="${campo}"]`).forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById(campo).value = valor;
    const imgEl = document.getElementById('preview-img');
    if (campo === 'posimg') imgEl.style.objectPosition = valor;
}

// ── Upload ──
const posterInput   = document.getElementById('poster');
const uploadZone    = document.getElementById('upload-zone');
const uploadPreview = document.getElementById('upload-preview');
const uploadPrevImg = document.getElementById('upload-preview-img');

posterInput.addEventListener('change', mostrarPreviewImagen);
uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
uploadZone.addEventListener('drop', e => {
    e.preventDefault(); uploadZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const dt = new DataTransfer(); dt.items.add(file);
    posterInput.files = dt.files;
    mostrarPreviewImagen();
});

function mostrarPreviewImagen() {
    const file = posterInput.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        uploadPrevImg.src          = ev.target.result;
        uploadZone.style.display   = 'none';
        uploadPreview.style.display = 'block';
        previewImg.src             = ev.target.result;
        previewImg.style.display   = 'block';
        previewPh.style.display    = 'none';
    };
    reader.readAsDataURL(file);
}

function quitarImagen() {
    posterInput.value              = '';
    uploadZone.style.display       = 'block';
    uploadPreview.style.display    = 'none';
    previewImg.style.display       = 'none';
    previewPh.style.display        = 'flex';
}

// ── Resumen paso 3 ──
function actualizarResumen() {
    document.getElementById('res-titulo').textContent =
        document.getElementById('titulo').value || '—';
    const fecha = document.getElementById('fechaevento').value;
    document.getElementById('res-fecha').textContent = fecha
        ? new Date(fecha).toLocaleDateString('es-MX', { weekday:'long', year:'numeric', month:'long', day:'numeric' })
        : '—';
    document.getElementById('res-desc').textContent =
        document.getElementById('descripcion').value || '—';
    document.getElementById('res-font').textContent =
        document.getElementById('fontfamily').value;

    const cf = document.getElementById('colorfondo').value;
    const ct = document.getElementById('colortexto').value;
    document.getElementById('res-swatch-fondo').style.background = cf;
    document.getElementById('res-swatch-texto').style.background = ct;
    document.getElementById('res-hex-fondo').textContent = cf;
    document.getElementById('res-hex-texto').textContent = ct;
}

// ── Validación antes de submit ──
document.getElementById('form-crear').addEventListener('submit', function(e) {
    const titulo = document.getElementById('titulo').value.trim();
    const fecha  = document.getElementById('fechaevento').value;
    if (!titulo) { e.preventDefault(); alert('El título del evento es obligatorio.'); irPaso(1); return; }
    if (!fecha)  { e.preventDefault(); alert('La fecha del evento es obligatoria.'); irPaso(1); return; }
});
</script>
@endsection