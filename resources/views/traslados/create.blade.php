@extends('layouts.dashboard')

@section('page-title', 'Crear Traslado')

@section('dashboard-content')
    <div class="content-card">
        <form action="{{ route('traslados.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <h5>Información General</h5>

                    <div class="form-group mb-3">
                        <label>Unidad Responsable <span class="text-danger">*</span></label>
                        <select name="unidad_id" id="unidadSelect" class="form-control" required>
                            <option value="">Seleccione una unidad</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->dependency_unit_id }}">
                                    {{ $unit->short_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Subunidad Responsable <span class="text-danger">*</span></label>
                        <select name="subunidad_id" id="subunidadSelect" class="form-control" required>
                            <option value="">Seleccione una subunidad</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Funcionario Responsable <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Seleccione un funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="mt-4">Inicio del Traslado</h5>
                    {{-- Centros y Sedes Iniciales --}}
                    <x-centros-sedes-selector :centros="$centros" prefix="inicial" />

                    <h5 class="mt-4">Destino del Traslado</h5>
                    <x-centros-sedes-selector :centros="$centros" prefix="final" />

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Fecha de Finalización <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" rows="6" placeholder="Detalles de la necesidad..."></textarea>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <h5>Características de la Necesidad</h5>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Riesgo</label>
                            <select name="nivel_riesgo" class="form-control">
                                <option value="1">🟢 Bajo</option>
                                <option value="2">🟡 Medio</option>
                                <option value="3">🔴 Alto</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Complejidad</label>
                            <select name="nivel_complejidad" class="form-control">
                                <option value="1">Bajo</option>
                                <option value="2">Medio</option>
                                <option value="3">Alto</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Presupuesto Solicitado</label>
                        <input type="number" name="presupuesto_solicitado" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Presupuesto Aceptado</label>
                        <input type="number" name="presupuesto_aceptado" class="form-control" disabled>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="chk-personas" name="requiere_personal"
                            value="1">
                        <label class="form-check-label" for="chk-personas">Requiere trasladar personas</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="chk-materiales" name="requiere_materiales"
                            value="1">
                        <label class="form-check-label" for="chk-materiales">Requiere trasladar materiales</label>
                    </div>

                    <div id="personas-section" style="display:none;">
                        <h6>Personal Necesitado</h6>
                        <button type="button" class="btn-modern btn-save mb-2" onclick="agregarPersona()">Agregar
                            Persona</button>
                        <div class="table-responsive">
                            <table class="table table-hover" id="personasTable">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Documento</th>
                                        <th>Cargo</th>
                                        <th width="80">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="materiales-section" style="display:none;">
                        <h6>Materiales Necesitados</h6>
                        <button type="button" class="btn-modern btn-save mb-2" onclick="abrirModalMateriales()">Agregar
                            Material</button>
                        <div class="table-responsive">
                            <table class="table table-hover" id="materialesTable">
                                <thead>
                                    <tr>
                                        <th>Nombre Material</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th width="80">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('traslados.index') }}" class="btn-modern btn-cancel">Cancelar</a>
                <button type="submit" class="btn-modern btn-save">Guardar Traslado</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h5,
        h6 {
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.1);
            outline: none;
        }

        .btn-modern {
            min-width: 140px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-save {
            background: #4cd137;
            color: #fff;
        }

        .btn-save:hover {
            background: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(76, 209, 55, 0.4);
        }

        .btn-cancel {
            background-color: #fff;
            border: 1px solid #43a047;
            color: #43a047;
        }

        .btn-cancel:hover {
            background: #43a047;
            color: #fff;
            transform: translateY(-2px);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let personaIndex = 0;
        let materialIndex = 0;
        let materialesSeleccionados = [];

        function toggleSections() {
            document.getElementById('personas-section').style.display = document.getElementById('chk-personas').checked ?
                'block' : 'none';
            document.getElementById('materiales-section').style.display = document.getElementById('chk-materiales')
                .checked ? 'block' : 'none';
        }
        document.getElementById('chk-personas').addEventListener('change', toggleSections);
        document.getElementById('chk-materiales').addEventListener('change', toggleSections);
        toggleSections();

        function agregarPersona() {
            const tbody = document.querySelector('#personasTable tbody');
            const row = document.createElement('tr');
            row.innerHTML =
                `
        <td><input type="text" name="personas[${personaIndex}][nombre]" class="form-control" placeholder="Nombre completo" required></td>
        <td><input type="text" name="personas[${personaIndex}][documento]" class="form-control" placeholder="Documento" required></td>
        <td><input type="text" name="personas[${personaIndex}][cargo]" class="form-control" placeholder="Cargo"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button></td>`;
            tbody.appendChild(row);
            personaIndex++;
        }

        // Materiales
        function abrirModalMateriales() {
            const modal = new bootstrap.Modal(document.getElementById('modalMateriales'));
            modal.show();
            cargarMateriales();
        }

        function cargarMateriales(page = 1, search = '') {
            const tbody = document.getElementById('materialesModalBody');
            tbody.innerHTML =
                `<tr><td colspan="5" class="text-center"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>`;

            fetch(`{{ route('traslados.buscar-materiales') }}?page=${page}&search=${encodeURIComponent(search)}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><p>No se encontraron materiales</p></td></tr>`;
                        return;
                    }
                    data.data.forEach(mat => {
                        const ya = materialesSeleccionados.includes(mat.id);
                        const row = document.createElement('tr');
                        row.className = ya ? 'table-success' : 'material-row';
                        row.innerHTML = `
                <td><strong>${mat.material_name}</strong></td>
                <td><span class="badge bg-info">${mat.material_type || 'N/A'}</span></td>
                <td><span class="badge badge-stock bg-primary">${mat.material_quantity} unidades</span></td>
                <td><small class="text-muted">${mat.inventory?.sede?.nom_sede || 'N/A'}</small></td>
                <td>
                    ${ya 
                        ? '<span class="badge bg-success">Seleccionado</span>'
                        : `<button type="button" class="btn btn-success btn-sm" onclick="seleccionarMaterial(event, ${mat.id}, '${mat.material_name.replace(/'/g,"\\'")}', '${mat.material_type || 'N/A'}')">
                                                        <i class="fas fa-check"></i> Seleccionar
                                                    </button>`}
                </td>`;
                        tbody.appendChild(row);
                    });
                    renderizarPaginacion(data);
                }).catch(err => {
                    tbody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error al cargar materiales</td></tr>`;
                });
        }

        function seleccionarMaterial(event, id, nombre, tipo) {
            event.stopPropagation();
            if (materialesSeleccionados.includes(id)) {
                alert('Este material ya ha sido agregado');
                return;
            }

            const tbody = document.querySelector('#materialesTable tbody');
            const row = document.createElement('tr');
            row.setAttribute('data-material-id', id);
            row.innerHTML =
                `
        <td><strong>${nombre}</strong><input type="hidden" name="materiales[${materialIndex}][inventory_material_id]" value="${id}"></td>
        <td><span class="badge bg-info">${tipo}</span></td>
        <td><input type="number" name="materiales[${materialIndex}][cantidad]" class="form-control form-control-sm" placeholder="0" min="1" required style="width:100px;"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarMaterial(this, ${id})"><i class="fas fa-trash"></i></button></td>`;
            tbody.appendChild(row);
            materialIndex++;
            materialesSeleccionados.push(id);
            bootstrap.Modal.getInstance(document.getElementById('modalMateriales')).hide();
            mostrarNotificacion('Material agregado correctamente', 'success');
        }

        function eliminarMaterial(button, materialId) {
            materialesSeleccionados = materialesSeleccionados.filter(x => x !== materialId);
            setTimeout(() => button.closest('tr').remove(), 50);
        }

        document.getElementById('searchMaterial').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => cargarMateriales(1, e.target.value), 500);
        });

        function renderizarPaginacion(data) {
            const container = document.getElementById('paginationContainer');
            container.innerHTML = '';
            if (data.last_page <= 1) return;
            const ul = document.createElement('ul');
            ul.className = 'pagination pagination-sm mb-0';

            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${data.current_page===1?'disabled':''}`;
            prevLi.innerHTML =
                `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${data.current_page-1}, document.getElementById('searchMaterial').value)">Anterior</a>`;
            ul.appendChild(prevLi);

            for (let i = 1; i <= data.last_page; i++) {
                if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i===data.current_page?'active':''}`;
                    li.innerHTML =
                        `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${i}, document.getElementById('searchMaterial').value)">${i}</a>`;
                    ul.appendChild(li);
                } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                    const li = document.createElement('li');
                    li.className = 'page-item disabled';
                    li.innerHTML = '<span class="page-link">...</span>';
                    ul.appendChild(li);
                }
            }

            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${data.current_page===data.last_page?'disabled':''}`;
            nextLi.innerHTML =
                `<a class="page-link" href="#" onclick="event.preventDefault(); cargarMateriales(${data.current_page+1}, document.getElementById('searchMaterial').value)">Siguiente</a>`;
            ul.appendChild(nextLi);

            container.appendChild(ul);
        }

        function eliminarFila(button) {
            button.closest('tr').remove();
        }

        function mostrarNotificacion(msg, tipo = 'success') {
            const div = document.createElement('div');
            div.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            div.style.cssText = 'top:20px; right:20px; z-index:9999; min-width:300px;';
            div.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 3000);
        }
    </script>
    <script>
        const unidades = @json($units);

        document.getElementById('unidadSelect').addEventListener('change', function() {
            const unidadId = parseInt(this.value);
            const subSelect = document.getElementById('subunidadSelect');

            subSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';

            if (!unidadId) return;

            // 🔴 AQUÍ estaba el error
            const unidad = unidades.find(
                u => u.dependency_unit_id === unidadId
            );

            if (unidad && unidad.subunits.length) {
                unidad.subunits.forEach(sub => {
                    const option = document.createElement('option');

                    // 🔴 Y AQUÍ
                    option.value = sub.subunit_id;
                    option.textContent = sub.name;

                    subSelect.appendChild(option);
                });
            }
        });
    </script>
@endpush
