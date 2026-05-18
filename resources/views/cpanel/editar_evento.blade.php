@extends('layouts.app')

@section('title', 'Editar Evento')

@section('content')

<div class="container">

<h2 class="mb-4">Editar Evento</h2>

<form action="{{ route('eventos.update', $evento->id_evento) }}"
      method="POST"
      enctype="multipart/form-data"
      id="formEvento">

@csrf
@method('PUT')

{{-- ===================== --}}
{{-- CAMPOS OCULTOS --}}
{{-- ===================== --}}

<input type="hidden" name="pos_img" id="pos_img_input" value="{{ $evento->pos_img }}">
<input type="hidden" name="pos_titulo" id="pos_titulo_input" value="{{ $evento->pos_titulo }}">
<input type="hidden" name="pos_desc" id="pos_desc_input" value="{{ $evento->pos_desc }}">

{{-- Campo oculto para el borde combinado --}}
<input type="hidden" name="borde" id="borde_completo" value="{{ $evento->borde }}">

{{-- ===================== --}}
{{-- DATOS PRINCIPALES --}}
{{-- ===================== --}}

<input type="text"
name="titulo"
id="titulo"
class="form-control mb-2"
value="{{ $evento->titulo }}"
required>

<input type="datetime-local"
name="fecha_evento"
id="fecha"
class="form-control mb-2"
value="{{ date('Y-m-d\TH:i', strtotime($evento->fecha_evento)) }}"
required>

<textarea name="descripcion"
id="descripcion"
class="form-control mb-3">{{ $evento->descripcion }}</textarea>


{{-- ===================== --}}
{{-- IMAGEN --}}
{{-- ===================== --}}

@if($evento->poster)
<img src="{{ asset('storage/'.$evento->poster) }}"
style="width:200px;height:auto;"
class="mb-2 rounded">
@endif

<input type="file"
name="poster"
id="poster"
class="form-control mb-3"
accept="image/*">


{{-- ===================== --}}
{{-- COLORES --}}
{{-- ===================== --}}

<label>Color fondo</label>
<input type="color"
name="color_fondo"
id="color_fondo"
class="form-control mb-2"
value="{{ $evento->color_fondo }}">

<label>Color texto</label>
<input type="color"
name="color_texto"
id="color_texto"
class="form-control mb-2"
value="{{ $evento->color_texto }}">


{{-- ===================== --}}
{{-- BORDE --}}
{{-- ===================== --}}

<label>Color borde</label>
<input type="color"
name="color_borde"
id="color_borde"
class="form-control mb-2"
value="{{ $evento->borde ? explode(' ', $evento->borde)[2] ?? '#000000' : '#000000' }}">

<label>Grosor borde (px)</label>
<input type="number"
name="grosor_borde"
id="grosor_borde"
class="form-control mb-2"
value="{{ $evento->borde ? explode(' ', $evento->borde)[0] ?? 3 : 3 }}">

<label>Estilo borde</label>
<select name="estilo_borde" id="estilo_borde" class="form-control mb-2">
    <option value="solid" {{ $evento->borde && strpos($evento->borde, 'solid') !== false ? 'selected' : '' }}>Sólido</option>
    <option value="dashed" {{ $evento->borde && strpos($evento->borde, 'dashed') !== false ? 'selected' : '' }}>Discontinuo</option>
    <option value="dotted" {{ $evento->borde && strpos($evento->borde, 'dotted') !== false ? 'selected' : '' }}>Punteado</option>
    <option value="double" {{ $evento->borde && strpos($evento->borde, 'double') !== false ? 'selected' : '' }}>Doble</option>
</select>


{{-- ===================== --}}
{{-- TIPOGRAFÍA --}}
{{-- ===================== --}}

<label>Tipografía</label>

<select name="font_family"
id="font_family"
class="form-control mb-2">

<option value="Arial" {{ $evento->font_family=='Arial'?'selected':'' }}>Arial</option>
<option value="Verdana" {{ $evento->font_family=='Verdana'?'selected':'' }}>Verdana</option>
<option value="Georgia" {{ $evento->font_family=='Georgia'?'selected':'' }}>Georgia</option>
<option value="Times New Roman" {{ $evento->font_family=='Times New Roman'?'selected':'' }}>Times New Roman</option>
<option value="Montserrat" {{ $evento->font_family=='Montserrat'?'selected':'' }}>Montserrat</option>

</select>


{{-- ===================== --}}
{{-- TAMAÑOS --}}
{{-- ===================== --}}

<label>Tamaño título</label>
<input type="number"
name="size_titulo"
id="size_titulo"
class="form-control mb-2"
value="{{ $evento->size_titulo }}">

<label>Tamaño descripción</label>
<input type="number"
name="size_desc"
id="size_desc"
class="form-control mb-3"
value="{{ $evento->size_desc }}">


{{-- ===================== --}}
{{-- POSICIONES --}}
{{-- ===================== --}}

<label>Posición Imagen</label>
<select name="pos_img"
id="pos_img"
class="form-control mb-2">

<option value="top" {{ $evento->pos_img=='top'?'selected':'' }}>Arriba</option>
<option value="bottom" {{ $evento->pos_img=='bottom'?'selected':'' }}>Abajo</option>

</select>


<label>Posición Título</label>
<select name="pos_titulo"
id="pos_titulo"
class="form-control mb-2">

<option value="top" {{ $evento->pos_titulo=='top'?'selected':'' }}>Arriba</option>
<option value="center" {{ $evento->pos_titulo=='center'?'selected':'' }}>Centro</option>

</select>


<label>Posición Descripción</label>
<select name="pos_desc"
id="pos_desc"
class="form-control mb-3">

<option value="bottom" {{ $evento->pos_desc=='bottom'?'selected':'' }}>Abajo</option>
<option value="center" {{ $evento->pos_desc=='center'?'selected':'' }}>Centro</option>

</select>


{{-- ===================== --}}
{{-- BOTÓN --}}
{{-- ===================== --}}

<button class="btn btn-primary" type="submit">
Actualizar Evento
</button>

</form>

<hr>

<h4>Vista previa</h4>

<div id="preview"
class="p-4 mt-3 text-center shadow"
style="
border-radius:10px;
min-height:250px;
max-width:400px;
margin:auto;
transition: all 0.3s ease;
">
</div>

</div>

<script>

// Función para combinar el borde
function combinarBorde() {
    let grosor = document.getElementById("grosor_borde").value || 3;
    let estilo = document.getElementById("estilo_borde").value;
    let color = document.getElementById("color_borde").value;
    let bordeCompleto = `${grosor}px ${estilo} ${color}`;
    document.getElementById("borde_completo").value = bordeCompleto;
    return bordeCompleto;
}

// Función para actualizar la vista previa
function actualizarPreview() {

    // Obtener valores
    let titulo = document.getElementById("titulo").value;
    let descripcion = document.getElementById("descripcion").value;
    
    let colorFondo = document.getElementById("color_fondo").value;
    let colorTexto = document.getElementById("color_texto").value;
    
    let bordeCompleto = combinarBorde();
    
    let font = document.getElementById("font_family").value;
    
    let sizeTitulo = document.getElementById("size_titulo").value || 20;
    let sizeDesc = document.getElementById("size_desc").value || 14;
    
    let posImg = document.getElementById("pos_img").value;
    let posTitulo = document.getElementById("pos_titulo").value;
    let posDesc = document.getElementById("pos_desc").value;
    
    let preview = document.getElementById("preview");
    
    // Aplicar estilos a la preview
    preview.style.backgroundColor = colorFondo;
    preview.style.color = colorTexto;
    preview.style.fontFamily = font;
    preview.style.border = bordeCompleto;
    
    // Función para renderizar según posiciones
    function renderPreview(imgSrc) {
        
        let imgHTML = '';
        if (imgSrc && imgSrc !== '') {
            imgHTML = `<img src="${imgSrc}" style="width:100%; height:150px; object-fit:cover; border-radius:10px; margin:10px 0;">`;
        }
        
        let tituloHTML = `<h3 style="font-size:${sizeTitulo}px; margin:10px 0; text-align:${posTitulo === 'center' ? 'center' : 'left'};">${titulo || 'Título del evento'}</h3>`;
        
        let descHTML = `<p style="font-size:${sizeDesc}px; margin:10px 0; text-align:${posDesc === 'center' ? 'center' : 'left'};">${descripcion || 'Descripción del evento'}</p>`;
        
        // Construir según la posición de la imagen
        let contenido = '';
        
        if (posImg === 'top') {
            contenido = imgHTML + tituloHTML + descHTML;
        } else {
            // bottom
            contenido = tituloHTML + descHTML + imgHTML;
        }
        
        preview.innerHTML = contenido;
    }
    
    // Manejar la imagen
    let input = document.getElementById("poster");
    
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            renderPreview(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        let imgActual = "{{ $evento->poster ? asset('storage/'.$evento->poster) : '' }}";
        renderPreview(imgActual);
    }
}

// Función para extraer valores del borde existente
function extraerValoresBorde(borde) {
    if (!borde) return { grosor: 3, estilo: 'solid', color: '#000000' };
    
    let partes = borde.split(' ');
    let grosor = parseInt(partes[0]) || 3;
    let estilo = partes[1] || 'solid';
    let color = partes[2] || '#000000';
    
    return { grosor, estilo, color };
}

// Inicializar los campos de borde desde el borde guardado
function inicializarCamposBorde() {
    let bordeGuardado = "{{ $evento->borde }}";
    let valores = extraerValoresBorde(bordeGuardado);
    
    document.getElementById("grosor_borde").value = valores.grosor;
    document.getElementById("estilo_borde").value = valores.estilo;
    document.getElementById("color_borde").value = valores.color;
}

// ======================
// EVENTOS PARA ACTUALIZAR PREVIEW
// ======================

document.getElementById("titulo").addEventListener("input", actualizarPreview);
document.getElementById("descripcion").addEventListener("input", actualizarPreview);
document.getElementById("color_fondo").addEventListener("input", actualizarPreview);
document.getElementById("color_texto").addEventListener("input", actualizarPreview);
document.getElementById("color_borde").addEventListener("input", actualizarPreview);
document.getElementById("grosor_borde").addEventListener("input", actualizarPreview);
document.getElementById("estilo_borde").addEventListener("change", actualizarPreview);
document.getElementById("font_family").addEventListener("change", actualizarPreview);
document.getElementById("size_titulo").addEventListener("input", actualizarPreview);
document.getElementById("size_desc").addEventListener("input", actualizarPreview);
document.getElementById("pos_img").addEventListener("change", actualizarPreview);
document.getElementById("pos_titulo").addEventListener("change", actualizarPreview);
document.getElementById("pos_desc").addEventListener("change", actualizarPreview);
document.getElementById("poster").addEventListener("change", actualizarPreview);

// Actualizar el campo oculto antes de enviar el formulario
document.getElementById("formEvento").addEventListener("submit", function() {
    combinarBorde();
});

// Inicializar
window.onload = function() {
    inicializarCamposBorde();
    actualizarPreview();
};

</script>

@endsection