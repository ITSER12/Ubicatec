@extends('layouts.app')

@section('title', 'Bienvenido - Ubicatec')
@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --teal:#376073;--teal-dark:#2c4f60;--teal-light:#5a8fa3;
    --green:#27ae60;--red:#e74c3c;--orange:#f39c12;--blue:#2980b9;--purple:#8e44ad;
    --bg:#f0f6f9;--surface:#fff;--border:rgba(55,96,115,.13);
    --text:#1e3040;--muted:#5a7a8a;--faint:#9ab3c0;
    --shadow-sm:0 2px 8px rgba(0,0,0,.07);
    --shadow-md:0 6px 24px rgba(0,0,0,.10);
    --shadow-lg:0 16px 48px rgba(0,0,0,.16);
    --ease:cubic-bezier(.34,1.56,.64,1);
    --radius:14px;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column;}

/* ── Header ── */
.u-header{
    background:linear-gradient(135deg,#0f2535 0%,var(--teal-dark) 50%,var(--teal) 100%);
    padding:14px 28px;display:flex;align-items:center;gap:16px;
    box-shadow:0 3px 16px rgba(0,0,0,.25);position:relative;overflow:hidden;z-index:10;
}
.u-header::after{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse at 0% 100%,rgba(74,156,109,.12) 0%,transparent 50%),
               radial-gradient(ellipse at 100% 0%,rgba(90,143,163,.1) 0%,transparent 50%);
    pointer-events:none;
}
.logo-box{
    width:44px;height:44px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);
    border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
    position:relative;z-index:1;
}
.logo-box svg{width:22px;height:22px;fill:white;}
.brand{position:relative;z-index:1;}
.brand-name{font-family:'Instrument Serif',serif;font-size:1.5rem;color:#fff;letter-spacing:-.3px;line-height:1;}
.brand-sub{font-size:.7rem;color:rgba(255,255,255,.6);margin-top:2px;}
.header-center{flex:1;display:flex;justify-content:center;position:relative;z-index:1;}
.header-datetime{
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:22px;
    padding:6px 18px;font-size:.78rem;color:rgba(255,255,255,.9);
    display:flex;align-items:center;gap:10px;backdrop-filter:blur(6px);
}
.datetime-sep{opacity:.35;}
#live-time{font-weight:800;font-variant-numeric:tabular-nums;letter-spacing:.5px;}
.header-right{position:relative;z-index:1;display:flex;align-items:center;gap:10px;}
.header-badge{
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);border-radius:20px;
    padding:5px 14px;font-size:.72rem;color:rgba(255,255,255,.85);
    display:flex;align-items:center;gap:6px;
}
.pulse-dot{width:7px;height:7px;border-radius:50%;background:#4ade80;animation:blink 2s ease-in-out infinite;flex-shrink:0;}
@keyframes blink{0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(74,222,128,.4)}50%{opacity:.5;box-shadow:0 0 0 5px rgba(74,222,128,0)}}
.user-pill{
    display:flex;align-items:center;gap:8px;
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);border-radius:22px;
    padding:4px 12px 4px 5px;cursor:default;
}
.user-avatar-sm{
    width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.25);
    border:1.5px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;
    font-size:.65rem;font-weight:800;color:#fff;overflow:hidden;flex-shrink:0;
}
.user-avatar-sm img{width:100%;height:100%;object-fit:cover;}
.user-pill-name{font-size:.73rem;color:#fff;font-weight:600;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.role-badge{font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:10px;letter-spacing:.3px;white-space:nowrap;}
.role-administrador{background:rgba(231,76,60,.35);color:#ffb3b3;border:1px solid rgba(231,76,60,.4);}
.role-docente{background:rgba(39,174,96,.3);color:#a8f0c8;border:1px solid rgba(39,174,96,.35);}
.role-jefe_departamento{background:rgba(142,68,173,.3);color:#d4a8f5;border:1px solid rgba(142,68,173,.35);}
.role-estudiante{background:rgba(255,255,255,.2);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.25);}

/* ── Alerts ── */
.u-alert{padding:9px 16px;border-radius:10px;font-size:.79rem;margin:14px 24px 0;}
.u-alert-ok{background:#e8fdf2;color:#1e7e44;border:1px solid #b2dfdb;}
.u-alert-err{background:#fde8e8;color:#c0392b;border:1px solid #f5c6cb;}

/* ── Layout ── */
.u-main{flex:1;padding:20px 24px 28px;display:flex;flex-direction:column;gap:20px;}

/* ── Welcome banner ── */
.welcome-banner{
    background:linear-gradient(135deg,#0f2535 0%,var(--teal-dark) 50%,var(--teal) 80%,#4a9c6d 100%);
    border-radius:var(--radius);padding:26px 32px;
    display:flex;align-items:center;justify-content:space-between;gap:20px;
    box-shadow:var(--shadow-md);position:relative;overflow:hidden;
}
.welcome-banner::before{content:'';position:absolute;right:-50px;top:-50px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,.04);}
.welcome-banner::after{content:'';position:absolute;right:80px;bottom:-70px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.03);}
.welcome-text{position:relative;z-index:1;}
.welcome-greeting{font-size:.72rem;color:rgba(255,255,255,.65);font-weight:600;letter-spacing:.8px;text-transform:uppercase;margin-bottom:6px;}
.welcome-title{font-family:'Instrument Serif',serif;font-size:1.9rem;color:#fff;line-height:1.15;margin-bottom:6px;}
.welcome-title .hi{color:#a8e6cf;}
.welcome-name{color:#ffd59e;}
.welcome-desc{font-size:.79rem;color:rgba(255,255,255,.65);max-width:400px;line-height:1.65;}
.welcome-actions{position:relative;z-index:1;display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
.btn-w{
    padding:9px 20px;border-radius:10px;font-size:.79rem;font-weight:700;
    cursor:pointer;text-decoration:none;border:none;
    transition:transform .2s var(--ease),opacity .2s,box-shadow .2s;
    display:flex;align-items:center;gap:7px;letter-spacing:.2px;
}
.btn-w:hover{transform:translateY(-2px);opacity:.92;}
.btn-w-glass{background:rgba(255,255,255,.18);color:#fff;border:1.5px solid rgba(255,255,255,.32);backdrop-filter:blur(6px);}
.btn-w-glass:hover{background:rgba(255,255,255,.25);}
.btn-w-white{background:#fff;color:var(--teal-dark);box-shadow:0 4px 14px rgba(0,0,0,.12);}

/* ── KPI Grid ── */
.kpi-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;}
@media(max-width:1200px){.kpi-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:700px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
.kpi-card{
    background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);
    padding:16px 18px;box-shadow:var(--shadow-sm);display:flex;align-items:flex-start;gap:12px;
    transition:transform .22s var(--ease),box-shadow .22s;cursor:default;
    animation:fadeUp .4s var(--ease) both;
}
.kpi-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-md);}
.kpi-card.span-2{grid-column:span 2;}
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.kpi-card:nth-child(1){animation-delay:.05s}.kpi-card:nth-child(2){animation-delay:.09s}
.kpi-card:nth-child(3){animation-delay:.13s}.kpi-card:nth-child(4){animation-delay:.17s}
.kpi-card:nth-child(5){animation-delay:.21s}.kpi-card:nth-child(6){animation-delay:.25s}
.kpi-card:nth-child(7){animation-delay:.28s}
.kpi-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.kpi-body{flex:1;min-width:0;}
.kpi-label{font-size:.67rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:3px;}
.kpi-value{font-size:1.6rem;font-weight:800;line-height:1;font-variant-numeric:tabular-nums;margin-bottom:2px;}
.kpi-sub{font-size:.67rem;color:var(--faint);}
.kpi-green .kpi-icon{background:#e8fdf2;color:var(--green);}.kpi-green .kpi-value{color:var(--green);}
.kpi-red   .kpi-icon{background:#fde8e8;color:var(--red);}.kpi-red   .kpi-value{color:var(--red);}
.kpi-orange.kpi-icon{background:#fef3e2;color:var(--orange);}.kpi-orange .kpi-value{color:var(--orange);}
.kpi-teal  .kpi-icon{background:#e4f0f5;color:var(--teal);}.kpi-teal  .kpi-value{color:var(--teal);}
.kpi-blue  .kpi-icon{background:#e4f0fb;color:var(--blue);}.kpi-blue  .kpi-value{color:var(--blue);}
.kpi-purple.kpi-icon{background:#f3eafd;color:var(--purple);}.kpi-purple .kpi-value{color:var(--purple);}
.kpi-card.import-card{grid-column:span 2;padding:14px 18px;align-items:center;}

/* ── Import pill ── */
.import-pill{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.import-pill form{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.import-pill input[type=file]{font-size:.72rem;max-width:200px;color:var(--muted);}
.import-pill .btn-import{
    padding:6px 16px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));
    color:#fff;border:none;border-radius:8px;font-size:.73rem;font-weight:700;
    cursor:pointer;transition:opacity .2s;white-space:nowrap;
}
.import-pill .btn-import:hover{opacity:.85;}
.import-label{font-size:.73rem;color:var(--muted);font-weight:600;white-space:nowrap;}

/* ── Dashboard grid ── */
.dash-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:16px;}
@media(max-width:1100px){.dash-grid{grid-template-columns:1fr 1fr;}}
@media(max-width:650px){.dash-grid{grid-template-columns:1fr;}}
.dash-card{
    background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);
    padding:20px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:14px;
    animation:fadeUp .5s var(--ease) both;
}
.dash-card:nth-child(1){animation-delay:.3s}
.dash-card:nth-child(2){animation-delay:.35s}
.dash-card:nth-child(3){animation-delay:.4s}
.dash-card:nth-child(4){animation-delay:.45s}
.dash-card:nth-child(5){animation-delay:.5s}
.card-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;}
.card-title{
    font-family:'Instrument Serif',serif;font-size:.95rem;color:var(--teal);
    display:flex;align-items:center;gap:7px;
}
.card-title svg{width:16px;height:16px;stroke:var(--teal);fill:none;stroke-width:2;}
.card-link{
    font-size:.71rem;color:var(--teal-light);text-decoration:none;font-weight:700;
    transition:color .2s;display:flex;align-items:center;gap:3px;
}
.card-link:hover{color:var(--teal);}

/* ── Chart ── */
.chart-wrap{position:relative;height:185px;}

/* ── Eventos ── */
.event-list{display:flex;flex-direction:column;gap:7px;max-height:280px;overflow-y:auto;}
.event-list::-webkit-scrollbar{width:4px;}
.event-list::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px;}
.event-item{
    display:flex;align-items:flex-start;gap:9px;padding:9px 11px;border-radius:10px;
    background:#f8fbfd;border:1px solid var(--border);transition:background .15s,transform .15s var(--ease);
}
.event-item:hover{background:#eef5fa;transform:translateX(2px);}
.event-date-box{
    width:36px;height:36px;border-radius:9px;flex-shrink:0;
    background:linear-gradient(135deg,var(--teal-dark),var(--teal));
    display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;line-height:1;
}
.event-day{font-size:.72rem;font-weight:800;}
.event-month{font-size:.52rem;opacity:.75;text-transform:uppercase;}
.event-body{flex:1;min-width:0;}
.event-title{font-size:.79rem;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.event-desc{font-size:.68rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.event-empty{text-align:center;color:var(--faint);font-size:.78rem;padding:24px 0;}

/* ── Acceso rápido ── */
.quick-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px;}
.quick-btn{
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;
    padding:14px 8px;border-radius:11px;border:1.5px solid var(--border);
    background:#f8fbfd;text-decoration:none;color:var(--text);
    font-size:.7rem;font-weight:700;text-align:center;
    transition:transform .22s var(--ease),box-shadow .2s,background .2s,border-color .2s;
}
.quick-btn:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);background:#fff;color:var(--teal);border-color:rgba(55,96,115,.3);}
.quick-btn .qb-icon{font-size:1.4rem;}
.quick-btn .qb-label{font-size:.68rem;}

/* ── Aulas status ── */
/* ── Sección estado aulas ── */
.edificios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
}
.edificio-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 10px;
    animation: fadeUp .5s var(--ease) both;
}
.edificio-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.edificio-card-title {
    font-family: 'Instrument Serif', serif;
    font-size: .88rem;
    color: var(--teal);
    display: flex;
    align-items: center;
    gap: 6px;
}
.edificio-card-title svg {
    width: 15px; height: 15px;
    stroke: var(--teal); fill: none; stroke-width: 2;
}
.edificio-card-link {
    font-size: .69rem;
    color: var(--teal-light);
    text-decoration: none;
    font-weight: 700;
    transition: color .2s;
    display: flex;
    align-items: center;
    gap: 3px;
}
.edificio-card-link:hover { color: var(--teal); }
.edificio-resumen {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.ed-stat {
    flex: 1;
    min-width: 56px;
    padding: 7px 8px;
    border-radius: 9px;
    border: 1px solid var(--border);
    background: #f8fbfd;
    text-align: center;
}
.ed-stat .es-val {
    font-size: 1.1rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    line-height: 1;
}
.ed-stat .es-lbl {
    font-size: .6rem;
    color: var(--faint);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-top: 2px;
}
.ed-stat.green .es-val { color: var(--green); }
.ed-stat.red .es-val   { color: var(--red); }
.ed-stat.orange .es-val{ color: var(--orange); }
.aulas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 5px;
}
.aula-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 9px;
    border-radius: 8px;
    background: #f8fbfd;
    border: 1px solid var(--border);
    font-size: .75rem;
    transition: background .15s;
    gap: 4px;
}
.aula-row:hover { background: #eef5fa; }
.aula-name { font-weight: 700; color: var(--text); font-size: .72rem; }
.aula-chip {
    padding: 2px 7px;
    border-radius: 9px;
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .4px;
    white-space: nowrap;
}
.chip-libre    { background: #e8fdf2; color: #27ae60; }
.chip-ocupada  { background: #fde8e8; color: #c0392b; }
.chip-apartada { background: #fef3e2; color: #e67e22; }
.chip-bloqueada{ background: #ecf0f1; color: #636e72; }
@media (max-width: 700px) {
    .edificios-grid { grid-template-columns: 1fr; }
    .aulas-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
}
.aula-row:hover{background:#eef5fa;}
.aula-name{font-weight:700;color:var(--text);}
.aula-chip{padding:2px 9px;border-radius:10px;font-size:.63rem;font-weight:800;letter-spacing:.4px;white-space:nowrap;}
.chip-libre{background:#e8fdf2;color:#27ae60;}
.chip-ocupada{background:#fde8e8;color:#c0392b;}
.chip-apartada{background:#fef3e2;color:#e67e22;}
.chip-bloqueada{background:#ecf0f1;color:#636e72;}

/* ── Donut legend ── */
.donut-legend{display:flex;justify-content:center;gap:14px;font-size:.7rem;color:var(--muted);flex-wrap:wrap;}
.leg-dot{width:10px;height:10px;border-radius:3px;display:inline-block;}

/* ── Stats mini row ── */
.stats-mini{display:flex;gap:8px;flex-wrap:wrap;}
.stat-pill{
    flex:1;min-width:80px;padding:10px 12px;border-radius:10px;border:1px solid var(--border);
    background:#f8fbfd;text-align:center;
}
.stat-pill .sp-val{font-size:1.15rem;font-weight:800;font-variant-numeric:tabular-nums;}
.stat-pill .sp-lbl{font-size:.63rem;color:var(--faint);margin-top:2px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;}

/* ── Top eventos ── */
.top-evento-card{
    padding:10px 12px;border-radius:10px;border:1px solid var(--border);
    background:#f8fbfd;display:flex;align-items:center;gap:10px;
    transition:background .15s;
}
.top-evento-card:hover{background:#eef5fa;}
.top-evento-poster{
    width:42px;height:42px;border-radius:8px;object-fit:cover;flex-shrink:0;
    background:var(--teal);display:flex;align-items:center;justify-content:center;font-size:1.2rem;
}
.top-evento-poster img{width:100%;height:100%;object-fit:cover;border-radius:8px;}
.top-evento-info{flex:1;min-width:0;}
.top-evento-title{font-size:.78rem;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.top-evento-meta{font-size:.67rem;color:var(--muted);margin-top:2px;display:flex;gap:8px;}
.top-evento-stat{display:flex;align-items:center;gap:3px;}
.top-evento-stat svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2.5;}

/* ── Solicitudes ── */
.sol-list{display:flex;flex-direction:column;gap:7px;max-height:260px;overflow-y:auto;}
.sol-list::-webkit-scrollbar{width:4px;}
.sol-list::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px;}
.sol-item{
    display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:10px;
    background:#f8fbfd;border:1px solid var(--border);transition:background .15s;
}
.sol-item:hover{background:#eef5fa;}
.sol-avatar{
    width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));
    display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;
}
.sol-info{flex:1;min-width:0;}
.sol-title{font-size:.78rem;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.sol-meta{font-size:.67rem;color:var(--muted);margin-top:1px;}
.sol-badge{padding:2px 8px;border-radius:8px;font-size:.62rem;font-weight:800;letter-spacing:.3px;white-space:nowrap;}
.sol-pendiente{background:#fef3e2;color:#e67e22;border:1px solid rgba(243,156,18,.3);}
.sol-aprobado{background:#e8fdf2;color:#27ae60;border:1px solid rgba(39,174,96,.3);}
.sol-rechazado{background:#fde8e8;color:#c0392b;border:1px solid rgba(231,76,60,.3);}
.sol-stats{display:flex;gap:8px;}
.sol-stat{flex:1;padding:8px 10px;border-radius:10px;border:1px solid var(--border);background:#f8fbfd;text-align:center;}
.sol-stat .ss-val{font-size:1.1rem;font-weight:800;font-variant-numeric:tabular-nums;}
.sol-stat .ss-lbl{font-size:.6rem;color:var(--faint);font-weight:700;text-transform:uppercase;letter-spacing:.4px;margin-top:1px;}

/* ── Respaldo btn ── */
.btn-respaldo{
    display:inline-flex;align-items:center;gap:6px;
    padding:7px 16px;border-radius:9px;font-size:.73rem;font-weight:700;
    background:linear-gradient(135deg,#1a3a4a,var(--teal-dark));
    color:#fff;border:none;cursor:pointer;text-decoration:none;
    transition:opacity .2s,transform .2s var(--ease);white-space:nowrap;
}
.btn-respaldo:hover{opacity:.85;transform:translateY(-1px);}

/* ── Separador visual ── */
.section-label{
    font-size:.67rem;font-weight:800;color:var(--faint);text-transform:uppercase;
    letter-spacing:1px;padding:0 2px;display:flex;align-items:center;gap:8px;
}
.section-label::after{content:'';flex:1;height:1px;background:var(--border);}

/* ── Footer ── */
.u-footer{
    background:linear-gradient(135deg,#b8d67a,#a5c95e);
    text-align:center;padding:11px;font-size:.74rem;color:#2d5016;font-weight:600;
}
</style>
@endsection


@section('content')
@php
    $usuario       = session('usuario');
    $nombreMostrar = $usuario?->nombre ?? 'Usuario';
    $rolActual     = $usuario?->rol ?? 'estudiante';
    $esAdmin       = in_array($rolActual, ['administrador','jefe_departamento']);
@endphp

<div style="display:flex;flex-direction:column;min-height:100vh;">

{{-- ══ HEADER ══ --}}
<header class="u-header">
    <div class="logo-box">
        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <div class="brand">
        <div class="brand-name">Ubicatec</div>
        <div class="brand-sub">Sistema de gestión del campus</div>
    </div>
    <div class="header-center">
        <div class="header-datetime">
            <span id="live-day"></span>
            <span class="datetime-sep">|</span>
            <span id="live-time"></span>
            <span class="datetime-sep">|</span>
            <span id="live-date"></span>
        </div>
    </div>
    <div class="header-right">
        <div class="header-badge">
            <span class="pulse-dot"></span>
            Sistema activo
        </div>
        @if($usuario)
        <div class="user-pill">
            <div class="user-avatar-sm">
                @if($usuario->foto)
                    <img src="{{ asset('storage/'.$usuario->foto) }}" alt="{{ $usuario->nombre }}">
                @else
                    {{ strtoupper(substr($usuario->nombre,0,1).substr($usuario->ape_paterno??'',0,1)) }}
                @endif
            </div>
            <span class="user-pill-name">{{ Str::words($usuario->nombre,1,'') }} {{ Str::words($usuario->ape_paterno??'',1,'') }}</span>
            <span class="role-badge role-{{ $rolActual }}">
                @switch($rolActual)
                    @case('administrador') 👑 Admin @break
                    @case('jefe_departamento') 🏛️ Jefe Dto. @break
                    @case('docente') 👨‍🏫 Docente @break
                    @default 🎓 Estudiante
                @endswitch
            </span>
        </div>
        @endif
    </div>
</header>

@if(session('success'))
<div class="u-alert u-alert-ok">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="u-alert u-alert-err">❌ {{ session('error') }}</div>
@endif

<div class="u-main">

    {{-- ══ WELCOME BANNER ══ --}}
    <div class="welcome-banner">
        <div class="welcome-text">
            <div class="welcome-greeting">Panel de control</div>
            <h1 class="welcome-title">
                <span class="hi">¡Hola,</span>
                <span class="welcome-name">{{ Str::words($nombreMostrar,1,'') }}!</span>
                Bienvenido a Ubicatec
            </h1>
            <p class="welcome-desc">
                Sistema integral de localización de aulas, gestión de horarios,
                eventos y usuarios del campus universitario.
            </p>
        </div>
        <div class="welcome-actions">
            <a href="{{ url('mapa') }}" class="btn-w btn-w-glass">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
                Ver mapa
            </a>
            <a href="{{ url('aulas') }}" class="btn-w btn-w-white">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Disponibilidad
            </a>
            @if($rolActual === 'administrador')
            <a href="{{ route('respaldo.descargar') }}" class="btn-respaldo">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Respaldo BD
            </a>
            @endif
        </div>
    </div>

    {{-- ══ KPIs ══ --}}
    <div class="section-label">Estadísticas rápidas</div>
    <div class="kpi-grid">

        <div class="kpi-card kpi-green">
            <div class="kpi-icon">🟢</div>
            <div class="kpi-body">
                <div class="kpi-label">Aulas libres</div>
                <div class="kpi-value" id="kpi-libres">{{ $aulasLibres }}</div>
                <div class="kpi-sub">En este momento</div>
            </div>
        </div>

        <div class="kpi-card kpi-red">
            <div class="kpi-icon">🔴</div>
            <div class="kpi-body">
                <div class="kpi-label">Aulas ocupadas</div>
                <div class="kpi-value" id="kpi-ocupadas">{{ $aulasOcupadas }}</div>
                <div class="kpi-sub">{{ $dia }}</div>
            </div>
        </div>

        <div class="kpi-card kpi-orange">
            <div class="kpi-icon">📌</div>
            <div class="kpi-body">
                <div class="kpi-label">Apartados hoy</div>
                <div class="kpi-value">{{ $apartadosHoy }}</div>
                <div class="kpi-sub">Semana: {{ $apartadosSemana }}</div>
            </div>
        </div>

        <div class="kpi-card kpi-teal">
            <div class="kpi-icon">📅</div>
            <div class="kpi-body">
                <div class="kpi-label">Eventos próximos</div>
                <div class="kpi-value">{{ $eventosProximos }}</div>
                <div class="kpi-sub">Hoy: {{ $eventosHoy }} · Sem: {{ $eventosSemana }}</div>
            </div>
        </div>

        <div class="kpi-card kpi-blue">
            <div class="kpi-icon">👥</div>
            <div class="kpi-body">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value">{{ $totalUsuarios }}</div>
                <div class="kpi-sub">Registrados</div>
            </div>
        </div>

        {{-- KPI Solicitudes pendientes (admins) | Horarios (otros roles) --}}
        @if($esAdmin)
        <div class="kpi-card kpi-purple">
            <div class="kpi-icon">📩</div>
            <div class="kpi-body">
                <div class="kpi-label">Solicitudes</div>
                <div class="kpi-value">{{ $solicitudesPendientes }}</div>
                <div class="kpi-sub">Pendientes de revisar</div>
            </div>
        </div>
        @else
        <div class="kpi-card kpi-purple">
            <div class="kpi-icon">📋</div>
            <div class="kpi-body">
                <div class="kpi-label">Horarios</div>
                <div class="kpi-value">{{ $totalHorarios }}</div>
                <div class="kpi-sub">Clases del semestre</div>
            </div>
        </div>
        @endif

        @if($rolActual === 'administrador')
        <div class="kpi-card import-card span-2" style="grid-column:span 2;">
            <div class="kpi-icon" style="background:#fff8e7;color:#e67e22;flex-shrink:0;">📂</div>
            <div class="kpi-body">
                <div class="kpi-label">Importar horarios</div>
                <div class="import-pill">
                    <span class="import-label">Subir nuevo Excel:</span>
                    <form action="{{ route('horarios.importar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx,.xls" required>
                        <button type="submit" class="btn-import">Importar</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /kpi-grid --}}

    {{-- ══ DASHBOARD PRINCIPAL ══ --}}
    <div class="section-label">Panel de actividad</div>
    <div class="dash-grid">

        {{-- Columna 1: Gráfica eventos por mes --}}
        <div class="dash-card">
            <div class="card-header">
                <div class="card-title">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Eventos — últimos 6 meses
                </div>
                <a href="{{ url('eventos') }}" class="card-link">Ver todos →</a>
            </div>
            <div class="chart-wrap">
                <canvas id="chartEventos"></canvas>
            </div>
            <div class="stats-mini">
                <div class="stat-pill">
                    <div class="sp-val" style="color:var(--teal);">{{ array_sum($cantidades) }}</div>
                    <div class="sp-lbl">Total 6m</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val" style="color:var(--green);">{{ $eventosHoy }}</div>
                    <div class="sp-lbl">Hoy</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val" style="color:var(--orange);">{{ $eventosSemana }}</div>
                    <div class="sp-lbl">Esta semana</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val" style="color:var(--purple);">{{ $topLike?->likes ?? 0 }}</div>
                    <div class="sp-lbl">Máx likes</div>
                </div>
            </div>
        </div>

        {{-- Columna 2: Top interacciones + acceso rápido --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Top interacciones de eventos --}}
            <div class="dash-card" style="gap:10px;">
                <div class="card-header">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        Top interacciones
                    </div>
                    <a href="{{ url('eventos') }}" class="card-link">Ver →</a>
                </div>
                @if($topLike)
                <div class="top-evento-card">
                    <div class="top-evento-poster">
                        @if($topLike->poster)
                            <img src="{{ asset('storage/'.$topLike->poster) }}" alt="{{ $topLike->titulo }}">
                        @else ❤️ @endif
                    </div>
                    <div class="top-evento-info">
                        <div class="top-evento-title">{{ Str::limit($topLike->titulo,26) }}</div>
                        <div class="top-evento-meta">
                            <span class="top-evento-stat" style="color:var(--red);">
                                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                {{ $topLike->likes }} likes
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @if($topFav)
                <div class="top-evento-card">
                    <div class="top-evento-poster">
                        @if($topFav->poster)
                            <img src="{{ asset('storage/'.$topFav->poster) }}" alt="{{ $topFav->titulo }}">
                        @else ⭐ @endif
                    </div>
                    <div class="top-evento-info">
                        <div class="top-evento-title">{{ Str::limit($topFav->titulo,26) }}</div>
                        <div class="top-evento-meta">
                            <span class="top-evento-stat" style="color:var(--orange);">
                                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                {{ $topFav->favoritos }} favoritos
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @if($topDislike ?? null)
                <div class="top-evento-card">
                    <div class="top-evento-poster" style="background:#fde8e8;">
                        @if($topDislike->poster)
                            <img src="{{ asset('storage/'.$topDislike->poster) }}" alt="{{ $topDislike->titulo }}">
                        @else 👎 @endif
                    </div>
                    <div class="top-evento-info">
                        <div class="top-evento-title">{{ Str::limit($topDislike->titulo,26) }}</div>
                        <div class="top-evento-meta">
                            <span class="top-evento-stat" style="color:var(--muted);">
                                <svg viewBox="0 0 24 24"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3z"/></svg>
                                {{ $topDislike->dislikes }} dislikes
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Acceso rápido --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 14 14"/></svg>
                        Acceso rápido
                    </div>
                </div>
                <div class="quick-grid">
                    <a href="{{ url('mapa') }}" class="quick-btn">
                        <span class="qb-icon">🗺️</span>
                        <span class="qb-label">Mapa campus</span>
                    </a>
                    <a href="{{ url('eventos') }}" class="quick-btn">
                        <span class="qb-icon">📅</span>
                        <span class="qb-label">Eventos</span>
                    </a>
                    <a href="{{ url('aulas') }}" class="quick-btn">
                        <span class="qb-icon">🏫</span>
                        <span class="qb-label">Aulas</span>
                    </a>
                    @if($esAdmin)
                    <a href="{{ url('usuarios') }}" class="quick-btn">
                        <span class="qb-icon">👥</span>
                        <span class="qb-label">Usuarios</span>
                    </a>
                    <a href="{{ route('solicitudes.index') }}" class="quick-btn">
                        <span class="qb-icon">📩</span>
                        <span class="qb-label">Solicitudes</span>
                    </a>
                    <a href="{{ route('respaldo.descargar') }}" class="quick-btn">
                        <span class="qb-icon">🗄️</span>
                        <span class="qb-label">Respaldo BD</span>
                    </a>
                    @else
                    <a href="{{ route('solicitudes.create') }}" class="quick-btn">
                        <span class="qb-icon">📝</span>
                        <span class="qb-label">Proponer evento</span>
                    </a>
                    <a href="{{ url('galeria') }}" class="quick-btn">
                        <span class="qb-icon">🖼️</span>
                        <span class="qb-label">Galería</span>
                    </a>
                    @endif
                </div>
            </div>

        </div>

        {{-- Columna 3: Próximos eventos + donut aulas --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Próximos eventos --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Próximos eventos
                    </div>
                    <a href="{{ url('eventos') }}" class="card-link">Ver →</a>
                </div>
                <div class="event-list">
                    @forelse($proximosEventos as $ev)
                        @php $fecha = \Carbon\Carbon::parse($ev->fecha_evento); @endphp
                        <div class="event-item">
                            <div class="event-date-box">
                                <div class="event-day">{{ $fecha->format('d') }}</div>
                                <div class="event-month">{{ $fecha->translatedFormat('M') }}</div>
                            </div>
                            <div class="event-body">
                                <div class="event-title">{{ $ev->titulo }}</div>
                                <div class="event-desc">{{ $fecha->format('H:i') }} h · {{ Str::limit($ev->descripcion ?? '—', 40) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="event-empty">📭 Sin eventos próximos</div>
                    @endforelse
                </div>
            </div>

            {{-- Donut aulas --}}
            <div class="dash-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>
                        Distribución de aulas
                    </div>
                </div>
                <div class="chart-wrap" style="height:150px;">
                    <canvas id="chartAulas"></canvas>
                </div>
                <div class="donut-legend">
                    <span style="display:flex;align-items:center;gap:4px;"><span class="leg-dot" style="background:#27ae60;"></span>Libres</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span class="leg-dot" style="background:#e74c3c;"></span>Ocupadas</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span class="leg-dot" style="background:#f39c12;"></span>Apartadas</span>
                </div>
            </div>

        </div>

    </div>{{-- /dash-grid --}}

    {{-- ══ SEGUNDA FILA: Solicitudes de eventos + Donut usuarios (solo admins) ══ --}}
    @if($esAdmin)
    <div class="section-label">Administración</div>
    <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:16px;">

        {{-- Panel solicitudes de eventos --}}
        <div class="dash-card" style="animation-delay:.55s;">
            <div class="card-header">
                <div class="card-title">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Solicitudes de eventos
                </div>
                <a href="{{ route('solicitudes.index') }}" class="card-link">Gestionar →</a>
            </div>
            {{-- Contadores --}}
            <div class="sol-stats">
                <div class="sol-stat">
                    <div class="ss-val" style="color:var(--orange);">{{ $solicitudesPendientes }}</div>
                    <div class="ss-lbl">Pendientes</div>
                </div>
                <div class="sol-stat">
                    <div class="ss-val" style="color:var(--green);">{{ $solicitudesAprobadas }}</div>
                    <div class="ss-lbl">Aprobadas</div>
                </div>
                <div class="sol-stat">
                    <div class="ss-val" style="color:var(--red);">{{ $solicitudesRechazadas }}</div>
                    <div class="ss-lbl">Rechazadas</div>
                </div>
            </div>
            {{-- Lista últimas solicitudes --}}
            <div class="sol-list">
                @forelse($ultimasSolicitudes as $sol)
                <div class="sol-item">
                    <div class="sol-avatar">📩</div>
                    <div class="sol-info">
                        <div class="sol-title">{{ Str::limit($sol->titulo, 34) }}</div>
                        <div class="sol-meta">
                            @if($sol->fechaevento)
                                {{ \Carbon\Carbon::parse($sol->fechaevento)->format('d M Y') }} ·
                            @endif
                            ID usuario: {{ $sol->usuario_id }}
                        </div>
                    </div>
                    <span class="sol-badge sol-{{ $sol->estado }}">{{ strtoupper($sol->estado) }}</span>
                </div>
                @empty
                <div class="event-empty">📭 Sin solicitudes recientes</div>
                @endforelse
            </div>
        </div>

        {{-- Donut distribución de usuarios por rol --}}
        <div class="dash-card" style="animation-delay:.6s;">
            <div class="card-header">
                <div class="card-title">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Usuarios por rol
                </div>
                <a href="{{ url('usuarios') }}" class="card-link">Gestionar →</a>
            </div>
            <div class="chart-wrap" style="height:160px;">
                <canvas id="chartRoles"></canvas>
            </div>
            <div class="donut-legend" style="flex-direction:column;align-items:flex-start;gap:5px;padding:0 4px;">
                @php
                    $roleColors = [
                        'administrador'    => ['bg'=>'#e74c3c','label'=>'👑 Admin'],
                        'jefe_departamento'=> ['bg'=>'#8e44ad','label'=>'🏛️ Jefe Dto.'],
                        'docente'          => ['bg'=>'#27ae60','label'=>'👨‍🏫 Docente'],
                        'estudiante'       => ['bg'=>'#2980b9','label'=>'🎓 Estudiante'],
                    ];
                @endphp
                @foreach($usuariosPorRol as $rol => $total)
                <span style="display:flex;align-items:center;gap:6px;font-size:.71rem;">
                    <span class="leg-dot" style="background:{{ $roleColors[$rol]['bg'] ?? '#9ab3c0' }};"></span>
                    {{ $roleColors[$rol]['label'] ?? $rol }}: <strong>{{ $total }}</strong>
                </span>
                @endforeach
            </div>
        </div>

    </div>
    @endif

    {{-- ── ESTADO DE AULAS ── --}}
<div class="section-label">Estado actual de aulas</div>
<div class="edificios-grid">

    {{-- Edificio A --}}
    <div class="edificio-card">
        <div class="edificio-card-header">
            <div class="edificio-card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Edificio A
            </div>
            <a href="{{ url('aulas') }}" class="edificio-card-link">Ver →</a>
        </div>

        @php
            $libresA   = collect($estadoAulas)->where('estado', 'libre')->count();
            $ocupadasA = collect($estadoAulas)->where('estado', 'ocupada')->count();
            $apartadasA= collect($estadoAulas)->whereIn('estado', ['apartada','bloqueada'])->count();
        @endphp
        <div class="edificio-resumen">
            <div class="ed-stat green">
                <div class="es-val">{{ $libresA }}</div>
                <div class="es-lbl">Libres</div>
            </div>
            <div class="ed-stat red">
                <div class="es-val">{{ $ocupadasA }}</div>
                <div class="es-lbl">Ocupadas</div>
            </div>
            <div class="ed-stat orange">
                <div class="es-val">{{ $apartadasA }}</div>
                <div class="es-lbl">No disp.</div>
            </div>
        </div>

        <div class="aulas-grid">
            @foreach($estadoAulas as $a)
            <div class="aula-row">
                <span class="aula-name">{{ $a['aula'] }}</span>
                <span class="aula-chip chip-{{ $a['estado'] }}">{{ strtoupper($a['estado']) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Edificio B --}}
    <div class="edificio-card">
        <div class="edificio-card-header">
            <div class="edificio-card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Edificio B
            </div>
            <a href="{{ url('aulas/edificio-b') }}" class="edificio-card-link">Ver →</a>
        </div>

        @php
            $libresB   = collect($estadoAulasB)->where('estado', 'libre')->count();
            $ocupadasB = collect($estadoAulasB)->where('estado', 'ocupada')->count();
            $apartadasB= collect($estadoAulasB)->whereIn('estado', ['apartada','bloqueada'])->count();
        @endphp
        <div class="edificio-resumen">
            <div class="ed-stat green">
                <div class="es-val">{{ $libresB }}</div>
                <div class="es-lbl">Libres</div>
            </div>
            <div class="ed-stat red">
                <div class="es-val">{{ $ocupadasB }}</div>
                <div class="es-lbl">Ocupadas</div>
            </div>
            <div class="ed-stat orange">
                <div class="es-val">{{ $apartadasB }}</div>
                <div class="es-lbl">No disp.</div>
            </div>
        </div>

        <div class="aulas-grid">
            @foreach($estadoAulasB as $a)
            <div class="aula-row">
                <span class="aula-name">{{ $a['aula'] }}</span>
                <span class="aula-chip chip-{{ $a['estado'] }}">{{ strtoupper($a['estado']) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Edificio C / Labs --}}
    @if(!empty($estadoAulasC))
    <div class="edificio-card">
        <div class="edificio-card-header">
            <div class="edificio-card-title">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Edificio C — Laboratorios
            </div>
            <a href="{{ url('aulas/edificio-c') }}" class="edificio-card-link">Ver →</a>
        </div>

        @php
            $libresC   = collect($estadoAulasC)->where('estado', 'libre')->count();
            $ocupadasC = collect($estadoAulasC)->where('estado', 'ocupada')->count();
            $apartadasC= collect($estadoAulasC)->whereIn('estado', ['apartada','bloqueada'])->count();
        @endphp
        <div class="edificio-resumen">
            <div class="ed-stat green">
                <div class="es-val">{{ $libresC }}</div>
                <div class="es-lbl">Libres</div>
            </div>
            <div class="ed-stat red">
                <div class="es-val">{{ $ocupadasC }}</div>
                <div class="es-lbl">Ocupadas</div>
            </div>
            <div class="ed-stat orange">
                <div class="es-val">{{ $apartadasC }}</div>
                <div class="es-lbl">No disp.</div>
            </div>
        </div>

        <div class="aulas-grid">
            @foreach($estadoAulasC as $a)
            <div class="aula-row">
                <span class="aula-name">{{ $a['aula'] }}</span>
                <span class="aula-chip chip-{{ $a['estado'] }}">{{ strtoupper($a['estado']) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Manufactura --}}
    @if(!empty($estadoManufactura))
    <div class="edificio-card">
        <div class="edificio-card-header">
            <div class="edificio-card-title">
                <svg viewBox="0 0 24 24"><path d="M2 20h20M4 20V10l8-6 8 6v10"/><path d="M9 20v-5h6v5"/></svg>
                Manufactura
            </div>
            <a href="{{ url('aulas/manufactura') }}" class="edificio-card-link">Ver →</a>
        </div>
        <div class="aulas-grid">
            @foreach($estadoManufactura as $a)
            <div class="aula-row">
                <span class="aula-name">{{ $a['aula'] }}</span>
                <span class="aula-chip chip-{{ $a['estado'] }}">{{ strtoupper($a['estado']) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

</div>{{-- /u-main --}}

<footer class="u-footer">
    © {{ date('Y') }} Ubicatec &nbsp;·&nbsp; Sistema de gestión del campus universitario &nbsp;·&nbsp; {{ $dia }}
</footer>
</div>
@endsection


@section('scripts')
<script>
/* ── Reloj en vivo ── */
(function(){
    const D=['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    const M=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    function tick(){
        const n=new Date();
        document.getElementById('live-day').textContent=D[n.getDay()];
        document.getElementById('live-time').textContent=
            String(n.getHours()).padStart(2,'0')+':'+
            String(n.getMinutes()).padStart(2,'0')+':'+
            String(n.getSeconds()).padStart(2,'0');
        document.getElementById('live-date').textContent=
            n.getDate()+' '+M[n.getMonth()]+' '+n.getFullYear();
    }
    tick(); setInterval(tick,1000);
})();

/* ── Animación de números KPI al cargar ── */
function animateCount(el, target, duration=900){
    const start = performance.now();
    function step(now){
        const p = Math.min((now-start)/duration,1);
        const ease = 1-Math.pow(1-p,3);
        el.textContent = Math.round(target*ease);
        if(p<1) requestAnimationFrame(step);
        else el.textContent = target;
    }
    requestAnimationFrame(step);
}
document.addEventListener('DOMContentLoaded',()=>{
    document.querySelectorAll('.kpi-value, .sp-val, .ss-val').forEach(el=>{
        const val = parseInt(el.textContent.trim(),10);
        if(!isNaN(val) && val>0) animateCount(el,val);
    });
});

/* ── Chart: Eventos por mes ── */
(function(){
    const ctx = document.getElementById('chartEventos').getContext('2d');
    const meses  = @json(array_slice($meses,-6));
    const cants  = @json(array_slice($cantidades,-6));
    const maxVal = Math.max(...cants, 1);
    new Chart(ctx,{
        type:'bar',
        data:{
            labels:meses,
            datasets:[{
                label:'Eventos',
                data:cants,
                backgroundColor:cants.map(v=>`rgba(55,96,115,${0.15+0.55*(v/maxVal)})`),
                borderColor:'#376073',
                borderWidth:2,
                borderRadius:8,
                hoverBackgroundColor:'rgba(55,96,115,.45)',
            }]
        },
        options:{
            responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>`${c.parsed.y} eventos`}}},
            scales:{
                x:{grid:{display:false},ticks:{color:'#5a7a8a',font:{size:11}}},
                y:{grid:{color:'rgba(0,0,0,.05)'},ticks:{color:'#5a7a8a',font:{size:11},stepSize:1},beginAtZero:true}
            }
        }
    });
})();

/* ── Chart: Donut aulas ── */
(function(){
    const ctx = document.getElementById('chartAulas').getContext('2d');
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels:['Libres','Ocupadas','Apartadas'],
            datasets:[{
                data:[{{ $aulasLibres }},{{ $aulasOcupadas }},{{ $apartadosHoy }}],
                backgroundColor:['#27ae60','#e74c3c','#f39c12'],
                borderWidth:3,borderColor:'#fff',hoverOffset:8
            }]
        },
        options:{
            responsive:true,maintainAspectRatio:false,cutout:'68%',
            plugins:{legend:{display:false},tooltip:{callbacks:{label:c=>`${c.label}: ${c.parsed}`}}}
        }
    });
})();

/* ── Chart: Donut roles de usuarios (solo admin) ── */
@if($esAdmin)
(function(){
    const ctx = document.getElementById('chartRoles');
    if(!ctx) return;
    const roles = @json(array_keys($usuariosPorRol));
    const totals = @json(array_values($usuariosPorRol));
    const colors = {'administrador':'#e74c3c','jefe_departamento':'#8e44ad','docente':'#27ae60','estudiante':'#2980b9'};
    const bgColors = roles.map(r => colors[r] ?? '#9ab3c0');
    new Chart(ctx.getContext('2d'),{
        type:'doughnut',
        data:{
            labels:roles.map(r=>({administrador:'Admin',jefe_departamento:'Jefe Dto.',docente:'Docente',estudiante:'Estudiante'}[r]??r)),
            datasets:[{data:totals,backgroundColor:bgColors,borderWidth:3,borderColor:'#fff',hoverOffset:8}]
        },
        options:{
            responsive:true,maintainAspectRatio:false,cutout:'65%',
            plugins:{
                legend:{display:false},
                tooltip:{callbacks:{label:c=>`${c.label}: ${c.parsed} usuarios`}}
            }
        }
    });
})();
@endif

/* ── Auto-dismiss alerts ── */
setTimeout(()=>{
    document.querySelectorAll('.u-alert').forEach(el=>{
        el.style.transition='opacity .5s';
        el.style.opacity='0';
        setTimeout(()=>el.remove(),500);
    });
},4000);
</script>
@endsection