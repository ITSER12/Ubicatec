<div class="modal fade" id="modalEliminarEvento" tabindex="-1" aria-labelledby="modalEliminarEventoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEliminarEvento">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalEliminarEventoLabel" class="modal-title">Eliminar evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Seguro que deseas eliminar este evento?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

