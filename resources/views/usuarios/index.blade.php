@extends('layouts.app')
@section('title','Usuarios — Ubicatec')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
:root{
    --teal:#376073;--teal-dark:#2c4f60;--teal-light:#5a8fa3;
    --text:#1e3040;--text-muted:#5a7a8a;--text-faint:#9ab3c0;
    --surface:#fff;--bg:#eef4f8;--border:rgba(55,96,115,.13);
    --radius:12px;
    --shadow-sm:0 2px 8px rgba(0,0,0,.07);
    --shadow-md:0 6px 24px rgba(0,0,0,.10);
    --ease:cubic-bezier(.34,1.56,.64,1);
}
.u-page{display:flex;flex-direction:column;gap:20px;padding:4px 0 28px;}
.section-hero{
    background:linear-gradient(135deg,#0f2535,var(--teal-dark) 45%,var(--teal) 100%);
    border-radius:var(--radius);padding:22px 28px;
    display:flex;align-items:center;justify-content:space-between;gap:16px;
    box-shadow:var(--shadow-md);position:relative;overflow:hidden;
}
.section-hero::before{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse at 0% 100%,rgba(74,156,109,.15) 0%,transparent 55%),
               radial-gradient(ellipse at 100% 0%,rgba(90,143,163,.12) 0%,transparent 50%);
    pointer-events:none;
}
.sh-left{position:relative;z-index:1;}
.sh-title{font-family:'Instrument Serif',serif;font-size:1.6rem;color:#fff;line-height:1.1;}
.sh-sub{font-size:.75rem;color:rgba(255,255,255,.6);margin-top:4px;}
.sh-right{position:relative;z-index:1;display:flex;gap:10px;align-items:center;}
.kpi-chips{display:flex;gap:10px;flex-wrap:wrap;}
.kpi-chip{
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
    border-radius:20px;padding:6px 16px;font-size:.75rem;
    color:rgba(255,255,255,.9);backdrop-filter:blur(6px);
    display:flex;align-items:center;gap:7px;font-weight:600;
}
.kpi-chip-val{font-size:1rem;font-weight:800;}
.toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.search-box{position:relative;flex:1;max-width:320px;}
.search-box input{
    width:100%;padding:9px 14px 9px 38px;
    border:1.5px solid var(--border);border-radius:20px;
    font-size:.82rem;color:var(--text);background:var(--surface);
    outline:none;transition:border-color .2s,box-shadow .2s;
}
.search-box input:focus{border-color:var(--teal);box-shadow:0 0 0 3px rgba(55,96,115,.12);}
.search-box svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;stroke:var(--text-faint);fill:none;stroke-width:2;}
.toolbar-actions{display:flex;gap:8px;}
.btn-create{
    display:flex;align-items:center;gap:7px;
    padding:9px 18px;border-radius:20px;
    background:linear-gradient(135deg,var(--teal-dark),var(--teal));
    color:#fff;border:none;font-size:.82rem;font-weight:700;
    cursor:pointer;transition:opacity .2s,transform .15s var(--ease);
    box-shadow:0 4px 14px rgba(55,96,115,.3);
}
.btn-create:hover{opacity:.88;transform:translateY(-1px);}
.table-card{
    background:var(--surface);border:1px solid var(--border);
    border-radius:var(--radius);overflow:hidden;
    box-shadow:var(--shadow-sm);
}
.table-card table{width:100%;border-collapse:collapse;}
.table-card thead{background:linear-gradient(135deg,#0f2535,var(--teal-dark));}
.table-card thead th{
    padding:12px 16px;font-size:.72rem;font-weight:700;
    color:rgba(255,255,255,.8);text-transform:uppercase;letter-spacing:.8px;
    border:none;white-space:nowrap;
}
.table-card tbody tr{border-bottom:1px solid var(--border);transition:background .15s;}
.table-card tbody tr:last-child{border-bottom:none;}
.table-card tbody tr:hover{background:#f5f9fc;}
.table-card tbody td{padding:12px 16px;font-size:.82rem;color:var(--text);vertical-align:middle;}
.user-cell{display:flex;align-items:center;gap:10px;}
.user-avatar{
    width:34px;height:34px;border-radius:50%;object-fit:cover;
    border:2px solid rgba(55,96,115,.2);flex-shrink:0;
    background:var(--teal);display:flex;align-items:center;justify-content:center;
    font-size:.7rem;color:#fff;font-weight:700;
}
img.user-avatar{background:transparent;}
.user-name{font-weight:600;color:var(--text);}
.user-control{font-size:.7rem;color:var(--text-faint);}
.rol-badge{
    padding:3px 10px;border-radius:20px;
    font-size:.68rem;font-weight:700;letter-spacing:.3px;
    display:inline-flex;align-items:center;gap:4px;white-space:nowrap;
}
.rol-administrador{background:#fde8e8;color:#c0392b;}
.rol-docente{background:#e4f0fb;color:#2980b9;}
.rol-jefe_departamento{background:#f3eafd;color:#7d3fba;}
.rol-estudiante{background:#e8fdf2;color:#27ae60;}
.carrera-badge{
    padding:3px 10px;border-radius:6px;
    font-size:.7rem;font-weight:600;
    background:rgba(55,96,115,.08);color:var(--teal);white-space:nowrap;
}
.action-btns{display:flex;gap:6px;}
.btn-action{
    width:30px;height:30px;border-radius:8px;border:1.5px solid var(--border);
    background:var(--surface);cursor:pointer;display:flex;align-items:center;justify-content:center;
    transition:all .2s var(--ease);color:var(--text-muted);
}
.btn-action svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
.btn-action.edit:hover{background:#e4f0fb;border-color:#2980b9;color:#2980b9;}
.btn-action.del:hover{background:#fde8e8;border-color:#e74c3c;color:#e74c3c;}
.btn-action.rol:hover{background:#f3eafd;border-color:#7d3fba;color:#7d3fba;}
.empty-row td{text-align:center;padding:40px;color:var(--text-faint);font-size:.85rem;}
.chart-section{
    background:var(--surface);border:1px solid var(--border);
    border-radius:var(--radius);padding:24px;box-shadow:var(--shadow-sm);
}
.chart-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.chart-title{font-family:'Instrument Serif',serif;font-size:1.05rem;color:var(--teal);display:flex;align-items:center;gap:8px;}
.chart-title svg{width:17px;height:17px;stroke:var(--teal);fill:none;stroke-width:2;}
.chart-wrap{position:relative;height:240px;max-width:500px;margin:0 auto;}
.modal-content{border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);}
.modal-header-custom{
    background:linear-gradient(135deg,#0f2535,var(--teal));
    padding:20px 24px 16px;display:flex;align-items:center;justify-content:space-between;
}
.modal-header-title{font-family:'Instrument Serif',serif;font-size:1.2rem;color:#fff;display:flex;align-items:center;gap:10px;}
.modal-header-icon{width:32px;height:32px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;}
.modal-body-custom{padding:20px 24px;}
.form-group{margin-bottom:14px;}
.form-label-custom{font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;display:block;}
.form-control-custom{
    width:100%;padding:9px 13px;
    border:1.5px solid var(--border);border-radius:9px;
    font-size:.85rem;color:var(--text);background:var(--surface);
    outline:none;transition:border-color .2s,box-shadow .2s;font-family:'Inter',sans-serif;
}
.form-control-custom:focus{border-color:var(--teal);box-shadow:0 0 0 3px rgba(55,96,115,.12);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.modal-footer-custom{padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px;background:#fafafa;}
.btn-cancel{padding:8px 18px;border-radius:9px;background:none;border:1.5px solid var(--border);color:var(--text-muted);font-size:.82rem;font-weight:600;cursor:pointer;transition:all .2s;}
.btn-cancel:hover{background:#f0f4f8;}
.btn-save{padding:8px 22px;border-radius:9px;background:linear-gradient(135deg,var(--teal-dark),var(--teal));color:#fff;border:none;font-size:.82rem;font-weight:700;cursor:pointer;transition:opacity .2s,transform .15s var(--ease);box-shadow:0 4px 12px rgba(55,96,115,.25);}
.btn-save:hover{opacity:.88;transform:translateY(-1px);}
.rol-options{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px;}
.rol-option{
    padding:12px 14px;border-radius:10px;border:2px solid var(--border);
    cursor:pointer;transition:all .2s var(--ease);display:flex;align-items:center;gap:10px;
    background:var(--surface);
}
.rol-option:hover{border-color:var(--teal);background:#f0f7fa;}
.rol-option.selected{border-color:var(--teal);background:#e4f0f5;}
.rol-option input[type=radio]{display:none;}
.rol-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.rol-label{font-size:.8rem;font-weight:700;color:var(--text);}
.rol-desc{font-size:.68rem;color:var(--text-faint);margin-top:1px;}
@media(max-width:768px){
    .form-row{grid-template-columns:1fr;}
    .kpi-chips{display:none;}
    .rol-options{grid-template-columns:1fr;}
}
</style>
@endsection

@section('content')
<div class="u-page">

    {{-- ── HERO ── --}}
    <div class="section-hero">
        <div class="sh-left">
            <div class="sh-title">👥 Gestión de Usuarios</div>
            <div class="sh-sub">Administra los usuarios registrados en el campus</div>
        </div>
        <div class="sh-right">
            <div class="kpi-chips">
                <div class="kpi-chip">
                    <span>Total</span>
                    <span class="kpi-chip-val" id="kpi-total">—</span>
                </div>
                <div class="kpi-chip">
                    <span>🎓</span>
                    <span class="kpi-chip-val" id="kpi-estudiantes">—</span>
                </div>
                <div class="kpi-chip">
                    <span>👨‍🏫</span>
                    <span class="kpi-chip-val" id="kpi-docentes">—</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TOOLBAR ── --}}
    <div class="toolbar">
        <div class="search-box">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="searchInput" placeholder="Buscar por nombre, no. control o correo…">
        </div>
        <div class="toolbar-actions">
            <button class="btn-create" onclick="abrirModalCrear()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Usuario
            </button>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="table-card">
        <table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Ap. Paterno</th>
                    <th>Ap. Materno</th>
                    <th>Correo</th>
                    <th>Carrera</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <tr class="empty-row"><td colspan="7">Cargando usuarios…</td></tr>
            </tbody>
        </table>
    </div>

    {{-- ── GRÁFICA ── --}}
    <div class="chart-section">
        <div class="chart-header">
            <div class="chart-title">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>
                Distribución por carrera
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="graficaCarreras"></canvas>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════
     MODAL — CREAR / EDITAR USUARIO
════════════════════════════════════════ --}}
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formUsuario">
                <input type="hidden" id="edit_id">
                <div class="modal-header-custom">
                    <div class="modal-header-title">
                        <div class="modal-header-icon" id="modal-icon">✏️</div>
                        <span id="modal-titulo">Nuevo Usuario</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <div class="form-group">
                        <label class="form-label-custom">No. Control</label>
                        <input type="text" id="no_control" class="form-control-custom" placeholder="Ej. 20130001" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label-custom">Nombre</label>
                            <input type="text" id="nombre" class="form-control-custom" placeholder="Nombre(s)" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label-custom">Ap. Paterno</label>
                            <input type="text" id="ape_paterno" class="form-control-custom" placeholder="Apellido paterno" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Ap. Materno</label>
                        <input type="text" id="ape_materno" class="form-control-custom" placeholder="Apellido materno">
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Correo electrónico</label>
                        <input type="email" id="correo" class="form-control-custom" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Contraseña</label>
                        <input type="password" id="password" class="form-control-custom" placeholder="Dejar vacío para no cambiar">
                    </div>
                    <div class="form-group">
                        <label class="form-label-custom">Carrera</label>
                        <select id="cod_carrera" class="form-control-custom" required>
                            <option value="">Selecciona una carrera</option>
                            <option value="A">Ambiental</option>
                            <option value="C">Contador Público</option>
                            <option value="E">Electromecánica</option>
                            <option value="G">Gestión Empresarial</option>
                            <option value="I">Industrial</option>
                            <option value="S">Sistemas Computacionales</option>
                            <option value="T">Turismo</option>
                            <option value="MX">Extraescolares (MX)</option>
                            <option value="VX">Extraescolares (VX)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-save">
                        <span id="btn-save-text">💾 Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     MODAL — CAMBIAR ROL
     (admin ve los 4 roles, jefe solo docente y estudiante)
════════════════════════════════════════ --}}
@if(session('usuario')?->tieneRol(['administrador','jefe_departamento']))
<div class="modal fade" id="modalRol" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="modal-header-title">
                    <div class="modal-header-icon">🛡️</div>
                    <span>Cambiar Rol</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body-custom">
                <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:4px;">
                    Usuario: <strong id="rol-usuario-nombre"></strong>
                </p>
                <input type="hidden" id="rol-usuario-id">
                <div class="rol-options">

                    {{-- Solo admin puede asignar admin y jefe --}}
                    @if(session('usuario')?->rol === 'administrador')
                    <label class="rol-option" onclick="selectRol(this,'administrador')">
                        <input type="radio" name="nuevo_rol" value="administrador">
                        <div class="rol-icon" style="background:#fde8e8;">👑</div>
                        <div>
                            <div class="rol-label">Admin</div>
                            <div class="rol-desc">Acceso total</div>
                        </div>
                    </label>
                    <label class="rol-option" onclick="selectRol(this,'jefe_departamento')">
                        <input type="radio" name="nuevo_rol" value="jefe_departamento">
                        <div class="rol-icon" style="background:#f3eafd;">🏛️</div>
                        <div>
                            <div class="rol-label">Jefe Dto.</div>
                            <div class="rol-desc">Gestión parcial</div>
                        </div>
                    </label>
                    @endif

                    {{-- Admin y jefe pueden asignar docente y estudiante --}}
                    <label class="rol-option" onclick="selectRol(this,'docente')">
                        <input type="radio" name="nuevo_rol" value="docente">
                        <div class="rol-icon" style="background:#e4f0fb;">👨‍🏫</div>
                        <div>
                            <div class="rol-label">Docente</div>
                            <div class="rol-desc">Sin usuarios</div>
                        </div>
                    </label>
                    <label class="rol-option" onclick="selectRol(this,'estudiante')">
                        <input type="radio" name="nuevo_rol" value="estudiante">
                        <div class="rol-icon" style="background:#e8fdf2;">🎓</div>
                        <div>
                            <div class="rol-label">Estudiante</div>
                            <div class="rol-desc">Solo lectura</div>
                        </div>
                    </label>

                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-save" onclick="guardarRol()">🛡️ Asignar Rol</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
const TOKEN = '{{ csrf_token() }}';
const PUEDE_CAMBIAR_ROL = {{ session('usuario')?->tieneRol(['administrador','jefe_departamento']) ? 'true' : 'false' }};

const modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));
const modalRolEl   = document.getElementById('modalRol');
const modalRol     = modalRolEl ? new bootstrap.Modal(modalRolEl) : null;

let chart;
let todosUsuarios = [];

const CARRERAS = {
    "A":"Ambiental","C":"Contador Público","E":"Electromecánica",
    "G":"Gestión Empresarial","I":"Industrial","S":"Sistemas Computacionales",
    "T":"Turismo","MX":"Extraescolares (MX)","VX":"Extraescolares (VX)"
};
const ROL_LABELS = {
    administrador:'👑 Admin', docente:'👨‍🏫 Docente',
    jefe_departamento:'🏛️ Jefe Dto.', estudiante:'🎓 Estudiante'
};
const ROL_COLORS = {
    administrador:'rol-administrador', docente:'rol-docente',
    jefe_departamento:'rol-jefe_departamento', estudiante:'rol-estudiante'
};

/* ── Iniciales avatar ── */
function iniciales(u){
    return ((u.nombre?.[0]??'') + (u.ape_paterno?.[0]??'')).toUpperCase();
}

/* ── Avatar: foto si existe, iniciales si no ── */
function avatarHtml(u){
    if(u.foto){
        return `<img class="user-avatar" src="/storage/${u.foto}" alt="${iniciales(u)}">`;
    }
    return `<div class="user-avatar">${iniciales(u)}</div>`;
}

/* ── Render tabla ── */
function renderTabla(usuarios){
    const tbody = document.getElementById('tbody');
    if(!usuarios.length){
        tbody.innerHTML = `<tr class="empty-row"><td colspan="7">Sin usuarios encontrados</td></tr>`;
        return;
    }
    tbody.innerHTML = usuarios.map(u => `
        <tr>
            <td>
                <div class="user-cell">
                    ${avatarHtml(u)}
                    <div>
                        <div class="user-name">${u.nombre} ${u.ape_paterno}</div>
                        <div class="user-control">${u.no_control}</div>
                    </div>
                </div>
            </td>
            <td>${u.ape_paterno}</td>
            <td>${u.ape_materno ?? '—'}</td>
            <td style="color:var(--text-muted);font-size:.78rem;">${u.correo ?? '—'}</td>
            <td><span class="carrera-badge">${CARRERAS[u.cod_carrera] ?? u.cod_carrera ?? '—'}</span></td>
            <td>
                <span class="rol-badge ${ROL_COLORS[u.rol] ?? 'rol-estudiante'}">
                    ${ROL_LABELS[u.rol] ?? u.rol ?? 'Estudiante'}
                </span>
            </td>
            <td>
                <div class="action-btns">
                    <button class="btn-action edit" onclick="abrirModalEditar('${u.no_control}')" title="Editar">
                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    ${PUEDE_CAMBIAR_ROL ? `
                    <button class="btn-action rol" onclick="abrirModalRol('${u.no_control}','${u.nombre} ${u.ape_paterno}','${u.rol??'estudiante'}')" title="Cambiar rol">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </button>` : ''}
                    <button class="btn-action del" onclick="eliminarUsuario('${u.no_control}')" title="Eliminar">
                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

/* ── Cargar usuarios ── */
async function cargarUsuarios(){
    try {
        const res = await fetch("{{ route('usuarios.api') }}");
        todosUsuarios = await res.json();
        renderTabla(todosUsuarios);
        actualizarKPIs(todosUsuarios);
        crearGrafica(todosUsuarios);
    } catch(err){
        console.error('Error cargando usuarios:', err);
    }
}

/* ── KPIs ── */
function actualizarKPIs(u){
    document.getElementById('kpi-total').textContent = u.length;
    document.getElementById('kpi-estudiantes').textContent = u.filter(x=>!x.rol||x.rol==='estudiante').length;
    document.getElementById('kpi-docentes').textContent = u.filter(x=>x.rol==='docente').length;
}

/* ── Búsqueda ── */
document.getElementById('searchInput').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    const filtrados = todosUsuarios.filter(u =>
        (u.nombre??'').toLowerCase().includes(q) ||
        (u.no_control??'').toLowerCase().includes(q) ||
        (u.correo??'').toLowerCase().includes(q) ||
        (u.ape_paterno??'').toLowerCase().includes(q)
    );
    renderTabla(filtrados);
});

/* ── Modal crear ── */
function abrirModalCrear(){
    document.getElementById('edit_id').value = '';
    document.getElementById('formUsuario').reset();
    document.getElementById('modal-titulo').textContent = 'Nuevo Usuario';
    document.getElementById('modal-icon').textContent = '✏️';
    document.getElementById('no_control').removeAttribute('readonly');
    modalUsuario.show();
}

/* ── Modal editar ── */
async function abrirModalEditar(id){
    const res = await fetch(`/usuarios/${id}/editar`);
    const u   = await res.json();
    document.getElementById('edit_id').value     = u.no_control;
    document.getElementById('no_control').value  = u.no_control;
    document.getElementById('no_control').setAttribute('readonly','true');
    document.getElementById('nombre').value      = u.nombre;
    document.getElementById('ape_paterno').value = u.ape_paterno;
    document.getElementById('ape_materno').value = u.ape_materno ?? '';
    document.getElementById('correo').value      = u.correo ?? '';
    document.getElementById('password').value    = '';
    document.getElementById('cod_carrera').value = u.cod_carrera ?? '';
    document.getElementById('modal-titulo').textContent = 'Editar Usuario';
    document.getElementById('modal-icon').textContent   = '✏️';
    modalUsuario.show();
}

/* ── Modal rol ── */
function abrirModalRol(id, nombre, rolActual){
    document.getElementById('rol-usuario-id').value          = id;
    document.getElementById('rol-usuario-nombre').textContent = nombre;
    document.querySelectorAll('.rol-option').forEach(el => {
        el.classList.remove('selected');
        const radio = el.querySelector('input[type=radio]');
        if(radio && radio.value === rolActual){
            el.classList.add('selected');
            radio.checked = true;
        }
    });
    modalRol.show();
}

function selectRol(el, val){
    document.querySelectorAll('.rol-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type=radio]').checked = true;
}

async function guardarRol(){
    const id              = document.getElementById('rol-usuario-id').value;
    const rolSeleccionado = document.querySelector('input[name=nuevo_rol]:checked')?.value;

    if(!rolSeleccionado) return Swal.fire('Atención','Selecciona un rol','warning');

    try {
        const res = await fetch(`/usuarios/${id}/rol`, {
            method: 'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ rol: rolSeleccionado })
        });

        const data = await res.json();

        if(!res.ok){
            return Swal.fire({ icon:'error', title:'No autorizado', text: data.message ?? 'Sin permisos' });
        }

        if(data.ok){
            modalRol.hide();
            await cargarUsuarios();
            Swal.fire({ icon:'success', title:`Rol cambiado a: ${rolSeleccionado}`, timer:1800, showConfirmButton:false });
        }
    } catch(err){
        console.error('Error:', err);
        Swal.fire({ icon:'error', title:'Error al actualizar rol' });
    }
}

/* ── Guardar usuario ── */
document.getElementById('formUsuario').onsubmit = async function(e){
    e.preventDefault();
    const id = document.getElementById('edit_id').value;
    const fd = new FormData();
    fd.append('no_control',  document.getElementById('no_control').value);
    fd.append('nombre',      document.getElementById('nombre').value);
    fd.append('ape_paterno', document.getElementById('ape_paterno').value);
    fd.append('ape_materno', document.getElementById('ape_materno').value);
    fd.append('correo',      document.getElementById('correo').value);
    fd.append('password',    document.getElementById('password').value);
    fd.append('cod_carrera', document.getElementById('cod_carrera').value);
    if(id){ fd.append('_method','PUT'); }
    const url = id ? `/usuarios/${id}` : `/usuarios`;
    await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN':TOKEN}, body:fd });
    modalUsuario.hide();
    Swal.fire({ icon:'success', title: id ? 'Actualizado' : 'Creado', text:'Usuario guardado correctamente.', timer:2000, showConfirmButton:false });
    cargarUsuarios();
};

/* ── Eliminar ── */
function eliminarUsuario(id){
    Swal.fire({
        title:'¿Eliminar usuario?', text:'Esta acción no se puede deshacer.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#e74c3c', cancelButtonColor:'#376073',
        confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar'
    }).then(async r => {
        if(!r.isConfirmed) return;
        await fetch(`/usuarios/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':TOKEN} });
        Swal.fire({ icon:'success', title:'Eliminado', timer:1800, showConfirmButton:false });
        cargarUsuarios();
    });
}

/* ── Gráfica ── */
function crearGrafica(usuarios){
    const conteo = {};
    usuarios.forEach(u => { conteo[u.cod_carrera] = (conteo[u.cod_carrera]||0) + 1; });
    const ordenado = Object.entries(conteo).sort((a,b) => (CARRERAS[a[0]]??'').localeCompare(CARRERAS[b[0]]??''));
    const labels = ordenado.map(c => CARRERAS[c[0]] ?? c[0]);
    const data   = ordenado.map(c => c[1]);
    const colores = ['#376073','#27ae60','#2980b9','#f39c12','#8b5cf6','#e74c3c','#14b8a6','#f97316','#64748b'];
    const ctx = document.getElementById('graficaCarreras').getContext('2d');
    if(chart) chart.destroy();
    chart = new Chart(ctx, {
        type:'doughnut',
        data:{ labels, datasets:[{ data, backgroundColor:colores, borderWidth:3, borderColor:'#fff', hoverOffset:8 }] },
        options:{
            responsive:true, maintainAspectRatio:false, cutout:'65%',
            plugins:{
                legend:{ position:'right', labels:{ boxWidth:14, padding:12, font:{size:12,family:'Inter'} } },
                tooltip:{ callbacks:{ label: ctx => ` ${ctx.label}: ${ctx.parsed} usuarios` } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>
@endsection