@extends('layouts.app')

@section('title', 'Mapa Campus - Ubicatec')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --teal:#376073; --teal-dark:#2c4f60; --teal-light:#5a8fa3;
    --green:#4a9c6d; --green-light:#d1ead8; --sand:#d6d2c4;
    --sky:#e8f4f8; --white:#ffffff; --text:#1e3040;
    --text-muted:#5a7a8a; --radius:12px;
    --shadow-sm:0 2px 8px rgba(0,0,0,.08);
    --shadow-md:0 6px 24px rgba(0,0,0,.12);
    --shadow-lg:0 16px 48px rgba(0,0,0,.18);
    --ease:cubic-bezier(.34,1.56,.64,1);
}
body{font-family:'Inter',sans-serif;background:#f0f6f9;color:var(--text);min-height:100vh;display:flex;flex-direction:column;}

/* ── Header ── */
.campus-header{background:linear-gradient(135deg,var(--teal-dark) 0%,var(--teal) 60%,var(--teal-light) 100%);padding:18px 32px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 3px 16px rgba(0,0,0,.2);position:relative;overflow:hidden;}
.campus-header::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
.header-brand{display:flex;align-items:center;gap:14px;position:relative;z-index:1;}
.header-logo{width:44px;height:44px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);border-radius:10px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(6px);}
.header-logo svg{width:24px;height:24px;fill:white}
.header-title{font-family:'Instrument Serif',serif;font-size:1.6rem;color:#fff;letter-spacing:-.3px;}
.header-sub{font-size:.78rem;color:rgba(255,255,255,.7);margin-top:1px}
.header-badge{position:relative;z-index:1;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:6px 14px;font-size:.75rem;color:rgba(255,255,255,.85);backdrop-filter:blur(6px);display:flex;align-items:center;gap:6px;}
.badge-dot{width:7px;height:7px;border-radius:50%;background:#4ade80;animation:blink 2s ease-in-out infinite;}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}

/* ── Main ── */
.campus-main{flex:1;padding:28px 32px;display:flex;flex-direction:column;gap:20px;}
.section-title{font-family:'Instrument Serif',serif;font-size:1.3rem;color:var(--teal);display:flex;align-items:center;gap:10px;}
.section-title::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,var(--teal) 0%,transparent 100%);opacity:.2;}

/* ── Mapa ── */
.map-wrapper{position:relative;background:linear-gradient(160deg,#e8f3f8 0%,#ddeef5 50%,#d5eaee 100%);border-radius:18px;border:1px solid rgba(55,96,115,.15);box-shadow:var(--shadow-md);overflow:visible;height:580px;}
.map-wrapper::before{content:'';position:absolute;inset:0;border-radius:18px;background:radial-gradient(circle at 20% 80%,rgba(74,156,109,.08) 0%,transparent 50%),radial-gradient(circle at 80% 20%,rgba(55,96,115,.06) 0%,transparent 50%);pointer-events:none;}
.campus-svg{position:absolute;inset:0;width:100%;height:100%;border-radius:18px;}

/* ── Edificios SVG ── */
.building-group{cursor:pointer}
.building-body{transition:transform .3s var(--ease),filter .3s;transform-origin:center;}
.building-group:hover .building-body{transform:translateY(-4px) scale(1.04);filter:drop-shadow(0 8px 20px rgba(55,96,115,.35));}
.building-label{font-family:'Inter',sans-serif;font-size:11px;font-weight:700;fill:var(--text);letter-spacing:.3px;}
.area-static{cursor:pointer;transition:filter .25s;}
.area-static:hover{filter:brightness(1.08)}

/* ── Tooltip ── */
.map-tooltip{position:absolute;background:#fff;border-radius:14px;padding:0;box-shadow:var(--shadow-lg);border:1px solid rgba(55,96,115,.15);min-width:220px;max-width:280px;pointer-events:none;opacity:0;transform:translateY(8px) scale(.97);transition:opacity .25s ease,transform .25s var(--ease);z-index:200;display:none;}
.map-tooltip.visible{opacity:1;transform:translateY(0) scale(1);}
.tooltip-header{background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;padding:12px 16px 10px;border-radius:13px 13px 0 0;display:flex;align-items:center;gap:8px;}
.tooltip-icon{width:28px;height:28px;background:rgba(255,255,255,.18);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;}
.tooltip-title{font-weight:700;font-size:.85rem;line-height:1.2}
.tooltip-sub{font-size:.7rem;opacity:.75;margin-top:1px}
.tooltip-body{padding:12px 16px 14px;font-size:.78rem;color:var(--text-muted);line-height:1.6}
.tooltip-body strong{color:var(--text)}
.tooltip-footer{padding:0 16px 12px;display:flex;gap:8px;flex-direction:column;}
.tooltip-btn{display:block;width:100%;padding:8px;background:linear-gradient(135deg,var(--teal),var(--teal-light));color:#fff;border:none;border-radius:8px;font-size:.75rem;font-weight:700;letter-spacing:.3px;cursor:pointer;transition:opacity .2s,transform .15s;text-decoration:none;text-align:center;}
.tooltip-btn:hover{opacity:.88;transform:translateY(-1px)}
.tooltip-btn-gallery{display:block;width:100%;padding:8px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border:none;border-radius:8px;font-size:.75rem;font-weight:700;letter-spacing:.3px;cursor:pointer;transition:opacity .2s,transform .15s;text-align:center;}
.tooltip-btn-gallery:hover{opacity:.88;transform:translateY(-1px)}
.tooltip-arrow{position:absolute;width:12px;height:12px;background:#fff;border-left:1px solid rgba(55,96,115,.15);border-top:1px solid rgba(55,96,115,.15);transform:rotate(45deg);bottom:-6px;left:50%;margin-left:-6px;}
.tooltip-arrow.arrow-top{bottom:auto;top:-6px;border-left:none;border-top:none;border-right:1px solid rgba(55,96,115,.15);border-bottom:1px solid rgba(55,96,115,.15);}

/* ── Leyenda ── */
.map-legend{position:absolute;bottom:16px;left:16px;background:rgba(255,255,255,.85);backdrop-filter:blur(8px);border:1px solid rgba(55,96,115,.12);border-radius:10px;padding:10px 14px;font-size:.72rem;color:var(--text-muted);display:flex;gap:14px;z-index:10;}
.legend-item{display:flex;align-items:center;gap:5px}
.legend-dot{width:10px;height:10px;border-radius:3px;flex-shrink:0;}

/* ── Animaciones edificios ── */
@keyframes buildingIn{from{opacity:0;transform:translateY(12px) scale(.9)}to{opacity:1;transform:translateY(0) scale(1)}}
.building-group{animation:buildingIn .5s var(--ease) both;}
.building-group:nth-child(1){animation-delay:.1s}.building-group:nth-child(2){animation-delay:.2s}.building-group:nth-child(3){animation-delay:.3s}
@keyframes ping{0%{transform:scale(1);opacity:.7}70%{transform:scale(1.8);opacity:0}100%{transform:scale(1.8);opacity:0}}
.building-ping{animation:ping 2s ease-out infinite;transform-origin:center;}

/* ══════════════════════════════════════════════
   GALERÍA — MODAL PRINCIPAL
══════════════════════════════════════════════ */
.gallery-overlay{
    position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);
    z-index:1000;display:flex;align-items:flex-start;justify-content:center;
    padding:20px;overflow-y:auto;
    opacity:0;pointer-events:none;
    transition:opacity .35s ease;
}
.gallery-overlay.active{opacity:1;pointer-events:all;}

.gallery-modal{
    background:#fff;border-radius:22px;width:100%;max-width:900px;
    box-shadow:0 30px 80px rgba(0,0,0,.35);
    transform:translateY(40px) scale(.97);
    transition:transform .4s var(--ease),opacity .35s ease;
    opacity:0;overflow:hidden;margin:auto;
}
.gallery-overlay.active .gallery-modal{transform:translateY(0) scale(1);opacity:1;}

.gallery-modal-header{
    background:linear-gradient(135deg,#4c1d95,#7c3aed 55%,#a855f7);
    padding:20px 24px;display:flex;align-items:center;gap:14px;position:relative;overflow:hidden;
}
.gallery-modal-header::before{
    content:'';position:absolute;inset:0;
    background:radial-gradient(circle at 80% 50%,rgba(255,255,255,.08) 0%,transparent 60%);
    pointer-events:none;
}
.gallery-modal-icon{width:42px;height:42px;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.gallery-modal-title{font-family:'Instrument Serif',serif;font-size:1.3rem;color:#fff;flex:1;letter-spacing:-.2px;}
.gallery-modal-sub{font-size:.74rem;color:rgba(255,255,255,.7);margin-top:2px;}
.gallery-modal-close{background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;width:36px;height:36px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s,transform .15s;z-index:1;}
.gallery-modal-close:hover{background:rgba(255,255,255,.28);transform:rotate(90deg);}

/* Grid de fotos */
.gallery-body{padding:22px 24px;}
.gallery-empty{text-align:center;padding:48px 20px;color:var(--text-muted);}
.gallery-empty-icon{font-size:3rem;margin-bottom:12px;opacity:.4;animation:floatIcon 3s ease-in-out infinite;}
@keyframes floatIcon{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.gallery-empty p{font-size:.85rem;line-height:1.6;}
.gallery-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:16px;
}

/* Card de foto individual */
.gallery-card{
    border-radius:14px;overflow:hidden;background:#f8fafc;
    border:1px solid rgba(55,96,115,.1);
    box-shadow:0 2px 8px rgba(0,0,0,.06);
    transition:transform .3s var(--ease),box-shadow .3s;
    cursor:pointer;
    animation:cardIn .5s var(--ease) both;
}
@keyframes cardIn{
    from{opacity:0;transform:translateY(20px) scale(.95)}
    to{opacity:1;transform:translateY(0) scale(1)}
}
.gallery-card:hover{transform:translateY(-6px) scale(1.02);box-shadow:0 12px 32px rgba(0,0,0,.15);}
.gallery-card-img{width:100%;height:160px;object-fit:cover;display:block;background:linear-gradient(135deg,#e8f3f8,#d5eaee);}
.gallery-card-img.placeholder-img{display:flex;align-items:center;justify-content:center;font-size:2.5rem;}
.gallery-card-info{padding:12px 14px;}
.gallery-card-title{font-weight:700;font-size:.82rem;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.gallery-card-desc{font-size:.73rem;color:var(--text-muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.gallery-card-meta{font-size:.68rem;color:#a0b8c8;margin-top:8px;display:flex;align-items:center;gap:4px;}

/* Acciones admin en card */
.gallery-card-actions{display:flex;gap:6px;padding:0 14px 12px;}
.btn-del-photo{flex:1;padding:6px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:7px;font-size:.7rem;font-weight:700;cursor:pointer;transition:background .2s;}
.btn-del-photo:hover{background:#c0392b;color:#fff;}

/* ── Botón agregar foto ── */
.btn-add-photo{
    display:flex;align-items:center;gap:8px;
    padding:10px 18px;
    background:linear-gradient(135deg,#7c3aed,#a855f7);
    color:#fff;border:none;border-radius:10px;
    font-size:.8rem;font-weight:700;cursor:pointer;
    transition:opacity .2s,transform .15s,box-shadow .2s;
    box-shadow:0 4px 14px rgba(124,58,237,.35);
    margin-bottom:18px;
}
.btn-add-photo:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 8px 20px rgba(124,58,237,.4);}
.lightbox-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.92);
    backdrop-filter: blur(12px);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s ease;
}
.lightbox-overlay.active {
    opacity: 1;
    pointer-events: all;
}
.lightbox-content {
    max-width: 800px;
    width: 100%;
    transform: scale(.9);
    transition: transform .4s var(--ease);
}
.lightbox-overlay.active .lightbox-content {
    transform: scale(1);
}
.lightbox-arrow {
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.2);
    color: #fff;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .2s, transform .15s, opacity .2s;
    z-index: 1;
}
.lightbox-arrow:hover {
    background: rgba(255,255,255,.24);
    transform: scale(1.1);
}
/* ─.lightbox-overlay─ Formulario subir foto ── */
.upload-form{
    background:linear-gradient(135deg,#f5f3ff,#ede9fe);
    border:1.5px solid rgba(124,58,237,.2);
    border-radius:14px;padding:20px;margin-bottom:20px;
    display:none;
    animation:slideDown .35s var(--ease) both;
}
.upload-form.open{display:block;}
@keyframes slideDown{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}
.upload-form label{display:block;font-size:.74rem;font-weight:700;color:#5b21b6;margin-bottom:5px;margin-top:12px;}
.upload-form label:first-child{margin-top:0;}
.upload-form input[type=text],.upload-form textarea{
    width:100%;padding:9px 12px;border:1.5px solid rgba(124,58,237,.25);
    border-radius:9px;font-size:.82rem;background:#fff;
    transition:border-color .2s,box-shadow .2s;outline:none;
    font-family:'Inter',sans-serif;
}
.upload-form input[type=text]:focus,.upload-form textarea:focus{border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.12);}
.upload-form textarea{resize:vertical;min-height:72px;}
.upload-form input[type=file]{
    width:100%;padding:9px 12px;border:1.5px dashed rgba(124,58,237,.35);
    border-radius:9px;font-size:.78rem;background:#fff;cursor:pointer;
}
.upload-form-actions{display:flex;gap:10px;margin-top:16px;}
.btn-upload-submit{flex:1;padding:10px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border:none;border-radius:9px;font-weight:700;font-size:.82rem;cursor:pointer;transition:opacity .2s,transform .15s;}
.btn-upload-submit:hover{opacity:.88;transform:translateY(-1px);}
.btn-upload-cancel{flex:1;padding:10px;background:#fff;color:#7c3aed;border:1.5px solid rgba(124,58,237,.3);border-radius:9px;font-weight:600;font-size:.82rem;cursor:pointer;transition:background .2s;}
.btn-upload-cancel:hover{background:#f5f3ff;}

/* ── Lightbox (ver foto en grande) ── */

.lightbox-img{width:100%;max-height:65vh;object-fit:contain;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.6);display:block;}
.lightbox-info{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px 20px;margin-top:14px;backdrop-filter:blur(6px);}
.lightbox-title{font-family:'Instrument Serif',serif;font-size:1.1rem;color:#fff;margin-bottom:4px;}
.lightbox-desc{font-size:.8rem;color:rgba(255,255,255,.7);line-height:1.6;}
.lightbox-close{position:absolute;top:20px;right:24px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);color:#fff;width:40px;height:40px;border-radius:50%;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s,transform .15s;}
.lightbox-close:hover{background:rgba(255,255,255,.24);transform:rotate(90deg);}

/* ── Toast ── */
.toast{
    position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);
    background:var(--teal-dark);color:#fff;padding:12px 22px;border-radius:30px;
    font-size:.82rem;font-weight:600;z-index:3000;
    opacity:0;transition:opacity .3s,transform .3s var(--ease);
    pointer-events:none;white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,.25);
    display:flex;align-items:center;gap:8px;
}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0);}

/* ── Footer ── */
.campus-footer{background:linear-gradient(135deg,#b8d67a,#a5c95e);text-align:center;padding:14px;font-size:.78rem;color:#2d5016;font-weight:600;letter-spacing:.2px;}

/* ── Confetti partículas ── */
.confetti-piece{position:fixed;width:8px;height:8px;border-radius:2px;pointer-events:none;z-index:9999;animation:confettiFall linear forwards;}
@keyframes confettiFall{0%{transform:translateY(-20px) rotate(0deg);opacity:1;}100%{transform:translateY(110vh) rotate(720deg);opacity:0;}}
</style>
@endsection

@section('content')
<header class="campus-header">
    <div class="header-brand">
        <div class="header-logo">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22" stroke="white" stroke-width="1.5" fill="none"/></svg>
        </div>
        <div>
            <div class="header-title">Ubicatec</div>
            <div class="header-sub">Sistema de localización del campus</div>
        </div>
    </div>
    <div class="header-badge">
        <span class="badge-dot"></span>
        Campus activo
    </div>
</header>

<div class="campus-main">
    <div class="section-title">Selecciona el área que deseas conocer</div>

    <div class="map-wrapper" id="mapWrapper">
        <svg class="campus-svg" viewBox="0 0 900 560" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="grassGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#6dbf67"/><stop offset="100%" stop-color="#4a9c4a"/>
                </linearGradient>
                <linearGradient id="buildGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#5a8fa3"/><stop offset="100%" stop-color="#2c4f60"/>
                </linearGradient>
                <filter id="buildShadow" x="-20%" y="-20%" width="140%" height="140%">
                    <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="rgba(0,0,0,.25)"/>
                </filter>
                <filter id="areaShadow">
                    <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,.12)"/>
                </filter>
            </defs>

            {{-- Caminos --}}
            <rect x="0" y="270" width="900" height="14" fill="#ccc9b5" rx="2" opacity=".7"/>
            <rect x="200" y="0" width="12" height="560" fill="#ccc9b5" rx="2" opacity=".6"/>
            <rect x="520" y="0" width="12" height="560" fill="#ccc9b5" rx="2" opacity=".6"/>
            <rect x="200" y="410" width="320" height="10" fill="#ccc9b5" rx="2" opacity=".6"/>

            {{-- Cancha Fútbol Grande --}}
            <g class="area-static" data-area="cancha-futbol" transform="translate(20,40)">
                <rect width="170" height="210" rx="8" fill="url(#grassGrad)" filter="url(#areaShadow)"/>
                <rect x="0" y="0" width="170" height="210" rx="8" fill="none" stroke="#fff" stroke-width="2" opacity=".6"/>
                <line x1="0" y1="105" x2="170" y2="105" stroke="#fff" stroke-width="1.5" opacity=".7"/>
                <circle cx="85" cy="105" r="28" fill="none" stroke="#fff" stroke-width="1.5" opacity=".7"/>
                <circle cx="85" cy="105" r="3" fill="#fff" opacity=".8"/>
                <rect x="55" y="0" width="60" height="28" rx="2" fill="none" stroke="#fff" stroke-width="1.5" opacity=".6"/>
                <rect x="55" y="182" width="60" height="28" rx="2" fill="none" stroke="#fff" stroke-width="1.5" opacity=".6"/>
                <text x="85" y="228" text-anchor="middle" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#2d5016">⚽ Cancha Fútbol</text>
            </g>

            {{-- Cancha Fútbol Central --}}
            <g class="area-static" data-area="cancha-futbol2" transform="translate(235,60)">
                <rect width="260" height="160" rx="8" fill="url(#grassGrad)" opacity=".9" filter="url(#areaShadow)"/>
                <rect x="0" y="0" width="260" height="160" rx="8" fill="none" stroke="#fff" stroke-width="1.5" opacity=".6"/>
                <line x1="130" y1="0" x2="130" y2="160" stroke="#fff" stroke-width="1.5" opacity=".6"/>
                <circle cx="130" cy="80" r="24" fill="none" stroke="#fff" stroke-width="1.5" opacity=".7"/>
                <circle cx="130" cy="80" r="3" fill="#fff" opacity=".8"/>
                <rect x="0" y="50" width="32" height="60" rx="2" fill="none" stroke="#fff" stroke-width="1.2" opacity=".6"/>
                <rect x="228" y="50" width="32" height="60" rx="2" fill="none" stroke="#fff" stroke-width="1.2" opacity=".6"/>
                <text x="130" y="180" text-anchor="middle" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#2d5016">⚽ Canchas Deportivas</text>
            </g>

            {{-- Canchas Voleibol --}}
            <g class="area-static" data-area="voley" transform="translate(535,310)">
                <rect width="260" height="140" rx="8" fill="url(#grassGrad)" opacity=".85" filter="url(#areaShadow)"/>
                <rect x="0" y="0" width="260" height="140" rx="8" fill="none" stroke="#fff" stroke-width="1.5" opacity=".6"/>
                <line x1="130" y1="0" x2="130" y2="140" stroke="#fff" stroke-width="2" opacity=".8"/>
                <line x1="0" y1="70" x2="260" y2="70" stroke="#fff" stroke-width="1" stroke-dasharray="8,4" opacity=".5"/>
                <text x="130" y="160" text-anchor="middle" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#2d5016">🏐 Canchas Voleibol</text>
            </g>

            {{-- Asta Bandera --}}
            <g class="area-static" data-area="asta" transform="translate(215,295)">
                <circle cx="20" cy="20" r="18" fill="#e8f4f8" stroke="#376073" stroke-width="2" filter="url(#areaShadow)"/>
                <line x1="20" y1="6" x2="20" y2="34" stroke="#376073" stroke-width="2.5" stroke-linecap="round"/>
                <polygon points="20,7 34,11 20,15" fill="#e63946" opacity=".9">
                    <animateTransform attributeName="transform" type="rotate" values="-3 20 11; 3 20 11; -3 20 11" dur="2s" repeatCount="indefinite"/>
                </polygon>
                <text x="20" y="52" text-anchor="middle" font-family="Inter,sans-serif" font-size="10" font-weight="600" fill="#376073">Asta Bandera</text>
            </g>

            {{-- Cooperativa --}}
            <g class="area-static" data-area="cooperativa" transform="translate(530,55)">
                <polygon points="55,0 110,40 0,40" fill="#d4926a" filter="url(#areaShadow)"/>
                <rect x="10" y="40" width="90" height="55" rx="4" fill="#e8c9a0" stroke="#c4956a" stroke-width="1.5"/>
                <rect x="18" y="48" width="22" height="18" rx="2" fill="#aad4e8" stroke="#888" stroke-width="1"/>
                <rect x="70" y="48" width="22" height="18" rx="2" fill="#aad4e8" stroke="#888" stroke-width="1"/>
                <rect x="39" y="68" width="32" height="27" rx="3" fill="#a0734a" stroke="#7a5030" stroke-width="1.5"/>
                <circle cx="55" cy="82" r="2.5" fill="#d4a04a"/>
                <rect x="5" y="100" width="100" height="14" rx="4" fill="#376073"/>
                <text x="55" y="111" text-anchor="middle" font-family="Inter,sans-serif" font-size="9" font-weight="700" fill="#fff">COOPERATIVA</text>
                <text x="55" y="128" text-anchor="middle" font-family="Inter,sans-serif" font-size="10" font-weight="600" fill="#376073">🏪 Cooperativa</text>
            </g>

            {{-- Área de Prácticas --}}
            <g class="area-static" data-area="practicas" transform="translate(20,310)">
                <rect width="80" height="60" rx="6" fill="#dde8ef" stroke="#376073" stroke-width="1.5" filter="url(#areaShadow)"/>
                <text x="40" y="35" text-anchor="middle" font-family="Inter,sans-serif" font-size="9" font-weight="600" fill="#376073">P1</text>
                <rect x="90" y="0" width="80" height="60" rx="6" fill="#dde8ef" stroke="#376073" stroke-width="1.5" filter="url(#areaShadow)"/>
                <text x="130" y="35" text-anchor="middle" font-family="Inter,sans-serif" font-size="9" font-weight="600" fill="#376073">P2</text>
                <rect x="0" y="70" width="80" height="60" rx="6" fill="#dde8ef" stroke="#376073" stroke-width="1.5" filter="url(#areaShadow)"/>
                <text x="40" y="105" text-anchor="middle" font-family="Inter,sans-serif" font-size="9" font-weight="600" fill="#376073">P3</text>
                <text x="85" y="158" text-anchor="middle" font-family="Inter,sans-serif" font-size="10" font-weight="600" fill="#376073">🔬 Área de Prácticas</text>
            </g>

            {{-- Edificio A --}}
            <g class="building-group" id="btn-edificio-a" data-building="a">
                <circle cx="330" cy="385" r="28" fill="#376073" opacity=".15" class="building-ping"/>
                <g class="building-body" filter="url(#buildShadow)">
                    <rect x="270" y="330" width="120" height="110" rx="10" fill="url(#buildGrad)"/>
                    <rect x="282" y="344" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="307" y="344" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="332" y="344" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="357" y="344" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="282" y="370" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="307" y="370" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="332" y="370" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="357" y="370" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="315" y="396" width="30" height="44" rx="4" fill="rgba(255,255,255,.14)"/>
                    <rect x="265" y="325" width="130" height="12" rx="5" fill="#2c4f60"/>
                    <text x="330" y="336" text-anchor="middle" font-family="Instrument Serif,serif" font-size="9" fill="rgba(255,255,255,.75)" letter-spacing="2">A</text>
                </g>
                <text class="building-label" x="330" y="460" text-anchor="middle" font-size="12">Edificio A</text>
            </g>

            {{-- Edificio B --}}
            <g class="building-group" id="btn-edificio-b" data-building="b">
                <circle cx="820" cy="455" r="28" fill="#376073" opacity=".15" class="building-ping"/>
                <g class="building-body" filter="url(#buildShadow)">
                    <rect x="760" y="415" width="120" height="110" rx="10" fill="url(#buildGrad)"/>
                    <rect x="772" y="429" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="797" y="429" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="822" y="429" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="847" y="429" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="772" y="455" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="797" y="455" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="822" y="455" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="847" y="455" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="805" y="481" width="30" height="44" rx="4" fill="rgba(255,255,255,.14)"/>
                    <rect x="755" y="410" width="130" height="12" rx="5" fill="#2c4f60"/>
                    <text x="820" y="421" text-anchor="middle" font-family="Instrument Serif,serif" font-size="9" fill="rgba(255,255,255,.75)" letter-spacing="2">B</text>
                </g>
                <text class="building-label" x="820" y="542" text-anchor="middle" font-size="12">Edificio B</text>
            </g>

            {{-- Edificio C --}}
            <g class="building-group" id="btn-edificio-c" data-building="c">
                <circle cx="810" cy="195" r="28" fill="#376073" opacity=".15" class="building-ping"/>
                <g class="building-body" filter="url(#buildShadow)">
                    <rect x="750" y="155" width="120" height="110" rx="10" fill="url(#buildGrad)"/>
                    <rect x="762" y="169" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="787" y="169" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="812" y="169" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="837" y="169" width="18" height="18" rx="3" fill="rgba(255,255,255,.28)"/>
                    <rect x="762" y="195" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="787" y="195" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="812" y="195" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="837" y="195" width="18" height="18" rx="3" fill="rgba(255,255,255,.20)"/>
                    <rect x="795" y="221" width="30" height="44" rx="4" fill="rgba(255,255,255,.14)"/>
                    <rect x="745" y="150" width="130" height="12" rx="5" fill="#2c4f60"/>
                    <text x="810" y="161" text-anchor="middle" font-family="Instrument Serif,serif" font-size="9" fill="rgba(255,255,255,.75)" letter-spacing="2">C</text>
                </g>
                <text class="building-label" x="810" y="282" text-anchor="middle" font-size="12">Edificio C</text>
            </g>
        </svg>

        <!-- TOOLTIP -->
        <div class="map-tooltip" id="mapTooltip">
            <div class="tooltip-header">
                <div class="tooltip-icon" id="ttIcon"></div>
                <div>
                    <div class="tooltip-title" id="ttTitle"></div>
                    <div class="tooltip-sub" id="ttSub"></div>
                </div>
            </div>
            <div class="tooltip-body" id="ttBody"></div>
            <div class="tooltip-footer" id="ttFooter"></div>
            <div class="tooltip-arrow" id="ttArrow"></div>
        </div>

        <!-- LEYENDA -->
        <div class="map-legend">
            <div class="legend-item"><div class="legend-dot" style="background:#5cb85c"></div><span>Canchas</span></div>
            <div class="legend-item"><div class="legend-dot" style="background:#376073"></div><span>Edificios</span></div>
            <div class="legend-item"><div class="legend-dot" style="background:#dde8ef;border:1px solid #376073"></div><span>Prácticas</span></div>
            <div class="legend-item"><div class="legend-dot" style="background:#a855f7"></div><span>📸 Galería</span></div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL GALERÍA
══════════════════════════════════════════════ -->
<div class="gallery-overlay" id="galleryOverlay">
    <div class="gallery-modal" id="galleryModal">

        <!-- Header -->
        <div class="gallery-modal-header">
            <div class="gallery-modal-icon" id="galleryModalIcon">📸</div>
            <div>
                <div class="gallery-modal-title" id="galleryModalTitle">Galería</div>
                <div class="gallery-modal-sub" id="galleryModalSub">Imágenes del área</div>
            </div>
            <button class="gallery-modal-close" id="galleryModalClose" aria-label="Cerrar galería">✕</button>
        </div>

        <!-- Body -->
        <div class="gallery-body">

            {{-- Botón y formulario de subida (solo admin) --}}
            @if(session('usuario') && session('usuario')->rol === 'administrador')
            <button class="btn-add-photo" id="btnToggleForm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Agregar fotografía
            </button>

            <div class="upload-form" id="uploadForm">
                <form id="formSubirFoto" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="area" id="formArea">
                    <label>Título de la imagen *</label>
                    <input type="text" name="titulo" id="inputTitulo" placeholder="Ej: Entrada principal" required>
                    <label>Descripción</label>
                    <textarea name="descripcion" id="inputDesc" placeholder="Describe qué muestra esta foto..."></textarea>
                    <label>Imagen *</label>
                    <input type="file" name="imagen" id="inputImagen" accept="image/*" required>
                    <div class="upload-form-actions">
                        <button type="submit" class="btn-upload-submit">✨ Guardar imagen</button>
                        <button type="button" class="btn-upload-cancel" id="btnCancelForm">Cancelar</button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Grid de imágenes -->
            <div id="galleryGrid" class="gallery-grid">
                <!-- Las fotos se inyectan via JS desde la ruta API -->
            </div>

            <div id="galleryEmpty" class="gallery-empty" style="display:none;">
                <div class="gallery-empty-icon">🖼️</div>
                <p><strong>Sin fotos aún</strong><br>
                @if(session('usuario') && session('usuario')->rol === 'administrador')
                    Usa el botón de arriba para agregar la primera imagen de esta área.
                @else
                    Próximamente se agregarán imágenes de esta área.
                @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- LIGHTBOX -->
<div class="lightbox-overlay" id="lightboxOverlay">
    <button class="lightbox-close" id="lightboxClose" aria-label="Cerrar">&times;</button>

    <!-- Flecha anterior -->
    <button class="lightbox-arrow lb-prev" id="lbPrev" onclick="lightboxNav(-1)" aria-label="Anterior">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </button>

    <div class="lightbox-content">
        <img src="" alt="" id="lightboxImg" class="lightbox-img">
        <div class="lightbox-info">
            <div class="lightbox-title" id="lightboxTitle"></div>
            <div class="lightbox-desc" id="lightboxDesc"></div>
        </div>
    </div>

    <!-- Flecha siguiente -->
    <button class="lightbox-arrow lb-next" id="lbNext" onclick="lightboxNav(1)" aria-label="Siguiente">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </button>
</div>

<!-- TOAST -->
<div class="toast" id="toastMsg"></div>

<footer class="campus-footer">
    © 2025 Ubicatec &nbsp;·&nbsp; Campus Universitario
</footer>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── Rol del usuario ──
    const esAdmin = {{ session('usuario') && session('usuario')->rol === 'administrador' ? 'true' : 'false' }};

    // ── DATA tooltip ──
    const DATA = {
        a: {
            icon:'🏛️', title:'Edificio A', sub:'Planta baja y alta',
            body:`<strong>Planta baja:</strong> EA1, EA2, EA3, EA4, CC1, Lab<br>
                  <strong>Planta alta:</strong> A5, A7, A9, A10, Oficinas<br>
                  <strong>Estado:</strong> Ver disponibilidad en tiempo real`,
            btn:{ text:'Ver disponibilidad →', href:'{{ route("aulas.index") }}' }
        },
        b: {
            icon:'🏢', title:'Edificio B', sub:'Planta baja y alta',
            body:`<strong>Planta baja:</strong> EB1–EB9, Biblioteca, Lobby<br>
                  <strong>Planta alta:</strong> EB10–EB15, Lab Cisco, CC2, CC3<br>
                  <strong>Estado:</strong> Ver disponibilidad en tiempo real`,
            btn:{ text:'Ver disponibilidad →', href:'{{ route("edificiob.index") }}' }
        },
        c: {
            icon:'🔬', title:'Edificio C', sub:'Laboratorios y aulas',
            body:'Laboratorios de cómputo y ciencias.<br>Próximamente disponible.', btn:null
        },
        'cancha-futbol': {
            icon:'⚽', title:'Cancha de Fútbol', sub:'Área deportiva norte',
            body:'Cancha reglamentaria. Disponible fuera de horario escolar.', btn:null
        },
        'cancha-futbol2': {
            icon:'⚽', title:'Canchas Deportivas', sub:'Área central',
            body:'Canchas multiusos para fútbol y actividades deportivas.', btn:null
        },
        voley: {
            icon:'🏐', title:'Canchas de Voleibol', sub:'Área deportiva sur',
            body:'Dos canchas para clases de Educación Física y uso libre.', btn:null
        },
        cooperativa: {
            icon:'🏪', title:'Cooperativa', sub:'Servicios estudiantiles',
            body:'Venta de alimentos, copias, papelería y artículos de primera necesidad.', btn:null
        },
        asta: {
            icon:'🚩', title:'Asta Bandera', sub:'Plaza cívica',
            body:'Plaza cívica principal. Punto de reunión para actos oficiales.', btn:null
        },
        practicas: {
            icon:'🔬', title:'Área de Prácticas', sub:'Talleres y laboratorios',
            body:'Salones P1, P2, P3 para prácticas de taller y laboratorio.', btn:null
        }
    };

    // ── TOOLTIP ──
    const tooltip  = document.getElementById('mapTooltip');
    const wrapper  = document.getElementById('mapWrapper');
    const ttIcon   = document.getElementById('ttIcon');
    const ttTitle  = document.getElementById('ttTitle');
    const ttSub    = document.getElementById('ttSub');
    const ttBody   = document.getElementById('ttBody');
    const ttFooter = document.getElementById('ttFooter');
    const ttArrow  = document.getElementById('ttArrow');

    function showTooltip(key, anchorEl) {
        const d = DATA[key];
        if (!d) return;
        ttIcon.textContent  = d.icon;
        ttTitle.textContent = d.title;
        ttSub.textContent   = d.sub;
        ttBody.innerHTML    = d.body;

        // Botón principal
        let footerHTML = d.btn
            ? `<a href="${d.btn.href}" class="tooltip-btn">${d.btn.text}</a>`
            : '';
        // Botón galería (todas las áreas excepto asta)
        if (key !== 'asta') {
            footerHTML += `<button class="tooltip-btn-gallery" onclick="openGallery('${key}')">📸 Ver galería</button>`;
        }
        ttFooter.innerHTML = footerHTML;

        tooltip.style.display = 'block';
        const wRect = wrapper.getBoundingClientRect();
        const aRect = anchorEl.getBoundingClientRect();
        const tw = tooltip.offsetWidth  || 240;
        const th = tooltip.offsetHeight || 160;
        let left = aRect.left - wRect.left + (aRect.width / 2) - (tw / 2);
        let top  = aRect.top  - wRect.top  - th - 14;
        let arrowTop = false;
        if (top < 8) { top = aRect.top - wRect.top + aRect.height + 14; arrowTop = true; }
        left = Math.max(8, Math.min(left, wRect.width - tw - 8));
        tooltip.style.left = left + 'px';
        tooltip.style.top  = top  + 'px';
        ttArrow.className  = 'tooltip-arrow' + (arrowTop ? ' arrow-top' : '');
        requestAnimationFrame(() => tooltip.classList.add('visible'));
    }

    function hideTooltip() {
        tooltip.classList.remove('visible');
        setTimeout(() => { if (!tooltip.classList.contains('visible')) tooltip.style.display = 'none'; }, 260);
    }

    document.querySelectorAll('.building-group').forEach(g => {
        const key = g.dataset.building;
        g.addEventListener('mouseenter', () => showTooltip(key, g));
        g.addEventListener('mouseleave', hideTooltip);
        g.addEventListener('click', () => { if (DATA[key]?.btn) window.location.href = DATA[key].btn.href; });
    });

    document.querySelectorAll('.area-static').forEach(g => {
        const key = g.dataset.area;
        g.addEventListener('mouseenter', () => showTooltip(key, g));
        g.addEventListener('mouseleave', hideTooltip);
        g.addEventListener('click', () => { if (key !== 'asta') openGallery(key); });
    });

    tooltip.addEventListener('mouseenter', () => tooltip.classList.add('visible'));
    tooltip.addEventListener('mouseleave', hideTooltip);


    // ════════════════════════════════════
    //  GALERÍA
    // ════════════════════════════════════
    const galleryOverlay   = document.getElementById('galleryOverlay');
    const galleryModal     = document.getElementById('galleryModal');
    const galleryModalIcon = document.getElementById('galleryModalIcon');
    const galleryModalTitle= document.getElementById('galleryModalTitle');
    const galleryModalSub  = document.getElementById('galleryModalSub');
    const galleryModalClose= document.getElementById('galleryModalClose');
    const galleryGrid      = document.getElementById('galleryGrid');
    const galleryEmpty     = document.getElementById('galleryEmpty');
    const uploadForm       = document.getElementById('uploadForm');
    const btnToggleForm    = document.getElementById('btnToggleForm');
    const btnCancelForm    = document.getElementById('btnCancelForm');
    const formSubirFoto    = document.getElementById('formSubirFoto');
    const formArea         = document.getElementById('formArea');

    let currentArea = null;

    window.openGallery = function(areaKey) {
        currentArea = areaKey;
        const d = DATA[areaKey];
        galleryModalIcon.textContent  = d?.icon  || '📸';
        galleryModalTitle.textContent = (d?.title || areaKey) + ' — Galería';
        galleryModalSub.textContent   = d?.sub   || 'Imágenes del área';
        if (formArea) formArea.value = areaKey;

        // Cerrar tooltip
        hideTooltip();

        // Abrir modal
        galleryOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Cargar fotos
        loadPhotos(areaKey);
    };

    galleryModalClose.addEventListener('click', closeGallery);
    galleryOverlay.addEventListener('click', e => { if (e.target === galleryOverlay) closeGallery(); });

    function closeGallery() {
        galleryOverlay.classList.remove('active');
        document.body.style.overflow = '';
        if (uploadForm) {
            uploadForm.classList.remove('open');
        }
    }

    // Escape para cerrar
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (lightboxOverlay.classList.contains('active')) closeLightbox();
            else if (galleryOverlay.classList.contains('active')) closeGallery();
        }
        if (lightboxOverlay.classList.contains('active') && e.key === 'ArrowLeft')  lightboxNav(-1);
        if (lightboxOverlay.classList.contains('active') && e.key === 'ArrowRight') lightboxNav(1);
    });

    // Toggle formulario
    if (btnToggleForm) {
        btnToggleForm.addEventListener('click', () => {
            uploadForm.classList.toggle('open');
            btnToggleForm.innerHTML = uploadForm.classList.contains('open')
                ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg> Cancelar'
                : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Agregar fotografía';
        });
    }
    if (btnCancelForm) {
        btnCancelForm.addEventListener('click', () => {
            uploadForm.classList.remove('open');
            if (btnToggleForm) btnToggleForm.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Agregar fotografía';
        });
    }

    // Envío del formulario
    if (formSubirFoto) {
        formSubirFoto.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(formSubirFoto);
            try {
                const res = await fetch('{{ route("galeria.store") }}', {
                    method:'POST',
                    body: fd,
                    headers:{ 'X-Requested-With':'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.ok) {
                    formSubirFoto.reset();
                    uploadForm.classList.remove('open');
                    if (btnToggleForm) btnToggleForm.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Agregar fotografía';
                    loadPhotos(currentArea);
                    showToast('✅ Imagen agregada correctamente');
                    launchConfetti();
                } else {
                    showToast('⚠️ ' + (data.message || 'Error al guardar'));
                }
            } catch(err) {
                showToast('❌ Error de conexión');
            }
        });
    }

    // Cargar fotos desde API
    async function loadPhotos(area) {
        galleryGrid.innerHTML = '<div style="text-align:center;padding:32px;color:#a0b8c8;font-size:.82rem;">Cargando imágenes…</div>';
        galleryEmpty.style.display = 'none';
        try {
            const res  = await fetch(`{{ url('galeria/fotos') }}?area=${encodeURIComponent(area)}`, {
                headers:{ 'X-Requested-With':'XMLHttpRequest' }
            });
            const data = await res.json();
            renderPhotos(data.fotos || []);
        } catch(err) {
            galleryGrid.innerHTML = '<div style="text-align:center;padding:32px;color:#e74c3c;font-size:.82rem;">Error al cargar imágenes.</div>';
        }
    }

    function renderPhotos(fotos) {
    galleryGrid.innerHTML = '';
    fotosActuales = fotos; // ← guarda el array completo

    if (!fotos.length) {
        galleryEmpty.style.display = 'block';
        return;
    }
    galleryEmpty.style.display = 'none';

    fotos.forEach((f, i) => {
        const card = document.createElement('div');
        card.className = 'gallery-card';
        card.style.animationDelay = `${i * 0.07}s`;
        const imgUrl = f.imagen_url;
        const imgTag = imgUrl
            ? `<img src="${imgUrl}" alt="${f.titulo}" class="gallery-card-img" loading="lazy">`
            : `<div class="gallery-card-img placeholder-img">🖼️</div>`;
        const adminBtns = esAdmin
            ? `<div class="gallery-card-actions"><button class="btn-del-photo" onclick="deletePhoto(${f.id})">Eliminar</button></div>`
            : '';
        card.innerHTML = `${imgTag}
            <div class="gallery-card-info">
                <div class="gallery-card-title">${f.titulo}</div>
                <div class="gallery-card-desc">${f.descripcion || 'Sin descripción'}</div>
                <div class="gallery-card-meta">${f.created_at_human}</div>
            </div>${adminBtns}`;

        // ← pasa el índice i
        card.querySelector('.gallery-card-img').addEventListener('click',
            () => openLightbox(i)
        );
        galleryGrid.appendChild(card);
    });
}

    // Eliminar foto (admin)
    window.deletePhoto = async function(id) {
        if (!confirm('¿Eliminar esta imagen? Esta acción no se puede deshacer.')) return;
        try {
            const res  = await fetch(`{{ url('galeria/fotos') }}/${id}`, {
                method:'DELETE',
                headers:{
                    'X-Requested-With':'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'
                }
            });
            const data = await res.json();
            if (data.ok) {
                loadPhotos(currentArea);
                showToast('🗑️ Imagen eliminada');
            }
        } catch(err) {
            showToast('❌ Error al eliminar');
        }
    };


    // ════════════════════════════════════
    //  LIGHTBOX
    // ════════════════════════════════════
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImg     = document.getElementById('lightboxImg');
    const lightboxTitle   = document.getElementById('lightboxTitle');
    const lightboxDesc    = document.getElementById('lightboxDesc');
    const lightboxClose   = document.getElementById('lightboxClose');
    let fotosActuales = [];
    let lightboxIndex = 0;

    function openLightbox(index) {
    const f = fotosActuales[index];
    if (!f || !f.imagen_url) return;
    lightboxIndex = index;
    lightboxImg.src      = f.imagen_url;
    lightboxImg.alt      = f.titulo;
    lightboxTitle.textContent = f.titulo;
    lightboxDesc.textContent  = f.descripcion || '';
    // Actualiza visibilidad de flechas
    document.getElementById('lbPrev').style.opacity = index === 0 ? '0.3' : '1';
    document.getElementById('lbNext').style.opacity = index === fotosActuales.length - 1 ? '0.3' : '1';
    lightboxOverlay.classList.add('active');
}
window.lightboxNav = function(dir) {
    const next = lightboxIndex + dir;
    if (next < 0 || next >= fotosActuales.length) return;
    openLightbox(next);
}

    function closeLightbox() {
        lightboxOverlay.classList.remove('active');
    }

    lightboxClose.addEventListener('click', closeLightbox);
    lightboxOverlay.addEventListener('click', e => { if (e.target === lightboxOverlay) closeLightbox(); });


    // ════════════════════════════════════
    //  TOAST
    // ════════════════════════════════════
    const toastEl = document.getElementById('toastMsg');
    let toastTimer = null;
    function showToast(msg) {
        toastEl.textContent = msg;
        toastEl.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 3200);
    }


    // ════════════════════════════════════
    //  CONFETTI 🎊
    // ════════════════════════════════════
    function launchConfetti() {
        const colors = ['#7c3aed','#a855f7','#376073','#27ae60','#f39c12','#e74c3c','#3b82f6'];
        for (let i = 0; i < 60; i++) {
            const p = document.createElement('div');
            p.className = 'confetti-piece';
            p.style.cssText = `
                left:${Math.random()*100}vw;
                background:${colors[Math.floor(Math.random()*colors.length)]};
                width:${6+Math.random()*8}px;
                height:${6+Math.random()*8}px;
                border-radius:${Math.random()>.5?'50%':'2px'};
                animation-duration:${1.5+Math.random()*2}s;
                animation-delay:${Math.random()*.5}s;
                top:0;
            `;
            document.body.appendChild(p);
            p.addEventListener('animationend', () => p.remove());
        }
    }

});
</script>
@endsection