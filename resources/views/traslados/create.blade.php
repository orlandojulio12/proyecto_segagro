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
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
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

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Centro de formación inicial *</label>
                        <select name="centro_inicial_id" class="form-select modern-input" required>
                            <option value="">Seleccionar centro</option>
                            @foreach ($centros as $c)
                                <option value="{{ $c->id }}">{{ $c->nom_centro }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de formación inicial *</label>
                        <select name="sede_inicial_id" class="form-select modern-input" required>
                            <option value="">Seleccionar sede</option>
                            @foreach ($sedes as $s)
                                <option value="{{ $s->id }}">{{ $s->nom_sede }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Centro de formación final *</label>
                        <select name="centro_final_id" class="form-select modern-input" required>
                            <option value="">Seleccionar centro</option>
                            @foreach ($centros as $c)
                                <option value="{{ $c->id }}">{{ $c->nom_centro }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de formación final *</label>
                        <select name="sede_final_id" class="form-select modern-input" required>
                            <option value="">Seleccionar sede</option>
                            @foreach ($sedes as $s)
                                <option value="{{ $s->id }}">{{ $s->nom_sede }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                    <button type="button" class="btn btn-success btn-sm mb-3" onclick="agregarMaterial()">
                                        <i class="fas fa-plus"></i> Agregar Material
                                    </button>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="materialesTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre Material</th>
                                                    <th>Cantidad</th>
                                                    <th>Tipo de Material</th>
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
    </style>
@endpush

@push('scripts')
    <script>
        let personaIndex = 0;
        let materialIndex = 0;

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

        // Agregar persona
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

        // Agregar material
        function agregarMaterial() {
            const tbody = document.querySelector('#materialesTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="materiales[${materialIndex}][nombre]" class="form-control" placeholder="Nombre del material" required></td>
                <td><input type="number" name="materiales[${materialIndex}][cantidad]" class="form-control" placeholder="0" min="1" required></td>
                <td><input type="text" name="materiales[${materialIndex}][tipo]" class="form-control" placeholder="Tipo"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
            materialIndex++;
        }

        // Eliminar fila
        function eliminarFila(button) {
            button.closest('tr').remove();
        }
    </script>
@endpush