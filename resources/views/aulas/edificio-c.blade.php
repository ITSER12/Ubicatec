@extends('layouts.app')

@section('title', 'Mapa Edificio C')

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
    --green-aula:#70ad47;--green-aula-dark:#5d9438;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;}

/* ── Header ── */
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

/* ── Layout principal ── */
.u-main{flex:1;padding:20px 24px;display:flex;flex-direction:column;gap:16px;}
.u-alert{padding:8px 14px;border-radius:9px;font-size:.78rem;margin-bottom:6px;}
.u-alert-ok{background:#e8fdf2;color:#27ae60;border:1px solid #b2dfdb;}
.u-alert-err{background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;}
.u-footer{background:linear-gradient(135deg,#b8d67a,#a5c95e);text-align:center;padding:10px;font-size:.75rem;color:#2d5016;font-weight:600;}

/* ── Strip de estado ── */
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
.u-legend{display:flex;align-items:center;gap:16px;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:8px 16px;font-size:.73rem;color:var(--muted);width:fit-content;box-shadow:var(--shadow-sm);}
.leg-dot{width:11px;height:11px;border-radius:3px;flex-shrink:0;}

/* ── Grid de plantas ── */
.plants-grid{display:grid;grid-template-columns:1.45fr 1fr;gap:18px;flex:1;align-items:start;}
.plant-block{background:var(--surface);border:1.5px solid var(--border);border-radius:16px;padding:16px 18px;box-shadow:var(--shadow-md);display:flex;flex-direction:column;gap:10px;position:relative;transition:box-shadow .3s;}
.plant-block:hover{box-shadow:0 10px 32px rgba(55,96,115,.13);}
.plant-title{font-family:'Instrument Serif',serif;font-size:.98rem;color:var(--teal);display:flex;align-items:center;gap:7px;border-bottom:1.5px solid #e8f2f7;padding-bottom:8px;margin-bottom:4px;}
.plant-title svg{width:16px;height:16px;stroke:var(--teal);fill:none;stroke-width:2;}

/* ── Mapa posicionado ── */
.mapa-c{position:relative;width:100%;min-height:780px;background:#eef3f6;border:1.5px solid #d4e1e8;border-radius:14px;overflow:hidden;}

/* ── Caja base de mapa ── */
.map-box{position:absolute;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:7px;overflow:hidden;box-sizing:border-box;line-height:1.2;}

/* ── Label box (áreas con galería) ── */
.label-box{border:1.5px dashed #c8dce6;background:#f8fbfd;border-radius:10px;font-size:.68rem;font-weight:500;color:var(--muted);white-space:normal;}
.label-box-galeria{cursor:pointer;transition:background .2s,box-shadow .2s,transform .15s,border-color .2s,color .2s;}
.label-box-galeria:hover{background:#dff0f7;box-shadow:0 4px 12px rgba(55,96,115,.18);transform:translateY(-2px);border-color:var(--teal);color:var(--teal);}
.label-box.vertical{writing-mode:vertical-rl;text-orientation:mixed;}
.txt-big{font-family:'Instrument Serif',serif;font-size:.95rem;font-weight:700;color:var(--text);}
.txt-huge{font-family:'Instrument Serif',serif;font-size:1.3rem;font-weight:700;color:var(--text);}
.txt-small{font-size:.64rem;color:var(--muted);margin-top:3px;line-height:1.25;}

/* ── Aula box (reservables) ── */
.aula-box{border:2px solid #c8dce6;background:#f0f6f9;border-radius:11px;font-size:.68rem;font-weight:700;cursor:pointer;transition:transform .22s var(--ease),box-shadow .22s,background .2s,border-color .2s;user-select:none;gap:3px;}
.aula-box:hover{transform:translateY(-3px) scale(1.04);box-shadow:0 8px 20px rgba(55,96,115,.18);}
.aula-box.selected{outline:3px solid var(--teal);outline-offset:2px;}
.aula-box .aula-icon{font-size:.9rem;line-height:1;}
.aula-box.ocupada{background:linear-gradient(135deg,#e74c3c,#c0392b)!important;color:#fff;border-color:#c0392b;box-shadow:0 4px 14px rgba(231,76,60,.35);}
.aula-box.libre{background:linear-gradient(135deg,var(--green-aula),var(--green-aula-dark))!important;color:#fff;border-color:var(--green-aula-dark);box-shadow:0 4px 14px rgba(93,148,56,.32);}
.aula-box.apartada{background:linear-gradient(135deg,#f39c12,#e67e22)!important;color:#fff;border-color:#e67e22;box-shadow:0 4px 14px rgba(243,156,18,.35);}
.aula-box.bloqueada{background:linear-gradient(135deg,#636e72,#2d3436)!important;color:#fff;border-color:#2d3436;box-shadow:0 4px 14px rgba(45,52,54,.35);cursor:not-allowed;}

/* ── Pasillo/Lobby centrado ── */
.pasillo-wrap{display:flex;flex-direction:column;align-items:center;justify-content:space-evenly;width:100%;height:100%;}
.pasillo-v{writing-mode:vertical-rl;text-orientation:mixed;font-family:'Instrument Serif',serif;font-size:1.05rem;font-weight:700;color:var(--text);}
.lobby-h{font-family:'Instrument Serif',serif;font-size:1rem;font-weight:700;color:var(--text);}

/* ── Panel de información ── */
.panel-info{background:linear-gradient(150deg,#f0f7ff,#e4f0f8);border:1.5px solid #c8dde8;border-radius:13px;padding:14px 16px;font-size:.8rem;position:relative;overflow:hidden;transition:box-shadow .3s;text-align:left;}
.panel-info:hover{box-shadow:0 6px 20px rgba(55,96,115,.12);}
.panel-info::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:2.5px;background:linear-gradient(90deg,transparent,var(--teal),transparent);animation:scan 3s linear infinite;}
@keyframes scan{0%{left:-100%}100%{left:100%}}
.panel-placeholder{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;min-height:100px;gap:8px;color:#a0b8c8;}
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

/* ── Botones de acción ── */
.btn-apartar{width:100%;padding:9px;background:linear-gradient(135deg,var(--teal),var(--teal-light));color:#fff;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .15s;letter-spacing:.3px;display:block;}
.btn-apartar:hover{opacity:.85;transform:translateY(-1px);}
.btn-bloquear{width:100%;padding:9px;background:linear-gradient(135deg,#636e72,#2d3436);color:#fff;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:opacity .2s;letter-spacing:.3px;display:block;}
.btn-bloquear:hover{opacity:.85;}
.aviso-no-disponible{padding:8px 10px;font-size:.72rem;color:#c0392b;text-align:center;background:#fde8e8;border-radius:8px;display:block;}
.info-box{background:#e8f4fd;border:1px solid #bee3f8;border-radius:10px;padding:10px 14px;font-size:.77rem;color:var(--teal);margin-top:6px;}
.info-box strong{display:block;margin-bottom:4px;}

/* ── Modal reserva ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center;}
.modal-overlay.active{display:flex;}
.modal-box{background:#fff;border-radius:18px;padding:28px 30px;width:400px;max-width:96vw;box-shadow:0 24px 64px rgba(0,0,0,.22);animation:modalIn .32s var(--ease) forwards;max-height:90vh;overflow-y:auto;}
@keyframes modalIn{from{opacity:0;transform:scale(.86) translateY(18px)}to{opacity:1;transform:scale(1) translateY(0)}}
.modal-title{font-family:'Instrument Serif',serif;font-size:1.05rem;color:var(--teal);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
.modal-box label{display:block;font-size:.75rem;font-weight:600;color:var(--muted);margin-bottom:4px;margin-top:10px;}
.modal-box input,.modal-box select,.modal-box textarea{width:100%;padding:8px 10px;border:1.5px solid #c8dce6;border-radius:9px;font-size:.85rem;transition:border-color .2s;}
.modal-box input:focus,.modal-box select:focus,.modal-box textarea:focus{outline:none;border-color:var(--teal);}
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
.modal-slot.override{background:#fff3cd;color:#b7770d;border-color:#f39c12;}
.modal-slot.override:hover,.modal-slot.override.activo{background:#f39c12;color:#fff;transform:scale(1.05);}
.no-slots-msg{font-size:.75rem;color:#aaa;padding:8px;text-align:center;}

/* ── Modal galería ── */
.gallery-overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);z-index:1000;display:flex;align-items:flex-start;justify-content:center;padding:20px;overflow-y:auto;opacity:0;pointer-events:none;transition:opacity .35s ease;}
.gallery-overlay.active{opacity:1;pointer-events:all;}
.gallery-modal{background:#fff;border-radius:22px;width:100%;max-width:900px;box-shadow:0 30px 80px rgba(0,0,0,.35);transform:translateY(40px) scale(.97);transition:transform .4s var(--ease),opacity .35s ease;opacity:0;overflow:hidden;margin:auto;}
.gallery-overlay.active .gallery-modal{transform:translateY(0) scale(1);opacity:1;}
.gallery-modal-header{background:linear-gradient(135deg,#2c4f60,#376073 55%,#5a8fa3);padding:20px 24px;display:flex;align-items:center;gap:14px;position:relative;overflow:hidden;}
.gallery-modal-icon{width:42px;height:42px;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.gallery-modal-title{font-family:'Instrument Serif',serif;font-size:1.3rem;color:#fff;flex:1;}
.gallery-modal-sub{font-size:.74rem;color:rgba(255,255,255,.7);margin-top:2px;}
.gallery-modal-close{background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;width:36px;height:36px;border-radius:50%;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .2s,transform .15s;}
.gallery-modal-close:hover{background:rgba(255,255,255,.28);transform:rotate(90deg);}
.gallery-body{padding:22px 24px;}
.gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
.gallery-card{border-radius:14px;overflow:hidden;background:#f8fafc;border:1px solid rgba(55,96,115,.1);box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .3s var(--ease),box-shadow .3s;cursor:pointer;}
.gallery-card:hover{transform:translateY(-6px) scale(1.02);box-shadow:0 12px 32px rgba(0,0,0,.15);}
.gallery-card-img{width:100%;height:160px;object-fit:cover;display:block;background:linear-gradient(135deg,#e8f3f8,#d5eaee);}
.gallery-card-info{padding:12px 14px;}
.gallery-card-title{font-weight:700;font-size:.82rem;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.gallery-card-desc{font-size:.73rem;color:var(--muted);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.gallery-card-meta{font-size:.68rem;color:#a0b8c8;margin-top:8px;}
.gallery-card-actions{display:flex;gap:6px;padding:0 14px 12px;}
.btn-del-photo{flex:1;padding:6px;background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;border-radius:7px;font-size:.7rem;font-weight:700;cursor:pointer;}
.btn-del-photo:hover{background:#c0392b;color:#fff;}
.gallery-empty{text-align:center;padding:48px 20px;color:var(--muted);font-size:.85rem;}
.btn-add-photo{display:flex;align-items:center;gap:8px;padding:10px 18px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border:none;border-radius:10px;font-size:.8rem;font-weight:700;cursor:pointer;margin-bottom:18px;transition:opacity .2s,transform .15s;}
.btn-add-photo:hover{opacity:.9;transform:translateY(-1px);}
.upload-form{background:linear-gradient(135deg,#f0f7fa,#e4f0f7);border:1.5px solid rgba(55,96,115,.2);border-radius:14px;padding:20px;margin-bottom:20px;display:none;}
.upload-form.open{display:block;}
.upload-form label{display:block;font-size:.74rem;font-weight:700;color:var(--teal-dark);margin-bottom:5px;margin-top:12px;}
.upload-form label:first-child{margin-top:0;}
.upload-form input[type=text],.upload-form textarea{width:100%;padding:9px 12px;border:1.5px solid rgba(55,96,115,.25);border-radius:9px;font-size:.82rem;background:#fff;outline:none;transition:border-color .2s;}
.upload-form input[type=text]:focus,.upload-form textarea:focus{border-color:var(--teal);}
.upload-form textarea{resize:vertical;min-height:72px;}
.upload-form input[type=file]{width:100%;padding:9px 12px;border:1.5px dashed rgba(55,96,115,.35);border-radius:9px;font-size:.78rem;background:#fff;cursor:pointer;}
.upload-form-actions{display:flex;gap:10px;margin-top:16px;}
.btn-upload-submit{flex:1;padding:10px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border:none;border-radius:9px;font-weight:700;font-size:.82rem;cursor:pointer;}
.btn-upload-cancel{flex:1;padding:10px;background:#fff;color:var(--teal);border:1.5px solid rgba(55,96,115,.3);border-radius:9px;font-weight:600;font-size:.82rem;cursor:pointer;}

/* ── Lightbox ── */
.lightbox-overlay{position:fixed;inset:0;background:rgba(0,0,0,.92);backdrop-filter:blur(12px);z-index:2000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .3s ease;}
.lightbox-overlay.active{opacity:1;pointer-events:all;}
.lightbox-content{max-width:800px;width:100%;transform:scale(.9);transition:transform .4s var(--ease);}
.lightbox-overlay.active .lightbox-content{transform:scale(1);}
.lightbox-img{width:100%;max-height:65vh;object-fit:contain;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.6);display:block;}
.lightbox-info{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:16px 20px;margin-top:14px;}
.lightbox-title{font-family:'Instrument Serif',serif;font-size:1.1rem;color:#fff;margin-bottom:4px;}
.lightbox-desc{font-size:.8rem;color:rgba(255,255,255,.7);line-height:1.6;}
.lightbox-close{position:absolute;top:20px;right:24px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);color:#fff;width:40px;height:40px;border-radius:50%;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;}
.lightbox-close:hover{background:rgba(255,255,255,.24);transform:rotate(90deg);}

/* ── Toast ── */
.toast{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--teal-dark);color:#fff;padding:12px 22px;border-radius:30px;font-size:.82rem;font-weight:600;z-index:3000;opacity:0;transition:opacity .3s,transform .3s var(--ease);pointer-events:none;white-space:nowrap;}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0);}

/* ── Responsive ── */
@media (max-width:1200px){
    .plants-grid{grid-template-columns:1fr;}
    .mapa-c{min-height:680px;}
}
@media (max-width:768px){
    .u-header{flex-wrap:wrap;}
    .u-search{order:3;width:100%;}
    .u-search input{width:100%;}
    .mapa-c{min-height:580px;}
    .txt-huge{font-size:1rem;}
    .txt-big{font-size:.82rem;}
    .pasillo-v{font-size:.85rem;}
    .lobby-h{font-size:.85rem;}
}
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

    {{-- ── HEADER ── --}}
    <header class="u-header">
        <div class="u-logo">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        </div>
        <span class="u-brand">Ubicatec</span>
        <div class="u-search">
            <input id="buscador" type="search" placeholder="🔍  Buscar aula, laboratorio, área…">
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

        {{-- ── STRIP DE ESTADO ── --}}
        <div class="u-strip">
            <span class="day-chip">{{ $dia }}</span>
            <span class="strip-sep">|</span>
            🕐 <strong id="hora-display"></strong>
            <span class="strip-sep">|</span>
            🟢 Libres: <strong id="cont-libres">{{ count($libres) }}</strong>
            <span class="strip-sep">|</span>
            🔴 Ocupadas: <strong id="cont-ocupadas">{{ count($ocupadas) }}</strong>
            <span class="strip-sep">|</span>
            🟠 Apartadas: <strong id="cont-apartadas">{{ count($apartados) }}</strong>
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
                <input type="range" id="sliderHora" min="420" max="1320" step="30" value="0">
                <span id="sliderHoraLabel">{{ substr($hora,0,5) }}</span>
                <span id="badge-simulada" class="hora-simulada-badge">Simulando hora</span>
                <button type="button" onclick="resetSlider()" title="Volver a ahora"
                    style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--muted);padding:0 2px;">⟳</button>
            </div>

            <div class="hora-slider-wrap" style="margin-left:8px;">
                <label>Ver día:</label>
                <select id="selectorDia" style="border:none;background:transparent;font-size:.78rem;font-weight:700;color:var(--teal);cursor:pointer;outline:none;padding:2px 4px;">
                    <option value="LUNES">Lunes</option>
                    <option value="MARTES">Martes</option>
                    <option value="MIERCOLES">Miércoles</option>
                    <option value="JUEVES">Jueves</option>
                    <option value="VIERNES">Viernes</option>
                    <option value="SABADO">Sábado</option>
                </select>
                <span id="badge-dia-simulado" class="hora-simulada-badge" style="display:none;">Simulando día</span>
                <button type="button" onclick="resetDia()" title="Volver a ahora"
                    style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--muted);padding:0 2px;">⟳</button>
            </div>

            <div class="u-legend" style="margin-left:8px;">
                <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#70ad47"></span>Libre</span>
                <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#e74c3c"></span>Ocupada</span>
                <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#f39c12"></span>Apartada</span>
                <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#636e72"></span>Bloqueada</span>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
     GRID DE PLANTAS — EDIFICIO C
     Reemplaza el bloque <div class="plants-grid"> de tu blade guía
     con este bloque completo.
══════════════════════════════════════════════ --}}
<div class="plants-grid">

    {{-- ════════════════════════════
         PLANTA BAJA  (columna izq)
    ════════════════════════════ --}}
    <div class="plant-block" id="planta-baja-c">
        <div class="plant-title">
            <svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="13" rx="2"/><path d="M3 9l9-5 9 5"/></svg>
            Edificio C — Planta Baja
        </div>

        <div class="mapa-c">

            {{-- Auditorio --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-auditorio"
                 style="left:1%;top:1%;width:25%;height:39%;">
                <span class="txt-huge">Auditorio</span>
            </div>

            {{-- Cubículo de inglés --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-cubiculo-ingles"
                 style="left:27%;top:1%;width:11%;height:9%;">
                Cubículo de Inglés
            </div>

            

            {{-- Controles eléctricos --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-controles-electricos"
                 style="left:39%;top:29%;width:20%;height:8%;">
                Controles eléctricos
            </div>

            {{-- Simulación (AULA VERDE) --}}
            <div class="map-box aula-box"
                data-aula="SIMULACION"
                data-area="c-baja-simulacion"
                style="left:60%;top:27%;width:11%;height:12%;">
                <span class="aula-icon">🧪</span>
                <span>Simulación</span>
            </div>

            {{-- LOBBY --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-lobby"
                 style="left:1%;top:42%;width:85%;height:10%;">
                <span class="txt-big">LOBBY</span>
            </div>

            {{-- Entrada lateral --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-entrada-lateral"
                 style="left:87%;top:42%;width:10%;height:10%;">
                Entrada a<br>Edificio C
            </div>

            {{-- Área de investigación --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-area-investigacion"
                 style="left:1%;top:54%;width:12%;height:14%;">
                Área de investigación
            </div>

            {{-- Automatización (AULA VERDE) --}}
            <div class="map-box aula-box"
                 data-aula="AUTOMATIZACION"
                 style="left:14%;top:54%;width:10%;height:11%;">
                <span class="aula-icon">⚙️</span>
                <span>Automatización</span>
            </div>

            {{-- División Industrial --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-division-industrial"
                 style="left:25%;top:54%;width:13%;height:10%;">
                División Industrial
            </div>

            {{-- División Contabilidad --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-division-contabilidad"
                 style="left:25%;top:65%;width:13%;height:10%;">
                División Contabilidad
            </div>

            {{-- División Electromecánica --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-division-electromecanica"
                 style="left:25%;top:76%;width:13%;height:10%;">
                División Electromecánica
            </div>

            {{-- Pasillo / zona de circulación --}}
            <div class="map-box label-box"
                 style="left:39%;top:54%;width:22%;height:32%;border-style:solid;background:#f0f5f8;pointer-events:none;">
            </div>

            {{-- Escaleras a planta alta --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-escaleras"
                 style="left:62%;top:54%;width:14%;height:11%;">
                Escaleras a planta alta
            </div>

            {{-- Baños Mujeres — solo admin/jefe pueden bloquear --}}
            <div class="map-box aula-box"
                 data-aula="BANIOS_MUJERES_C"
                 data-tipo="banio"
                 style="left:62%;top:66%;width:14%;height:12%;">
                <span class="aula-icon">🚻</span>
                <span>Baños Mujeres<br><small>Intersec. 2do piso</small></span>
            </div>

            {{-- Entrada principal inferior --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-baja-entrada-principal"
                 style="left:46%;top:88%;width:11%;height:9%;">
                Entrada a<br>Edificio C
            </div>
        
        </div>

        {{-- Acción (apartar/bloquear) se inyecta por JS --}}
        <div id="accion-baja" style="display:none;"></div>
        <div class="panel-info panel-info-below" id="panel-baja">
    <div class="panel-placeholder">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <path d="M3 9h18M9 21V9"/>
        </svg>
        <span>Selecciona un aula</span>
    </div>
</div>

<div id="accion-baja" style="display:none;"></div>
    </div>

    {{-- ════════════════════════════
         PLANTA ALTA  (columna der)
    ════════════════════════════ --}}
    <div class="plant-block" id="planta-alta-c">
        <div class="plant-title">
            <svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="13" rx="2"/><path d="M3 9l9-5 9 5"/><line x1="12" y1="4" x2="12" y2="2"/></svg>
            Edificio C — Planta Alta
        </div>

        <div class="mapa-c">

            {{-- Dpto. Posgrado e Investigación --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-posgrado-investigacion"
                 style="left:23%;top:1%;width:23%;height:11%;">
                Departamento de Posgrado e Investigación
            </div>

            {{-- Lab. Tiempos y Movimientos (AULA VERDE) --}}
            <div class="map-box aula-box"
                 data-aula="LAB_TIEMPOS_MOV"
                 style="left:2%;top:13%;width:19%;height:13%;">
                <span class="aula-icon">⏱️</span>
                <span>Lab. Tiempos y Movimientos</span>
            </div>

            {{-- Lab. Metrología (AULA VERDE) --}}
            <div class="map-box aula-box"
                 data-aula="LAB_METROLOGIA"
                 style="left:2%;top:27%;width:19%;height:13%;">
                <span class="aula-icon">🔬</span>
                <span>Lab. de Metrología</span>
            </div>

            {{-- Materiales --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-materiales"
                 style="left:2%;top:41%;width:13%;height:9%;">
                Materiales
            </div>

            {{-- Área academia --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-area-academica"
                 style="left:2%;top:51%;width:20%;height:22%;">
                <div>
                    <div class="txt-big">Área academia</div>
                    <div class="txt-small">Dirección académica · Subdirección académica · Desarrollo Académico · Posgrado</div>
                </div>
            </div>

            {{-- Baños Hombres — solo admin/jefe pueden bloquear --}}
            <div class="map-box aula-box"
                 data-aula="BANIOS_HOMBRES_C"
                 data-tipo="banio"
                 style="left:2%;top:74%;width:19%;height:9%;">
                <span class="aula-icon">🚻</span>
                <span>Baños Hombres<br><small>Intersec. escaleras</small></span>
            </div>

            {{-- Escaleras a planta baja --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-escaleras"
                 style="left:2%;top:84%;width:19%;height:10%;">
                Escaleras a planta baja
            </div>

            {{-- Pasillo + Lobby --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-pasillo-lobby"
                 style="left:23%;top:13%;width:20%;height:81%;">
                <div class="pasillo-wrap">
                    <span class="pasillo-v">Pasillo</span>
                    <span class="lobby-h">Lobby</span>
                </div>
            </div>

            {{-- Dpto. Psicología --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-psicologia"
                 style="left:44%;top:13%;width:21%;height:10%;">
                Departamento de Psicología
            </div>

            {{-- Dpto. Ciencias Básicas / Psicología --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-ciencias-basicas"
                 style="left:44%;top:24%;width:21%;height:11%;">
                Dpto. de Ciencias Básicas / Psicología
            </div>

            {{-- Dpto. Gestión Empresarial 1 --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-gestion-1"
                 style="left:44%;top:36%;width:21%;height:10%;">
                Departamento de Gestión Empresarial
            </div>

            {{-- Dpto. Gestión Empresarial 2 --}}
            <div class="map-box label-box label-box-galeria"
                 data-area="c-alta-gestion-2"
                 style="left:44%;top:47%;width:21%;height:10%;">
                Departamento de Gestión Empresarial
            </div>

            {{-- Controles eléctricos (vertical) --}}
            <div class="map-box label-box label-box-galeria vertical"
                 data-area="c-alta-controles-electricos"
                 style="left:65.5%;top:58%;width:6%;height:24%;">
                Controles eléctricos
            </div>

            

        </div>
        {{-- Panel información Planta Alta --}}
            <div class="panel-info panel-info-below" id="panel-alta">
                <div class="panel-placeholder">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span>Selecciona un aula</span>
                </div>
            </div>

<div id="accion-alta" style="display:none;"></div>

<div id="accion-baja" style="display:none;"></div>
        <div id="accion-alta" style="display:none;"></div>
    </div>

</div>{{-- /plants-grid --}}
    </div>{{-- /u-main --}}

    <footer class="u-footer">
        © 2025 Ubicatec — Edificio C &nbsp;·&nbsp; {{ $dia }} {{ substr($hora,0,5) }}
    </footer>

</div>{{-- /wrapper --}}

{{-- ══════════════════════════════════════════════
     MODAL RESERVA / BLOQUEO
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalApartar">
    <div class="modal-box">
        <div class="modal-title">
            <span id="modal-icono">📌</span>&nbsp;
            <span id="modal-titulo-texto">Reservar Aula</span>
            <span id="modal-aula-nombre" style="color:var(--teal-light);font-size:.9rem;font-family:'Inter',sans-serif;margin-left:4px;"></span>
        </div>

        <div id="modal-apartados-existentes"
             style="display:none;background:#fef3e2;border:1px solid #f39c12;border-radius:8px;padding:8px 12px;font-size:.73rem;color:#b7770d;margin-bottom:8px;"></div>

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
                <div id="modal-slots-container">
                    <p class="no-slots-msg">Cargando…</p>
                </div>
            </div>

            <div id="nota-limite-docente"
                 style="display:none;background:#e8fdf2;border:1px solid #27ae60;border-radius:8px;padding:7px 12px;font-size:.72rem;color:#27ae60;margin-bottom:4px;">
                ⏱️ Puedes reservar <strong>máximo 2 horas</strong>. Los slots disponibles ya están ajustados a ese límite.
            </div>

            <div id="seccion-hora-manual" style="display:none;">
                <div class="modal-section-title" style="color:#b7770d;">✏️ Horario personalizado</div>
                <div style="display:flex;gap:10px;">
                    <div style="flex:1;">
                        <label>Hora inicio</label>
                        <input type="time" id="manual-hora-inicio" value="07:00">
                    </div>
                    <div style="flex:1;">
                        <label>Hora fin</label>
                        <input type="time" id="manual-hora-fin" value="09:00">
                    </div>
                </div>
                <button type="button" onclick="aplicarHoraManual()"
                    style="margin-top:6px;width:100%;padding:7px;background:#f39c12;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.77rem;">
                    📋 Aplicar horario personalizado
                </button>
            </div>

            <div id="seccion-datos-reserva" style="display:none;">
                <div class="modal-section-title">📝 Datos</div>
                <div id="campos-aula">
                    <label>Asunto</label>
            <input type="text" name="materia_reserva" id="modal-asunto" placeholder="Ej: Proyectos integradores"><div id="resumen-horas" style="display:none;margin-top:8px;padding:7px 10px;background:#e8f4fd;border-radius:8px;font-size:.73rem;color:var(--teal);">
                        ⏱️ <strong id="duracion-texto">—</strong>
                    </div>
                </div>
                <div id="campos-banio" style="display:none;">
                    <label>Motivo</label>
                    <input type="text" name="nombre_solicitante" placeholder="Ej: Limpieza, Mantenimiento…">
                    <div id="resumen-horas"
                         style="display:none;margin-top:8px;padding:7px 10px;background:#e8f4fd;border-radius:8px;font-size:.73rem;color:var(--teal);">
                        ⏱️ <strong id="duracion-texto">—</strong>
                    </div>
                </div>
                <div id="campos-banio" style="display:none;">
                    <label>Motivo</label>
                    <input type="text" name="nombre_solicitante" placeholder="Ej: Limpieza, Mantenimiento…">
                </div>
            </div>

            <div id="modal-preview"
                 style="display:none;margin-top:11px;padding:8px 12px;background:#e8f4fd;border-radius:8px;font-size:.73rem;color:var(--teal);"></div>
            <div id="modal-error"
                 style="display:none;margin-top:8px;padding:8px 12px;background:#fde8e8;border:1px solid #e74c3c;border-radius:8px;font-size:.75rem;color:#c0392b;"></div>

            <div class="modal-actions">
                <button type="submit" class="btn-confirmar">✅ Confirmar</button>
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            </div>
        </form>

        <p class="modal-note" id="modal-nota">⏳ El apartado expira al inicio de la siguiente semana</p>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL GALERÍA
══════════════════════════════════════════════ --}}
<div class="gallery-overlay" id="galleryOverlay">
    <div class="gallery-modal" id="galleryModal">
        <div class="gallery-modal-header">
            <div class="gallery-modal-icon" id="galleryModalIcon">🏛️</div>
            <div>
                <div class="gallery-modal-title" id="galleryModalTitle">Galería</div>
                <div class="gallery-modal-sub"   id="galleryModalSub">Imágenes del área</div>
            </div>
            <button class="gallery-modal-close" id="galleryModalClose" aria-label="Cerrar">✕</button>
        </div>

        <div class="gallery-body">
            @if(session('usuario')?->rol === 'administrador')
                <button class="btn-add-photo" id="btnToggleForm">➕ Agregar fotografía</button>
                <div class="upload-form" id="uploadForm">
                    <form id="formSubirFoto" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="area" id="formArea">
                        <label>Título de la imagen</label>
                        <input type="text" name="titulo" id="inputTitulo" placeholder="Ej: Laboratorio de metrología" required>
                        <label>Descripción</label>
                        <textarea name="descripcion" id="inputDesc" placeholder="Describe qué muestra esta foto…"></textarea>
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

{{-- ══════════════════════════════════════════════
     LIGHTBOX
══════════════════════════════════════════════ --}}
<div class="lightbox-overlay" id="lightboxOverlay" style="position:fixed;">
    <button class="lightbox-close" id="lightboxClose">✕</button>
    <div class="lightbox-content">
        <img src="" alt="" id="lightboxImg" class="lightbox-img">
        <div class="lightbox-info">
            <div class="lightbox-title" id="lightboxTitle"></div>
            <div class="lightbox-desc"  id="lightboxDesc"></div>
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
// ══════════════════════════════════════════════════════════
// EDIFICIO C — Script único corregido
// Usa HORARIOS + APARTADOS desde Laravel
// Modal de reservar/bloquear funcionando
// Galería funcionando para labels y aulas con data-area
// ══════════════════════════════════════════════════════════

const HORARIOS   = @json($horarios ?? []);
const APARTADOS  = @json($apartados ?? []);
const DIA_REAL   = "{{ $dia }}";
const ROL        = "{{ session('usuario') ? (session('usuario')->rol ?? 'estudiante') : 'estudiante' }}";
const ES_ADMIN   = ['admin', 'administrador', 'jefe_departamento'].includes(ROL);
const ES_DOCENTE = ROL === 'docente';
const ES_ESTUDIANTE = ROL === 'estudiante';

let DIA_HOY = DIA_REAL;
let aulaActiva = null;
let plantaActiva = null;
let slotsDocente = [];

const JORNADA_INI = 420;   // 07:00
const JORNADA_FIN = 1320;  // 22:00

const selectorDia = document.getElementById('selectorDia');
const badgeDia = document.getElementById('badge-dia-simulado');
const slider = document.getElementById('sliderHora');
const sliderLbl = document.getElementById('sliderHoraLabel');
const badgeSim = document.getElementById('badge-simulada');
const horaDisplay = document.getElementById('hora-display');
const toastEl = document.getElementById('toastMsg');

if (selectorDia) selectorDia.value = DIA_REAL;

const now = new Date();
let HORA_REAL_MIN = now.getHours() * 60 + now.getMinutes();
let horaMin = HORA_REAL_MIN;

const NOMBRES_AULAS = {
    'SIMULACION': 'Simulación',
    'AUTOMATIZACION': 'Automatización',
    'LAB_TIEMPOS_MOV': 'Laboratorio de Tiempos y Movimientos',
    'LAB_METROLOGIA': 'Laboratorio de Metrología',
    'BANIOS_MUJERES_C': 'Baños Mujeres',
    'BANIOS_HOMBRES_C': 'Baños Hombres',
};

const AREA_LABELS = {
    'c-baja-auditorio': { icon:'🏛️', title:'Auditorio' },
    'c-baja-cubiculo-ingles': { icon:'🇬🇧', title:'Cubículo de Inglés' },
    'c-baja-controles-electricos': { icon:'⚡', title:'Controles eléctricos' },
    'c-baja-lobby': { icon:'🏢', title:'Lobby' },
    'c-baja-entrada-lateral': { icon:'🚪', title:'Entrada lateral' },
    'c-baja-area-investigacion': { icon:'🔬', title:'Área de investigación' },
    'c-baja-division-industrial': { icon:'🏭', title:'División Industrial' },
    'c-baja-division-contabilidad': { icon:'📊', title:'División Contabilidad' },
    'c-baja-division-electromecanica': { icon:'⚙️', title:'División Electromecánica' },
    'c-baja-escaleras': { icon:'🪜', title:'Escaleras a planta alta' },
    'c-baja-entrada-principal': { icon:'🚪', title:'Entrada principal' },

    'c-alta-posgrado-investigacion': { icon:'🎓', title:'Departamento de Posgrado e Investigación' },
    'c-alta-materiales': { icon:'🧱', title:'Materiales' },
    'c-alta-area-academica': { icon:'📚', title:'Área academia' },
    'c-alta-escaleras': { icon:'🪜', title:'Escaleras a planta baja' },
    'c-alta-pasillo-lobby': { icon:'🏢', title:'Pasillo / Lobby' },
    'c-alta-psicologia': { icon:'🧠', title:'Departamento de Psicología' },
    'c-alta-ciencias-basicas': { icon:'🧪', title:'Dpto. de Ciencias Básicas / Psicología' },
    'c-alta-gestion-1': { icon:'💼', title:'Departamento de Gestión Empresarial' },
    'c-alta-gestion-2': { icon:'💼', title:'Departamento de Gestión Empresarial' },
    'c-alta-controles-electricos': { icon:'⚡', title:'Controles eléctricos' },

    'c-baja-simulacion': { icon:'🧪', title:'Simulación' },
    'c-baja-automatizacion': { icon:'⚙️', title:'Automatización' },
    'c-baja-banios-mujeres': { icon:'🚻', title:'Baños Mujeres' },
    'c-alta-lab-tiempos-mov': { icon:'⏱️', title:'Laboratorio de Tiempos y Movimientos' },
    'c-alta-lab-metrologia': { icon:'🔬', title:'Laboratorio de Metrología' },
    'c-alta-banios-hombres': { icon:'🚻', title:'Baños Hombres' },
};

function showToast(msg, tipo = 'info'){
    if(!toastEl) return;
    toastEl.textContent = msg;
    toastEl.style.backgroundColor =
        tipo === 'success' ? '#27ae60' :
        tipo === 'error'   ? '#c0392b' :
                             '#376073';
    toastEl.classList.add('show');
    clearTimeout(showToast._timer);
    showToast._timer = setTimeout(() => toastEl.classList.remove('show'), 3200);
}

function norm(s){ return (s ?? '').toString().trim().toUpperCase(); }
function hToMin(h){
    if(!h) return 0;
    const p = h.toString().split(':');
    return (+p[0] * 60) + (+p[1]);
}
function minToH(m){
    return String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0');
}

function normAula(raw){
    const a = (raw ?? '').toString().trim().toUpperCase().replace(/\s+/g, '');
    const MAP = {
        'SIMULACION': 'SIMULACION',
        'AUTOMATIZACION': 'AUTOMATIZACION',
        'LAB_TIEMPOS_MOV': 'LAB_TIEMPOS_MOV',
        'LAB_METROLOGIA': 'LAB_METROLOGIA',
        'BANIOS_MUJERES_C': 'BANIOS_MUJERES_C',
        'BANIOS_HOMBRES_C': 'BANIOS_HOMBRES_C',
    };
    return MAP[a] ?? a;
}

function esBanio(raw){
    const v = norm(raw);
    return v.includes('BANIO') || v.includes('BAÑO') || v.includes('BANIOS');
}

function getBloqueado(raw){
    const bd = normAula(raw);
    return APARTADOS.find(ap =>
        normAula(ap.aula) === bd &&
        norm(ap.dia) === norm(DIA_HOY) &&
        Number(ap.tipo_bloqueo) === 1 &&
        hToMin(ap.hora_inicio) <= horaMin &&
        horaMin < hToMin(ap.hora_fin)
    ) || null;
}

function getApartado(raw){
    const bd = normAula(raw);
    return APARTADOS.find(ap =>
        normAula(ap.aula) === bd &&
        norm(ap.dia) === norm(DIA_HOY) &&
        Number(ap.tipo_bloqueo) !== 1 &&
        hToMin(ap.hora_inicio) <= horaMin &&
        horaMin < hToMin(ap.hora_fin)
    ) || null;
}

function getClase(raw){
    const bd = normAula(raw);
    return HORARIOS.find(h =>
        normAula(h.aula) === bd &&
        norm(h.dia) === norm(DIA_HOY) &&
        hToMin(h.hora_inicio) <= horaMin &&
        horaMin < hToMin(h.hora_fin)
    ) || null;
}

function isOcupada(raw){ return !!getClase(raw); }
function isApartada(raw){ return !!getApartado(raw); }
function isBloqueada(raw){ return !!getBloqueado(raw); }

function getSlots(raw, dia){
    const bd = normAula(raw);
    const d = norm(dia);

    const bloques = [
        ...HORARIOS
            .filter(h => normAula(h.aula) === bd && norm(h.dia) === d)
            .map(h => ({
                hi: hToMin(h.hora_inicio),
                hf: hToMin(h.hora_fin),
                libre: false,
                label: h.materia,
                tipo: 'clase'
            })),
        ...APARTADOS
            .filter(ap => normAula(ap.aula) === bd && norm(ap.dia) === d)
            .map(ap => ({
                hi: hToMin(ap.hora_inicio),
                hf: hToMin(ap.hora_fin),
                libre: false,
                label: ap.nombre_solicitante ?? 'Apartado',
                tipo: Number(ap.tipo_bloqueo) === 1 ? 'bloqueado' : 'apartado'
            }))
    ].sort((a,b) => a.hi - b.hi);

    const slots = [];
    let cur = JORNADA_INI;

    for(const b of bloques){
        if(b.hi > cur) slots.push({ hi: cur, hf: b.hi, libre: true });
        slots.push(b);
        cur = Math.max(cur, b.hf);
    }

    if(cur < JORNADA_FIN) slots.push({ hi: cur, hf: JORNADA_FIN, libre: true });
    return slots;
}

function traducirGrupo(grupo){
    if(!grupo) return null;
    const g = grupo.toString().trim().toUpperCase();
    if(g.startsWith('MX') || g.startsWith('VX')) return 'Extraescolar';

    const carreras = {
        S:'Sistemas Computacionales',
        E:'Electromecanica',
        I:'Industrial',
        C:'Contaduría',
        G:'Gestión Empresarial',
        T:'Turismo',
        A:'Ambiental',
    };

    const letra = g.charAt(0);
    const sufijo = g.slice(1);
    const carrera = carreras[letra] ?? letra;
    const partes = sufijo.match(/^(\d+)?([A-Z])?$/);
    const semestre = partes?.[1] ? `${partes[1]}°` : '';
    const grp = partes?.[2] ? ` Grupo ${partes[2]}` : '';
    return `${carrera}${semestre ? ' ' + semestre : ''}${grp}`;
}

function panelHTML(raw){
    const banio = esBanio(raw);
    const bloq = getBloqueado(raw);
    const apart = getApartado(raw);
    const clase = getClase(raw);
    const slots = getSlots(raw, DIA_HOY);
    const futuros = slots.filter(s => s.hf > horaMin);
    const nombre = NOMBRES_AULAS[normAula(raw)] || normAula(raw);

    let chip = '', extra = '';

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
        return `
            <div class="panel-content">
                <div class="info-row">
                    <span class="info-label">Área</span>
                    <span class="info-val"><strong>${nombre}</strong>${chip}</span>
                </div>
                ${extra}
            </div>
        `;
    }

    let slotsHTML = '';
    if(!ES_ESTUDIANTE && futuros.length > 0){
        slotsHTML = `
            <div class="slots-disponibles">
                <div class="slots-title">Horarios del día</div>
                <div class="slots-grid">
                    ${futuros.slice(0,8).map(s => {
                        const cls = s.libre ? 'slot-libre' : (s.tipo === 'bloqueado' ? 'slot-bloqueado' : 'slot-ocupado');
                        const lbl = s.libre ? 'Libre' : (s.tipo === 'bloqueado' ? 'Bloqueado' : (s.label?.slice(0,14) ?? 'Ocupado'));
                        return `<span class="slot-chip ${cls}">${minToH(s.hi)}-${minToH(s.hf)} <small>${lbl}</small></span>`;
                    }).join('')}
                </div>
            </div>
        `;
    }

    let estudHTML = '';
    if(ES_ESTUDIANTE){
        const prox = slots.filter(s => s.libre && s.hi >= horaMin).slice(0,3);
        estudHTML = `
            <div class="info-box">
                <div class="slots-title">Próximas disponibilidades</div>
                <div>
                    ${prox.length > 0
                        ? prox.map(s => `<span class="slot-chip slot-libre" style="margin:2px 4px 2px 0">${minToH(s.hi)}-${minToH(s.hf)}</span>`).join('')
                        : '<span style="color:var(--muted)">Sin disponibilidad hoy</span>'
                    }
                </div>
            </div>
        `;
    }

    return `
        <div class="panel-content">
            <div class="info-row">
                <span class="info-label">Aula</span>
                <span class="info-val"><strong>${nombre}</strong>${chip}</span>
            </div>
            ${extra}
            ${slotsHTML}
            ${estudHTML}
        </div>
    `;
}

function accionHTML(raw){
    const banio = esBanio(raw);
    const bloq = isBloqueada(raw);
    const clase = getClase(raw);
    const slots = getSlots(raw, DIA_HOY);
    const hayLibre = slots.some(s => s.libre && s.hf > horaMin);

    if(ES_ESTUDIANTE) return '';

    if(banio){
        if(!ES_ADMIN){
            return `
                <div class="aviso-no-disponible" style="background:#fef9e7;color:#b7770d;border:1px solid #f39c12;">
                    🔒 Solo administradores pueden gestionar esta área
                </div>
            `;
        }
        if(bloq) return `<button class="btn-apartar" onclick="abrirModal('${raw}',true)">🔧 Modificar estado</button>`;
        return `<button class="btn-bloquear" onclick="abrirModal('${raw}',true)">🚫 Marcar como no disponible</button>`;
    }

    if(ES_ADMIN){
        return `
            <button class="btn-apartar" onclick="abrirModal('${raw}',false)">📌 Apartar / Reservar aula</button>
            <button class="btn-bloquear" style="margin-top:6px" onclick="abrirModal('${raw}',true)">🔒 Bloquear aula</button>
        `;
    }

    if(ES_DOCENTE){
        if(!clase && !bloq){
            return `<button class="btn-apartar" onclick="abrirModal('${raw}',false)">📌 Apartar aula</button>`;
        }

        const btnOtro = hayLibre
            ? `<button class="btn-apartar" style="margin-top:8px;background:linear-gradient(135deg,#2f9e63,#3db977)" onclick="abrirModal('${raw}',false)">📅 Apartar en otro horario</button>`
            : '';

        return `<div class="aviso-no-disponible">⛔ Ocupada en este horario</div>${btnOtro}`;
    }

    return '';
}

function colorear(){
    let lc = 0, oc = 0, ac = 0;

    document.querySelectorAll('.aula-box').forEach(el => {
        const raw = el.dataset.aula;
        const b = isBloqueada(raw);
        const o = isOcupada(raw);
        const a = isApartada(raw);

        el.classList.toggle('bloqueada', b);
        el.classList.toggle('ocupada', !b && o && !a);
        el.classList.toggle('libre', !b && !o && !a);
        el.classList.toggle('apartada', !b && a);

        if(!b && !o && !a) lc++;
        else if(!b && o && !a) oc++;
        else if(!b && a) ac++;
    });

    const libres = document.getElementById('cont-libres');
    const ocupadas = document.getElementById('cont-ocupadas');
    const apartadas = document.getElementById('cont-apartadas');

    if(libres) libres.textContent = lc;
    if(ocupadas) ocupadas.textContent = oc;
    if(apartadas) apartadas.textContent = ac;
}

function seleccionar(raw, planta){
    document.querySelectorAll('.aula-box').forEach(el => el.classList.remove('selected'));

    const el = document.querySelector(`.aula-box[data-aula="${raw}"]`);
    if(el) el.classList.add('selected');

    aulaActiva = raw;
    plantaActiva = planta;

    const panel = document.getElementById('panel-' + planta);
    const accion = document.getElementById('accion-' + planta);
    const otrAcc = document.getElementById('accion-' + (planta === 'baja' ? 'alta' : 'baja'));

    if(panel) panel.innerHTML = panelHTML(raw);

    if(accion){
        const html = accionHTML(raw);
        accion.innerHTML = html;
        accion.style.display = html ? 'block' : 'none';
    }

    if(otrAcc) otrAcc.style.display = 'none';
}

function refrescarPanelActivo(){
    if(aulaActiva && plantaActiva) seleccionar(aulaActiva, plantaActiva);
}

function actualizarSlider(min){
    horaMin = min;
    const lbl = minToH(min);

    if(sliderLbl) sliderLbl.textContent = lbl;
    if(horaDisplay) horaDisplay.textContent = lbl;

    if(slider){
        const pct = ((min - JORNADA_INI) / (JORNADA_FIN - JORNADA_INI)) * 100;
        slider.style.background = `linear-gradient(90deg, var(--teal) ${pct}%, #cbd8de ${pct}%)`;
    }

    if(badgeSim) badgeSim.style.display = (min === HORA_REAL_MIN) ? 'none' : 'inline-flex';

    colorear();
    refrescarPanelActivo();
}

function resetSlider(){
    if(slider) slider.value = HORA_REAL_MIN;
    actualizarSlider(HORA_REAL_MIN);
}
window.resetSlider = resetSlider;

function resetDia(){
    DIA_HOY = DIA_REAL;
    if(selectorDia) selectorDia.value = DIA_REAL;
    if(badgeDia) badgeDia.style.display = 'none';
    colorear();
    refrescarPanelActivo();
}
window.resetDia = resetDia;

function mostrarError(msg){
    const err = document.getElementById('modal-error');
    if(!err) return;
    err.textContent = msg;
    err.style.display = 'block';
}

function limpiarError(){
    const err = document.getElementById('modal-error');
    if(err) err.style.display = 'none';
}

function abrirModal(raw, esBloqueo){
    aulaActiva = raw;
    slotsDocente = [];

    document.getElementById('modal-aula-input').value = normAula(raw);
    document.getElementById('modal-aula-nombre').textContent = NOMBRES_AULAS[normAula(raw)] || normAula(raw);
    document.getElementById('modal-tipo-bloqueo').value = esBloqueo ? '1' : '0';
    document.getElementById('modal-override').value = '0';
    document.getElementById('modal-hora-inicio-hidden').value = '';
    document.getElementById('modal-hora-fin-hidden').value = '';
    document.getElementById('modal-dia').value = DIA_HOY;
    document.getElementById('modal-preview').style.display = 'none';
    document.getElementById('resumen-horas').style.display = 'none';
    document.getElementById('seccion-datos-reserva').style.display = 'none';
    document.getElementById('nota-limite-docente').style.display = ES_DOCENTE ? 'block' : 'none';
    limpiarError();

    if(esBloqueo){
        document.getElementById('modal-icono').textContent = '🚫';
        document.getElementById('modal-titulo-texto').textContent = 'Estado del área';
        document.getElementById('modal-nota').textContent = 'El área quedará marcada como no disponible.';
        document.getElementById('seccion-slots').style.display = 'none';
        document.getElementById('seccion-hora-manual').style.display = 'none';
        document.getElementById('campos-aula').style.display = 'none';
        document.getElementById('campos-banio').style.display = 'block';
        document.getElementById('seccion-datos-reserva').style.display = 'block';
        document.getElementById('modal-hora-inicio-hidden').value = '07:00';
        document.getElementById('modal-hora-fin-hidden').value = '22:00';
    } else {
        document.getElementById('modal-icono').textContent = '📌';
        document.getElementById('modal-titulo-texto').textContent = ES_ADMIN ? 'Reservar / Override' : 'Apartar aula';
        document.getElementById('modal-nota').textContent = ES_DOCENTE
            ? 'Selecciona 1 o 2 bloques de 1 hora consecutivos.'
            : 'Puedes reservar dentro de los espacios libres del día.';
        document.getElementById('seccion-slots').style.display = 'block';
        document.getElementById('seccion-hora-manual').style.display = ES_ADMIN ? 'block' : 'none';
        document.getElementById('campos-aula').style.display = 'block';
        document.getElementById('campos-banio').style.display = 'none';
        renderSlotsModal();
    }

    document.getElementById('modalApartar').classList.add('active');
}
window.abrirModal = abrirModal;

function cerrarModal(){
    document.getElementById('modalApartar').classList.remove('active');
    slotsDocente = [];
}
window.cerrarModal = cerrarModal;

function renderSlotsModal(){
    if(!aulaActiva) return;

    const dia = document.getElementById('modal-dia').value;
    const slots = getSlots(aulaActiva, dia);
    const cont = document.getElementById('modal-slots-container');
    if(!cont) return;

    slotsDocente = [];
    cont.innerHTML = '';

    if(!slots.length){
        cont.innerHTML = '<p class="modal-note">Sin horarios registrados.</p>';
        return;
    }

    let botonesHTML = '';

    for(const s of slots){
        if(s.libre){
            if(ES_DOCENTE){
                let cursor = s.hi;
                while(cursor < s.hf){
                    const fin = Math.min(cursor + 60, s.hf);
                    if(fin - cursor >= 30){
                        botonesHTML += `
                            <button type="button" class="modal-slot disponible"
                                data-hi="${cursor}" data-hf="${fin}"
                                onclick="seleccionarSlotDocente(${cursor},${fin},this)">
                                ${minToH(cursor)}-${minToH(fin)}<br><small>Libre</small>
                            </button>`;
                    }
                    cursor += 60;
                }
            } else {
                botonesHTML += `
                    <button type="button" class="modal-slot disponible"
                        onclick="elegirSlot(${s.hi},${s.hf},false,this)">
                        ${minToH(s.hi)}-${minToH(s.hf)}<br><small>Libre</small>
                    </button>`;
            }
        } else {
            const lbl = s.tipo === 'bloqueado' ? 'Bloqueado' : s.tipo === 'apartado' ? 'Apartado' : 'Clase';

            botonesHTML += ES_ADMIN
                ? `<button type="button" class="modal-slot override"
                        onclick="elegirSlot(${s.hi},${s.hf},true,this)">
                        ${minToH(s.hi)}-${minToH(s.hf)}<br><small>${lbl}</small>
                   </button>`
                : `<button type="button" class="modal-slot no-disponible" disabled>
                        ${minToH(s.hi)}-${minToH(s.hf)}<br><small>${lbl}</small>
                   </button>`;
        }
    }

    cont.innerHTML = `<div class="modal-slots-grid">${botonesHTML}</div>`;
}
window.renderSlotsModal = renderSlotsModal;

function seleccionarSlotDocente(hi, hf, btn){
    limpiarError();

    const idx = slotsDocente.findIndex(s => s.hi === hi && s.hf === hf);
    if(idx !== -1){
        slotsDocente.splice(idx, 1);
        btn.classList.remove('activo');
        actualizarResumenDocente();
        return;
    }

    if(slotsDocente.length >= 2){
        mostrarError('Máximo 2 horas para docentes.');
        return;
    }

    if(slotsDocente.length === 1){
        const prev = slotsDocente[0];
        if((prev.hf !== hi) && (hf !== prev.hi)){
            document.querySelectorAll('.modal-slot.activo').forEach(b => b.classList.remove('activo'));
            slotsDocente = [];
        }
    }

    slotsDocente.push({ hi, hf });
    btn.classList.add('activo');
    actualizarResumenDocente();
}
window.seleccionarSlotDocente = seleccionarSlotDocente;

function actualizarResumenDocente(){
    if(slotsDocente.length === 0){
        document.getElementById('modal-hora-inicio-hidden').value = '';
        document.getElementById('modal-hora-fin-hidden').value = '';
        document.getElementById('seccion-datos-reserva').style.display = 'none';
        document.getElementById('resumen-horas').style.display = 'none';
        return;
    }

    slotsDocente.sort((a,b) => a.hi - b.hi);
    const hiTotal = slotsDocente[0].hi;
    const hfTotal = slotsDocente[slotsDocente.length - 1].hf;
    const dur = hfTotal - hiTotal;

    document.getElementById('modal-hora-inicio-hidden').value = minToH(hiTotal);
    document.getElementById('modal-hora-fin-hidden').value = minToH(hfTotal);
    document.getElementById('modal-override').value = '0';
    document.getElementById('seccion-datos-reserva').style.display = 'block';
    document.getElementById('duracion-texto').textContent = `${minToH(hiTotal)} – ${minToH(hfTotal)} (${Math.floor(dur/60)}h)`;
    document.getElementById('resumen-horas').style.display = 'block';
    limpiarError();
}

function elegirSlot(hi, hf, override, btn){
    document.querySelectorAll('.modal-slot').forEach(b => b.classList.remove('activo'));
    btn.classList.add('activo');

    document.getElementById('modal-hora-inicio-hidden').value = minToH(hi);
    document.getElementById('modal-hora-fin-hidden').value = minToH(hf);
    document.getElementById('modal-override').value = override ? '1' : '0';
    document.getElementById('seccion-datos-reserva').style.display = 'block';

    const dur = hf - hi;
    document.getElementById('duracion-texto').textContent = `${minToH(hi)} – ${minToH(hf)} (${Math.floor(dur/60)}h${dur % 60 ? ' ' + (dur % 60) + 'min' : ''})`;
    document.getElementById('resumen-horas').style.display = 'block';

    const dia = document.getElementById('modal-dia').value;
    const prev = document.getElementById('modal-preview');
    prev.style.background = override ? '#fef3e2' : '#e8f4fd';
    prev.style.borderColor = override ? '#f4d48e' : '#cfe2ea';
    prev.style.color = override ? '#a56a08' : 'var(--teal)';
    prev.innerHTML = `<strong>${normAula(aulaActiva)}</strong> · ${dia} · <strong>${minToH(hi)}</strong> a <strong>${minToH(hf)}</strong>${override ? ' · Override' : ''}`;
    prev.style.display = 'block';

    limpiarError();
}
window.elegirSlot = elegirSlot;

function aplicarHoraManual(){
    const hi = document.getElementById('manual-hora-inicio')?.value;
    const hf = document.getElementById('manual-hora-fin')?.value;

    if(!hi || !hf){
        mostrarError('Debes seleccionar hora inicio y fin.');
        return;
    }

    if(hToMin(hf) <= hToMin(hi)){
        mostrarError('La hora fin debe ser mayor a la hora inicio.');
        return;
    }

    document.getElementById('modal-hora-inicio-hidden').value = hi;
    document.getElementById('modal-hora-fin-hidden').value = hf;
    document.getElementById('modal-override').value = '1';
    document.getElementById('seccion-datos-reserva').style.display = 'block';
    document.getElementById('duracion-texto').textContent = `${hi} – ${hf}`;
    document.getElementById('resumen-horas').style.display = 'block';

    const prev = document.getElementById('modal-preview');
    prev.style.display = 'block';
    prev.innerHTML = `<strong>${normAula(aulaActiva)}</strong> · ${document.getElementById('modal-dia').value} · <strong>${hi}</strong> a <strong>${hf}</strong> · Override manual`;

    limpiarError();
}
window.aplicarHoraManual = aplicarHoraManual;

// ══════════════════════════════════════════════════════════
// GALERÍA
// ══════════════════════════════════════════════════════════
(() => {
    const overlay = document.getElementById('galleryOverlay');
    const modalClose = document.getElementById('galleryModalClose');
    const modalIcon = document.getElementById('galleryModalIcon');
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalSub = document.getElementById('galleryModalSub');
    const galleryGrid = document.getElementById('galleryGrid');
    const galleryEmpty = document.getElementById('galleryEmpty');
    const formSubir = document.getElementById('formSubirFoto');
    const btnToggle = document.getElementById('btnToggleForm');
    const btnCancel = document.getElementById('btnCancelForm');
    const formArea = document.getElementById('formArea');
    const uploadFormEl = document.getElementById('uploadForm');
    const lightboxOv = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxTitl = document.getElementById('lightboxTitle');
    const lightboxDesc = document.getElementById('lightboxDesc');
    const lightboxClos = document.getElementById('lightboxClose');

    let currentArea = null;

    document.querySelectorAll('.label-box-galeria[data-area], .aula-box[data-area]').forEach(el => {
        const evento = el.classList.contains('aula-box') ? 'dblclick' : 'click';
        el.addEventListener(evento, (e) => {
            e.stopPropagation();
            const area = el.dataset.area;
            if(area) openGallery(area);
        });
    });

    function openGallery(areaKey){
        currentArea = areaKey;
        const d = AREA_LABELS[areaKey] || { icon:'🏛️', title:areaKey };
        if(modalIcon) modalIcon.textContent = d.icon;
        if(modalTitle) modalTitle.textContent = d.title;
        if(modalSub) modalSub.textContent = 'Imágenes del área';
        if(formArea) formArea.value = areaKey;
        if(overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        loadPhotos(areaKey);
    }

    function closeGallery(){
        if(overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
        if(uploadFormEl) uploadFormEl.classList.remove('open');
        if(btnToggle) btnToggle.textContent = '➕ Agregar fotografía';
    }

    if(modalClose) modalClose.addEventListener('click', closeGallery);
    if(overlay) overlay.addEventListener('click', e => { if(e.target === overlay) closeGallery(); });

    if(btnToggle){
        btnToggle.addEventListener('click', () => {
            if(!uploadFormEl) return;
            uploadFormEl.classList.toggle('open');
            btnToggle.textContent = uploadFormEl.classList.contains('open')
                ? 'Cancelar'
                : '➕ Agregar fotografía';
        });
    }

    if(btnCancel){
        btnCancel.addEventListener('click', () => {
            if(uploadFormEl) uploadFormEl.classList.remove('open');
            if(btnToggle) btnToggle.textContent = '➕ Agregar fotografía';
        });
    }

    if(formSubir){
        formSubir.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(formSubir);

            try{
                const res = await fetch(`{{ route('galeria.store') }}`, {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await res.json();

                if(data.ok){
                    formSubir.reset();
                    if(uploadFormEl) uploadFormEl.classList.remove('open');
                    if(btnToggle) btnToggle.textContent = '➕ Agregar fotografía';
                    await loadPhotos(currentArea);
                    showToast('Imagen agregada correctamente', 'success');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch {
                showToast('Error de conexión', 'error');
            }
        });
    }

    async function loadPhotos(area){
        if(!galleryGrid || !galleryEmpty) return;
        galleryGrid.innerHTML = `<div class="gallery-empty" style="display:block">Cargando imágenes…</div>`;
        galleryEmpty.style.display = 'none';

        try{
            const res = await fetch(`{{ url('galeria/fotos') }}?area=${encodeURIComponent(area)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            renderPhotos(data.fotos || []);
        } catch {
            galleryGrid.innerHTML = `<div class="gallery-empty" style="display:block;color:var(--red)">Error al cargar imágenes.</div>`;
        }
    }

    function renderPhotos(fotos){
        if(!galleryGrid || !galleryEmpty) return;

        galleryGrid.innerHTML = '';
        if(!fotos.length){
            galleryEmpty.style.display = 'block';
            return;
        }

        galleryEmpty.style.display = 'none';

        fotos.forEach(f => {
            const card = document.createElement('div');
            card.className = 'gallery-card';

            const imgTag = f.imagen_url
                ? `<img src="${f.imagen_url}" alt="${f.titulo}" class="gallery-card-img" loading="lazy">`
                : `<div class="gallery-card-img" style="display:flex;align-items:center;justify-content:center;font-size:2rem;">🖼️</div>`;

            const adminBtns = ES_ADMIN
                ? `<div class="gallery-card-actions">
                       <button class="btn-del-photo" onclick="deletePhoto(${f.id})">Eliminar</button>
                   </div>`
                : '';

            card.innerHTML = `
                ${imgTag}
                <div class="gallery-card-info">
                    <div class="gallery-card-title">${f.titulo}</div>
                    <div class="gallery-card-desc">${f.descripcion || 'Sin descripción'}</div>
                    <div class="gallery-card-meta">${f.created_at_human || ''}</div>
                </div>
                ${adminBtns}
            `;

            if(f.imagen_url){
                card.querySelector('.gallery-card-img')?.addEventListener('click', () => {
                    openLightbox(f.imagen_url, f.titulo, f.descripcion || '');
                });
            }

            galleryGrid.appendChild(card);
        });
    }

    window.deletePhoto = async function(id){
        if(!confirm('¿Eliminar esta imagen?')) return;

        try{
            const res = await fetch(`{{ url('galeria/fotos') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await res.json();

            if(data.ok){
                await loadPhotos(currentArea);
                showToast('Imagen eliminada', 'success');
            } else {
                showToast(data.message || 'Error al eliminar', 'error');
            }
        } catch {
            showToast('Error al eliminar', 'error');
        }
    };

    function openLightbox(url, title, desc){
        if(lightboxImg) lightboxImg.src = url;
        if(lightboxTitl) lightboxTitl.textContent = title;
        if(lightboxDesc) lightboxDesc.textContent = desc;
        if(lightboxOv) lightboxOv.classList.add('active');
    }

    function closeLightbox(){
        if(lightboxOv) lightboxOv.classList.remove('active');
    }

    if(lightboxClos) lightboxClos.addEventListener('click', closeLightbox);
    if(lightboxOv) lightboxOv.addEventListener('click', e => { if(e.target === lightboxOv) closeLightbox(); });

    document.addEventListener('keydown', e => {
        if(e.key === 'Escape'){
            if(lightboxOv?.classList.contains('active')) closeLightbox();
            else if(overlay?.classList.contains('active')) closeGallery();
            else if(document.getElementById('modalApartar')?.classList.contains('active')) cerrarModal();
        }
    });
})();

// ══════════════════════════════════════════════════════════
// EVENTOS
// ══════════════════════════════════════════════════════════
document.querySelectorAll('#planta-baja-c .aula-box').forEach(el => {
    el.addEventListener('click', () => seleccionar(el.dataset.aula, 'baja'));
});

document.querySelectorAll('#planta-alta-c .aula-box').forEach(el => {
    el.addEventListener('click', () => seleccionar(el.dataset.aula, 'alta'));
});

if(slider){
    slider.min = JORNADA_INI;
    slider.max = JORNADA_FIN;
    slider.step = 30;
    slider.value = HORA_REAL_MIN;
    slider.addEventListener('input', () => actualizarSlider(+slider.value));
}

if(selectorDia){
    selectorDia.addEventListener('change', () => {
        DIA_HOY = selectorDia.value;
        if(badgeDia) badgeDia.style.display = (DIA_HOY !== DIA_REAL) ? 'inline-flex' : 'none';
        colorear();
        refrescarPanelActivo();
    });
}

document.getElementById('modalApartar')?.addEventListener('click', function(e){
    if(e.target === this) cerrarModal();
});

document.getElementById('buscador')?.addEventListener('input', function(){
    const texto = this.value.toLowerCase().trim();
    document.querySelectorAll('.aula-box, .label-box-galeria').forEach(el => {
        const nombre = el.textContent.toLowerCase();
        el.style.opacity = (!texto || nombre.includes(texto)) ? '1' : '0.25';
    });
});

setInterval(() => {
    const n = new Date();
    const minActual = n.getHours() * 60 + n.getMinutes();
    if(minActual !== HORA_REAL_MIN){
        HORA_REAL_MIN = minActual;
        if(badgeSim && badgeSim.style.display === 'none'){
            resetSlider();
        } else {
            colorear();
            refrescarPanelActivo();
        }
    }
}, 10000);

// Arranque
actualizarSlider(HORA_REAL_MIN);
colorear();
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