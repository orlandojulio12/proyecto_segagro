@extends('layouts.dashboard')

@section('page-title', 'Necesidad de Infraestructura')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('dashboard-content')
    <div class="content-card">
        <form action="{{ route('infraestructura.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <h5>Información General</h5>

                    <!-- Unidad -->
                    <div class="form-group mb-3">
                        <label>Unidad Responsable <span class="text-danger">*</span></label>
                        <select name="unidad_id" id="unidadSelect" class="form-control" required>
                            <option value="">Seleccione una unidad</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->dependency_unit_id }}">{{ $unit->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subunidad -->
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
                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- COMPONENTE CENTROS Y SEDES --}}
                    <x-centros-sedes-selector :centros="$centros" prefix="inicial" />

                    <div class="form-group mb-3">
                         <label>Area <span class="text-danger">*</span></label>
                        <select name="area_id" id="areaSelect" class="form-select" required>
                            <option value="">Seleccione un área</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Room / Classroom <span class="text-danger">*</span></label>
                        <select name="ambiente" id="roomSelect" class="form-select" required>
                            <option value="">Select room</option>
                        </select>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <h5>Características de la Necesidad</h5>

                    <div class="form-group mb-3">
                        <label>Motivo de la Necesidad <span class="text-danger">*</span></label>
                        <select name="motivo_necesidad" class="form-control" required>
                            <option value="">Seleccione el motivo</option>
                            <option value="Falla de equipo">Falla de equipo</option>
                            <option value="Actualización de infraestructura">Actualización de infraestructura</option>
                            <option value="Cumplimiento normativo">Cumplimiento normativo</option>
                            <option value="Solicitud de usuario">Solicitud de usuario</option>
                            <option value="Mantenimiento preventivo programado">Mantenimiento preventivo programado</option>
                            <option value="Emergencia / urgencia">Emergencia / urgencia</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Tipo de Necesidad <span class="text-danger">*</span></label>
                        <select name="tipo_necesidad" class="form-control" required>
                            <option value="">Seleccione el tipo</option>
                            <option value="Reparación de instalaciones">Reparación de instalaciones</option>
                            <option value="Instalación de nuevo equipamiento">Instalación de nuevo equipamiento</option>
                            <option value="Mantenimiento preventivo">Mantenimiento preventivo</option>
                            <option value="Reemplazo de componentes">Reemplazo de componentes</option>
                            <option value="Mejora de infraestructura">Mejora de infraestructura</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Riesgo <span class="text-danger">*</span></label>
                            <select name="nivel_riesgo" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="1">🟢 Bajo</option>
                                <option value="2">🟡 Medio</option>
                                <option value="3">🔴 Alto</option>
                            </select>
                            <small class="text-muted">Impacto en seguridad</small>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label>Nivel de Complejidad <span class="text-danger">*</span></label>
                            <select name="nivel_complejidad" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="1">Bajo</option>
                                <option value="2">Medio</option>
                                <option value="3">Alto</option>
                            </select>
                            <small class="text-muted">Recursos requeridos</small>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="requiere_traslado" value="0">

                            <input class="form-check-input" type="checkbox" name="requiere_traslado" value="1"
                                id="requiereTraslado">
                            <label class="form-check-label" for="requiereTraslado">
                                ¿Requiere traslado de equipos o personal?
                            </label>
                        </div>
                    </div>

                    <div id="trasladoDestinos" style="display: none;">
                        <!-- Usamos el mismo componente de centros y sedes -->
                        <x-centros-sedes-selector :centros="$centros" prefix="final" :required="false" />
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
                    <label>Evidencia Fotográfica</label>
                    <input type="file" name="imagen" class="form-control" id="imagenInput" accept="image/*">
                    <small class="text-muted">Adjunte una imagen que evidencie la necesidad</small>
                    <div id="imagenPreview" class="mt-3"></div>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label>Descripción Detallada <span class="text-danger">*</span></label>
                    <textarea name="descripcion" rows="6" class="form-control" required
                        placeholder="Describa con detalle la necesidad..."></textarea>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('infraestructura.index') }}" class="btn-modern btn-cancel">Cancelar</a>
                <button type="submit" class="btn-modern btn-save">Guardar Necesidad</button>
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

        h5 {
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

        #imagenPreview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ===== 1. Imagen: Vista previa =====
            const imagenInput = document.getElementById('imagenInput');
            const imagenPreview = document.getElementById('imagenPreview');

            imagenInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagenPreview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagenPreview.innerHTML = '';
                }
            });

            // ===== 2. Unidades → Subunidades =====
            const unitsData = @json($units->mapWithKeys(fn($u) => [$u->dependency_unit_id => $u->subunits]));
            const unidadSelect = document.getElementById('unidadSelect');
            const subunidadSelect = document.getElementById('subunidadSelect');

            unidadSelect.addEventListener('change', function() {
                const unitId = this.value;
                subunidadSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';
                if (unitId && unitsData[unitId]) {
                    unitsData[unitId].forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.subunit_id;
                        opt.textContent = s.name;
                        subunidadSelect.appendChild(opt);
                    });
                }
            });

            // ===== 3. Mostrar/Ocultar Traslado =====
            const requiereTraslado = document.getElementById('requiereTraslado');
            const trasladoDiv = document.getElementById('trasladoDestinos');

            requiereTraslado.addEventListener('change', function() {
                trasladoDiv.style.display = this.checked ? 'block' : 'none';
            });

            // ===== 4. Selección de Centro → Sede → Área =====
            const centroInput = document.getElementById('inicial_centro_id');
            const sedeInput = document.getElementById('inicial_sede_id');
            const areaSelect = document.getElementById('areaSelect');

            function resetAreaSelect() {
                areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
            }

            // Se dispara al cambiar la sede (input hidden)
            sedeInput.addEventListener('input', function() {
                const centroId = centroInput.value;
                const sedeId = this.value;

                resetAreaSelect();

                if (!centroId || !sedeId) return; // 🔹 evita fetch innecesario

                fetch(`/centros/${centroId}/sedes-centro?sede_id=${sedeId}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Error en la respuesta del servidor');
                        return res.json();
                    })
                    .then(data => {
                        areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                        if (!data || data.length === 0) {
                            areaSelect.innerHTML = '<option value="">No hay áreas disponibles</option>';
                            return;
                        }
                        data.forEach(area => {
                            const opt = document.createElement('option');
                            opt.value = area.id;
                            opt.textContent = area.name;
                            areaSelect.appendChild(opt);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        areaSelect.innerHTML = '<option value="">Error cargando áreas</option>';
                    });
            });

            // ===== 5. Selección de Área → Rooms =====
            const roomSelect = document.getElementById('roomSelect');
            areaSelect.addEventListener('change', function() {
                const areaId = this.value;
                roomSelect.innerHTML = '<option value="">Cargando...</option>';

                if (!areaId) {
                    roomSelect.innerHTML = '<option value="">Seleccione un room</option>';
                    return;
                }

                fetch(`/areas/${areaId}/rooms`)
                    .then(res => res.json())
                    .then(data => {
                        roomSelect.innerHTML = '<option value="">Seleccione un room</option>';
                        if (!data || data.length === 0) {
                            roomSelect.innerHTML = '<option value="">No hay rooms disponibles</option>';
                            return;
                        }
                        data.forEach(room => {
                            const opt = document.createElement('option');
                            opt.value = room.id;
                            opt.textContent =
                                `${room.name} `;
                            roomSelect.appendChild(opt);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        roomSelect.innerHTML = '<option value="">Error cargando rooms</option>';
                    });
            });

            // ===== 6. Funciones modales y selección Centros/Sedes =====
            function getPrefixFromId(id) {
                return id.split('_')[0];
            }

            window.openModal = id => {
                document.getElementById(id).classList.add('show');
                document.body.classList.add('modal-open');
            };
            window.closeModal = id => {
                document.getElementById(id).classList.remove('show');
                document.body.classList.remove('modal-open');
            };
            window.openModalSede = prefix => {
                const centroId = document.getElementById(prefix + '_centro_id').value;
                if (!centroId) {
                    alert('Primero debe seleccionar un centro');
                    return;
                }
                openModal(prefix + '_sedeModal');
            };

            // Render inicial y filtros de centros/sedes
            document.querySelectorAll('.seleccionar-centro').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const prefix = this.dataset.prefix;
                    const centroIdInput = document.getElementById(prefix + '_centro_id');
                    const centroSeleccionado = document.getElementById(prefix +
                        '_centroSeleccionado');
                    const sedeInput = document.getElementById(prefix + '_sede_id');
                    const sedeSeleccionada = document.getElementById(prefix + '_sedeSeleccionada');

                    centroIdInput.value = this.dataset.id;
                    centroSeleccionado.value = this.dataset.nombre;

                    sedeInput.value = '';
                    sedeSeleccionada.value = '';
                    sedeSeleccionada.placeholder = 'Cargando sedes...';
                    sedeSeleccionada.disabled = true;

                    closeModal(prefix + '_centroModal');

                    // Traer sedes
                    fetch(`/centros/${this.dataset.id}/sedes`).then(r => r.json()).then(sedes => {
                        const listaSedes = document.getElementById(prefix + '_listaSedes');
                        listaSedes.innerHTML = '';
                        sedes.forEach(sede => {
                            const tr = document.createElement('tr');
                            tr.innerHTML =
                                `<td>${sede.nom_sede}</td>
                        <td><button type="button" class="btn btn-sm btn-success seleccionar-sede" data-id="${sede.id}" data-nombre="${sede.nom_sede}" data-prefix="${prefix}">Seleccionar</button></td>`;
                            listaSedes.appendChild(tr);
                        });
                        sedeSeleccionada.placeholder = 'Seleccione una sede...';
                        sedeSeleccionada.disabled = false;
                    });
                });
            });

            // Delegación para seleccionar sede
            document.body.addEventListener('click', e => {
                if (e.target && e.target.classList.contains('seleccionar-sede')) {
                    const prefix = e.target.dataset.prefix;
                    const sedeInput = document.getElementById(prefix + '_sede_id');
                    sedeInput.value = e.target.dataset.id;
                    document.getElementById(prefix + '_sedeSeleccionada').value = e.target.dataset.nombre;
                    closeModal(prefix + '_sedeModal');

                    // 🔹 Disparar carga de áreas
                    sedeInput.dispatchEvent(new Event('input'));
                }
            });

        });
    </script>
@endpush
