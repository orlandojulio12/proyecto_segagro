@extends('layouts.dashboard')

@section('page-title', 'Necesidad de Infraestructura')

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
                    <x-centros-sedes-selector :centros="$centros" />

                    <div class="form-group mb-3">
                        <label>Área de la Necesidad <span class="text-danger">*</span></label>
                        <select name="area_necesidad" class="form-control" required>
                            <option value="">Seleccione un área</option>
                            <option value="Talleres">Talleres</option>
                            <option value="Laboratorios">Laboratorios</option>
                            <option value="Aulas">Aulas</option>
                            <option value="Áreas Comunes">Áreas Comunes</option>
                            <option value="Oficinas Administrativas">Oficinas Administrativas</option>
                            <option value="Exteriores">Exteriores</option>
                        </select>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <h5>Características de la Necesidad</h5>

                    <div class="form-group mb-3">
                        <label>Tipo de Necesidad <span class="text-danger">*</span></label>
                        <select name="tipo_necesidad" class="form-control" required>
                            <option value="">Seleccione el tipo</option>
                            <option value="Eléctrica">Eléctrica</option>
                            <option value="Hidráulica">Hidráulica</option>
                            <option value="Refrigeración">Refrigeración</option>
                            <option value="Infraestructura Civil">Infraestructura Civil</option>
                            <option value="Mobiliario">Mobiliario</option>
                            <option value="Equipos">Equipos</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Motivo de la Necesidad <span class="text-danger">*</span></label>
                        <select name="motivo_necesidad" class="form-control" required>
                            <option value="">Seleccione el motivo</option>
                            <option value="Mantenimiento Preventivo">Mantenimiento Preventivo</option>
                            <option value="Mantenimiento Correctivo">Mantenimiento Correctivo</option>
                            <option value="Instalación Nueva">Instalación Nueva</option>
                            <option value="Reparación">Reparación</option>
                            <option value="Reemplazo">Reemplazo</option>
                            <option value="Mejora">Mejora</option>
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
                            <input class="form-check-input" type="checkbox" name="requiere_traslado" id="requiereTraslado">
                            <label class="form-check-label" for="requiereTraslado">
                                ¿Requiere traslado de equipos o personal?
                            </label>
                        </div>
                    </div>

                    <div id="trasladoDestinos" style="display: none;">
                        <!-- Usamos el mismo componente de centros y sedes -->
                        <x-centros-sedes-selector :centros="$centros" :required="false"
                            centroId="{{ old('centro_final_id') }}" sedeId="{{ old('sede_final_id') }}"
                            centroNombre="{{ old('centro_final_name') }}" sedeNombre="{{ old('sede_final_name') }}" />
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

        // Mapeamos unidades → subunidades
        const unitsData = @json($units->mapWithKeys(fn($u) => [$u->dependency_unit_id => $u->subunits]));

        document.getElementById('unidadSelect').addEventListener('change', function() {
            const unitId = this.value;
            const subSelect = document.getElementById('subunidadSelect');
            subSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';

            if (unitId && unitsData[unitId]) {
                unitsData[unitId].forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.subunit_id;
                    opt.textContent = s.name;
                    subSelect.appendChild(opt);
                });
            }
        });

        document.getElementById('requiereTraslado').addEventListener('change', function() {
            const trasladoDiv = document.getElementById('trasladoDestinos');
            trasladoDiv.style.display = this.checked ? 'block' : 'none';
        });
    </script>
@endpush
