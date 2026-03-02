<div class="modal fade" id="createDependencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('dependencies.store') }}" class="modal-content">
            @csrf

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-building"></i>
                    Crear Dependencia
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre corto</label>
                    <input type="text" name="short_name" class="form-control"
                        placeholder="Ej: TIC" required>
                </div>

                <div class="mb-3">
                    <label>Nombre completo</label>
                    <input type="text" name="full_name" class="form-control"
                        placeholder="Tecnologías de la Información y Comunicaciones" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Guardar
                </button>
            </div>
        </form>
    </div>
</div>