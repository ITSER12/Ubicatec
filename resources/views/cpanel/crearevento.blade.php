@extends('layouts.app')
@section('content')
<div class="create-ev-layout">

    {{-- Columna izquierda: formulario --}}
    <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data" id="form-crear">
        @csrf

        <div class="form-step" id="step-1">
            <h3>① Información básica</h3>
            <div class="form-group">
                <label for="titulo">Título del evento</label>
                <input type="text" name="titulo" id="titulo" required placeholder="Nombre del evento">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" placeholder="Describe el evento..."></textarea>
            </div>
            <div class="form-group">
                <label for="fechaevento">Fecha y hora</label>
                <input type="datetime-local" name="fechaevento" id="fechaevento">
            </div>
            <button type="button" class="btn-next" onclick="irPaso(2)">Siguiente →</button>
        </div>

        <div class="form-step hidden" id="step-2">
            <h3>② Imagen y estilo</h3>
            <div class="form-group">
                <label for="poster">Imagen del evento</label>
                <input type="file" name="poster" id="poster" accept="image/*">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Color de fondo</label>
                    <input type="color" name="colorfondo" value="#ffffff" id="colorfondo">
                </div>
                <div class="form-group">
                    <label>Color de texto</label>
                    <input type="color" name="colortexto" value="#1e3040" id="colortexto">
                </div>
            </div>
            <button type="button" onclick="irPaso(1)">← Anterior</button>
            <button type="button" class="btn-next" onclick="irPaso(3)">Siguiente →</button>
        </div>

        <div class="form-step hidden" id="step-3">
            <h3>③ Confirmar y publicar</h3>
            <p>Revisa la previsualización a la derecha y confirma.</p>
            <button type="button" onclick="irPaso(2)">← Anterior</button>
            <button type="submit" class="btn-ev btn-ev-primary">✓ Publicar evento</button>
        </div>
    </form>

    {{-- Columna derecha: preview en vivo --}}
    <div class="ev-preview-wrap">
        <div class="section-label">Vista previa del cartel</div>
        <div class="ev-card" id="preview-card">
            <div class="ev-card-img-wrap">
                <img src="" alt="preview" class="ev-card-img" id="preview-img" style="display:none">
                <div class="ev-card-img-placeholder" id="preview-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5" opacity=".3">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            </div>
            <div class="ev-card-body">
                <div class="ev-card-title" id="preview-titulo">Título del evento</div>
                <div class="ev-card-desc" id="preview-desc">Descripción del evento...</div>
                <div class="ev-card-fecha" id="preview-fecha"></div>
            </div>
        </div>
    </div>

</div>

<style>
.create-ev-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    padding: 24px 0;
}
.form-step { display: flex; flex-direction: column; gap: 16px; }
.form-step.hidden { display: none; }
.form-group label { font-size: .8rem; font-weight: 700; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; display: block; }
.form-group input, .form-group textarea, .form-group select {
    width: 100%; padding: 10px 14px; border: 1.5px solid var(--border);
    border-radius: 9px; font-size: .87rem; color: var(--text);
    background: var(--surface); outline: none; transition: border-color .2s; }
.form-group input:focus, .form-group textarea:focus { border-color: var(--teal); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.btn-next { background: linear-gradient(135deg, var(--teal-dark), var(--teal));
    color: #fff; border: none; border-radius: 9px; padding: 10px 22px;
    font-weight: 700; cursor: pointer; align-self: flex-end; }
h3 { font-family: 'Instrument Serif', serif; color: var(--teal); margin-bottom: 8px; }
.ev-preview-wrap { position: sticky; top: 20px; }
.ev-card-img-placeholder {
    width: 100%; height: 185px; background: var(--bg);
    display: flex; align-items: center; justify-content: center; }
.ev-card-fecha { font-size: .75rem; color: var(--text-muted); margin-top: 6px; }
@media (max-width: 768px) {
    .create-ev-layout { grid-template-columns: 1fr; }
    .ev-preview-wrap { position: static; }
}
</style>

<script>
function irPaso(n) {
    document.querySelectorAll('.form-step').forEach(s => s.classList.add('hidden'));
    document.getElementById('step-' + n).classList.remove('hidden');
}

// Preview en vivo
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
        previewFecha.textContent = d.toLocaleDateString('es-MX',
            { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }
});

document.getElementById('colorfondo').addEventListener('input', e =>
    previewCard.style.background = e.target.value);

document.getElementById('colortexto').addEventListener('input', e =>
    previewCard.style.color = e.target.value);

document.getElementById('poster').addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        previewImg.src = ev.target.result;
        previewImg.style.display = 'block';
        previewPh.style.display  = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection