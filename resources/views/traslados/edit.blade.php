@extends('layouts.dashboard')

@section('page-title', 'Editar Traslado')

@section('dashboard-content')
    <div class="content-card">
        <form action="{{ route('traslados.update', $traslado->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <h5>Información General</h5>
                    <div class="form-group mb-3">
                        <label>Unidad Responsable <span class="text-danger">*</span></label>
                        <select name="unidad_id" id="unidadSelect" class="form-control" required>
                            <option value="">Seleccione una unidad</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->dependency_unit_id }}"
                                    {{ $traslado->unidad_id == $unit->dependency_unit_id ? 'selected' : '' }}>
                                    {{ $unit->short_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Subunidad Responsable <span class="text-danger">*</span></label>
                        <select name="subunidad_id" id="subunidadSelect" class="form-control" required>
                            <option value="">Seleccione una subunidad</option>
                            @if ($traslado->unidad_id)
                                @php
                                    $unidad = $units->firstWhere('dependency_unit_id', $traslado->unidad_id);
                                @endphp
                                @foreach ($unidad->subunits as $sub)
                                    <option value="{{ $sub->subunit_id }}"
                                        {{ $traslado->subunidad_id == $sub->subunit_id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Funcionario Responsable <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Seleccione un funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $traslado->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="mt-4">Inicio del Traslado</h5>
                    <x-centros-sedes-selector :centros="$centros" prefix="inicial" :centroId="$traslado->centro_inicial_id" :sedeId="$traslado->sede_inicial_id"
                        :centroNombre="$traslado->centroInicial->nom_centro ?? ''" :sedeNombre="$traslado->sedeInicial->nom_sede ?? ''" />

                    <h5 class="mt-4">Destino del Traslado</h5>
                    <x-centros-sedes-selector :centros="$centros" prefix="final" :centroId="$traslado->centro_final_id" :sedeId="$traslado->sede_final_id"
                        :centroNombre="$traslado->centroFinal->nom_centro ?? ''" :sedeNombre="$traslado->sedeFinal->nom_sede ?? ''" />

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_inicio" class="form-control"
                                value="{{ $traslado->fecha_inicio->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Fecha de Finalización <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_fin" class="form-control"
                                value="{{ $traslado->fecha_fin->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" rows="6">{{ $traslado->descripcion }}</textarea>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <h5>Características de la Necesidad</h5>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Riesgo</label>
                            <select name="nivel_riesgo" class="form-control">
                                <option value="1" {{ $traslado->nivel_riesgo == '1' ? 'selected' : '' }}>🟢 Bajo
                                </option>
                                <option value="2" {{ $traslado->nivel_riesgo == '2' ? 'selected' : '' }}>🟡 Medio
                                </option>
                                <option value="3" {{ $traslado->nivel_riesgo == '3' ? 'selected' : '' }}>🔴 Alto
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Complejidad</label>
                            <select name="nivel_complejidad" class="form-control">
                                <option value="1" {{ $traslado->nivel_complejidad == '1' ? 'selected' : '' }}>Bajo
                                </option>
                                <option value="2" {{ $traslado->nivel_complejidad == '2' ? 'selected' : '' }}>Medio
                                </option>
                                <option value="3" {{ $traslado->nivel_complejidad == '3' ? 'selected' : '' }}>Alto
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Presupuesto Solicitado</label>
                        <input type="number" name="presupuesto_solicitado" class="form-control"
                            value="{{ $traslado->presupuesto_solicitado }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Presupuesto Aceptado</label>
                        <input type="number" name="presupuesto_aceptado" class="form-control"
                            value="{{ $traslado->presupuesto_aceptado }}" disabled>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="chk-personas" name="requiere_personal"
                            value="1" {{ $traslado->requiere_personal ? 'checked' : '' }}>
                        <label class="form-check-label" for="chk-personas">Requiere trasladar personas</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="chk-materiales" name="requiere_materiales"
                            value="1" {{ $traslado->requiere_materiales ? 'checked' : '' }}>
                        <label class="form-check-label" for="chk-materiales">Requiere trasladar materiales</label>
                    </div>

                    <!-- Personal existente -->
                    <div id="personas-section" style="display: {{ $traslado->requiere_personal ? 'block' : 'none' }};">
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
                                <tbody>
                                    @foreach ($traslado->personal as $index => $p)
                                        <tr>
                                            <td><input type="text" name="personal[{{ $index }}][nombre]"
                                                    class="form-control" value="{{ $p->name }}" required>
                                                <input type="hidden" name="personal[{{ $index }}][id]"
                                                    value="{{ $p->id }}">
                                            </td>
                                            <td><input type="text" name="personal[{{ $index }}][documento]"
                                                    class="form-control" value="{{ $p->documento ?? '' }}" required></td>
                                            <td><input type="text" name="personal[{{ $index }}][cargo]"
                                                    class="form-control" value="{{ $p->pivot->cargo ?? '' }}"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm"
                                                    onclick="this.closest('tr').remove()"><i
                                                        class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Materiales existentes -->
                    <div id="materiales-section"
                        style="display: {{ $traslado->requiere_materiales ? 'block' : 'none' }};">
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
                                <tbody>
                                    @foreach ($traslado->materiales as $index => $m)
                                        <tr data-material-id="{{ $m->id }}">
                                            <td>
                                                <strong>{{ $m->material_name }}</strong>
                                                <input type="hidden"
                                                    name="materiales[{{ $index }}][inventory_material_id]"
                                                    value="{{ $m->id }}">
                                            </td>
                                            <td><span class="badge bg-info">{{ $m->material_type ?? 'N/A' }}</span></td>
                                            <td><input type="number" name="materiales[{{ $index }}][cantidad]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $m->pivot->cantidad }}" min="1" required
                                                    style="width:100px;"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm"
                                                    onclick="eliminarMaterial(this, {{ $m->id }})"><i
                                                        class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pendiente" {{ $traslado->status == 'pendiente' ? 'selected' : '' }}>Pendiente
                            </option>
                            <option value="completada" {{ $traslado->status == 'completada' ? 'selected' : '' }}>
                                Completada</option>
                        </select>
                    </div>

                </div>
            </div>

            <hr class="my-4">

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('traslados.index') }}" class="btn-modern btn-cancel">Cancelar</a>
                <button type="submit" class="btn-modern btn-save">Actualizar Traslado</button>
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
        // Personas
        let personaIndex = {{ $traslado->personal->count() }};

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
        let materialIndex = {{ $traslado->materiales->count() }};
        let materialesSeleccionados = [
            @foreach ($traslado->materiales as $m)
                {{ $m->id }},
            @endforeach
        ];

        function toggleSections() {
            document.getElementById('personas-section').style.display = document.getElementById('chk-personas').checked ?
                'block' : 'none';
            document.getElementById('materiales-section').style.display = document.getElementById('chk-materiales')
                .checked ? 'block' : 'none';
        }

        document.getElementById('chk-personas').addEventListener('change', toggleSections);
        document.getElementById('chk-materiales').addEventListener('change', toggleSections);
        toggleSections();


        const unidades = @json($units);
        const unidadSelect = document.getElementById('unidadSelect');
        const subunidadSelect = document.getElementById('subunidadSelect');

        function cargarSubunidades(selectedId = null) {
            subunidadSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';
            const unidadId = parseInt(unidadSelect.value);
            if (!unidadId) return;

            const unidad = unidades.find(u => u.dependency_unit_id === unidadId);
            if (!unidad) return;

            unidad.subunits.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.subunit_id;
                option.textContent = sub.name;
                if (selectedId && selectedId == sub.subunit_id) option.selected = true;
                subunidadSelect.appendChild(option);
            });
        }

        unidadSelect.addEventListener('change', () => cargarSubunidades());
        cargarSubunidades({{ $traslado->subunidad_id }});
    </script>
@endpush
