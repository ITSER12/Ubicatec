@extends('layouts.app')

@section('title','Salones de prácticas - Ubicatec')

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
body{
    font-family:'Inter',sans-serif;
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
    display:flex;
    flex-direction:column;
}
.u-header{
    background:linear-gradient(135deg,var(--teal-dark),var(--teal) 60%,var(--teal-light));
    padding:10px 20px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 3px 14px rgba(0,0,0,.2);
}
.u-logo{
    width:38px;height:38px;
    background:rgba(255,255,255,.15);
    border:1.5px solid rgba(255,255,255,.3);
    border-radius:9px;
    display:flex;align-items:center;justify-content:center;
}
.u-logo svg{width:20px;height:20px;fill:white}
.u-brand{
    font-family:'Instrument Serif',serif;
    font-size:1.35rem;
    color:#fff;
    letter-spacing:-.2px;
}
.u-search{flex:1;display:flex;justify-content:center;}
.u-search input{
    width:55%;max-width:600px;
    padding:8px 18px;
    border-radius:22px;
    border:none;
    background:rgba(255,255,255,.18);
    color:#fff;
    font-size:.85rem;
    outline:none;
    backdrop-filter:blur(6px);
    transition:background .25s;
}
.u-search input::placeholder{color:rgba(255,255,255,.65);}
.u-search input:focus{background:rgba(255,255,255,.28);}
.u-back{
    color:rgba(255,255,255,.8);
    font-size:.78rem;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:5px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    border-radius:18px;
    padding:5px 12px;
    transition:background .2s;
}
.u-back:hover{background:rgba(255,255,255,.22);color:#fff;}
.role-badge{
    font-size:.7rem;
    font-weight:700;
    padding:3px 10px;
    border-radius:12px;
    letter-spacing:.4px;
    white-space:nowrap;
}
.role-admin{background:#e74c3c;color:#fff;}
.role-jefe{background:#f39c12;color:#fff;}
.role-docente{background:#27ae60;color:#fff;}
.role-estudiante{background:rgba(255,255,255,.25);color:#fff;border:1px solid rgba(255,255,255,.35);}

.u-main{
    flex:1;
    padding:20px 24px;
    display:flex;
    flex-direction:column;
    gap:16px;
}
.u-strip{
    display:flex;
    align-items:center;
    gap:12px;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:12px;
    padding:10px 16px;
    box-shadow:var(--shadow-sm);
    font-size:.8rem;
    color:var(--muted);
    flex-wrap:wrap;
}
.u-strip strong{color:var(--text);}
.strip-sep{color:var(--border);margin:0 4px;}
.day-chip{
    background:linear-gradient(135deg,var(--teal-dark),var(--teal));
    color:#fff;
    border-radius:20px;
    padding:3px 12px;
    font-size:.75rem;
    font-weight:700;
    letter-spacing:.3px;
}
.hora-slider-wrap{
    display:flex;
    align-items:center;
    gap:8px;
    background:#f0f6f9;
    border:1px solid var(--border);
    border-radius:10px;
    padding:5px 12px;
}
.hora-slider-wrap label{
    font-size:.72rem;
    font-weight:700;
    color:var(--teal);
    white-space:nowrap;
}
#sliderHora{
    -webkit-appearance:none;
    appearance:none;
    width:160px;
    height:5px;
    border-radius:3px;
    outline:none;
    cursor:pointer;
}
#sliderHora::-webkit-slider-thumb{
    -webkit-appearance:none;
    width:16px;height:16px;
    border-radius:50%;
    background:var(--teal);
    box-shadow:0 2px 6px rgba(55,96,115,.4);
    cursor:pointer;
}
#sliderHoraLabel{
    font-size:.78rem;
    font-weight:700;
    color:var(--teal);
    min-width:38px;
    text-align:center;
}
.hora-simulada-badge{
    background:#fff3cd;
    color:#b7770d;
    border:1px solid #f39c12;
    border-radius:8px;
    padding:2px 8px;
    font-size:.68rem;
    font-weight:700;
    display:none;
}
.u-legend{
    display:flex;
    align-items:center;
    gap:16px;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:10px;
    padding:8px 16px;
    font-size:.73rem;
    color:var(--muted);
    width:fit-content;
    box-shadow:var(--shadow-sm);
}
.leg-dot{width:11px;height:11px;border-radius:3px;flex-shrink:0;}

.prac-layout{
    display:grid;
    grid-template-columns: minmax(320px, 1.15fr) minmax(280px, .85fr);
    gap:18px;
    flex:1;
}
.prac-map-card,.prac-side-card{
    background:var(--surface);
    border:1.5px solid var(--border);
    border-radius:16px;
    padding:16px 18px;
    box-shadow:var(--shadow-md);
}
.prac-map-card:hover,.prac-side-card:hover{
    box-shadow:0 10px 32px rgba(55,96,115,.13);
}
.plant-title{
    font-family:'Instrument Serif',serif;
    font-size:1rem;
    color:var(--teal);
    display:flex;
    align-items:center;
    gap:7px;
    border-bottom:1.5px solid #e8f2f7;
    padding-bottom:8px;
    margin-bottom:12px;
}
.plant-title svg{width:16px;height:16px;stroke:var(--teal);fill:none;stroke-width:2;}

.mapa-prac{
    position:relative;
    width:100%;
    min-height:560px;
    border-radius:14px;
    border:1.5px solid #c8dde8;
    overflow:hidden;
    background:
        linear-gradient(180deg,rgba(255,255,255,.45),rgba(255,255,255,.45)),
        repeating-linear-gradient(
            0deg,
            #edf4f8 0px,
            #edf4f8 36px,
            #e7f0f5 36px,
            #e7f0f5 37px
        ),
        repeating-linear-gradient(
            90deg,
            #edf4f8 0px,
            #edf4f8 36px,
            #e7f0f5 36px,
            #e7f0f5 37px
        );
}
.mapa-prac::before{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(140deg,rgba(55,96,115,.04),transparent 35%,rgba(90,143,163,.06));
    pointer-events:none;
}
.icono-prac{
    position:absolute;
    left:20px;
    top:18px;
    font-size:1.35rem;
    color:var(--teal);
    z-index:1;
}
.corredor-label{
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
    border:1.5px dashed #c8dce6;
    background:#f8fbfd;
    border-radius:12px;
    padding:10px 16px;
    font-size:.78rem;
    font-weight:600;
    color:var(--muted);
    white-space:nowrap;
    text-align:center;
}

.aula-box{
    position:absolute;
    width:132px;
    height:110px;
    border-radius:14px;
    border:2px solid #c8dce6;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    font-size:.83rem;
    font-weight:700;
    cursor:pointer;
    transition:transform .22s var(--ease),box-shadow .22s,background .2s,border-color .2s;
    user-select:none;
    gap:4px;
    padding:8px;
}
.aula-box:hover{
    transform:translateY(-4px) scale(1.04);
    box-shadow:0 8px 20px rgba(55,96,115,.18);
}
.aula-box.selected{outline:3px solid var(--teal);outline-offset:2px;}
.aula-box .aula-icon{font-size:1.05rem;line-height:1;}
.aula-box.ocupada{
    background:linear-gradient(135deg,#e74c3c,#c0392b)!important;
    color:#fff;border-color:#c0392b;
    box-shadow:0 4px 14px rgba(231,76,60,.35);
}
.aula-box.libre{
    background:linear-gradient(135deg,#27ae60,#2ecc71)!important;
    color:#fff;border-color:#27ae60;
    box-shadow:0 4px 14px rgba(46,204,113,.35);
}
.aula-box.apartada{
    background:linear-gradient(135deg,#f39c12,#e67e22)!important;
    color:#fff;border-color:#e67e22;
    box-shadow:0 4px 14px rgba(243,156,18,.35);
}
.aula-box.bloqueada{
    background:linear-gradient(135deg,#636e72,#2d3436)!important;
    color:#fff;border-color:#2d3436;
    box-shadow:0 4px 14px rgba(45,52,54,.35);
    cursor:not-allowed;
}

#salon2{left:110px;top:85px;}
#salon3{left:110px;bottom:90px;}
#salon1{right:110px;top:50%;transform:translateY(-50%);}
#salon1:hover{transform:translateY(calc(-50% - 4px)) scale(1.04);}
#salon1.selected{outline:3px solid var(--teal);outline-offset:2px;}

.panel-info{
    background:linear-gradient(150deg,#f0f7ff,#e4f0f8);
    border:1.5px solid #c8dde8;
    border-radius:13px;
    padding:14px 16px;
    font-size:.8rem;
    position:relative;
    overflow:hidden;
    transition:box-shadow .3s;
    min-height:220px;
}
.panel-info:hover{box-shadow:0 6px 20px rgba(55,96,115,.12);}
.panel-info::before{
    content:'';
    position:absolute;
    top:0;left:-100%;
    width:100%;height:2.5px;
    background:linear-gradient(90deg,transparent,var(--teal),transparent);
    animation:scan 3s linear infinite;
}
@keyframes scan{0%{left:-100%}100%{left:100%}}
.panel-placeholder{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:170px;
    gap:8px;
    color:#a0b8c8;
    text-align:center;
}
.panel-placeholder svg{animation:pulse 2.2s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:.35;transform:scale(1)}50%{opacity:.8;transform:scale(1.06)}}
.panel-placeholder span{font-size:.75rem;}
.panel-content{animation:slideIn .3s var(--ease) both;}
@keyframes slideIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.info-row{
    display:flex;
    align-items:flex-start;
    gap:6px;
    margin-bottom:6px;
    border-bottom:1px dashed #d8e8f0;
    padding-bottom:5px;
}
.info-row:last-child{border-bottom:none;margin-bottom:0;}
.info-label{
    color:var(--muted);
    font-weight:600;
    min-width:76px;
    font-size:.73rem;
    flex-shrink:0;
}
.info-val{color:var(--text);font-size:.8rem;}
.estado-chip{
    display:inline-block;
    padding:2px 9px;
    border-radius:11px;
    font-size:.68rem;
    font-weight:700;
    letter-spacing:.4px;
    margin-left:3px;
}
.chip-ocupada{background:#fde8e8;color:#c0392b;}
.chip-libre{background:#e8fdf2;color:#27ae60;}
.chip-apartada{background:#fef3e2;color:#e67e22;}
.chip-bloqueada{background:#dfe6e9;color:#2d3436;}

.slots-disponibles{margin-top:8px;}
.slots-title{
    font-size:.7rem;
    font-weight:700;
    color:var(--muted);
    letter-spacing:.4px;
    text-transform:uppercase;
    margin-bottom:6px;
}
.slots-grid{display:flex;flex-wrap:wrap;gap:5px;}
.slot-chip{
    padding:3px 8px;
    border-radius:8px;
    font-size:.67rem;
    font-weight:700;
    border:1.5px solid;
    white-space:nowrap;
}
.slot-libre{background:#e8fdf2;color:#27ae60;border-color:#27ae60;}
.slot-ocupado{background:#fde8e8;color:#c0392b;border-color:#e74c3c;opacity:.75;}
.slot-bloqueado{background:#dfe6e9;color:#636e72;border-color:#b2bec3;opacity:.75;}
.slot-apartado{background:#fef3e2;color:#e67e22;border-color:#f39c12;opacity:.85;}

.acciones-wrap{
    display:none;
    flex-direction:column;
    gap:10px;
    margin-top:12px;
}
.btn-apartar,.btn-bloquear,.btn-galeria{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    font-size:.8rem;
    font-weight:700;
    cursor:pointer;
    transition:opacity .2s,transform .15s;
    letter-spacing:.3px;
    display:block;
}
.btn-apartar{
    background:linear-gradient(135deg,var(--teal),var(--teal-light));
    color:#fff;
}
.btn-bloquear{
    background:linear-gradient(135deg,#636e72,#2d3436);
    color:#fff;
}
.btn-galeria{
    background:#eef5f8;
    color:var(--teal);
    border:1px solid rgba(55,96,115,.22);
}
.btn-apartar:hover,.btn-bloquear:hover,.btn-galeria:hover{
    opacity:.88;
    transform:translateY(-1px);
}
.aviso-no-disponible{
    padding:8px 10px;
    font-size:.72rem;
    color:#c0392b;
    text-align:center;
    background:#fde8e8;
    border-radius:8px;
    display:block;
}

.modal-overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    backdrop-filter:blur(3px);
    z-index:999;
    align-items:center;
    justify-content:center;
}
.modal-overlay.active{display:flex;}
.modal-box{
    background:#fff;
    border-radius:18px;
    padding:28px 30px;
    width:400px;
    max-width:96vw;
    box-shadow:0 24px 64px rgba(0,0,0,.22);
    animation:modalIn .32s var(--ease) forwards;
    max-height:90vh;
    overflow-y:auto;
}
@keyframes modalIn{from{opacity:0;transform:scale(.86) translateY(18px)}to{opacity:1;transform:scale(1) translateY(0)}}
.modal-title{
    font-family:'Instrument Serif',serif;
    font-size:1.05rem;
    color:var(--teal);
    margin-bottom:16px;
    display:flex;
    align-items:center;
    gap:8px;
}
.modal-box label{
    display:block;
    font-size:.75rem;
    font-weight:600;
    color:var(--muted);
    margin-bottom:4px;
    margin-top:10px;
}
.modal-box input,.modal-box select{
    width:100%;
    padding:8px 10px;
    border:1.5px solid #c8dce6;
    border-radius:9px;
    font-size:.85rem;
    transition:border-color .2s;
}
.modal-box input:focus,.modal-box select:focus{
    outline:none;
    border-color:var(--teal);
}
.modal-actions{display:flex;gap:10px;margin-top:18px;}
.btn-confirmar{
    flex:1;
    padding:9px;
    background:linear-gradient(135deg,#27ae60,#2ecc71);
    color:#fff;
    border:none;
    border-radius:9px;
    font-weight:700;
    cursor:pointer;
    font-size:.84rem;
}
.btn-cancelar{
    flex:1;
    padding:9px;
    background:#f0f4f8;
    color:var(--muted);
    border:1.5px solid #c8dce6;
    border-radius:9px;
    font-weight:600;
    cursor:pointer;
    font-size:.84rem;
}
.modal-note{font-size:.68rem;color:#aaa;margin-top:10px;text-align:center;}
.u-alert{padding:8px 14px;border-radius:9px;font-size:.78rem;margin-bottom:6px;}
.u-alert-ok{background:#e8fdf2;color:#27ae60;border:1px solid #b2dfdb;}
.u-alert-err{background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;}
.u-footer{
    background:linear-gradient(135deg,#b8d67a,#a5c95e);
    text-align:center;
    padding:10px;
    font-size:.75rem;
    color:#2d5016;
    font-weight:600;
}
.info-box{
    background:#e8f4fd;
    border:1px solid #bee3f8;
    border-radius:10px;
    padding:10px 14px;
    font-size:.77rem;
    color:var(--teal);
}
.info-box strong{display:block;margin-bottom:4px;}

@media (max-width: 980px){
    .prac-layout{grid-template-columns:1fr;}
    .u-search input{width:100%;}
}
@media (max-width: 768px){
    .u-header{
        flex-wrap:wrap;
        justify-content:space-between;
    }
    .u-search{
        order:4;
        width:100%;
        flex:0 0 100%;
    }
    .u-search input{width:100%;}
    .mapa-prac{
        min-height:700px;
    }
    #salon2{left:50%;top:60px;transform:translateX(-50%);}
    #salon3{left:50%;bottom:70px;transform:translateX(-50%);}
    #salon1{left:50%;top:50%;right:auto;transform:translate(-50%,-50%);}
    #salon2:hover,#salon3:hover{transform:translateX(-50%) translateY(-4px) scale(1.04);}
    #salon1:hover{transform:translate(-50%,calc(-50% - 4px)) scale(1.04);}
    .corredor-label{
        width:180px;
        white-space:normal;
        line-height:1.4;
    }
    .aula-box{
        width:146px;
        height:108px;
    }
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

<header class="u-header">
    <div class="u-logo">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <span class="u-brand">Ubicatec</span>

    <div class="u-search">
        <input id="buscador" type="search" placeholder="🔍  Buscar salón, materia, docente…">
    </div>

    @php $rol = session('usuario.rol') ?? session('usuario')->rol ?? 'estudiante'; @endphp
    @if($rol === 'administrador')
        <span class="role-badge role-admin">⚙️ Admin</span>
    @elseif($rol === 'jefe_departamento')
        <span class="role-badge role-jefe">🏛️ Jefe Dpto.</span>
    @elseif($rol === 'docente')
        <span class="role-badge role-docente">👨‍🏫 Docente</span>
    @else
        <span class="role-badge role-estudiante">🎓 Estudiante</span>
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
    <div class="u-strip">
        <span class="day-chip" id="dia-chip">{{ $dia ?? 'LUNES' }}</span>
        <span class="strip-sep">|</span>
        🕐 <strong id="hora-display"></strong>
        <span class="strip-sep">|</span>
        🟢 Libres: <strong id="cont-libres">0</strong>
        <span class="strip-sep">|</span>
        🔴 Ocupadas: <strong id="cont-ocupadas">0</strong>
        <span class="strip-sep">|</span>
        🟠 Apartadas: <strong id="cont-apartadas">0</strong>
        <span class="strip-sep">|</span>
        ⚫ Bloqueadas: <strong id="cont-bloqueadas">0</strong>
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
            <input type="range" id="sliderHora" min="420" max="1320" step="30" value="420">
            <span id="sliderHoraLabel">07:00</span>
            <span id="badge-simulada" class="hora-simulada-badge">Simulando hora</span>
            <button type="button" onclick="resetSlider()" title="Volver a hora actual"
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
            <span id="badge-dia-simulado" class="hora-simulada-badge">Simulando día</span>
            <button type="button" onclick="resetDia()" title="Volver a día actual"
                style="background:none;border:none;cursor:pointer;font-size:.8rem;color:var(--muted);padding:0 2px;">⟳</button>
        </div>

        <div class="u-legend" style="margin-left:8px;">
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#27ae60"></span>Libre</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#e74c3c"></span>Ocupada</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#f39c12"></span>Apartada</span>
            <span style="display:flex;align-items:center;gap:4px"><span class="leg-dot" style="background:#636e72"></span>Bloqueada</span>
        </div>
    </div>

    <div class="prac-layout">
        <div class="prac-map-card">
            <div class="plant-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="9" width="18" height="13" rx="2"/><path d="M3 9l9-5 9 5"/></svg>
                Salones de prácticas
            </div>

            <div class="mapa-prac" id="mapa-prac">
                <div class="icono-prac">⚓</div>

                <div id="salon2" class="aula-box libre"
                     data-aula="SALON_PRACTICAS_2"
                     data-nombre="Salón de Prácticas 2"
                     data-area="salones-practicas-2">
                    <span class="aula-icon">🧪</span>
                    <span>Salón de<br>Prácticas 2</span>
                </div>

                <div id="salon3" class="aula-box libre"
                     data-aula="SALON_PRACTICAS_3"
                     data-nombre="Salón de Prácticas 3"
                     data-area="salones-practicas-3">
                    <span class="aula-icon">🧪</span>
                    <span>Salón de<br>Prácticas 3</span>
                </div>

                <div id="salon1" class="aula-box libre"
                     data-aula="SALON_PRACTICAS_1"
                     data-nombre="Salón de Prácticas 1"
                     data-area="salones-practicas-1">
                    <span class="aula-icon">🧪</span>
                    <span>Salón de<br>Prácticas 1</span>
                </div>

                <div class="corredor-label">
                    Área central / circulación<br>Dobleclic en un salón para ver galería
                </div>
            </div>
        </div>

        <div class="prac-side-card">
            <div class="plant-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
                Información y acciones
            </div>

            <div class="panel-info" id="panel-prac">
                <div class="panel-placeholder">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>
                    <span>Selecciona un salón para consultar estado, clase y acciones disponibles</span>
                </div>
            </div>

            <div class="acciones-wrap" id="acciones-prac"></div>

            <div class="info-box" style="margin-top:12px;">
                <strong>Referencia visual</strong>
                Haz clic en un salón para ver detalles. Si haces doble clic, se abrirá la galería del área correspondiente.
            </div>
        </div>
    </div>
</div>

<footer class="u-footer">
    © 2025 Ubicatec — Salones de prácticas · <span id="footer-dia">{{ $dia ?? 'LUNES' }}</span> <span id="footer-hora">{{ isset($hora) ? substr($hora,0,5) : '07:00' }}</span>
</footer>
</div>

<div class="modal-overlay" id="modalApartar">
    <div class="modal-box">
        <div class="modal-title">
            <span>📌</span>
            <span>Reservar / bloquear</span>
            <span id="modal-aula-nombre" style="color:var(--teal-light);font-size:.9rem;font-family:'Inter',sans-serif;margin-left:4px;"></span>
        </div>

        <form method="POST" action="{{ route('aulas.apartar') }}">
            @csrf
            <input type="hidden" name="aula" id="modal-aula-input">
            <input type="hidden" name="tipo_bloqueo" id="modal-tipo-bloqueo" value="0">

            <label>Día de la semana</label>
            <select name="dia" id="modal-dia">
                @foreach(['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO'] as $d)
                    <option value="{{ $d }}" {{ ($dia ?? 'LUNES') === $d ? 'selected' : '' }}>
                        {{ $d }}
                    </option>
                @endforeach
            </select>

            <label>Hora inicio</label>
            <input type="time" name="hora_inicio" id="modal-hora-inicio" value="{{ isset($hora) ? substr($hora,0,5) : '07:00' }}">

            <label>Hora fin</label>
            <input type="time" name="hora_fin" id="modal-hora-fin" value="09:00">

           <label>Solicitante</label>
                    <input type="text" name="nombre_solicitante" id="modal-solicitante" placeholder="Ej: Marco">
                    
                    <label>Asunto</label>
                    <input type="text" name="materia_reserva" id="modal-asunto" placeholder="Ej: Proyectos integradores">

            <div class="modal-actions">
                <button type="submit" class="btn-confirmar">✅ Confirmar</button>
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            </div>
        </form>

        <p class="modal-note">⏳ Ajusta esta acción a tu flujo actual de reservas y bloqueos</p>
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
@endsection

@section('scripts')
<script>
const HORARIOS = @json($horarios ?? []);
const APARTADOS = @json($apartados ?? []);
const DIA_REAL = @json($dia ?? 'LUNES');
const HORA_REAL = @json($hora ?? '07:00:00');
const ROL = @json(session('usuario.rol') ?? (session('usuario')->rol ?? 'estudiante'));

let salonActivo = null;
let diaActual = DIA_REAL;
let horaActual = normalizarHora(HORA_REAL);

const aulasMeta = {
    SALON_PRACTICAS_1: { nombre:'Salón de Prácticas 1', area:'salones-practicas-1' },
    SALON_PRACTICAS_2: { nombre:'Salón de Prácticas 2', area:'salones-practicas-2' },
    SALON_PRACTICAS_3: { nombre:'Salón de Prácticas 3', area:'salones-practicas-3' }
};

function normalizarHora(h){
    return String(h || '07:00:00').substring(0,5);
}
function hToMin(h){
    if(!h) return 0;
    const clean = String(h).substring(0,5);
    const [hh, mm] = clean.split(':').map(Number);
    return (hh * 60) + mm;
}
function minToHora(min){
    const h = String(Math.floor(min / 60)).padStart(2,'0');
    const m = String(min % 60).padStart(2,'0');
    return `${h}:${m}`;
}
function formatDia(dia){
    const map = {
        LUNES:'Lunes', MARTES:'Martes', MIERCOLES:'Miércoles',
        JUEVES:'Jueves', VIERNES:'Viernes', SABADO:'Sábado'
    };
    return map[dia] || dia;
}
function estadoSalon(codigo){
    const horaMin = hToMin(horaActual);

    const bloqueado = APARTADOS.find(a =>
        a.aula === codigo &&
        a.dia === diaActual &&
        Number(a.tipo_bloqueo) === 1 &&
        hToMin(a.hora_inicio) <= horaMin &&
        hToMin(a.hora_fin) > horaMin
    );
    if (bloqueado) return 'bloqueada';

    const apartado = APARTADOS.find(a =>
        a.aula === codigo &&
        a.dia === diaActual &&
        Number(a.tipo_bloqueo) !== 1 &&
        hToMin(a.hora_inicio) <= horaMin &&
        hToMin(a.hora_fin) > horaMin
    );
    if (apartado) return 'apartada';

    const clase = HORARIOS.find(h =>
        h.aula === codigo &&
        h.dia === diaActual &&
        hToMin(h.hora_inicio) <= horaMin &&
        hToMin(h.hora_fin) > horaMin
    );
    if (clase) return 'ocupada';

    return 'libre';
}
function chipEstado(estado){
    if(estado === 'ocupada') return '<span class="estado-chip chip-ocupada">Ocupada</span>';
    if(estado === 'apartada') return '<span class="estado-chip chip-apartada">Apartada</span>';
    if(estado === 'bloqueada') return '<span class="estado-chip chip-bloqueada">Bloqueada</span>';
    return '<span class="estado-chip chip-libre">Libre</span>';
}
function obtenerClaseActual(codigo){
    const horaMin = hToMin(horaActual);
    return HORARIOS.find(h =>
        h.aula === codigo &&
        h.dia === diaActual &&
        hToMin(h.hora_inicio) <= horaMin &&
        hToMin(h.hora_fin) > horaMin
    ) || null;
}
function obtenerApartadoActual(codigo){
    const horaMin = hToMin(horaActual);
    return APARTADOS.find(a =>
        a.aula === codigo &&
        a.dia === diaActual &&
        hToMin(a.hora_inicio) <= horaMin &&
        hToMin(a.hora_fin) > horaMin
    ) || null;
}
function generarSlots(codigo){
    const slots = [];
    for(let min = 420; min < 1320; min += 60){
        const ini = minToHora(min);
        const fin = minToHora(min + 60);
        const ocupadoHorario = HORARIOS.find(h =>
            h.aula === codigo &&
            h.dia === diaActual &&
            hToMin(h.hora_inicio) < (min + 60) &&
            hToMin(h.hora_fin) > min
        );
        const ocupadoApartado = APARTADOS.find(a =>
            a.aula === codigo &&
            a.dia === diaActual &&
            hToMin(a.hora_inicio) < (min + 60) &&
            hToMin(a.hora_fin) > min
        );

        let tipo = 'libre';
        if (ocupadoApartado && Number(ocupadoApartado.tipo_bloqueo) === 1) tipo = 'bloqueado';
        else if (ocupadoApartado) tipo = 'apartado';
        else if (ocupadoHorario) tipo = 'ocupado';

        slots.push({ ini, fin, tipo });
    }
    return slots;
}
function renderSlotsHTML(codigo){
    const slots = generarSlots(codigo);
    return `
        <div class="slots-disponibles">
            <div class="slots-title">Disponibilidad del día</div>
            <div class="slots-grid">
                ${slots.map(s => {
                    const cls = s.tipo === 'libre'
                        ? 'slot-libre'
                        : s.tipo === 'ocupado'
                        ? 'slot-ocupado'
                        : s.tipo === 'apartado'
                        ? 'slot-apartado'
                        : 'slot-bloqueado';
                    return `<span class="slot-chip ${cls}">${s.ini}-${s.fin}</span>`;
                }).join('')}
            </div>
        </div>
    `;
}
function pintarSalones(){
    let libres = 0, ocupadas = 0, apartadas = 0, bloqueadas = 0;

    document.querySelectorAll('.aula-box').forEach(el => {
        const aula = el.dataset.aula;
        const estado = estadoSalon(aula);
        el.classList.remove('libre','ocupada','apartada','bloqueada');
        el.classList.add(estado);

        if(estado === 'libre') libres++;
        if(estado === 'ocupada') ocupadas++;
        if(estado === 'apartada') apartadas++;
        if(estado === 'bloqueada') bloqueadas++;
    });

    document.getElementById('cont-libres').textContent = libres;
    document.getElementById('cont-ocupadas').textContent = ocupadas;
    document.getElementById('cont-apartadas').textContent = apartadas;
    document.getElementById('cont-bloqueadas').textContent = bloqueadas;
}
function renderPanelSalon(codigo){
    const estado = estadoSalon(codigo);
    const clase = obtenerClaseActual(codigo);
    const apartado = obtenerApartadoActual(codigo);
    const meta = aulasMeta[codigo] || { nombre: codigo, area: '' };

    const referencia = clase
        ? (clase.materia ?? clase.nombre_materia ?? 'Clase registrada')
        : apartado
            ? (apartado.nombre_solicitante ?? 'Reservación registrada')
            : 'Sin actividad en este momento';

    const detalleHorario = clase
        ? `${normalizarHora(clase.hora_inicio)} - ${normalizarHora(clase.hora_fin)}`
        : apartado
            ? `${normalizarHora(apartado.hora_inicio)} - ${normalizarHora(apartado.hora_fin)}`
            : '—';

    document.getElementById('panel-prac').innerHTML = `
        <div class="panel-content">
            <div class="info-row">
                <span class="info-label">Aula</span>
                <div class="info-val"><strong>${meta.nombre}</strong> ${chipEstado(estado)}</div>
            </div>
            <div class="info-row">
                <span class="info-label">Código</span>
                <div class="info-val">${codigo}</div>
            </div>
            <div class="info-row">
                <span class="info-label">Día</span>
                <div class="info-val">${formatDia(diaActual)}</div>
            </div>
            <div class="info-row">
                <span class="info-label">Hora</span>
                <div class="info-val">${horaActual}</div>
            </div>
            <div class="info-row">
                <span class="info-label">Actividad</span>
                <div class="info-val">${referencia}</div>
            </div>
            <div class="info-row">
                <span class="info-label">Horario</span>
                <div class="info-val">${detalleHorario}</div>
            </div>
            ${renderSlotsHTML(codigo)}
        </div>
    `;
}
function renderAccionesSalon(codigo){
    const estado = estadoSalon(codigo);
    const acciones = document.getElementById('acciones-prac');
    acciones.style.display = 'flex';

    if(ROL === 'estudiante'){
        acciones.innerHTML = `
            <button type="button" class="btn-galeria" onclick="abrirGaleriaSalon()">🖼️ Ver galería</button>
        `;
        return;
    }

    if(estado === 'bloqueada'){
        acciones.innerHTML = `
            <span class="aviso-no-disponible">Este salón se encuentra bloqueado en la hora consultada.</span>
            <button type="button" class="btn-galeria" onclick="abrirGaleriaSalon()">🖼️ Ver galería</button>
        `;
        return;
    }

    acciones.innerHTML = `
        <button type="button" class="btn-apartar" onclick="abrirApartado(0)">📌 Apartar / reservar</button>
        <button type="button" class="btn-bloquear" onclick="abrirApartado(1)">⛔ Bloquear</button>
        <button type="button" class="btn-galeria" onclick="abrirGaleriaSalon()">🖼️ Ver galería</button>
    `;
}
function seleccionarSalon(el){
    document.querySelectorAll('.aula-box').forEach(x => x.classList.remove('selected'));
    el.classList.add('selected');

    salonActivo = {
        codigo: el.dataset.aula,
        nombre: el.dataset.nombre,
        area: el.dataset.area
    };

    renderPanelSalon(salonActivo.codigo);
    renderAccionesSalon(salonActivo.codigo);
}
function abrirApartado(tipoBloqueo = 0){
    if(!salonActivo) return;

    document.getElementById('modalApartar').classList.add('active');
    document.getElementById('modal-aula-nombre').textContent = salonActivo.nombre;
    document.getElementById('modal-aula-input').value = salonActivo.codigo;
    document.getElementById('modal-dia').value = diaActual;
    document.getElementById('modal-hora-inicio').value = horaActual;
    document.getElementById('modal-hora-fin').value = sumarUnaHora(horaActual);
    document.getElementById('modal-tipo-bloqueo').value = tipoBloqueo;
}
function cerrarModal(){
    document.getElementById('modalApartar').classList.remove('active');
}
function sumarUnaHora(hora){
    let min = hToMin(hora) + 60;
    if(min > 1320) min = 1320;
    return minToHora(min);
}
function abrirGaleriaSalon(){
    if(!salonActivo) return;
    window.location.href = `{{ url('/galeria') }}?area=${encodeURIComponent(salonActivo.area)}`;
}
function actualizarRelojUI(){
    document.getElementById('hora-display').textContent = horaActual;
    document.getElementById('sliderHoraLabel').textContent = horaActual;
    document.getElementById('dia-chip').textContent = formatDia(diaActual);
    document.getElementById('footer-dia').textContent = formatDia(diaActual);
    document.getElementById('footer-hora').textContent = horaActual;

    const simulandoHora = horaActual !== normalizarHora(HORA_REAL);
    const simulandoDia = diaActual !== DIA_REAL;

    document.getElementById('badge-simulada').style.display = simulandoHora ? 'inline-block' : 'none';
    document.getElementById('badge-dia-simulado').style.display = simulandoDia ? 'inline-block' : 'none';
}
function resetSlider(){
    horaActual = normalizarHora(HORA_REAL);
    document.getElementById('sliderHora').value = hToMin(horaActual);
    actualizarTodo();
}
function resetDia(){
    diaActual = DIA_REAL;
    document.getElementById('selectorDia').value = DIA_REAL;
    actualizarTodo();
}
function actualizarTodo(){
    actualizarRelojUI();
    pintarSalones();

    if(salonActivo){
        const el = document.querySelector(`.aula-box[data-aula="${salonActivo.codigo}"]`);
        if(el) seleccionarSalon(el);
    }
}
function filtrarBusqueda(txt){
    const term = String(txt || '').trim().toLowerCase();
    document.querySelectorAll('.aula-box').forEach(el => {
        const nombre = (el.dataset.nombre || '').toLowerCase();
        const codigo = (el.dataset.aula || '').toLowerCase();
        const visible = !term || nombre.includes(term) || codigo.includes(term);
        el.style.opacity = visible ? '1' : '.28';
        el.style.filter = visible ? 'none' : 'grayscale(.35)';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    horaActual = normalizarHora(HORA_REAL);

    const slider = document.getElementById('sliderHora');
    slider.value = hToMin(horaActual);

    const selectorDia = document.getElementById('selectorDia');
    selectorDia.value = DIA_REAL;

    slider.addEventListener('input', e => {
        horaActual = minToHora(Number(e.target.value));
        actualizarTodo();
    });

    selectorDia.addEventListener('change', e => {
        diaActual = e.target.value;
        actualizarTodo();
    });

    document.getElementById('buscador').addEventListener('input', e => {
        filtrarBusqueda(e.target.value);
    });

    document.querySelectorAll('.aula-box').forEach(el => {
        el.addEventListener('click', () => seleccionarSalon(el));
        el.addEventListener('dblclick', () => {
            salonActivo = {
                codigo: el.dataset.aula,
                nombre: el.dataset.nombre,
                area: el.dataset.area
            };
            abrirGaleriaSalon();
        });
    });

    document.getElementById('modalApartar').addEventListener('click', e => {
        if(e.target.id === 'modalApartar') cerrarModal();
    });

    actualizarTodo();
});
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