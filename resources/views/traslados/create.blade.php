{{-- resources/views/traslados/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Traslado')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Creación de Necesidad de Traslado</h2>
            <p class="text-muted">Completa la información para registrar una nueva necesidad</p>
        </div>
        <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('traslados.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Información General -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                    <p class="section-subtitle">Datos básicos para identificar la necesidad</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia responsable *</label>
                        <select name="dependencia_id" class="form-select modern-input" required>
                            <option value="">Seleccionar dependencia</option>
                            @foreach ($dependencias as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->responsible_department ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Funcionario *</label>
                        <select name="user_id" class="form-select modern-input" required>
                            <option value="">Seleccionar funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- COMPONENTE CENTRO Y SEDE INICIALES --}}
                    <x-centros-sedes-selector :centros="$centros" prefix="inicial" />

                    <br>
                    <h5 class="section-title"><i class="fas fa-calendar-alt"></i> Información de calendario</h5>
                    <p class="section-subtitle">Fechas de inicio y finalización de la necesidad</p>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-success fw-semibold">Fecha de inicio *</label>
                            <input type="date" name="fecha_inicio" class="form-control modern-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-success fw-semibold">Fecha de finalización *</label>
                            <input type="date" name="fecha_fin" class="form-control modern-input" required>
                        </div>
                    </div>
                    <br><br>
                    <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Detalles de la necesidad</h5>
                    <p class="section-subtitle">Descripción general de lo que se requiere trasladar</p>

                    <textarea name="descripcion" class="form-control modern-input" rows="4" placeholder="Escribe los detalles..."></textarea>

                </div>
            </div>

            <!-- Centros Finales -->
            <div class="col-md-6">
                <div class="content-card mb-4">

                    <h5 class="section-title"><i class="fas fa-exclamation-triangle"></i> Características adicionales</h5>
                    <p class="section-subtitle">Detalles técnicos y nivel de complejidad</p>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-success fw-semibold">Nivel de riesgo</label>
                            <select name="nivel_riesgo" class="form-control">
                                <option value="1">Bajo</option>
                                <option value="2">Medio</option>
                                <option value="3">Alto</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-success fw-semibold">Nivel de complejidad</label>
                            <select name="nivel_complejidad" class="form-control">
                                <option value="1">Bajo</option>
                                <option value="2">Medio</option>
                                <option value="3">Alto</option>
                            </select>
                        </div>

                        <br><br><br><br>
                        <h5 class="section-title"><i class="fas fa-dollar-sign"></i> Característica económica</h5>
                        <p class="section-subtitle">Información presupuestal</p>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-success fw-semibold">Presupuesto solicitado</label>
                                <input type="number" name="presupuesto_solicitado" class="form-control modern-input">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-success fw-semibold">Presupuesto aceptado</label>
                                <input type="number" name="presupuesto_aceptado" class="form-control modern-input"
                                    disabled>
                            </div>
                        </div>

                        <br><br><br><br>
                        <h5 class="section-title"><i class="fas fa-users"></i> Información personal y materiales</h5>
                        <p class="section-subtitle">Seleccione si requiere trasladar personas o materiales</p>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="chk-personas" name="requiere_personal" value="1"
                                        {{ old('requiere_personal') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="chk-personas">
                                        Requiere trasladar personas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="chk-materiales" name="requiere_materiales" value="1"
                                        {{ old('requiere_materiales') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="chk-materiales">
                                        Requiere trasladar materiales
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Sección personas --}}
                        <div id="personas-section" style="display: none;">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user-friends"></i> Personal necesitado</h6>
                                    <small class="text-muted">Personal necesario para el traslado</small>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="agregarPersona()">
                                        <i class="fas fa-plus"></i> Agregar Persona
                                    </button>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="personasTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Documento</th>
                                                    <th>Cargo</th>
                                                    <th width="100">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Filas dinámicas --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sección materiales --}}
                        <div id="materiales-section" style="display: none;">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-box"></i> Material necesitado</h6>
                                    <small class="text-muted">Materiales necesarios para el traslado</small>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="abrirModalMateriales()">
                                        <i class="fas fa-plus"></i> Agregar Material
                                    </button>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="materialesTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre Material</th>
                                                    <th>Tipo</th>
                                                    <th>Cantidad</th>
                                                    <th width="100">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Filas dinámicas --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>

    {{-- Modal de Selección de Materiales --}}
    <div class="modal fade" id="modalMateriales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-box-open me-2"></i>Seleccionar Material
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Buscador --}}
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-success"></i>
                        </span>
                        <input type="text" id="searchMaterial" class="form-control" 
                            placeholder="Buscar por nombre o tipo de material...">
                    </div>

                    {{-- Tabla de materiales --}}
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Material</th>
                                    <th>Tipo</th>
                                    <th>Stock</th>
                                    <th>Sede</th>
                                    <th width="100">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="materialesModalBody">
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div id="paginationContainer" class="d-flex justify-content-center mt-3">
                        {{-- Se llenará con JavaScript --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2f9e44;
            margin-bottom: 8px;
        }

        .section-subtitle {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .modern-input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .modern-input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.25);
        }

        .btn-lg {
            padding: 10px 22px;
            font-size: 15px;
            border-radius: 8px;
        }

        .form-check-input:checked {
            background-color: #4cd137;
            border-color: #4cd137;
        }

        .card-header {
            border-bottom: 2px solid #e9ecef;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table thead th {
            font-weight: 600;
            font-size: 14px;
            color: #495057;
        }

        .table td input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .table td input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.15);
        }

        .btn-danger {
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            transform: scale(1.05);
        }

        /* Modal */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-radius: 12px 12px 0 0;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #f8f9fa;
        }

        .badge-stock {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .material-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .material-row:hover {
            background-color: #e8f5e9 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let personaIndex = 0;
        let materialIndex = 0;
        let currentPage = 1;
        let searchTimeout = null;
        let materialesSeleccionados = []; // Para evitar duplicados

        // Toggle de secciones
        function toggleSections() {
            const personasChecked = document.getElementById('chk-personas').checked;
            const materialesChecked = document.getElementById('chk-materiales').checked;
            
            document.getElementById('personas-section').style.display = personasChecked ? 'block' : 'none';
            document.getElementById('materiales-section').style.display = materialesChecked ? 'block' : 'none';
        }

        document.getElementById('chk-personas').addEventListener('change', toggleSections);
        document.getElementById('chk-materiales').addEventListener('change', toggleSections);

        // Al cargar la página
        toggleSections();

        // ============= SECCIÓN DE PERSONAS =============
        function agregarPersona() {
            const tbody = document.querySelector('#personasTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="personas[${personaIndex}][nombre]" class="form-control" placeholder="Nombre completo" required></td>
                <td><input type="text" name="personas[${personaIndex}][documento]" class="form-control" placeholder="Cédula" required></td>
                <td><input type="text" name="personas[${personaIndex}][cargo]" class="form-control" placeholder="Cargo"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            personaIndex++;
        }

        // ============= SECCIÓN DE MATERIALES =============
        function abrirModalMateriales() {
            const modal = new bootstrap.Modal(document.getElementById('modalMateriales'));
            modal.show();
            cargarMateriales();
        }

        // Cargar materiales con búsqueda y paginación
        function cargarMateriales(page = 1, search = '') {
            const tbody = document.getElementById('materialesModalBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </td>
                </tr>
            `;

            fetch(`{{ route('traslados.buscar-materiales') }}?page=${page}&search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = '';
                    
                    if (data.data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No se encontraron materiales</p>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    data.data.forEach(material => {
                        // Verificar si ya está seleccionado
                        const yaSeleccionado = materialesSeleccionados.includes(material.id);
                        
                        const row = document.createElement('tr');
                        row.className = yaSeleccionado ? 'table-success' : 'material-row';
                        row.innerHTML = `
                            <td><strong>${material.material_name}</strong></td>
                            <td><span class="badge bg-info">${material.material_type || 'N/A'}</span></td>
                            <td><span class="badge badge-stock bg-primary">${material.material_quantity} unidades</span></td>
                            <td><small class="text-muted">${material.inventory?.sede?.nom_sede || 'N/A'}</small></td>
                            <td>
                                ${yaSeleccionado 
                                    ? '<span class="badge bg-success">Seleccionado</span>'
                                    : `<button type="button" class="btn btn-success btn-sm" onclick="seleccionarMaterial(${material.id}, '${material.material_name.replace(/'/g, "\\'")}', '${material.material_type || 'N/A'}')">
                                        <i class="fas fa-check"></i> Seleccionar
                                    </button>`
                                }
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Renderizar paginación
                    renderizarPaginacion(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Error al cargar materiales
                            </td>
                        </tr>
                    `;
                });
        }

        // Seleccionar material del modal
        function seleccionarMaterial(id, nombre, tipo) {
            // Verificar si ya está agregado
            if (materialesSeleccionados.includes(id)) {
                alert('Este material ya ha sido agregado');
                return;
            }

            const tbody = document.querySelector('#materialesTable tbody');
            const row = document.createElement('tr');
            row.setAttribute('data-material-id', id);
            row.innerHTML = `
                <td>
                    <strong>${nombre}</strong>
                    <input type="hidden" name="materiales[${materialIndex}][inventory_material_id]" value="${id}">
                </td>
                <td><span class="badge bg-info">${tipo}</span></td>
                <td>
                    <input type="number" name="materiales[${materialIndex}][cantidad]" 
                        class="form-control form-control-sm" placeholder="0" min="1" required style="width: 100px;">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMaterial(this, ${id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            materialIndex++;
            
            // Agregar a la lista de seleccionados
            materialesSeleccionados.push(id);

            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('modalMateriales')).hide();

            // Mostrar mensaje de éxito
            mostrarNotificacion('Material agregado correctamente', 'success');
        }

        // Eliminar material (actualizado para manejar la lista de seleccionados)
        function eliminarMaterial(button, materialId) {
            button.closest('tr').remove();
            
            // Remover de la lista de seleccionados
            materialesSeleccionados = materialesSeleccionados.filter(id => id !== materialId);
        }

        // Búsqueda con debounce
        document.getElementById('searchMaterial').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                cargarMateriales(1, e.target.value);
            }, 500);
        });

        // Renderizar paginación
        function renderizarPaginacion(data) {
            const container = document.getElementById('paginationContainer');
            container.innerHTML = '';

            if (data.last_page <= 1) return;

            const ul = document.createElement('ul');
            ul.className = 'pagination pagination-sm mb-0';

            // Botón anterior
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${data.current_page - 1}, document.getElementById('searchMaterial').value)">Anterior</a>`;
            ul.appendChild(prevLi);

            // Números de página
            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${i}, document.getElementById('searchMaterial').value)">${i}</a>`;
                    ul.appendChild(li);
                } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                    const li = document.createElement('li');
                    li.className = 'page-item disabled';
                    li.innerHTML = '<span class="page-link">...</span>';
                    ul.appendChild(li);
                }
            }

            // Botón siguiente
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${data.current_page + 1}, document.getElementById('searchMaterial').value)">Siguiente</a>`;
            ul.appendChild(nextLi);

            container.appendChild(ul);
        }

        // Eliminar fila genérica
        function eliminarFila(button) {
            button.closest('tr').remove();
        }

        // Notificación simple
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    </script>
@endpush