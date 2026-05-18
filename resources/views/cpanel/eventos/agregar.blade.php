<div class="modal fade" id="modalAgregarEvento" tabindex="-1" aria-labelledby="modalAgregarEventoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('eventos.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalAgregarEventoLabel" class="modal-title">Agregar evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_evento" class="form-label">Fecha del evento</label>
                        <input type="datetime-local" name="fecha_evento" id="edit_fecha_evento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

