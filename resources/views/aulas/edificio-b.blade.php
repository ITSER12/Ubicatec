@extends('layouts.app')

@section('title', 'Mapa Edificio B')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --teal:#376073;--teal-dark:#2c4f60;--teal-light:#5a8fa3;
    --green:#27ae60;--red:#e74c3c;--orange:#f39c12;
    --bg:#f0f6f9;--surface:#fff;--border:rgba(55,96,115,.15);
    --text:#1e3040;--muted:#5a7a8a;
    --shadow-sm:0 2px 8px rgba(0,0,0,.07);
    --shadow-md:0 6px 24px rgba(0,0,0,.10);
    --ease:cubic-bezier(.34,1.56,.64,1);
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;}
.u-header{background:linear-gradient(135deg,var(--teal-dark),var(--teal) 60%,var(--teal-light));padding:10px 20px;display:flex;align-items:center;gap:16px;box-shadow:0 3px 14px rgba(0,0,0,.2);}
.u-logo{width:38px;height:38px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);border-radius:9px;display:flex;align-items:center;justify-content:center;}
.u-logo svg{width:20px;height:20px;fill:white}
.u-brand{font-family:'Instrument Serif',serif;font-size:1.35rem;color:#fff;letter-spacing:-.2px;}
.u-search{flex:1;display:flex;justify-content:center;}
.u-search input{width:55%;max-width:600px;padding:8px 18px;border-radius:22px;border:none;background:rgba(255,255,255,.18);color:#fff;font-size:.85rem;outline:none;backdrop-filter:blur(6px);transition:background .25s;}
.u-search input::placeholder{color:rgba(255,255,255,.65);}
.u-search input:focus{background:rgba(255,255,255,.28);}
.u-back{color:rgba(255,255,255,.8);font-size:.78rem;text-decoration:none;display:flex;align-items:center;gap:5px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:18px;padding:5px 12px;transition:background .2s;}
.u-back:hover{background:rgba(255,255,255,.22);color:#fff;}
.role-badge{font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:12px;letter-spacing:.4px;white-space:nowrap;}
.role-admin{background:#e74c3c;color:#fff;}
.role-jefe{background:#f39c12;color:#fff;}
.role-docente{background:#27ae60;color:#fff;}
.role-estudiante{background:rgba(255,255,255,.25);color:#fff;border:1px solid rgba(255,255,255,.35);}
.u-main{flex:1;padding:20px 24px;display:flex;flex-direction:column;gap:16px;}
.u-strip{display:flex;align-items:center;gap:12px;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:10px 16px;box-shadow:var(--shadow-sm);font-size:.8rem;color:var(--muted);flex-wrap:wrap;}
.u-strip strong{color:var(--text);}
.strip-sep{color:var(--border);margin:0 4px;}
.day-chip{background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;letter-spacing:.3px;}
.hora-slider-wrap{display:flex;align-items:center;gap:8px;background:#f0f6f9;border:1px solid var(--border);border-radius:10px;padding:5px 12px;}
.hora-slider-wrap label{font-size:.72rem;font-weight:700;color:var(--teal);white-space:nowrap;}
#sliderHora{-webkit-appearance:none;appearance:none;width:160px;height:5px;border-radius:3px;outline:none;cursor:pointer;}
#sliderHora::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:var(--teal);box-shadow:0 2px 6px rgba(55,96,115,.4);cursor:pointer;}
#sliderHoraLabel{font-size:.78rem;font-weight:700;color:var(--teal);min-width:38px;text-align:center;}
.hora-simulada-badge{background:#fff3cd;color:#b7770d;border:1px solid #f39c12;border-radius:8px;padding:2px 8px;font-size:.68rem;font-weight:700;display:none;}

/* ── Grid de plantas (dos columnas como Edificio A) ── */
.plants-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;flex:1;}
.plant-block{background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:16px 18px;box-shadow:var(--shadow-md);display:flex;flex-direction:column;gap:10px;position:relative;transition:box-shadow .3s;}
.plant-block:hover{box-shadow:0 10px 32px rgba(55,96,115,.13);}
.plant-title{font-family:'Instrument Serif',serif;font-size:.95rem;color:var(--teal);display:flex;align-items:center;gap:7px;border-bottom:1.5px solid #e8f2f7;padding-bottom:8px;margin-bottom:2px;}
.plant-title svg{width:16px;height:16px;stroke:var(--teal);fill:none;stroke-width:2;}

/* ── Aula boxes ── */
.aula-row{display:flex;align-items:center;gap:8px;flex-wrap:nowrap;}
.aula-row .spacer{flex:1;display:flex;justify-content:center;}
.aula-box{width:62px;height:62px;border-radius:11px;border:2px solid #c8dce6;background:#f0f6f9;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;cursor:pointer;transition:transform .22s var(--ease),box-shadow .22s,background .2s,border-color .2s;user-select:none;gap:2px;text-align:center;line-height:1.2;}
.aula-box:hover{transform:translateY(-4px) scale(1.07);box-shadow:0 8px 20px rgba(55,96,115,.18);}
.aula-box.selected{outline:3px solid var(--teal);outline-offset:2px;}
.aula-box .aula-icon{font-size:.9rem;line-height:1;}
.aula-box.ocupada{background:linear-gradient(135deg,#e74c3c,#c0392b)!important;color:#fff;border-color:#c0392b;box-shadow:0 4px 14px rgba(231,76,60,.35);}
.aula-box.libre{background:linear-gradient(135deg,#27ae60,#2ecc71)!important;color:#fff;border-color:#27ae60;box-shadow:0 4px 14px rgba(46,204,113,.35);}
.aula-box.apartada{background:linear-gradient(135deg,#f39c12,#e67e22)!important;color:#fff;border-color:#e67e22;box-shadow:0 4px 14px rgba(243,156,18,.35);}
.aula-box.bloqueada{background:linear-gradient(135deg,#636e72,#2d3436)!important;color:#fff;border-color:#2d3436;box-shadow:0 4px 14px rgba(45,52,54,.35);cursor:not-allowed;}
.label-box{border:1.5px dashed #c8dce6;background:#f8fbfd;border-radius:10px;padding:6px 10px;font-size:.7rem;font-weight:500;text-align:center;color:var(--muted);white-space:nowrap;flex:1;}
.label-box.vertical{writing-mode:vertical-rl;text-orientation:mixed;padding:10px 6px;flex:none;white-space:normal;min-height:80px;display:flex;align-items:center;justify-content:center;}

/* ── Panel info ── */
.panel-info{background:linear-gradient(150deg,#f0f7ff,#e4f0f8);border:1.5px solid #c8dde8;border-radius:13px;padding:14px 16px;font-size:.8rem;position:relative;overflow:hidden;transition:box-shadow .3s;}
.panel-info:hover{box-shadow:0 6px 20px rgba(55,96,115,.12);}
.panel-info::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:2.5px;background:linear-gradient(90deg,transparent,var(--teal),transparent);animation:scan 3s linear infinite;}
@keyframes scan{0%{left:-100%}100%{left:100%}}
.panel-placeholder{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:120px;gap:8px;color:#a0b8c8;}
.panel-placeholder svg{animation:pulse 2.2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:.35;transform:scale(1)}50%{opacity:.8;transform:scale(1.06)}}
.panel-placeholder span{font-size:.75rem;}
.panel-content{animation:slideIn .3s var(--ease) both;}
@keyframes slideIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.info-row{display:flex;align-items:baseline;gap:6px;margin-bottom:5px;border-bottom:1px dashed #d8e8f0;padding-bottom:4px;}
.info-row:last-child{border-bottom:none;margin-bottom:0;}
.info-label{color:var(--muted);font-weight:600;min-width:68px;font-size:.73rem;flex-shrink:0;}
.info-val{color:var(--text);font-size:.8rem;}
.estado-chip{display:inline-block;padding:2px 9px;border-radius:11px;font-size:.68rem;font-weight:700;letter-spacing:.4px;margin-left:3px;}
.chip-ocupada{background:#fde8e8;color:#c0392b;}
.chip-libre{background:#e8fdf2;color:#27ae60;}
.chip-apartada{background:#fef3e2;color:#e67e22;}
.chip-bloqueada{background:#dfe6e9;color:#2d3436;}
.slots-disponibles{margin-top:8px;}
.slots-title{font-size:.7rem;font-weight:700;color:var(--muted);letter-spacing:.4px;text-transform:uppercase;margin-bottom:6px;}
.slots-grid{display:flex;flex-wrap:wrap;gap:5px;}
.slot-chip{padding:3px 8px;border-radius:8px;font-size:.67rem;font-weight:700;border:1.5px solid;white-space:nowrap;}
.slot-libre{background:#e8fdf2;color:#27ae60;border-color:#27ae60;}
.slot-ocupado{background:#fde8e8;color:#c0392b;border-color:#e74c3c;opacity:.75;}
.slot-bloqueado{background:#dfe6e9;color:#636e72;border-color:#b2bec3;opacity:.75;}
.btn-apartar{width:100%;padding:9px;background:linear-gradient(135deg,var(--teal),var(--teal-light));color:#fff;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .15s;letter-spacing:.3px;display:block;}
.btn-apartar:hover{opacity:.85;transform:translateY(-1px);}
.btn-bloquear{width:100%;padding:9px;background:linear-gradient(135deg,#636e72,#2d3436);color:#fff;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:opacity .2s;letter-spacing:.3px;display:block;}
.btn-bloquear:hover{opacity:.85;}
.aviso-no-disponible{padding:8px 10px;font-size:.72rem;color:#c0392b;text-align:center;background:#fde8e8;border-radius:8px;display:block;}
.u-legend{display:flex;align-items:center;gap:16px;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:8px 16px;font-size:.73rem;color:var(--muted);width:fit-content;box-shadow:var(--shadow-sm);}
.leg-dot{width:11px;height:11px;border-radius:3px;flex-shrink:0;}
.info-box{background:#e8f4fd;border:1px solid #bee3f8;border-radius:10px;padding:10px 14px;font-size:.77rem;color:var(--teal);margin-top:6px;}
.info-box strong{display:block;margin-bottom:4px;}

/* ── Modal ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center;}
.modal-overlay.active{display:flex;}
.modal-box{background:#fff;border-radius:18px;padding:28px 30px;width:400px;max-width:96vw;box-shadow:0 24px 64px rgba(0,0,0,.22);animation:modalIn .32s var(--ease) forwards;max-height:90vh;overflow-y:auto;}
@keyframes modalIn{from{opacity:0;transform:scale(.86) translateY(18px)}to{opacity:1;transform:scale(1) translateY(0)}}
.modal-title{font-family:'Instrument Serif',serif;font-size:1.05rem;color:var(--teal);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.modal-box label{display:block;font-size:.75rem;font-weight:600;color:var(--muted);margin-bottom:4px;margin-top:10px;}
.modal-box input,.modal-box select{width:100%;padding:8px 10px;border:1.5px solid #c8dce6;border-radius:9px;font-size:.85rem;transition:border-color .2s;}
.modal-box input:focus,.modal-box select:focus{outline:none;border-color:var(--teal);}
.modal-actions{display:flex;gap:10px;margin-top:18px;}
.btn-confirmar{flex:1;padding:9px;background:linear-gradient(135deg,#27ae60,#2ecc71);color:#fff;border:none;border-radius:9px;font-weight:700;cursor:pointer;font-size:.84rem;transition:opacity .2s;}
.btn-confirmar:hover{opacity:.85;}
.btn-cancelar{flex:1;padding:9px;background:#f0f4f8;color:var(--muted);border:1.5px solid #c8dce6;border-radius:9px;font-weight:600;cursor:pointer;font-size:.84rem;transition:background .2s;}
.btn-cancelar:hover{background:#e4ecf2;}
.modal-note{font-size:.68rem;color:#aaa;margin-top:10px;text-align:center;}
.modal-section-title{font-size:.72rem;font-weight:700;color:var(--teal);text-transform:uppercase;letter-spacing:.5px;margin:14px 0 6px;padding-bottom:4px;border-bottom:1.5px solid #e8f2f7;}
.modal-slots-grid{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:4px;}
.modal-slot{padding:5px 10px;border-radius:8px;font-size:.73rem;font-weight:700;cursor:pointer;border:2px solid;transition:all .15s;background:none;}
.modal-slot.disponible{background:#e8fdf2;color:#27ae60;border-color:#27ae60;}
.modal-slot.disponible:hover,.modal-slot.disponible.activo{background:#27ae60;color:#fff;transform:scale(1.05);}
.modal-slot.no-disponible{background:#fde8e8;color:#c0392b;border-color:#e74c3c;cursor:not-allowed;opacity:.6;}
.no-slots-msg{font-size:.75rem;color:#aaa;padding:8px;text-align:center;}
.u-alert{padding:8px 14px;border-radius:9px;font-size:.78rem;margin-bottom:6px;}
.u-alert-ok{background:#e8fdf2;color:#27ae60;border:1px solid #b2dfdb;}
.u-alert-err{background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;}
.u-footer{background:linear-gradient(135deg,#b8d67a,#a5c95e);text-align:center;padding:10px;font-size:.75rem;color:#2d5016;font-weight:600;}

@media(max-width:900px){
    .plants-grid{grid-template-columns:1fr;}
    .aula-row{flex-wrap:wrap;}
}
.planta-alta-grid {
    display: grid;
    grid-template-columns: 90px 1fr;
    grid-template-rows: auto auto auto auto;
    gap: 8px;
}
.cuerpo-acad-bloque {
    grid-column: 1;
    grid-row: 1 / 4;
    border: 1.5px dashed #c8dce6;
    background: #f8fbfd;
    border-radius: 10px;
    padding: 8px 4px;
    font-size: .65rem;
    font-weight: 600;
    text-align: center;
    color: var(--muted);
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1.4;
    min-height: 180px;  /* reducido de 220px */
    writing-mode: vertical-rl;  /* ← texto vertical */
    text-orientation: mixed;
    cursor: pointer;
}
.alta-col-right { grid-column: 2; display: flex; flex-direction: column; gap: 8px; }

/* Label-box clicable para galería */
.label-box-galeria {
    cursor: pointer;
    transition: background .2s, box-shadow .2s, transform .15s;
    position: relative;
}
.label-box-galeria:hover {
    background: #dff0f7;
    box-shadow: 0 4px 12px rgba(55,96,115,.18);
    transform: translateY(-2px);
    border-color: var(--teal);
    color: var(--teal);
}

/* Modal galería — reutilizado del mapa */
.gallery-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.75);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: flex; align-items: flex-start; justify-content: center;
    padding: 20px; overflow-y: auto;
    opacity: 0; pointer-events: none;
    transition: opacity .35s ease;
}
.gallery-overlay.active { opacity: 1; pointer-events: all; }
.gallery-modal {
    background: #fff; border-radius: 22px;
    width: 100%; max-width: 900px;
    box-shadow: 0 30px 80px rgba(0,0,0,.35);
    transform: translateY(40px) scale(.97);
    transition: transform .4s var(--ease), opacity .35s ease;
    opacity: 0; overflow: hidden; margin: auto;
}
.gallery-overlay.active .gallery-modal { transform: translateY(0) scale(1); opacity: 1; }
.gallery-modal-header {
    background: linear-gradient(135deg,#2c4f60,#376073 55%,#5a8fa3);
    padding: 20px 24px;
    display: flex; align-items: center; gap: 14px; position: relative; overflow: hidden;
}
.gallery-modal-icon { width:42px;height:42px;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0; }
.gallery-modal-title { font-family:'Instrument Serif',serif;font-size:1.3rem;color:#fff;flex:1; }
.gallery-modal-sub { font-size:.74rem;color:rgba(255,255,255,.7);margin-top:2px; }
.gallery-modal-close { background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;width:36px;height:36px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s,transform .15s; }
.gallery-modal-close:hover { background:rgba(255,255,255,.28);transform:rotate(90deg); }
.gallery-body { padding: 22px 24px; }
.gallery-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px; }
.gallery-card { border-radius:14px;overflow:hidden;background:#f8fafc;border:1px solid rgba(55,96,115,.1);box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .3s var(--ease),box-shadow .3s;cursor:pointer; }
.gallery-card:hover { transform:translateY(-6px) scale(1.02);box-shadow:0 12px 32px rgba(0,0,0,.15); }
.gallery-card-img { width:100%;height:160px;object-fit:cover;display:block;background:linear-gradient(135deg,#e8f3f8,#d5eaee); }
.gallery-card-info { padding:12px 14px; }
.gallery-card-title { font-weight:700;font-size:.82rem;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.gallery-card-desc { font-size:.73rem;color:var(--muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
.gallery-card-meta { font-size:.68rem;color:#a0b8c8;margin-top:8px; }
.gallery-card-actions { display:flex;gap:6px;padding:0 14px 12px; }
.btn-del-photo { flex:1;padding:6px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:7px;font-size:.7rem;font-weight:700;cursor:pointer; }
.btn-del-photo:hover { background:#c0392b;color:#fff; }
.gallery-empty { text-align:center;padding:48px 20px;color:var(--muted);font-size:.85rem; }
.btn-add-photo { display:flex;align-items:center;gap:8px;padding:10px 18px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border:none;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;margin-bottom:18px;transition:opacity .2s,transform .15s; }
.btn-add-photo:hover { opacity:.9;transform:translateY(-1px); }
.upload-form { background:linear-gradient(135deg,#f0f7fa,#e4f0f7);border:1.5px solid rgba(55,96,115,.2);border-radius:14px;padding:20px;margin-bottom:20px;display:none; }
.upload-form.open { display:block; }
.upload-form label { display:block;font-size:.74rem;font-weight:700;color:var(--teal-dark);margin-bottom:5px;margin-top:12px; }
.upload-form label:first-child { margin-top:0; }
.upload-form input[type=text], .upload-form textarea { width:100%;padding:9px 12px;border:1.5px solid rgba(55,96,115,.25);border-radius:9px;font-size:.82rem;background:#fff;outline:none;transition:border-color .2s; }
.upload-form input[type=text]:focus, .upload-form textarea:focus { border-color:var(--teal); }
.upload-form textarea { resize:vertical;min-height:72px; }
.upload-form input[type=file] { width:100%;padding:9px 12px;border:1.5px dashed rgba(55,96,115,.35);border-radius:9px;font-size:.78rem;background:#fff;cursor:pointer; }
.upload-form-actions { display:flex;gap:10px;margin-top:16px; }
.btn-upload-submit { flex:1;padding:10px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border:none;border-radius:9px;font-weight:700;font-size:.82rem;cursor:pointer; }
.btn-upload-cancel { flex:1;padding:10px;background:#fff;color:var(--teal);border:1.5px solid rgba(55,96,115,.3);border-radius:9px;font-weight:600;font-size:.82rem;cursor:pointer; }
/* Lightbox */
.lightbox-overlay { position:fixed;inset:0;background:rgba(0,0,0,.92);backdrop-filter:blur(12px);z-index:2000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .3s ease; }
.lightbox-overlay.active { opacity:1;pointer-events:all; }
.lightbox-content { max-width:800px;width:100%;transform:scale(.9);transition:transform .4s var(--ease); }
.lightbox-overlay.active .lightbox-content { transform:scale(1); }
.lightbox-img { width:100%;max-height:65vh;object-fit:contain;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.6);display:block; }
.lightbox-info { background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px 20px;margin-top:14px; }
.lightbox-title { font-family:'Instrument Serif',serif;font-size:1.1rem;color:#fff;margin-bottom:4px; }
.lightbox-desc { font-size:.8rem;color:rgba(255,255,255,.7);line-height:1.6; }
.lightbox-close { position:absolute;top:20px;right:24px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);color:#fff;width:40px;height:40px;border-radius:50%;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s; }
.lightbox-close:hover { background:rgba(255,255,255,.24);transform:rotate(90deg); }
.toast { position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--teal-dark);color:#fff;padding:12px 22px;border-radius:30px;font-size:.82rem;font-weight:600;z-index:3000;opacity:0;transition:opacity .3s,transform .3s var(--ease);pointer-events:none;white-space:nowrap; }
.toast.show { opacity:1;transform:translateX(-50%) translateY(0); }
.strip-action-btn{
    appearance:none;
    border:none;
    background:transparent;
    color:var(--teal);
    font-size:.78rem;
    font-weight:800;
    cursor:pointer;
    padding:2px 4px;
    border-radius:8px;
    line-height:1.2;
    transition:.18s ease;
}

.strip-action-btn:hover{
    background:rgba(11, 140, 140, .08);
    color:var(--teal-dark, #086c6c);
}

.strip-action-btn:focus-visible{
    outline:none;
    box-shadow:0 0 0 2px rgba(11,140,140,.18);
}
</style>
@endsection

@section('content')
<div style="display:flex;flex-direction:column;min-height:100vh;">

<header class="u-header">
    <div class="u-logo">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <span class="u-brand">Ubicatec</span>
    <div class="u-search">
        <input id="buscador" type="search" placeholder="🔍  Buscar aula, grupo, docente…">
    </div>
    @if(session('usuario'))
    @php $rol = session('usuario')->rol ?? 'estudiante'; @endphp
        @if($rol === 'administrador')
            <span class="role-badge role-admin">⚙️ Admin</span>
        @elseif($rol === 'jefe_departamento')
            <span class="role-badge role-jefe">🏛️ Jefe Dpto.</span>
        @elseif($rol === 'docente')
            <span class="role-badge role-docente">👨‍🏫 Docente</span>
        @else
            <span class="role-badge role-estudiante">🎓 Estudiante</span>
        @endif
    @endif
    <a href="{{ route('mapa.index') }}" class="u-back">← Volver al mapa</a>
</header>

@if(session('success'))
    <div class="u-alert u-alert-ok" style="margin:10px 24px 0">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="u-alert u-alert-err" style="margin:10px 24px 0">⚠️ {{ session('error') }}</div>
@endif

<div class="u-main">

    {{-- Strip de estado --}}
    <div class="u-strip">
        <span class="day-chip">{{ $dia }}</span>
        <span class="strip-sep">|</span>
        🕐 <strong id="hora-display"></strong>
        <span class="strip-sep">|</span>
        🟢 Libres: <strong id="cont-libres">0</strong>
        <span class="strip-sep">|</span>
        🔴 Ocupadas: <strong id="cont-ocupadas">0</strong>
        <span class="strip-sep">|</span>
        🟠 Apartadas: <strong id="cont-apartadas">0</strong>
        @php
            $rol = session('usuario')->rol ?? 'estudiante';
        @endphp

        @if(in_array($rol, ['administrador', 'jefe_departamento', 'docente']))
            <span class="strip-sep">|</span>
            <button type="button" class="strip-action-btn" onclick="abrirModalReservas()">
                📋 Mis reservaciones
            </button>
        @endif
        <div class="hora-slider-wrap" style="margin-left:auto;">
            <label>🕐 Ver a las:</label>
            <input type="range" id="sliderHora" min="420" max="1320" step="30"
                   value="0">
            <span id="sliderHoraLabel">{{ substr($hora,0,5) }}</span>
            <span id="badge-simulada" class="hora-simulada-badge">🔮 Simulando</span>
            <button type="button" onclick="resetSlider()" title="Volver a ahora"
                style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--muted);padding:0 2px;">⟳</button>
        </div>
        <div class="hora-slider-wrap" style="margin-left:8px;">
            <label>Ver día:</label>
            <select id="selectorDia" style="
                border:none;background:transparent;font-size:.78rem;
                font-weight:700;color:var(--teal);cursor:pointer;outline:none;
                padding:2px 4px;
            ">
                <option value="LUNES">Lunes</option>
                <option value="MARTES">Martes</option>
                <option value="MIERCOLES">Miércoles</option>
                <option value="JUEVES">Jueves</option>
                <option value="VIERNES">Viernes</option>
                <option value="SABADO">Sábado</option>
            </select>
            <span id="badge-dia-simulado" class="hora-simulada-badge" style="display:none;">
                Simulando día
            </span>
            <button type="button" onclick="resetDia()" title="Volver a ahora"
                style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--muted);padding:0 2px;">⟳</button>
        
        </div>
    </div>
    <div class="u-legend" style="margin-left:8px;">
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#27ae60"></span>Libre</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#e74c3c"></span>Ocupada</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#f39c12"></span>Apartada</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#636e72"></span>Bloqueada</span>
        </div>

    <div class="plants-grid">

        {{-- ══════════════════════════════
            PLANTA BAJA — EDIFICIO B
            EB1 | EB2 | [Lobby] | EB3 | EB4 | EB5
            EB6 | Biblioteca | Escaleras | Cuerpo Acad. | Baños | EB8 | EB9
        ══════════════════════════════ --}}
        <div class="plant-block" id="planta-baja-b">
            <div class="plant-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="13" rx="2"/><path d="M3 9l9-5 9 5"/></svg>
                Planta Baja — Edificio B
            </div>

            {{-- Fila 1: EB1 EB2 [Lobby] EB3 EB4 EB5 --}}
            <div class="aula-row">
                <div class="aula-box" data-aula="EB01"><span class="aula-icon">🚪</span>EB1</div>
                <div class="aula-box" data-aula="EB02"><span class="aula-icon">🚪</span>EB2</div>
                <div class="spacer">
                    <div class="label-box label-box-galeria" data-area="lobby-b" title="Ver galería">
                        🏛️ Lobby
                    </div>
                </div>
                <div class="aula-box" data-aula="EB03"><span class="aula-icon">🚪</span>EB3</div>
                <div class="aula-box" data-aula="EB04"><span class="aula-icon">🚪</span>EB4</div>
                <div class="aula-box" data-aula="EB05"><span class="aula-icon">🚪</span>EB5</div>
            </div>

            {{-- Panel info --}}
            <div class="panel-info" id="panel-baja-b">
                <div class="panel-placeholder">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span>Selecciona un aula</span>
                </div>
            </div>
            <div id="accion-baja-b" style="display:none;"></div>

            {{-- Fila 2: EB6 Biblioteca Escaleras CuerpoAcad Baños EB8 EB9 --}}
            <div class="aula-row">
                <div class="aula-box" data-aula="EB06"><span class="aula-icon">🚪</span>EB6</div>
                <div class="label-box label-box-galeria" data-area="biblioteca-b" style="flex:none;padding:6px 10px;" title="Ver galería">
                📚 Biblioteca
            </div>
            <div class="label-box label-box-galeria" data-area="escaleras-baja-b" style="flex:none;padding:6px 8px;" title="Ver galería">
                🪜 Esc. P.Alta
            </div>
            <div class="label-box label-box-galeria" data-area="cuerpo-acad-b" style="flex:none;padding:6px 8px;" title="Ver galería">
                🏛️ Cuerpo Acad.
            </div>
                <div class="aula-box" data-aula="Banios-BAJA-B" data-tipo="banio"><span class="aula-icon">🚻</span>Baños</div>
                <div style="width:62px;flex-shrink:0;"></div>
                <div class="aula-box" data-aula="EB08"><span class="aula-icon">🚪</span>EB8</div>
                <div class="aula-box" data-aula="EB09"><span class="aula-icon">🚪</span>EB9</div>
            </div>
        </div>

        {{-- ══════════════════════════════
            PLANTA ALTA — EDIFICIO B
            Columna izquierda: Cuerpo académico (abarca filas 1-3)
            Fila 1 (top-right): [espacio] + Lab Cisco + Lab Fisep
            Fila 2: EB10 | EB11 | Área Docentes | EB12 | EBC2(CC2) | NanoCubo | Área Docentes
            Fila 3: EB13 | Sala maestros | EB14 | Papelería | Esc.P.Baja | Baños | EBRD(CC3) | EB15
        ══════════════════════════════ --}}
        <div class="plant-block" id="planta-alta-b">
    <div class="plant-title">
        <svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="13" rx="2"/><path d="M3 9l9-5 9 5"/><line x1="12" y1="4" x2="12" y2="2"/></svg>
        Planta Alta — Edificio B
    </div>

    {{-- 
        Layout visual:
        [Cuerpo Acad.]  [EB10][EB11][LabCisco][LabFisep][EBC2][NanoCubo][ÁreaDoc]
        [Cuerpo Acad.]  [EB10][EB11][ÁreaDoc ][EB12    ][EBC2][NanoCubo][ÁreaDoc]
        [Cuerpo Acad.]  [panel-info]
        [Cuerpo Acad.]  [EB13][SalaMaest][EB14][Papelería][Esc.][Baños][CC3][EB15]
    --}}

    <div style="display:flex; gap:8px; align-items:stretch;">

        {{-- Columna izquierda: Cuerpo Académico vertical, ocupa todo el alto --}}
        <div class="label-box-galeria" data-area="cuerpo-acad-alta-b" title="Ver galería"
            style="
                flex-shrink:0; width:46px;
                border:1.5px dashed #c8dce6; background:#f8fbfd; border-radius:10px;
                padding:10px 4px; font-size:.64rem; font-weight:700;
                text-align:center; color:var(--muted);
                display:flex; align-items:center; justify-content:center;
                writing-mode:vertical-rl; text-orientation:mixed;
                letter-spacing:.5px; cursor:pointer; line-height:1.6;
            ">
            🏛️ Cuerpo Académico · Atención a Empresas
        </div>

        {{-- Columna derecha: todas las filas --}}
        <div style="flex:1; display:flex; flex-direction:column; gap:8px;">

            {{-- Fila superior: Labs arriba de su respectivo lugar --}}
            {{-- 
                Posición visual:
                [EB10][EB11] [LabCisco][LabFisep] [EBC2][NanoCubo][ÁreaDoc]
                             [ÁreaDoc ][EB12     ]
            --}}
            <div style="display:flex; gap:8px; align-items:flex-end;">

                {{-- EB10 y EB11 sin nada encima --}}
                <div style="display:flex; flex-direction:column; gap:4px; justify-content:flex-end;">
                    <div style="height:56px;"></div>{{-- espacio vacío arriba para alinear --}}
                    <div style="display:flex; gap:8px;">
                        <div class="aula-box" data-aula="EB10"><span class="aula-icon">🚪</span>EB10</div>
                        <div class="aula-box" data-aula="EB11"><span class="aula-icon">🚪</span>EB11</div>
                    </div>
                </div>

                {{-- Stack: [Lab Cisco][Lab Fisep] encima de [Área Docentes][EB12] --}}
                <div style="display:flex; flex-direction:column; gap:4px;">
                    {{-- Labs arriba --}}
                    <div style="display:flex; gap:8px;">
                        <div class="aula-box" data-aula="ECSI"
                            style="width:62px;height:52px;font-size:.61rem;">
                            <span class="aula-icon">🖧</span>Lab Cisco
                        </div>
                        <div class="aula-box" data-aula="EBUM"
                            style="width:62px;height:52px;font-size:.61rem;">
                            <span class="aula-icon">🔬</span>Lab Fisep
                        </div>
                    </div>
                    {{-- Área docentes + EB12 abajo --}}
                    <div style="display:flex; gap:8px;">
                        <div class="label-box label-box-galeria" data-area="area-docentes-b1"
                            style="width:62px;flex:none;padding:6px 4px;font-size:.63rem;text-align:center;" title="Ver galería">
                            👩‍🏫 Área<br>Docentes
                        </div>
                        <div class="aula-box" data-aula="EB12" style="width:62px;">
                            <span class="aula-icon">🚪</span>EB12
                        </div>
                    </div>
                </div>

                {{-- EBC2, NanoCubo, Área Docentes alineados abajo --}}
                <div style="display:flex; flex-direction:column; gap:4px; justify-content:flex-end;">
                    <div style="height:56px;"></div>{{-- espacio vacío arriba --}}
                    <div style="display:flex; gap:8px;">
                        <div class="aula-box" data-aula="EBC2"><span class="aula-icon">💻</span>CC2</div>
                        <div class="label-box label-box-galeria" data-area="nanocubo-b"
                            style="flex:none;padding:6px 8px;font-size:.65rem;" title="Ver galería">
                            🧊 Nano<br>Cubo
                        </div>
                        <div class="label-box label-box-galeria" data-area="area-docentes-b2"
                            style="flex:none;padding:6px 8px;font-size:.65rem;" title="Ver galería">
                            👩‍🏫 Área<br>Docentes
                        </div>
                    </div>
                </div>

            </div>{{-- /fila superior --}}

            {{-- Panel info planta alta --}}
            <div class="panel-info" id="panel-alta-b">
                <div class="panel-placeholder">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span>Selecciona un aula</span>
                </div>
            </div>
            <div id="accion-alta-b" style="display:none;"></div>

            {{-- Fila 3: EB13 | Sala maestros | EB14 | Papelería | Esc.P.Baja | Baños | CC3 | EB15 --}}
            <div class="aula-row">
                <div class="aula-box" data-aula="EB13"><span class="aula-icon">🚪</span>EB13</div>
                <div class="label-box label-box-galeria" data-area="sala-maestros-b"
                    style="flex:none;padding:6px 6px;font-size:.63rem;" title="Ver galería">
                    👨‍🏫 Sala<br>maestros
                </div>
                <div class="aula-box" data-aula="EB14"><span class="aula-icon">🚪</span>EB14</div>
                <div class="label-box label-box-galeria" data-area="papeleria-b"
                    style="flex:none;padding:6px 6px;font-size:.63rem;" title="Ver galería">
                    🛍️ Papelería
                </div>
                <div class="label-box label-box-galeria" data-area="escaleras-alta-b"
                    style="flex:none;padding:6px 6px;font-size:.63rem;" title="Ver galería">
                    🪜 Esc.<br>P.Baja
                </div>
                <div class="aula-box" data-aula="Banios-ALTA-B" data-tipo="banio">
                    <span class="aula-icon">🚻</span>Baños
                </div>
                <div class="aula-box" data-aula="EBRD"><span class="aula-icon">💻</span>CC3</div>
                <div class="aula-box" data-aula="EB15"><span class="aula-icon">🚪</span>EB15</div>
            </div>

        </div>{{-- /col derecha --}}
    </div>{{-- /flex principal --}}

</div>{{-- /planta-alta-b --}}

</div>{{-- /plants-grid --}}
</div>{{-- /u-main --}}

<footer class="u-footer">
    © 2025 Ubicatec — Edificio B &nbsp;·&nbsp; {{ $dia }} {{ substr($hora,0,5) }}
</footer>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL APARTAR / RESERVAR
══════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalApartar">
    <div class="modal-box">
        <div class="modal-title">
            <span id="modal-icono">📌</span>&nbsp;
            <span id="modal-titulo-texto">Reservar Aula</span>
            <span id="modal-aula-nombre" style="color:var(--teal-light);font-size:.9rem;font-family:'Inter',sans-serif;margin-left:4px;"></span>
        </div>
        <div id="modal-apartados-existentes" style="display:none;background:#fef3e2;border:1px solid #f39c12;border-radius:8px;padding:8px 12px;font-size:.73rem;color:#b7770d;margin-bottom:8px;"></div>
        <form method="POST" action="{{ route('aulas.apartar') }}" id="formApartar">
            @csrf
            <input type="hidden" name="aula"         id="modal-aula-input">
            <input type="hidden" name="hora_inicio"  id="modal-hora-inicio-hidden">
            <input type="hidden" name="hora_fin"     id="modal-hora-fin-hidden">
            <input type="hidden" name="override"     id="modal-override" value="0">
            <input type="hidden" name="tipo_bloqueo" id="modal-tipo-bloqueo" value="0">

            <label>Día de la semana</label>
            <select name="dia" id="modal-dia" onchange="renderSlotsModal()">
                @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $d)
                    <option value="{{ $d }}" {{ $d === $dia ? 'selected' : '' }}>
                        {{ $d }}{{ $d === $dia ? ' (hoy)' : '' }}
                    </option>
                @endforeach
            </select>

            <div id="seccion-slots">
                <div class="modal-section-title">🕒 Selecciona un horario</div>
                <div id="modal-slots-container"><p class="no-slots-msg">Cargando…</p></div>
            </div>

            <div id="seccion-hora-manual" style="display:none;">
                <div class="modal-section-title" style="color:#b7770d;">✏️ Horario personalizado</div>
                <div style="display:flex;gap:10px;">
                    <div style="flex:1;"><label>Hora inicio</label><input type="time" id="manual-hora-inicio" value="07:00"></div>
                    <div style="flex:1;"><label>Hora fin</label><input type="time" id="manual-hora-fin" value="09:00"></div>
                </div>
                <button type="button" onclick="aplicarHoraManual()"
                    style="margin-top:6px;width:100%;padding:7px;background:#f39c12;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.77rem;">
                    📋 Aplicar horario personalizado
                </button>
            </div>

            <div id="seccion-datos-reserva" style="display:none;">
                <div class="modal-section-title">📝 Datos</div>
                <div id="campos-aula">
                    <label>Solicitante</label>
<input type="text" name="nombre_solicitante" id="modal-solicitante" placeholder="Ej: Marco">

            <label>Asunto</label>
            <input type="text" name="materia_reserva" id="modal-asunto" placeholder="Ej: Proyectos integradores"><div id="resumen-horas" style="display:none;margin-top:8px;padding:7px 10px;background:#e8f4fd;border-radius:8px;font-size:.73rem;color:var(--teal);">
                        ⏱️ <strong id="duracion-texto">—</strong>
                    </div>
                </div>
                <div id="campos-banio" style="display:none;">
                    <label>Motivo</label>
                    <input type="text" name="nombre_solicitante" placeholder="Ej: Limpieza, Mantenimiento…">
                </div>
            </div>

            <div id="modal-preview" style="display:none;margin-top:11px;padding:8px 12px;background:#e8f4fd;border-radius:8px;font-size:.73rem;color:var(--teal);"></div>
            <div id="modal-error"   style="display:none;margin-top:8px;padding:8px 12px;background:#fde8e8;border:1px solid #e74c3c;border-radius:8px;font-size:.75rem;color:#c0392b;"></div>

            <div class="modal-actions">
                <button type="submit" class="btn-confirmar">✅ Confirmar</button>
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            </div>
        </form>
        <p class="modal-note" id="modal-nota">⏳ El apartado expira al inicio de la siguiente semana</p>
    </div>
</div>
{{-- MODAL GALERÍA ÁREAS --}}
<div class="gallery-overlay" id="galleryOverlay">
    <div class="gallery-modal" id="galleryModal">
        <div class="gallery-modal-header">
            <div class="gallery-modal-icon" id="galleryModalIcon">🏛️</div>
            <div>
                <div class="gallery-modal-title" id="galleryModalTitle">Galería</div>
                <div class="gallery-modal-sub" id="galleryModalSub">Imágenes del área</div>
            </div>
            <button class="gallery-modal-close" id="galleryModalClose" aria-label="Cerrar">✕</button>
        </div>
        <div class="gallery-body">
            @if(session('usuario')?->rol === 'administrador')
            <button class="btn-add-photo" id="btnToggleForm">
                ➕ Agregar fotografía
            </button>
            <div class="upload-form" id="uploadForm">
                <form id="formSubirFoto" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="area" id="formArea">
                    <label>Título de la imagen</label>
                    <input type="text" name="titulo" id="inputTitulo" placeholder="Ej: Entrada principal" required>
                    <label>Descripción</label>
                    <textarea name="descripcion" id="inputDesc" placeholder="Describe qué muestra esta foto..."></textarea>
                    <label>Imagen</label>
                    <input type="file" name="imagen" id="inputImagen" accept="image/*" required>
                    <div class="upload-form-actions">
                        <button type="submit" class="btn-upload-submit">Guardar imagen</button>
                        <button type="button" class="btn-upload-cancel" id="btnCancelForm">Cancelar</button>
                    </div>
                </form>
            </div>
            @endif
            <div id="galleryGrid" class="gallery-grid"></div>
            <div id="galleryEmpty" class="gallery-empty" style="display:none;">
                <p>
                    @if(session('usuario')?->rol === 'administrador')
                        Sin fotos aún. Usa el botón de arriba para agregar la primera imagen.
                    @else
                        Próximamente se agregarán imágenes de esta área.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- LIGHTBOX --}}
<div class="lightbox-overlay" id="lightboxOverlay" style="position:fixed;">
    <button class="lightbox-close" id="lightboxClose">✕</button>
    <div class="lightbox-content">
        <img src="" alt="" id="lightboxImg" class="lightbox-img">
        <div class="lightbox-info">
            <div class="lightbox-title" id="lightboxTitle"></div>
            <div class="lightbox-desc" id="lightboxDesc"></div>
        </div>
    </div>
</div>
<div class="modal-overlay" id="modalReservas">
    <div class="modal-box" style="max-width:760px;">
        <div class="modal-title">📋 Reservaciones de la semana</div>
        <div id="reservas-semana-info" style="margin-bottom:10px;color:var(--muted);font-size:.78rem;"></div>
        <div id="reservas-listado">
            <p style="color:#888;">Cargando…</p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancelar" onclick="cerrarModalReservas()">Cerrar</button>
        </div>
    </div>
</div>
{{-- TOAST --}}
<div class="toast" id="toastMsg"></div>
@endsection

@section('scripts')
<script>
const HORARIOS   = @json($horarios);
const APARTADOS  = @json($apartados);
const DIA_REAL = "{{ $dia }}";
let DIA_HOY = DIA_REAL;
// Al cargar, selecciona el día actual
const selectorDia = document.getElementById('selectorDia');
const badgeDia    = document.getElementById('badge-dia-simulado');

selectorDia.value = DIA_REAL;

selectorDia.addEventListener('change', () => {
    DIA_HOY = selectorDia.value;
    badgeDia.style.display = (DIA_HOY !== DIA_REAL) ? 'inline-block' : 'none';
    colorear();
    
    // Si hay aula seleccionada, refresca el panel
    if (aulaActiva && plantaActiva) {
        const panel  = document.getElementById('panel-' + plantaActiva);
        const accion = document.getElementById('accion-' + plantaActiva);
        panel.innerHTML  = panelHTML(aulaActiva);
        accion.innerHTML = accionHTML(aulaActiva);
        accion.style.display = accionHTML(aulaActiva) ? 'block' : 'none';
    }
});
// Reemplaza la línea estática de HORA_REAL_MIN
const _now = new Date();
let HORA_REAL_MIN = _now.getHours() * 60 + _now.getMinutes();

// Actualiza cada minuto automáticamente
setInterval(() => {
    const n = new Date();
    const minActual = n.getHours() * 60 + n.getMinutes();
    
    // Solo actualiza si cambió el minuto
    if (minActual !== HORA_REAL_MIN) {
        HORA_REAL_MIN = minActual;
        
        // Si el slider está en "ahora" (no simulando), lo mueve con la hora real
        if (horaMin === HORA_REAL_MIN - 1 || badgeSim.style.display === 'none') {
            resetSlider(); // mueve slider a la hora actual
        } else {
            colorear(); // solo recolorea sin mover el slider
        }
    }
}, 10000); // cada 10 segundos revisa (más ligero que cada segundo)
const ROL = "{{ session('usuario') ? (session('usuario')->rol ?? 'estudiante') : 'estudiante' }}";
const ES_ADMIN     = ['admin','administrador','jefe_departamento'].includes(ROL);
const ES_DOCENTE   = ROL === 'docente';
const ES_ESTUDIANTE = ROL === 'estudiante';
const JORNADA_INI = 420;
const JORNADA_FIN = 1320;

let horaMin = HORA_REAL_MIN;
let aulaActiva   = null;
let plantaActiva = null;

/* ── Utilidades ── */
function norm(s){ return (s??'').toString().trim().toUpperCase(); }
function hToMin(h){ if(!h) return 0; const p=h.toString().split(':'); return +p[0]*60+ +p[1]; }
function minToH(m){ return String(Math.floor(m/60)).padStart(2,'0')+':'+String(m%60).padStart(2,'0'); }

/**
 * Normaliza los códigos del Edificio B para que coincidan con la BD.
 * Ajusta según los nombres reales que uses en tu tabla de horarios.
 */
function normAula(raw){
    const a = norm(raw).replace(/\s+/g,'');
    const map = {
        'EB01':'EB01','EB02':'EB02','EB03':'EB03','EB04':'EB04','EB05':'EB05',
        'EB06':'EB06','EB08':'EB08','EB09':'EB09',
        'EB10':'EB10','EB11':'EB11','EB12':'EB12',
        'EB13':'EB13','EB14':'EB14','EB15':'EB15',
        'CC2':'CC2','CC3':'CC3','ECSI':'ECSI',
        'EBUM': 'EBUM',  // Lab Fisep
        'ECSI': 'ECSI',
    };
    return map[a] ?? a;
}

function esBanio(raw){ const a=norm(raw); return a.includes('BANIO')||a.includes('BAÑO'); }

/* ── Consultas de estado ── */
function getBloqueado(raw){
    const bd=normAula(raw);
    return APARTADOS.find(ap=>normAula(ap.aula)===bd&&norm(ap.dia)===norm(DIA_HOY)&&ap.tipo_bloqueo==1&&hToMin(ap.hora_inicio)<=horaMin&&horaMin<hToMin(ap.hora_fin))||null;
}
function getApartado(raw){
    const bd=normAula(raw);
    return APARTADOS.find(ap=>normAula(ap.aula)===bd&&norm(ap.dia)===norm(DIA_HOY)&&ap.tipo_bloqueo!=1&&hToMin(ap.hora_inicio)<=horaMin&&horaMin<hToMin(ap.hora_fin))||null;
}
function getClase(raw){
    const bd=normAula(raw);
    return HORARIOS.find(h=>normAula(h.aula)===bd&&norm(h.dia)===norm(DIA_HOY)&&hToMin(h.hora_inicio)<=horaMin&&horaMin<hToMin(h.hora_fin))||null;
}
function isOcupada(raw){ return !!getClase(raw); }
function isApartada(raw){ return !!getApartado(raw); }
function isBloqueada(raw){ return !!getBloqueado(raw); }

/* ── Slots del día ── */
function getSlots(raw, dia){
    const bd=normAula(raw), d=norm(dia);
    const bloques=[
        ...HORARIOS.filter(h=>normAula(h.aula)===bd&&norm(h.dia)===d)
            .map(h=>({hi:hToMin(h.hora_inicio),hf:hToMin(h.hora_fin),libre:false,label:h.materia,tipo:'clase'})),
        ...APARTADOS.filter(ap=>normAula(ap.aula)===bd&&norm(ap.dia)===d)
            .map(ap=>({hi:hToMin(ap.hora_inicio),hf:hToMin(ap.hora_fin),libre:false,
                label:ap.nombre_solicitante??'Apartado',tipo:ap.tipo_bloqueo==1?'bloqueado':'apartado'}))
    ].sort((a,b)=>a.hi-b.hi);
    const slots=[]; let cur=JORNADA_INI;
    for(const b of bloques){
        if(b.hi>cur) slots.push({hi:cur,hf:b.hi,libre:true});
        slots.push(b);
        cur=Math.max(cur,b.hf);
    }
    if(cur<JORNADA_FIN) slots.push({hi:cur,hf:JORNADA_FIN,libre:true});
    return slots;
}

/* ── HTML del panel ── */
function panelHTML(raw){
    const bd=normAula(raw), banio=esBanio(raw);
    const bloq=getBloqueado(raw), apart=getApartado(raw), clase=getClase(raw);
    const slots=getSlots(raw,DIA_HOY), futuros=slots.filter(s=>s.hf>horaMin);
     if(bloq){
        chip  = `<span class="estado-chip chip-bloqueada">🔒 NO DISPONIBLE</span>`;
        extra = `<div class="info-row"><span class="info-label">Motivo:</span><span class="info-val">${bloq.nombre_solicitante??bloq.label??'Sin especificar'}</span></div>`;
    } else if(apart){
        chip = `<span class="estado-chip chip-apartada">APARTADA</span>`;
    extra = `
        <div class="info-row">
            <span class="info-label">Horario:</span>
            <span class="info-val">${apart.hora_inicio.slice(0,5)} – ${apart.hora_fin.slice(0,5)}</span>
        </div>
        ${apart.materia_reserva ? `
        <div class="info-row">
            <span class="info-label">Asunto:</span>
            <span class="info-val">${apart.materia_reserva}</span>
        </div>` : ''}
        ${apart.nombre_solicitante ? `
        <div class="info-row">
            <span class="info-label">Solicitante:</span>
            <span class="info-val">${apart.nombre_solicitante}</span>
        </div>` : ''}`;
    } else if(clase){
        chip  = `<span class="estado-chip chip-ocupada">OCUPADA</span>`;
        extra = `<div class="info-row"><span class="info-label">Materia:</span><span class="info-val">${clase.materia}</span></div>
<div class="info-row">
    <span class="info-label">Grupo</span>
    <span class="info-val">
        <strong>${clase.grupo}</strong>
        ${traducirGrupo(clase.grupo) ?
            `<small style="color:var(--muted);margin-left:4px;">(${traducirGrupo(clase.grupo)})</small>`
            : ''}
    </span>
</div>                 <div class="info-row"><span class="info-label">Docente:</span><span class="info-val">${clase.docente??'Sin registrar'}</span></div>
                <div class="info-row"><span class="info-label">Horario:</span><span class="info-val">${clase.hora_inicio.slice(0,5)} – ${clase.hora_fin.slice(0,5)}</span></div>`;
    } else {
        chip  = `<span class="estado-chip chip-libre">DISPONIBLE</span>`;
        extra = `<div class="info-row"><span class="info-val" style="color:#27ae60">✅ Sin actividad ahora</span></div>`;
    }

    if(banio){
        return `<div class="panel-content">
            <div class="info-row"><span class="info-label">Baño:</span>
                <span class="info-val"><strong>${bd}</strong>${chip}</span></div>
            ${extra}
        </div>`;
    }

    let slotsHTML='';
    if(!ES_ESTUDIANTE && futuros.length>0){
        slotsHTML=`<div class="slots-disponibles">
            <div class="slots-title">Horarios del día</div>
            <div class="slots-grid">
                ${futuros.slice(0,8).map(s=>{
                    const cls=s.libre?'slot-libre':(s.tipo==='bloqueado'?'slot-bloqueado':'slot-ocupado');
                    const lbl=s.libre?'✅ Libre':(s.tipo==='bloqueado'?'🔒':(s.label?.slice(0,14)??'Ocupado'));
                    return `<span class="slot-chip ${cls}">${minToH(s.hi)}-${minToH(s.hf)} <small>${lbl}</small></span>`;
                }).join('')}
            </div>
        </div>`;
    }

    let estudHTML='';
    if(ES_ESTUDIANTE){
        const prox=slots.filter(s=>s.libre&&s.hi>=horaMin).slice(0,3);
        estudHTML=`<div class="info-box" style="margin-top:8px;">
            <strong>📅 Próximas disponibilidades:</strong>
            ${prox.length>0
                ? prox.map(s=>`<span style="display:inline-block;margin:2px 4px 2px 0;padding:2px 8px;background:#e8fdf2;border-radius:6px;font-size:.7rem;color:#27ae60;font-weight:700;">${minToH(s.hi)}-${minToH(s.hf)}</span>`).join('')
                : '<span style="color:#aaa">Sin disponibilidad hoy</span>'}
        </div>`;
    }

    return `<div class="panel-content">
        <div class="info-row"><span class="info-label">Aula:</span>
            <span class="info-val"><strong>${bd}</strong>${chip}</span></div>
        ${extra}${slotsHTML}${estudHTML}
    </div>`;
}

/* ── HTML del botón de acción ── */
function accionHTML(raw){
    const banio=esBanio(raw), bloq=isBloqueada(raw), clase=getClase(raw);
    const slots=getSlots(raw,DIA_HOY), hayLibre=slots.some(s=>s.libre&&s.hf>horaMin);
    if(ES_ESTUDIANTE) return '';
    if(banio){
        if(!ES_ADMIN) return '';
        return bloq
            ? `<button class="btn-apartar" onclick="abrirModal('${raw}',true)">🔧 Modificar estado del baño</button>`
            : `<button class="btn-bloquear" onclick="abrirModal('${raw}',true)">🚫 Marcar como no disponible</button>`;
    }
    if(ES_ADMIN)
        return `<button class="btn-apartar" onclick="abrirModal('${raw}',false)">📌 Apartar / Reservar aula</button>`;
    if(ES_DOCENTE){
        if(!clase && !bloq)
            return `<button class="btn-apartar" onclick="abrirModal('${raw}',false)">📌 Apartar aula</button>`;
        return `<div class="aviso-no-disponible">⛔ Ocupada en este horario
            ${hayLibre ? '<br><small style="color:#27ae60;font-weight:700">✅ Hay horarios libres más tarde</small>' : ''}
        </div>`;
    }
    return '';
}
function resetDia() {
    DIA_HOY = DIA_REAL;
    selectorDia.value = DIA_REAL;
    badgeDia.style.display = 'none';
    colorear();
    if (aulaActiva && plantaActiva) {
        const panel  = document.getElementById('panel-' + plantaActiva);
        const accion = document.getElementById('accion-' + plantaActiva);
        panel.innerHTML  = panelHTML(aulaActiva);
        accion.innerHTML = accionHTML(aulaActiva);
        accion.style.display = accionHTML(aulaActiva) ? 'block' : 'none';
    }
}
/* ── Colorear aulas ── */
function colorear(){
    let lc=0, oc=0, ac=0;
    document.querySelectorAll('.aula-box').forEach(el=>{
        const raw=el.dataset.aula;
        if(!raw) return;
        const b=isBloqueada(raw), o=isOcupada(raw), a=isApartada(raw);
        el.classList.toggle('bloqueada', b);
        el.classList.toggle('ocupada',  !b&&o&&!a);
        el.classList.toggle('libre',    !b&&!o&&!a);
        el.classList.toggle('apartada', !b&&a);
        if(!b&&!o&&!a) lc++; else if(!b&&o&&!a) oc++; else if(!b&&a) ac++;
    });
    document.getElementById('cont-libres').textContent   = lc;
    document.getElementById('cont-ocupadas').textContent = oc;
    document.getElementById('cont-apartadas').textContent= ac;
}

/* ── Seleccionar aula ── */
function seleccionar(raw, planta){
    document.querySelectorAll('.aula-box').forEach(b=>b.classList.remove('selected'));
    const el=document.querySelector(`.aula-box[data-aula="${raw}"]`);
    if(el) el.classList.add('selected');
    aulaActiva=raw; plantaActiva=planta;
    const panel =document.getElementById('panel-'+planta);
    const accion=document.getElementById('accion-'+planta);
    const otrPlanta=planta==='baja-b'?'alta-b':'baja-b';
    const otrAcc=document.getElementById('accion-'+otrPlanta);
    panel.innerHTML =panelHTML(raw);
    accion.innerHTML=accionHTML(raw);
    accion.style.display =accionHTML(raw)?'block':'none';
    if(otrAcc) otrAcc.style.display='none';
}

document.querySelectorAll('#planta-baja-b .aula-box').forEach(el=>{
    el.addEventListener('click',()=>seleccionar(el.dataset.aula,'baja-b'));
});
document.querySelectorAll('#planta-alta-b .aula-box').forEach(el=>{
    el.addEventListener('click',()=>seleccionar(el.dataset.aula,'alta-b'));
});

/* ── Slider ── */
const slider    =document.getElementById('sliderHora');
const sliderLbl =document.getElementById('sliderHoraLabel');
const badgeSim  =document.getElementById('badge-simulada');
const horaDisp  =document.getElementById('hora-display');

function actualizarSlider(min){
    horaMin=min;
    const lbl=minToH(min);
    sliderLbl.textContent=lbl; horaDisp.textContent=lbl;
    const pct=((min-420)/(1320-420))*100;
    slider.style.background=`linear-gradient(90deg,var(--teal) ${pct}%,#c8dce6 ${pct}%)`;
    badgeSim.style.display=(min===HORA_REAL_MIN)?'none':'inline-block';
    colorear();
    if(aulaActiva && plantaActiva){
        const panel =document.getElementById('panel-'+plantaActiva);
        const accion=document.getElementById('accion-'+plantaActiva);
        panel.innerHTML =panelHTML(aulaActiva);
        accion.innerHTML=accionHTML(aulaActiva);
        accion.style.display=accionHTML(aulaActiva)?'block':'none';
    }
}
slider.addEventListener('input',()=>actualizarSlider(+slider.value));
function resetSlider(){ slider.value=HORA_REAL_MIN; actualizarSlider(HORA_REAL_MIN); }
actualizarSlider(HORA_REAL_MIN);


// ── Diccionario de carreras para predicción ──
const CARRERAS = {
    's': 'Sistemas Computacionales',
    'e': 'Electromecanica',
    'i': 'Industrial',
    'c': 'Contaduría',
    'g': 'Gestión Empresarial',
    't': 'Turismo',
    'a': 'Ambiental',
};

// Palabras clave adicionales que mapean a inicial de carrera
const ALIAS_CARRERA = {
    'conta':    'c', 'contad':  'c', 'contaduria': 'c',
    'sist':     's', 'sistemas': 's', 'computac': 's',
    'indus':    'i', 'industrial': 'i',
    'electro':  'e', 'mecanica': 'e', 'electromecanica': 'e',
    'gestion':  'g', 'gestión': 'g', 'empresarial': 'g',
    'turismo':  't', 'tur': 't',
    'ambient':  'a', 'ambiental': 'a',
};
// Agrega esto junto a los diccionarios, FUERA del listener
function getDiaHoy() {
    // Intenta leer el selector de día simulado, si no usa la variable global
    const sel = document.getElementById('selectorDia');
    if (sel && sel.value) return sel.value;
    return typeof DIAREAL !== 'undefined' ? DIAREAL
         : typeof DIAHOY  !== 'undefined' ? DIAHOY
         : 'LUNES';
}

function resolverCarrera(q) {
    // Busca alias primero (ej: "conta" → 'c')
    for (const [alias, letra] of Object.entries(ALIAS_CARRERA)) {
        if (alias.startsWith(q) || q.startsWith(alias)) return letra;
    }
    // Si q es una sola letra que coincide con carrera
    if (CARRERAS[q]) return q;
    return null;
}

function buscarEnHorarios(letraCarrera, q) {
    return HORARIOS.filter(h => {
        const grupo = (h.grupo ?? '').toUpperCase();
        const matchCarrera = letraCarrera
            ? grupo.charAt(0) === letraCarrera.toUpperCase()
            : false;
        const matchTexto = (h.grupo   ?? '').toLowerCase().includes(q) ||
                           (h.docente ?? '').toLowerCase().includes(q) ||
                           (traducirGrupo(h.grupo) ?? '').toLowerCase().includes(q);
        return matchCarrera || matchTexto;
    });
    // Nota: NO filtra por día aquí, el filtro de día va en el listener con getDiaHoy()
}
/* ── Buscador ── */
const PANEL_PRINCIPAL = 'panel-baja-b';
const getPlanta = el => el?.closest('#planta-baja-b') ? 'baja-b' : 'alta-b';

document.getElementById('buscador').addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    if (!q) {
        document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
        return;
    }

    if (q === 'libres' || q === 'libre') {
        const l = [...document.querySelectorAll('.aula-box.libre')].map(e => normAula(e.dataset.aula));
        document.getElementById(PANEL_PRINCIPAL).innerHTML = `<div class="panel-content"><div class="info-row"><span class="info-label">Libres:</span><span class="info-val">${l.join(', ') || 'Ninguna'}</span></div></div>`;
        return;
    }
    if (q === 'ocupadas' || q === 'ocupada') {
        const o = [...document.querySelectorAll('.aula-box.ocupada')].map(e => normAula(e.dataset.aula));
        document.getElementById(PANEL_PRINCIPAL).innerHTML = `<div class="panel-content"><div class="info-row"><span class="info-label">Ocupadas:</span><span class="info-val">${o.join(', ') || 'Ninguna'}</span></div></div>`;
        return;
    }

    const todasAulas = [...document.querySelectorAll('.aula-box')];
    const pareceAula = /^(eb|ea|e1|e2|cc|ec|of|lab|ban|ealq)/i.test(q) || /^\d/.test(q);
    const letraCarrera = resolverCarrera(q);
    const pareceCarrera = !!letraCarrera || Object.values(CARRERAS).some(v => v.toLowerCase().includes(q));

    // ── RAMA A: Búsqueda por AULA ──
    if (pareceAula || !pareceCarrera) {

        const exacta = todasAulas.find(e => {
            const cod     = normAula(e.dataset.aula).toLowerCase();
            const raw     = e.dataset.aula.toLowerCase();
            const sinCero = cod.replace(/^eb0/, 'eb').replace(/^ebc/, 'cc').replace(/^ebrd/, 'cc3');
            return cod === q || raw === q || sinCero === q;
        });

        if (exacta) {
            document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
            exacta.classList.add('selected');
            seleccionar(exacta.dataset.aula, getPlanta(exacta));
            return;
        }

        const parciales = todasAulas
            .filter(e => {
                const cod     = normAula(e.dataset.aula).toLowerCase();
                const raw     = e.dataset.aula.toLowerCase();
                const sinCero = cod.replace(/^eb0/, 'eb').replace(/^ebc/, 'cc').replace(/^ebrd/, 'cc3');
                return cod.includes(q) || raw.includes(q) || sinCero.includes(q);
            })
            .sort((a, b) => a.dataset.aula.length - b.dataset.aula.length);

        if (parciales.length === 1) {
            document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
            parciales[0].classList.add('selected');
            seleccionar(parciales[0].dataset.aula, getPlanta(parciales[0]));
            return;
        }

        if (parciales.length > 1) {
            document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
            parciales.forEach(e => e.classList.add('selected'));
            const filas = parciales.map(e => {
                const aulaKey = e.dataset.aula;
                const planta  = getPlanta(e);
                return `<div class="info-row" style="cursor:pointer;padding:5px 4px;border-radius:7px;transition:background .15s;"
                    onmouseover="this.style.background='#f0f6f9'" onmouseout="this.style.background=''"
                    onclick="seleccionar('${aulaKey}','${planta}')">
                    <span class="info-label">${normAula(aulaKey)}</span>
                    <span class="info-val" style="color:var(--muted);font-size:.75rem;">${planta.includes('baja') ? 'Planta Baja' : 'Planta Alta'}</span>
                </div>`;
            }).join('');
            document.getElementById(PANEL_PRINCIPAL).innerHTML = `
                <div class="panel-content">
                    <div class="slots-title" style="margin-bottom:8px;">🔍 ${parciales.length} aulas encontradas</div>
                    ${filas}
                    <small style="color:#aaa;display:block;margin-top:6px;">Clic para ver el detalle</small>
                </div>`;
            return;
        }

        if (!pareceCarrera) {
            document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
            document.getElementById(PANEL_PRINCIPAL).innerHTML = `<div class="panel-content"><em style="color:#aaa">No se encontró "${q}".</em></div>`;
            return;
        }
    }

    // ── RAMA B: Búsqueda por CARRERA/GRUPO ──
    const todosCoinciden = buscarEnHorarios(letraCarrera, q);
    const aulasCarrera = [...new Set(todosCoinciden.filter(h => h.dia === getDiaHoy()).map(h => h.aula))];

    document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
    aulasCarrera.forEach(aula => {
        const el = document.querySelector(`.aula-box[data-aula="${aula}"]`);
        if (el) el.classList.add('selected');
    });

    const ahorita = todosCoinciden.filter(h =>
        h.dia === getDiaHoy() &&
        hToMin(h.hora_inicio) <= horaMin &&
        hToMin(h.hora_fin)    >  horaMin
    );

    const nombreCarrera = letraCarrera ? CARRERAS[letraCarrera] : q;

    if (ahorita.length > 0) {
        const filas = ahorita.map(h => {
            const el     = document.querySelector(`.aula-box[data-aula="${h.aula}"]`);
            const planta = getPlanta(el);
            return `<div class="info-row" style="cursor:pointer;padding:5px 4px;border-radius:7px;transition:background .15s;"
                onmouseover="this.style.background='#f0f6f9'" onmouseout="this.style.background=''"
                onclick="seleccionar('${h.aula}','${planta}')">
                <span class="info-label" style="min-width:54px;">${normAula(h.aula)}</span>
                <span class="info-val">
                    <strong>${h.grupo}</strong>
                    <small style="color:var(--muted);"> · ${traducirGrupo(h.grupo) ?? ''}</small>
                    ${h.docente ? `<br><small style="color:var(--muted);">${h.docente}</small>` : ''}
                </span>
            </div>`;
        }).join('');
        document.getElementById(PANEL_PRINCIPAL).innerHTML = `
            <div class="panel-content">
                <div class="slots-title" style="margin-bottom:8px;">🔍 ${nombreCarrera} — ahora en ${ahorita.length} aula${ahorita.length > 1 ? 's' : ''}</div>
                ${filas}
                <small style="color:#aaa;display:block;margin-top:6px;">Clic para ver el detalle</small>
            </div>`;
        return;
    }

    const enElDia = todosCoinciden.filter(h => h.dia === getDiaHoy());

    if (enElDia.length > 0) {
        const filas = enElDia.map(h => {
            const el     = document.querySelector(`.aula-box[data-aula="${h.aula}"]`);
            const planta = getPlanta(el);
            return `<div class="info-row" style="cursor:pointer;padding:5px 4px;border-radius:7px;transition:background .15s;"
                onmouseover="this.style.background='#f0f6f9'" onmouseout="this.style.background=''"
                onclick="seleccionar('${h.aula}','${planta}')">
                <span class="info-label" style="min-width:54px;">${normAula(h.aula)}</span>
                <span class="info-val">
                    <strong>${h.grupo}</strong>
                    <small style="color:var(--muted);"> · ${traducirGrupo(h.grupo) ?? ''}</small><br>
                    <small>${minToH(hToMin(h.hora_inicio))}–${minToH(hToMin(h.hora_fin))}</small>
                </span>
            </div>`;
        }).join('');
        const aulasU = [...new Set(enElDia.map(h => h.aula))].length;
        document.getElementById(PANEL_PRINCIPAL).innerHTML = `
            <div class="panel-content">
                <div class="slots-title" style="margin-bottom:8px;">📅 ${nombreCarrera} — hoy en ${aulasU} aula${aulasU > 1 ? 's' : ''} (no en este horario)</div>
                ${filas}
                <small style="color:#aaa;display:block;margin-top:6px;">Clic para ver el detalle</small>
            </div>`;
        return;
    }

    document.querySelectorAll('.aula-box').forEach(b => b.classList.remove('selected'));
    document.getElementById(PANEL_PRINCIPAL).innerHTML = `<div class="panel-content"><em style="color:#aaa">No se encontró "${q}" hoy.</em></div>`;
});

/* ── MODAL ── */
function abrirModal(raw, esBloqueo){
    aulaActiva=raw;
    document.getElementById('modal-aula-input').value   =normAula(raw);
    document.getElementById('modal-aula-nombre').textContent=normAula(raw);
    document.getElementById('modal-tipo-bloqueo').value =esBloqueo?'1':'0';
    document.getElementById('modal-override').value     ='0';
    document.getElementById('modal-hora-inicio-hidden').value='';
    document.getElementById('modal-hora-fin-hidden').value  ='';
    document.getElementById('modal-error').style.display   ='none';
    document.getElementById('modal-preview').style.display ='none';
    document.getElementById('resumen-horas').style.display ='none';
    document.getElementById('seccion-datos-reserva').style.display='none';

    const existentes=APARTADOS.filter(ap=>normAula(ap.aula)===normAula(raw)&&norm(ap.dia)===norm(document.getElementById('modal-dia').value));
    const divExt=document.getElementById('modal-apartados-existentes');
    if(existentes.length>0){
        divExt.style.display='block';
        divExt.innerHTML='⚠️ Apartados existentes este día:<br>'+existentes.map(ap=>`• ${ap.hora_inicio.slice(0,5)}–${ap.hora_fin.slice(0,5)} ${ap.nombre_solicitante??''}`).join('<br>');
    } else { divExt.style.display='none'; }

    if(esBloqueo){
        document.getElementById('modal-icono').textContent      ='🚫';
        document.getElementById('modal-titulo-texto').textContent='Estado del Baño';
        document.getElementById('modal-nota').textContent       ='⚠️ El baño quedará marcado como no disponible';
        document.getElementById('seccion-slots').style.display  ='none';
        document.getElementById('seccion-hora-manual').style.display='none';
        document.getElementById('campos-aula').style.display   ='none';
        document.getElementById('campos-banio').style.display  ='block';
        document.getElementById('seccion-datos-reserva').style.display='block';
        document.getElementById('modal-hora-inicio-hidden').value='07:00';
        document.getElementById('modal-hora-fin-hidden').value  ='22:00';
    } else {
        document.getElementById('modal-icono').textContent      ='📌';
        document.getElementById('modal-titulo-texto').textContent=ES_ADMIN?'Reservar / Override':'Apartar aula';
document.getElementById('modal-nota').textContent = ES_DOCENTE
    ? '🕐 Selecciona 1 o 2 bloques de 1h consecutivos (máx 2h)'
    : '⏳ El apartado expira al inicio de la siguiente semana';       
    document.getElementById('seccion-slots').style.display  ='block';
        document.getElementById('seccion-hora-manual').style.display=ES_ADMIN?'block':'none';
        document.getElementById('campos-aula').style.display   ='block';
        document.getElementById('campos-banio').style.display  ='none';
        renderSlotsModal();
    }
    document.getElementById('modalApartar').classList.add('active');
}

function cerrarModal(){
    document.getElementById('modalApartar').classList.remove('active');
}
document.getElementById('modalApartar').addEventListener('click',e=>{
    if(e.target===document.getElementById('modalApartar')) cerrarModal();
});

// ── Slots seleccionados por docente (máx 2 consecutivos de 1h) ──
let slotsDocente = []; // [{hi, hf}, {hi, hf}]

function renderSlotsModal(){
    if(!aulaActiva) return;
    const dia  = document.getElementById('modal-dia').value;
    const slots = getSlots(aulaActiva, dia);
    const cont  = document.getElementById('modal-slots-container');
    slotsDocente = []; // reset al reabrir
    if(!slots.length){ cont.innerHTML='<p class="no-slots-msg">Sin horarios registrados.</p>'; return; }

    let botonesHTML = '';
    for(const s of slots){
        if(s.libre){
            if(ES_DOCENTE){
                // Dividir en tramos de 1h exacta
                let cursor = s.hi;
                while(cursor < s.hf){
                    const fin = Math.min(cursor + 60, s.hf);
                    if(fin - cursor >= 30){ // ignorar fragmentos menores a 30 min
                        botonesHTML += `<button type="button"
                            class="modal-slot disponible"
                            data-hi="${cursor}" data-hf="${fin}"
                            onclick="seleccionarSlotDocente(${cursor},${fin},this)">
                            ${minToH(cursor)}-${minToH(fin)}<br>
                            <small>✅ 1h</small>
                        </button>`;
                    }
                    cursor += 60;
                }
            } else {
                // Admin/Jefe: slot completo
                botonesHTML += `<button type="button" class="modal-slot disponible"
                    onclick="seleccionarSlot(${s.hi},${s.hf},this)">
                    ${minToH(s.hi)}-${minToH(s.hf)}<br>
                    <small>✅ Libre</small>
                </button>`;
            }
        } else {
            const lbl = s.tipo==='bloqueado' ? '🔒 Bloqueado'
                      : s.tipo==='apartado'  ? '📌 Apartado'
                      : '📚 '+(s.label?.slice(0,12)??'Clase');
            botonesHTML += ES_ADMIN
                ? `<button type="button" class="modal-slot override"
                    onclick="seleccionarSlot(${s.hi},${s.hf},this,true)">
                    ${minToH(s.hi)}-${minToH(s.hf)}<br><small>${lbl}</small>
                  </button>`
                : `<button type="button" class="modal-slot no-disponible" disabled>
                    ${minToH(s.hi)}-${minToH(s.hf)}<br><small>${lbl}</small>
                  </button>`;
        }
    }
    cont.innerHTML = `<div class="modal-slots-grid">${botonesHTML}</div>`;
}

// ── Selección para docentes: máx 2 slots de 1h consecutivos ──
function seleccionarSlotDocente(hi, hf, btn){
    const errEl = document.getElementById('modal-error');
    errEl.style.display = 'none';

    const yaSelIdx = slotsDocente.findIndex(s => s.hi === hi && s.hf === hf);

    // Si ya estaba seleccionado → deseleccionar (toggle)
    if(yaSelIdx !== -1){
        slotsDocente.splice(yaSelIdx, 1);
        btn.classList.remove('activo');
        _actualizarResumenDocente();
        return;
    }

    // Máximo 2 slots
    if(slotsDocente.length >= 2){
        errEl.style.display = 'block';
        errEl.textContent   = '⚠️ Máximo 2 horas (2 bloques). Deselecciona uno primero.';
        return;
    }

    // Si ya hay 1 seleccionado, el nuevo debe ser consecutivo
    if(slotsDocente.length === 1){
        const prev = slotsDocente[0];
        const esConsecutivo = (prev.hf === hi) || (hf === prev.hi);
        if(!esConsecutivo){
            // Reemplazar selección: quitar el anterior y poner este
            document.querySelectorAll('.modal-slot.activo').forEach(b => b.classList.remove('activo'));
            slotsDocente = [];
        }
    }

    slotsDocente.push({hi, hf});
    btn.classList.add('activo');
    _actualizarResumenDocente();
}

function _actualizarResumenDocente(){
    if(slotsDocente.length === 0){
        document.getElementById('modal-hora-inicio-hidden').value = '';
        document.getElementById('modal-hora-fin-hidden').value    = '';
        document.getElementById('seccion-datos-reserva').style.display = 'none';
        document.getElementById('resumen-horas').style.display          = 'none';
        return;
    }

    // Ordenar por hora inicio y calcular rango total
    slotsDocente.sort((a, b) => a.hi - b.hi);
    const hiTotal = slotsDocente[0].hi;
    const hfTotal = slotsDocente[slotsDocente.length - 1].hf;
    const dur     = hfTotal - hiTotal;

    document.getElementById('modal-hora-inicio-hidden').value = minToH(hiTotal);
    document.getElementById('modal-hora-fin-hidden').value    = minToH(hfTotal);
    document.getElementById('modal-override').value           = '0';
    document.getElementById('seccion-datos-reserva').style.display = 'block';
    document.getElementById('duracion-texto').textContent =
        `${minToH(hiTotal)} – ${minToH(hfTotal)} (${Math.floor(dur/60)}h${dur%60>0?' '+dur%60+'min':''})`;
    document.getElementById('resumen-horas').style.display = 'block';
    document.getElementById('modal-error').style.display   = 'none';
}

function seleccionarSlot(hi, hf, btn, override=false){
    document.querySelectorAll('.modal-slot').forEach(b=>b.classList.remove('activo'));
    btn.classList.add('activo');
    document.getElementById('modal-hora-inicio-hidden').value=minToH(hi);
    document.getElementById('modal-hora-fin-hidden').value  =minToH(hf);
    document.getElementById('modal-override').value=override?'1':'0';
    document.getElementById('seccion-datos-reserva').style.display='block';
    const dur=hf-hi;
    document.getElementById('duracion-texto').textContent=`${minToH(hi)} – ${minToH(hf)} (${Math.floor(dur/60)}h ${dur%60>0?dur%60+'min':''})`;
    document.getElementById('resumen-horas').style.display='block';
    if(override){
        document.getElementById('modal-error').style.display='block';
        document.getElementById('modal-error').textContent='⚠️ Este horario ya tiene actividad. Solo admin puede hacer override.';
    } else { document.getElementById('modal-error').style.display='none'; }
}

function aplicarHoraManual(){
    const hi=hToMin(document.getElementById('manual-hora-inicio').value);
    const hf=hToMin(document.getElementById('manual-hora-fin').value);
    if(hf<=hi){ alert('La hora fin debe ser mayor a la hora inicio.'); return; }
    document.getElementById('modal-hora-inicio-hidden').value=minToH(hi);
    document.getElementById('modal-hora-fin-hidden').value  =minToH(hf);
    document.getElementById('modal-override').value='1';
    document.getElementById('seccion-datos-reserva').style.display='block';
    document.getElementById('duracion-texto').textContent=`${minToH(hi)} – ${minToH(hf)} (personalizado)`;
    document.getElementById('resumen-horas').style.display='block';
}

document.getElementById('formApartar').addEventListener('submit',function(e){
    const hi=document.getElementById('modal-hora-inicio-hidden').value;
    const hf=document.getElementById('modal-hora-fin-hidden').value;
    if(!hi||!hf){
        e.preventDefault();
        document.getElementById('modal-error').style.display='block';
        document.getElementById('modal-error').textContent='⚠️ Debes seleccionar un horario antes de confirmar.';
    }
});

// ── GALERÍA ÁREAS EDIFICIO A ──
(function(){
    const esAdmin = {{ session('usuario') && session('usuario')->rol === 'administrador' ? 'true' : 'false' }};

    const AREA_LABELS = {
    // Edificio A (ya existentes)
    'salida-canchas':  { icon: '🚪', title: 'Salida a Canchas' },
    'escaleras-2da':   { icon: '🪜', title: 'Escaleras 2da Planta' },
    'oficinas':        { icon: '🏢', title: 'Oficinas' },
    'area-medica':     { icon: '🏥', title: 'Área Médica' },
    'escaleras-1ra':   { icon: '🪜', title: 'Escaleras 1ra Planta' },
    // Edificio B — nuevos
    'lobby-b':              { icon: '🏛️', title: 'Lobby — Edificio B' },
    'biblioteca-b':         { icon: '📚', title: 'Biblioteca' },
    'escaleras-baja-b':     { icon: '🪜', title: 'Escaleras Planta Alta — Edificio B' },
    'cuerpo-acad-b':        { icon: '🏛️', title: 'Cuerpo Académico — Planta Baja' },
    'cuerpo-acad-alta-b':   { icon: '🏛️', title: 'Cuerpo Académico — Planta Alta' },
    'lab-fisep-b':          { icon: '🔬', title: 'Lab Fisep' },
    'area-docentes-b1':     { icon: '👩‍🏫', title: 'Área Docentes (1)' },
    'nanocubo-b':           { icon: '🧊', title: 'Nano Cubo' },
    'area-docentes-b2':     { icon: '👩‍🏫', title: 'Área Docentes (2)' },
    'sala-maestros-b':      { icon: '👨‍🏫', title: 'Sala de Maestros' },
    'papeleria-b':          { icon: '🛍️', title: 'Papelería' },
    'escaleras-alta-b':     { icon: '🪜', title: 'Escaleras Planta Baja — Edificio B' },
};

    const overlay       = document.getElementById('galleryOverlay');
    const modalClose    = document.getElementById('galleryModalClose');
    const modalIcon     = document.getElementById('galleryModalIcon');
    const modalTitle    = document.getElementById('galleryModalTitle');
    const modalSub      = document.getElementById('galleryModalSub');
    const galleryGrid   = document.getElementById('galleryGrid');
    const galleryEmpty  = document.getElementById('galleryEmpty');
    const uploadForm    = document.getElementById('uploadForm');
    const btnToggle     = document.getElementById('btnToggleForm');
    const btnCancel     = document.getElementById('btnCancelForm');
    const formSubir     = document.getElementById('formSubirFoto');
    const formArea      = document.getElementById('formArea');
    const lightboxOv    = document.getElementById('lightboxOverlay');
    const lightboxImg   = document.getElementById('lightboxImg');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDesc  = document.getElementById('lightboxDesc');
    const lightboxClose = document.getElementById('lightboxClose');
    const toastEl       = document.getElementById('toastMsg');

    let currentArea = null;
    let toastTimer  = null;

    // Abrir galería al hacer click en label-box
    document.querySelectorAll('.label-box-galeria').forEach(el => {
        el.addEventListener('click', () => openGallery(el.dataset.area));
    });

    function openGallery(areaKey) {
        currentArea = areaKey;
        const d = AREA_LABELS[areaKey] || { icon: '🏛️', title: areaKey };
        modalIcon.textContent  = d.icon;
        modalTitle.textContent = d.title;
        modalSub.textContent   = 'Imágenes del área';
        if (formArea) formArea.value = areaKey;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        loadPhotos(areaKey);
    }

    function closeGallery() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        if (uploadForm) uploadForm.classList.remove('open');
    }

    modalClose.addEventListener('click', closeGallery);
    overlay.addEventListener('click', e => { if (e.target === overlay) closeGallery(); });

    // Toggle formulario subida
    if (btnToggle) {
        btnToggle.addEventListener('click', () => {
            uploadForm.classList.toggle('open');
            btnToggle.textContent = uploadForm.classList.contains('open') ? '✕ Cancelar' : '➕ Agregar fotografía';
        });
    }
    if (btnCancel) {
        btnCancel.addEventListener('click', () => {
            uploadForm.classList.remove('open');
            if (btnToggle) btnToggle.textContent = '➕ Agregar fotografía';
        });
    }

    // Envío del formulario
    if (formSubir) {
        formSubir.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(formSubir);
            try {
                const res  = await fetch('{{ route("galeria.store") }}', {
                    method: 'POST', body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.ok) {
                    formSubir.reset();
                    uploadForm.classList.remove('open');
                    if (btnToggle) btnToggle.textContent = '➕ Agregar fotografía';
                    loadPhotos(currentArea);
                    showToast('✅ Imagen agregada correctamente');
                } else {
                    showToast(data.message || '❌ Error al guardar');
                }
            } catch { showToast('❌ Error de conexión'); }
        });
    }

    // Cargar fotos desde API
    async function loadPhotos(area) {
        galleryGrid.innerHTML = `<div style="text-align:center;padding:32px;color:#a0b8c8;font-size:.82rem">Cargando imágenes…</div>`;
        galleryEmpty.style.display = 'none';
        try {
            const res  = await fetch(`{{ url('galeria/fotos') }}?area=${encodeURIComponent(area)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            renderPhotos(data.fotos);
        } catch { galleryGrid.innerHTML = `<div style="text-align:center;padding:32px;color:#e74c3c">Error al cargar imágenes.</div>`; }
    }

    function renderPhotos(fotos) {
        galleryGrid.innerHTML = '';
        if (!fotos.length) { galleryEmpty.style.display = 'block'; return; }
        galleryEmpty.style.display = 'none';
        fotos.forEach((f, i) => {
            const card = document.createElement('div');
            card.className = 'gallery-card';
            card.style.animationDelay = (i * 0.07) + 's';
            const imgTag = f.imagen_url
                ? `<img src="${f.imagen_url}" alt="${f.titulo}" class="gallery-card-img" loading="lazy">`
                : `<div class="gallery-card-img" style="display:flex;align-items:center;justify-content:center;font-size:2.5rem">🖼️</div>`;
            const adminBtns = esAdmin
                ? `<div class="gallery-card-actions">
                    <button class="btn-del-photo" onclick="deletePhoto(${f.id})">🗑 Eliminar</button>
                  </div>` : '';
            card.innerHTML = `${imgTag}
                <div class="gallery-card-info">
                    <div class="gallery-card-title">${f.titulo}</div>
                    <div class="gallery-card-desc">${f.descripcion || 'Sin descripción'}</div>
                    <div class="gallery-card-meta">📅 ${f.created_at_human}</div>
                </div>${adminBtns}`;
            if (f.imagen_url) {
                card.querySelector('.gallery-card-img')
                    .addEventListener('click', () => openLightbox(f.imagen_url, f.titulo, f.descripcion));
            }
            galleryGrid.appendChild(card);
        });
    }

    // Eliminar foto
    window.deletePhoto = async function(id) {
        if (!confirm('¿Eliminar esta imagen? Esta acción no se puede deshacer.')) return;
        try {
            const res  = await fetch(`{{ url('galeria/fotos') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });
            const data = await res.json();
            if (data.ok) { loadPhotos(currentArea); showToast('🗑 Imagen eliminada'); }
        } catch { showToast('❌ Error al eliminar'); }
    };

    // Lightbox
    function openLightbox(url, title, desc) {
        lightboxImg.src          = url;
        lightboxTitle.textContent = title;
        lightboxDesc.textContent  = desc || '';
        lightboxOv.classList.add('active');
    }
    function closeLightbox() { lightboxOv.classList.remove('active'); }
    lightboxClose.addEventListener('click', closeLightbox);
    lightboxOv.addEventListener('click', e => { if (e.target === lightboxOv) closeLightbox(); });

    // Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (lightboxOv.classList.contains('active')) closeLightbox();
            else if (overlay.classList.contains('active')) closeGallery();
        }
    });

    // Toast
    function showToast(msg) {
        toastEl.textContent = msg;
        toastEl.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toastEl.classList.remove('show'), 3200);
    }
})();
// Traduce código de grupo a nombre completo
function traducirGrupo(grupo) {
    if (!grupo) return null;
    const g = grupo.toString().trim().toUpperCase();

    // Extraescolares
    if (g.startsWith('MX') || g.startsWith('VX')) {
        return `Extraescolar ${g}`;
    }

    const carreras = {
        'S': 'Sistemas Computacionales',
        'E': 'Electromecanica',
        'I': 'Industrial',
        'C': 'Contaduría',
        'G': 'Gestión Empresarial',
        'T': 'Turismo',
        'A': 'Ambiental',
    };

    const letra     = g.charAt(0);          // 'I', 'S', etc.
    const sufijo    = g.slice(1);            // 'A', 'B', 'C', '1', etc.
    const carrera   = carreras[letra] ?? letra;

    // Sufijo numérico = semestre, letra = grupo
    const partes = sufijo.match(/^(\d*)([A-Z]?)$/);
    const semestre = partes?.[1] ? `${partes[1]}°` : '';
    const grp      = partes?.[2] ? ` Grupo ${partes[2]}` : '';

    return `${carrera}${semestre ? ' · ' + semestre : ''}${grp}`;
}
async function abrirModalReservas() {
    const modal = document.getElementById('modalReservas');
    const listado = document.getElementById('reservas-listado');
    const info = document.getElementById('reservas-semana-info');

    modal.classList.add('active');
    listado.innerHTML = `<p style="color:#888;">Cargando reservaciones…</p>`;
    info.textContent = '';

    try {
        const res = await fetch(`{{ route('aulas.misReservaciones') }}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await res.json();

        info.textContent = `Semana activa: ${data.semana}`;

        if (!data.ok || !data.reservas || !data.reservas.length) {
            listado.innerHTML = `
                <div class="panel-placeholder">
                    <span>No has realizado reservaciones en la semana activa.</span>
                </div>
            `;
            return;
        }

        listado.innerHTML = data.reservas.map(r => `
            <div class="info-card" style="border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;margin-bottom:8px;background:#fff;">
                <div><strong>${r.aula}</strong> — ${r.dia}</div>
                <div style="font-size:.82rem;color:#555;">
                    ${r.hora_inicio.slice(0,5)} – ${r.hora_fin.slice(0,5)}
                </div>
                ${r.materia_reserva ? `<div style="margin-top:4px;"><strong>Asunto:</strong> ${r.materia_reserva}</div>` : ''}
                ${r.nombre_solicitante ? `<div><strong>Solicitante:</strong> ${r.nombre_solicitante}</div>` : ''}
                ${parseInt(r.override) === 1 ? `<div style="color:#b7770d;font-weight:700;">Override</div>` : ''}
            </div>
        `).join('');
    } catch (e) {
        listado.innerHTML = `<div style="color:#c0392b;">Error al cargar tus reservaciones.</div>`;
    }
}

function cerrarModalReservas() {
    document.getElementById('modalReservas').classList.remove('active');
}
</script>
@endsection