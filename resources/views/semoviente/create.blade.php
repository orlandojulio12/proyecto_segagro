@extends('layouts.dashboard')

@section('page-title', 'Nuevo Semoviente')

@section('dashboard-content')
    <div class="section-header">
        <h2>Registrar Nuevo Semoviente</h2>
    </div>

    <div class="container">
        <form action="{{ route('semoviente.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- ================== COLUMNA IZQUIERDA ================== -->
                <div class="col-md-6">
                    <!-- Información General -->
                    <div class="form-section">
                        <h3 class="section-title">Información General</h3>
                        <p class="section-desc">Datos básicos para identificar y clasificar la necesidad</p>

                        <div class="form-group">
                            <label>Dependencia responsable*</label>
                            <input type="text" name="responsible_department" class="form-control"
                                value="{{ old('responsible_department') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nombre del funcionario*</label>
                            <select name="staff_id" class="form-select modern-inpu" required>
                                <option value="">Seleccione un funcionario</option>
                                @foreach ($staff as $persona)
                                    <option value="{{ $persona->id }}"
                                        {{ old('staff_id') == $persona->id ? 'selected' : '' }}>
                                        {{ $persona->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label >Centro de formación *</label>
                            <select name="centro_id" id="centroSelect" class="form-select modern-input" required>
                                <option value="">Seleccionar centro</option>
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro->id }}"
                                        {{ old('centro_id') == $centro->id ? 'selected' : '' }}>
                                        {{ $centro->nom_centro }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label text-success fw-semibold">Sede de formación *</label>
                            <select name="sede_id" id="sedeSelect" class="form-select modern-input" required>
                                <option value="">Primero selecciona un centro</option>
                            </select>
                        </div>

                    </div>

                    <!-- Información calendario -->
                    <div class="form-section">
                        <h3 class="section-title text-success">Información calendario</h3>
                        <p class="section-desc">Fechas de inicio y finalización de la necesidad</p>

                        <div class="form-group">
                            <label>Fecha de nacimiento*</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Hora de nacimiento*</label>
                            <input type="time" name="birth_time" class="form-control" value="{{ old('birth_time') }}"
                                required>
                        </div>
                    </div>

                    <!-- Detalles de la necesidad -->
                    <div class="form-section">
                        <h3 class="section-title text-success">Detalles de la necesidad</h3>
                        <p class="section-desc">Información complementaria</p>

                        <div class="form-group">
                            <label>Imagen de la necesidad</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- ================== COLUMNA DERECHA ================== -->
                <div class="col-md-6">
                    <!-- Características Adicionales -->
                    <div class="form-section">
                        <h3 class="section-title">Características Adicionales</h3>
                        <p class="section-desc">Información técnica y específica</p>

                        <div class="form-group">
                            <label>Área de nacimiento*</label>
                            <select name="birth_area" class="form-select modern-inpu" required>
                                <option value="">Seleccione...</option>
                                <option value="Rural" {{ old('birth_area') == 'Rural' ? 'selected' : '' }}>Rural</option>
                                <option value="Urbano" {{ old('birth_area') == 'Urbano' ? 'selected' : '' }}>Urbano
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Ambiente de formación*</label>
                            <input type="text" name="training_environment" class="form-control"
                                value="{{ old('training_environment') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Género del nacimiento*</label>
                            <select name="gender" class="form-select modern-inpu" required>
                                <option value="">Seleccione...</option>
                                <option value="Macho" {{ old('gender') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                <option value="Femenino" {{ old('gender') == 'Femenino' ? 'selected' : '' }}>Femenino
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo de nacimiento*</label>
                            <select name="birth_type" class="form-select modern-inpu" required>
                                <option value="">Seleccione...</option>
                                <option value="Natural" {{ old('birth_type') == 'Natural' ? 'selected' : '' }}>Natural
                                </option>
                                <option value="Cesárea" {{ old('birth_type') == 'Cesárea' ? 'selected' : '' }}>Cesárea
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo de semoviente*</label>
                            <select name="animal_type" class="form-select modern-inpu" required>
                                <option value="">Seleccione...</option>
                                <option value="Vaca" {{ old('animal_type') == 'Vaca' ? 'selected' : '' }}>Vaca</option>
                                <option value="Toro" {{ old('animal_type') == 'Toro' ? 'selected' : '' }}>Toro</option>
                                <option value="Becerro" {{ old('animal_type') == 'Becerro' ? 'selected' : '' }}>Becerro
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Raza del semoviente*</label>
                            <input type="text" name="breed" class="form-control" value="{{ old('breed') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Peso del semoviente*</label>
                            <input type="text" name="weight" class="form-control" value="{{ old('weight') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Color del semoviente*</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Paquete de la madre*</label>
                            <input type="text" name="mother_package" class="form-control"
                                value="{{ old('mother_package') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Valor aproximado*</label>
                            <input type="number" name="estimated_value" class="form-control"
                                value="{{ old('estimated_value') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Estado del semoviente*</label>
                            <select name="status" class="form-select modern-inpu" required>
                                <option value="">Seleccione...</option>
                                <option value="En venta" {{ old('status') == 'En venta' ? 'selected' : '' }}>En venta
                                </option>
                                <option value="Vivo" {{ old('status') == 'Vivo' ? 'selected' : '' }}>Vivo</option>
                                <option value="Muerto" {{ old('status') == 'Muerto' ? 'selected' : '' }}>Muerto</option>
                                <option value="Sacrificio" {{ old('status') == 'Sacrificio' ? 'selected' : '' }}>
                                    Sacrificio</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <a href="{{ route('semoviente.index') }}" class="btn btn-cancelar">Cancelar</a>
                <button type="submit" class="btn btn-guardar">Guardar</button>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            /* Secciones estilo tarjeta */
            .form-section {
                background: #fff;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            }

            /* Títulos */
            .section-title {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 5px;
                color: #333;
            }

            .section-title.text-success {
                color: #28a745 !important;
            }

            .section-desc {
                font-size: 13px;
                color: #666;
                margin-bottom: 15px;
            }

            /* Inputs */
            .form-control,
            .form-select {
                border-radius: 6px;
                font-size: 14px;
                padding: 8px 10px;
                border: 1px solid #ccc;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #28a745;
                box-shadow: 0 0 5px rgba(40, 167, 69, 0.25);
            }

            /* Botones */
            .form-actions {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .btn-cancelar {
                background: #fff;
                color: #28a745;
                border: 2px solid #28a745;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: bold;
            }

            .btn-guardar {
                background: #28a745;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: bold;
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
        </style>
    @endpush

    @push('scripts')
        <script>
            document.getElementById('centroSelect').addEventListener('change', function() {
                const centroId = this.value;
                const sedeSelect = document.getElementById('sedeSelect');
                sedeSelect.innerHTML = '<option value="">Cargando sedes...</option>';

                if (centroId) {
                    fetch(`/centros/${centroId}/sedes`)
                        .then(response => response.json())
                        .then(sedes => {
                            sedeSelect.innerHTML = '<option value="">Seleccionar sede</option>';
                            sedes.forEach(sede => {
                                const option = document.createElement('option');
                                option.value = sede.id;
                                option.textContent = sede.nom_sede;
                                sedeSelect.appendChild(option);
                            });
                        })
                        .catch(() => sedeSelect.innerHTML = '<option value="">Error al cargar sedes</option>');
                } else {
                    sedeSelect.innerHTML = '<option value="">Primero selecciona un centro</option>';
                }
            });
        </script>
    @endpush
@endsection
