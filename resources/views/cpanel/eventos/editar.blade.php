<div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-labelledby="modalEditarEventoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditarEvento">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalEditarEventoLabel" class="modal-title">Editar evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_id_evento" class="form-label">ID</label>
                        <input type="number" id="edit_id_evento" name="id_evento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_titulo" class="form-label">Título</label>
                        <input type="text" id="edit_titulo" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fecha_evento" class="form-label">Fecha del evento</label>
                        <input type="date" id="edit_fecha_evento" name="fecha_evento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea id="edit_descripcion" name="descripcion" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

