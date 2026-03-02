<div class="modal fade" id="createSubunitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="subunitForm" class="modal-content">
            @csrf

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-code-branch"></i>
                    Crear Subdependencia
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label>Código</label>
                    <input type="text" name="subunit_code" class="form-control"
                        placeholder="Ej: TIC-01" required>
                </div>

                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control"
                        placeholder="Soporte Técnico" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-info text-white">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </form>
    </div>
</div>