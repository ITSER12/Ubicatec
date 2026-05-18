<form method="POST" action="{{ route('eventos.eliminarmultiple') }}" onsubmit="return confirmarEliminacion()">
    @csrf
    <!-- ...checkboxes y contenido... -->
    <button type="submit" class="btn btn-danger">Eliminar seleccionados</button>
</form>
