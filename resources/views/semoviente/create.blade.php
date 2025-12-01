@extends('layouts.dashboard')

@section('page-title', 'Registrar Nuevo Semoviente')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Registrar Nuevo Semoviente</h2>
            <p class="text-muted">Complete la información del semoviente a registrar</p>
        </div>
        <a href="{{ route('semoviente.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Volver
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

    <form action="{{ route('semoviente.store') }}" method="POST" enctype="multipart/form-data" id="semovienteForm">
        @csrf

        <div class="row g-4">
            {{-- Columna izquierda - Información General --}}
            <div class="col-12 col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Información General</h5>
                    <p class="section-subtitle">Datos básicos del semoviente</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia responsable *</label>
                        <input type="text" name="responsible_department" class="form-control modern-input"
                            value="{{ old('responsible_department') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Funcionario responsable *</label>
                        <select name="staff_id" class="form-select modern-input" required>
                            <option value="">Seleccionar funcionario</option>
                            @foreach ($staff as $persona)
                                <option value="{{ $persona->id }}"
                                    {{ old('staff_id') == $persona->id ? 'selected' : '' }}>
                                    {{ $persona->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Centro de formación *</label>
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

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de formación *</label>
                        <select name="sede_id" id="sedeSelect" class="form-select modern-input" required>
                            <option value="">Primero selecciona un centro</option>
                        </select>
                    </div>
                </div>

                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-calendar me-2"></i>Información de Nacimiento</h5>
                    <p class="section-subtitle">Fecha y hora del nacimiento</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha de nacimiento *</label>
                        <input type="date" name="birth_date" class="form-control modern-input" 
                            value="{{ old('birth_date') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Hora de nacimiento *</label>
                        <input type="time" name="birth_time" class="form-control modern-input" 
                            value="{{ old('birth_time') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Área de nacimiento *</label>
                        <select name="birth_area" class="form-select modern-input" required>
                            <option value="">Seleccionar área</option>
                            <option value="Rural" {{ old('birth_area') == 'Rural' ? 'selected' : '' }}>Rural</option>
                            <option value="Urbano" {{ old('birth_area') == 'Urbano' ? 'selected' : '' }}>Urbano</option>
                        </select>
                    </div>
                </div>

                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-image me-2"></i>Imagen del Semoviente</h5>
                    <p class="section-subtitle">Foto del animal (opcional)</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Imagen</label>
                        <input type="file" name="image" class="form-control modern-input" accept="image/*">
                        <small class="text-muted">Formatos soportados: JPG, PNG, GIF</small>
                    </div>
                </div>
            </div>

            {{-- Columna derecha - Características Adicionales --}}
            <div class="col-12 col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-clipboard-list me-2"></i>Características del Semoviente</h5>
                    <p class="section-subtitle">Información técnica y específica</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Ambiente de formación *</label>
                        <input type="text" name="training_environment" class="form-control modern-input"
                            value="{{ old('training_environment') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Género *</label>
                        <select name="gender" class="form-select modern-input" required>
                            <option value="">Seleccionar género</option>
                            <option value="Macho" {{ old('gender') == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Femenino" {{ old('gender') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Tipo de nacimiento *</label>
                        <select name="birth_type" class="form-select modern-input" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Natural" {{ old('birth_type') == 'Natural' ? 'selected' : '' }}>Natural</option>
                            <option value="Cesárea" {{ old('birth_type') == 'Cesárea' ? 'selected' : '' }}>Cesárea</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Tipo de semoviente *</label>
                        <select name="animal_type" class="form-select modern-input" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Vaca" {{ old('animal_type') == 'Vaca' ? 'selected' : '' }}>Vaca</option>
                            <option value="Toro" {{ old('animal_type') == 'Toro' ? 'selected' : '' }}>Toro</option>
                            <option value="Becerro" {{ old('animal_type') == 'Becerro' ? 'selected' : '' }}>Becerro</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Raza *</label>
                        <input type="text" name="breed" class="form-control modern-input" 
                            value="{{ old('breed') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Peso (kg) *</label>
                        <input type="number" name="weight" class="form-control modern-input" 
                            value="{{ old('weight') }}" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Color *</label>
                        <input type="text" name="color" class="form-control modern-input" 
                            value="{{ old('color') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Paquete de la madre *</label>
                        <input type="text" name="mother_package" class="form-control modern-input"
                            value="{{ old('mother_package') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Valor aproximado *</label>
                        <input type="number" name="estimated_value" class="form-control modern-input"
                            value="{{ old('estimated_value') }}" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Estado del semoviente *</label>
                        <select name="status" class="form-select modern-input" required>
                            <option value="">Seleccionar estado</option>
                            <option value="En venta" {{ old('status') == 'En venta' ? 'selected' : '' }}>En venta</option>
                            <option value="Vivo" {{ old('status') == 'Vivo' ? 'selected' : '' }}>Vivo</option>
                            <option value="Muerto" {{ old('status') == 'Muerto' ? 'selected' : '' }}>Muerto</option>
                            <option value="Sacrificio" {{ old('status') == 'Sacrificio' ? 'selected' : '' }}>Sacrificio</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="form-footer">
            <a href="{{ route('semoviente.index') }}" class="btn btn-light btn-lg shadow-sm me-3">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save me-2"></i>Registrar Semoviente
            </button>
        </div>
    </form>

@push('styles')
<style>
    /* Estilos específicos para semoviente create - No afectan al layout */
    .semoviente-create .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .semoviente-create .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .semoviente-create .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
    }

    .semoviente-create .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: calc(-0.5 * 1rem);
        margin-left: calc(-0.5 * 1rem);
    }

    .semoviente-create .row.g-4 {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
    }

    .semoviente-create .col-12 {
        flex: 0 0 auto;
        width: 100%;
    }

    .semoviente-create .col-md-6 {
        flex: 0 0 auto;
        width: 50%;
    }

    .semoviente-create .col-12.col-md-6 {
        padding-right: calc(var(--bs-gutter-x) * 0.5);
        padding-left: calc(var(--bs-gutter-x) * 0.5);
    }

    .semoviente-create .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }

    .semoviente-create .content-card:hover {
        box-shadow: 0 4px 20px rgba(76, 209, 55, 0.15);
    }

    .semoviente-create .section-title {
        font-size: 1.1rem;
        color: #4cd137;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .semoviente-create .section-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .semoviente-create .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .semoviente-create .modern-input {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .semoviente-create .modern-input:focus {
        border-color: #4cd137;
        box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.15);
        outline: none;
    }

    .semoviente-create .form-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 0;
    }

    .semoviente-create .btn {
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }

    .semoviente-create .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        border: none;
        margin-left: 8px;
    }

    .semoviente-create .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .semoviente-create .btn-light {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }

    .semoviente-create .btn-light:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-2px);
    }

    .semoviente-create .btn-outline-secondary {
        background: transparent;
        border: 1px solid #6c757d;
        color: #6c757d;
    }

    .semoviente-create .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }

    /* Alerts */
    .semoviente-create .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .semoviente-create .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #e74c3c;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .semoviente-create .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .semoviente-create .col-md-6 {
            width: 100%;
        }

        .semoviente-create .form-footer {
            flex-direction: column;
            gap: 10px;
        }

        .semoviente-create .form-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('semoviente-create');

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
