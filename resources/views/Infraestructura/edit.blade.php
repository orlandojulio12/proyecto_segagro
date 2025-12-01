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

                    {{-- COMPONENTE CENTROS Y SEDES CON VALORES --}}
                    <x-centros-sedes-selector 
                        :centros="$centros" 
                        :centro-id="$infraestructura->centro_id"
                        :sede-id="$infraestructura->sede_id"
                        :centro-nombre="$infraestructura->centro->nom_centro ?? ''"
                        :sede-nombre="$infraestructura->sede->nom_sede ?? ''"
                    />

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
                            <option value="1" {{ $infraestructura->nivel_riesgo == 1 ? 'selected' : '' }}>🟢 Bajo</option>
                            <option value="2" {{ $infraestructura->nivel_riesgo == 2 ? 'selected' : '' }}>🟡 Medio</option>
                            <option value="3" {{ $infraestructura->nivel_riesgo == 3 ? 'selected' : '' }}>🔴 Alto</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Nivel de Prioridad <span
                                class="text-danger">*</span></label>
                        <select name="nivel_prioridad" class="form-control" required>
                            <option value="1" {{ $infraestructura->nivel_prioridad == 1 ? 'selected' : '' }}>Baja</option>
                            <option value="2" {{ $infraestructura->nivel_prioridad == 2 ? 'selected' : '' }}>Media</option>
                            <option value="3" {{ $infraestructura->nivel_prioridad == 3 ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Tipo de Necesidad <span
                                class="text-danger">*</span></label>
                        <select name="tipo_necesidad" class="form-control" required>
                            <option value="Eléctrica"
                                {{ $infraestructura->tipo_necesidad == 'Eléctrica' ? 'selected' : '' }}>Eléctrica</option>
                            <option value="Hidráulica"
                                {{ $infraestructura->tipo_necesidad == 'Hidráulica' ? 'selected' : '' }}>Hidráulica</option>
                            <option value="Refrigeración"
                                {{ $infraestructura->tipo_necesidad == 'Refrigeración' ? 'selected' : '' }}>Refrigeración</option>
                            <option value="Civil" {{ $infraestructura->tipo_necesidad == 'Civil' ? 'selected' : '' }}>
                                Infraestructura Civil</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Motivo de la Necesidad</label>
                        <input type="text" name="motivo_necesidad" class="form-control"
                            value="{{ old('motivo_necesidad', $infraestructura->motivo_necesidad) }}">
                    </div>

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
                                        {{ $sede->nom_sede }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                    <input type="file" name="imagen" id="imagenInput" class="form-control" accept="image/*">
                    <small class="text-muted">Adjunte una nueva imagen si desea reemplazar la existente</small>
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
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('requiereTraslado').addEventListener('change', function() {
            document.getElementById('sedeSecundariaDiv').style.display = this.checked ? 'block' : 'none';
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
            }
        });
    </script>
@endpush