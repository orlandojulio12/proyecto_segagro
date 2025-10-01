@extends('layouts.dashboard')

@section('page-title', 'Editar Necesidad de Infraestructura')

@section('dashboard-content')
    <div class="content-card">
        <form action="{{ route('infraestructura.update', $infraestructura) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <h5>Información General</h5>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia Responsable <span
                                class="text-danger">*</span></label>
                        <select name="dependencia_id" class="form-control" required>
                            @foreach ($dependencias ?? [] as $dep)
                                <option value="{{ $dep->id }}"
                                    {{ $infraestructura->dependencia_id == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Funcionario Responsable <span
                                class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}"
                                    {{ $infraestructura->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Centro de Formación <span
                                class="text-danger">*</span></label>
                        <select name="centro_id" class="form-control" required>
                            @foreach ($centros ?? [] as $centro)
                                <option value="{{ $centro->id }}"
                                    {{ $infraestructura->centro_id == $centro->id ? 'selected' : '' }}>
                                    {{ $centro->nom_centro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de Formación <span
                                class="text-danger">*</span></label>
                        <select name="sede_id" class="form-control" required>
                            @foreach ($sedes ?? [] as $sede)
                                <option value="{{ $sede->id }}"
                                    {{ $infraestructura->sede_id == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nom_sede }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Ambiente de la Necesidad</label>
                        <input type="text" name="ambiente" class="form-control"
                            value="{{ old('ambiente', $infraestructura->ambiente) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha Inicial <span
                                class="text-danger">*</span></label>
                        <input type="date" name="fecha_inicio" class="form-control"
                            value="{{ old('fecha_inicio', $infraestructura->fecha_inicio) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha Final <span
                                class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin" class="form-control"
                            value="{{ old('fecha_fin', $infraestructura->fecha_fin) }}" required>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <h5>Características de la Necesidad</h5>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Nivel de Riesgo <span
                                class="text-danger">*</span></label>
                        <select name="nivel_riesgo" class="form-control" required>
                            <option value="1" {{ $infraestructura->nivel_riesgo == 1 ? 'selected' : '' }}>🟢 Bajo
                            </option>
                            <option value="2" {{ $infraestructura->nivel_riesgo == 2 ? 'selected' : '' }}>🟡 Medio
                            </option>
                            <option value="3" {{ $infraestructura->nivel_riesgo == 3 ? 'selected' : '' }}>🔴 Alto
                            </option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Nivel de Prioridad <span
                                class="text-danger">*</span></label>
                        <select name="nivel_prioridad" class="form-control" required>
                            <option value="1" {{ $infraestructura->nivel_prioridad == 1 ? 'selected' : '' }}>Baja
                            </option>
                            <option value="2" {{ $infraestructura->nivel_prioridad == 2 ? 'selected' : '' }}>Media
                            </option>
                            <option value="3" {{ $infraestructura->nivel_prioridad == 3 ? 'selected' : '' }}>Alta
                            </option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Tipo de Necesidad <span
                                class="text-danger">*</span></label>
                        <select name="tipo_necesidad" class="form-control" required>
                            <option value="Eléctrica"
                                {{ $infraestructura->tipo_necesidad == 'Eléctrica' ? 'selected' : '' }}>Eléctrica</option>
                            <option value="Hidráulica"
                                {{ $infraestructura->tipo_necesidad == 'Hidráulica' ? 'selected' : '' }}>Hidráulica
                            </option>
                            <option value="Refrigeración"
                                {{ $infraestructura->tipo_necesidad == 'Refrigeración' ? 'selected' : '' }}>Refrigeración
                            </option>
                            <option value="Civil" {{ $infraestructura->tipo_necesidad == 'Civil' ? 'selected' : '' }}>
                                Infraestructura Civil</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Motivo de la Necesidad</label>
                        <input type="text" name="motivo_necesidad" class="form-control"
                            value="{{ old('motivo_necesidad', $infraestructura->motivo_necesidad) }}">
                    </div>

                    {{-- Switch y sede de traslado en la misma fila --}}
                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input id="requiereTraslado" class="form-check-input" type="checkbox"
                                    name="requiere_traslado" {{ $infraestructura->requiere_traslado ? 'checked' : '' }}>
                                <label class="form-check-label" for="requiereTraslado">¿Requiere traslado?</label>
                            </div>
                        </div>
                        <div class="col-md-8" id="sedeSecundariaDiv"
                            style="{{ $infraestructura->requiere_traslado ? '' : 'display:none;' }}">
                            <label class="form-label text-success fw-semibold">Sede de Formación (Traslado)</label>
                            <select name="sede_traslado" class="form-control">
                                <option value="">Seleccione una sede</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id }}"
                                        {{ $infraestructura->sede_traslado == $sede->id ? 'selected' : '' }}>
                                        {{ $sede->nom_sede   }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <h5>Características Económicas</h5>
                        <div class="form-group mb-3">
                            <label class="form-label text-success fw-semibold">Fuente de Financiación <span
                                    class="text-danger">*</span></label>
                            <select name="fuente_financiacion" class="form-control" required>
                                <option value="Ferretería"
                                    {{ $infraestructura->fuente_financiacion == 'Ferretería' ? 'selected' : '' }}>
                                    Ferretería
                                </option>
                                <option value="Proyecto"
                                    {{ $infraestructura->fuente_financiacion == 'Proyecto' ? 'selected' : '' }}>
                                    Proyecto
                                </option>
                                <option value="Solicitud Económica"
                                    {{ $infraestructura->fuente_financiacion == 'Solicitud Económica' ? 'selected' : '' }}>
                                    Solicitud Económica
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label text-success fw-semibold">Presupuesto Solicitado</label>
                            <input type="number" name="presupuesto_solicitado" class="form-control"
                                value="{{ old('presupuesto_solicitado', $infraestructura->presupuesto_solicitado) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label text-success fw-semibold">Presupuesto Aceptado</label>
                            <input type="number" name="presupuesto_aceptado" class="form-control"
                                value="{{ old('presupuesto_aceptado', $infraestructura->presupuesto_aceptado) }}"
                                readonly>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Detalles -->
                <div class="row">
                    <div class="col-12">
                        <h5>Detalles de la Necesidad</h5>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label text-success fw-semibold">Evidencia Fotográfica</label>

                        {{-- Input para nueva imagen --}}
                        <input type="file" name="imagen" id="imagenInput" class="form-control" accept="image/*">

                        <small class="text-muted">Adjunte una nueva imagen si desea reemplazar la existente</small>

                        {{-- Preview dinámico --}}
                        <div id="imagenPreview" class="mt-3">
                            @if ($infraestructura->imagen)
                                <img src="{{ asset('storage/' . $infraestructura->imagen) }}" alt="Imagen actual"
                                    style="max-width: 100%; max-height: 250px; border-radius: 8px;">
                            @endif
                        </div>
                    </div>


                    <div class="col-md-6 form-group mb-3">
                        <label>Descripción Detallada <span class="text-danger">*</span></label>
                        <textarea name="descripcion" rows="6" class="form-control" required>{{ old('descripcion', $infraestructura->descripcion) }}</textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('infraestructura.index') }}" class="btn-modern btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-modern btn-save">Actualizar Necesidad</button>
                </div>
        </form>
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

        /* Tabla moderna */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 8px;
            width: 100%;
        }

        .table-modern thead {
            background: #4cd137;
            color: #fff;
            border-radius: 8px;
        }

        .table-modern thead th {
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .table-modern tbody tr {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table-modern tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-modern td {
            padding: 10px 12px;
            vertical-align: middle;
            text-align: center;
        }

        .table-modern input {
            text-align: center;
        }

        /* Botones de acciones */
        .btn-outline-danger {
            border-radius: 6px;
            padding: 6px 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #e63946;
            color: #fff;
            transform: scale(1.05);
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

        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(6px);
            background-color: rgba(0, 0, 0, 0.45);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .custom-modal-content {
            background: #fff;
            border-radius: 12px;
            width: 95%;
            max-width: 900px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
            animation: zoomIn 0.3s ease;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .custom-modal-body {
            padding: 20px;
            max-height: 65vh;
            overflow-y: auto;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .search-box input:focus {
            border-color: #43a047;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.1);
        }

        .search-box .search-icon {
            position: absolute;
            right: 14px;
            font-size: 16px;
            color: #888;
            cursor: pointer;
            transition: color 0.3s;
        }

        .search-box input:focus+.search-icon {
            color: #43a047;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .table thead {
            background: #f1f3f5;
            font-weight: bold;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background: #f9fafb;
            transition: background 0.2s ease;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-success {
            background: #4caf50;
            color: white;
        }

        .btn-success:hover {
            background: #43a047;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .close-btn:hover {
            color: #000;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            gap: 5px;
        }

        .pagination button {
            background: #f1f3f5;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .pagination button.active {
            background: #007bff;
            color: white;
            font-weight: bold;
        }

        .pagination button:hover {
            background: #e0e0e0;
        }

        #imagenPreview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPageCentros = 1;
        let currentPageSedes = 1;
        const rowsPerPage = 5;

        function renderTableCentros() {
            const rows = document.querySelectorAll('#listaCentros tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display =
                    index >= (currentPageCentros - 1) * rowsPerPage && index < currentPageCentros * rowsPerPage ?
                    '' : 'none';
            });

            const pagination = document.getElementById('paginationCentros');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.classList.toggle('active', i === currentPageCentros);
                btn.onclick = () => {
                    currentPageCentros = i;
                    renderTableCentros();
                };
                pagination.appendChild(btn);
            }
        }

        function renderTableSedes() {
            const rows = document.querySelectorAll('#listaSedes tr');
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            rows.forEach((row, index) => {
                row.style.display =
                    index >= (currentPageSedes - 1) * rowsPerPage && index < currentPageSedes * rowsPerPage ? '' :
                    'none';
            });

            const pagination = document.getElementById('paginationSedes');
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.classList.toggle('active', i === currentPageSedes);
                btn.onclick = () => {
                    currentPageSedes = i;
                    renderTableSedes();
                };
                pagination.appendChild(btn);
            }
        }

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
            if (id === 'centroModal') renderTableCentros();
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function openModalSede() {
            const centroId = document.getElementById('centro_id').value;
            if (!centroId) {
                alert('Primero debe seleccionar un centro');
                return;
            }
            document.getElementById('sedeModal').style.display = 'flex';
            renderTableSedes();
        }

        document.getElementById('filtroCentro').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            document.querySelectorAll('#listaCentros tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
            });
        });

        document.getElementById('filtroSede').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            document.querySelectorAll('#listaSedes tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filtro) ? '' : 'none';
            });
        });

        document.querySelectorAll('.seleccionar-centro').forEach(boton => {
            boton.addEventListener('click', function() {
                const centroId = this.dataset.id;
                const centroNombre = this.dataset.nombre;

                document.getElementById('centro_id').value = centroId;
                document.getElementById('centroSeleccionado').value = centroNombre;

                document.getElementById('sede_id').value = '';
                document.getElementById('sedeSeleccionada').value = '';
                document.getElementById('sedeSeleccionada').placeholder = 'Cargando sedes...';
                document.getElementById('sedeSeleccionada').disabled = true;

                closeModal('centroModal');

                fetch(`/centros/${centroId}/sedes`)
                    .then(response => response.json())
                    .then(sedes => {
                        const listaSedes = document.getElementById('listaSedes');
                        listaSedes.innerHTML = '';

                        sedes.forEach(sede => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                        <td>${sede.nom_sede}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success seleccionar-sede"
                                data-id="${sede.id}" data-nombre="${sede.nom_sede}">
                                Seleccionar
                            </button>
                        </td>
                    `;
                            listaSedes.appendChild(tr);
                        });

                        document.querySelectorAll('.seleccionar-sede').forEach(btn => {
                            btn.addEventListener('click', function() {
                                document.getElementById('sede_id').value = this.dataset
                                    .id;
                                document.getElementById('sedeSeleccionada').value = this
                                    .dataset.nombre;
                                closeModal('sedeModal');
                            });
                        });

                        document.getElementById('sedeSeleccionada').placeholder =
                            'Seleccione una sede...';
                        document.getElementById('sedeSeleccionada').disabled = false;
                        currentPageSedes = 1;
                        renderTableSedes();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('sedeSeleccionada').placeholder =
                            'Error al cargar sedes';
                    });
            });
        });
        document.getElementById('requiereTraslado').addEventListener('change', function() {
            document.getElementById('sedeSecundariaDiv').style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('imagenInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagenPreview');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        document.getElementById('imagenInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagenPreview');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" 
                                  style="max-width:100%; max-height:250px; border-radius:8px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
@endpush
