{{-- resources/views/contracts/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Contrato')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Nuevo Contrato</h2>
        <p class="text-muted">Registra un nuevo contrato en el sistema</p>
    </div>
    <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores en el formulario:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('contracts.store') }}" method="POST" id="contractForm">
    @csrf
    
    <div class="row">
        <!-- Información Básica del Contrato -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-file-contract"></i> Información del Contrato
                </h5>
                <p class="section-subtitle">Datos básicos del contrato</p>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-hashtag text-success"></i> Número de Contrato *
                    </label>
                    <input type="text" 
                           name="contract_number" 
                           class="form-control modern-input @error('contract_number') is-invalid @enderror" 
                           value="{{ old('contract_number') }}"
                           placeholder="Ej: CT-2025-001"
                           required>
                    @error('contract_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-handshake text-success"></i> Modalidad de Contratación *
                    </label>
                    <select name="hiring_modality_id" 
                            class="form-select modern-input @error('hiring_modality_id') is-invalid @enderror" 
                            required>
                        <option value="">Seleccionar modalidad...</option>
                        @foreach($hiringModalities as $modality)
                            <option value="{{ $modality->id }}" {{ old('hiring_modality_id') == $modality->id ? 'selected' : '' }}>
                                {{ $modality->modality_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hiring_modality_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-align-left text-success"></i> Objeto del Contrato *
                    </label>
                    <textarea name="contract_object" 
                              class="form-control modern-input @error('contract_object') is-invalid @enderror" 
                              rows="4" 
                              placeholder="Describe el objeto del contrato..."
                              required>{{ old('contract_object') }}</textarea>
                    @error('contract_object')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Información del Contratista -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-user-tie"></i> Información del Contratista
                </h5>
                <p class="section-subtitle">Datos del contratista</p>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-user text-success"></i> Nombre del Contratista *
                    </label>
                    <input type="text" 
                           name="contractor_name" 
                           class="form-control modern-input @error('contractor_name') is-invalid @enderror" 
                           value="{{ old('contractor_name') }}"
                           placeholder="Nombre completo del contratista"
                           required>
                    @error('contractor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-id-card text-success"></i> NIT del Contratista *
                    </label>
                    <input type="text" 
                           name="contractor_nit" 
                           class="form-control modern-input @error('contractor_nit') is-invalid @enderror" 
                           value="{{ old('contractor_nit') }}"
                           placeholder="Ej: 900123456-7"
                           required>
                    @error('contractor_nit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-sitemap text-success"></i> Dependencia *
                    </label>
                    <select name="dependencia_id" 
                            id="dependenciaSelect"
                            class="form-select modern-input @error('contract_type_id') is-invalid @enderror" 
                            required>
                        <option value="">Seleccionar dependencia...</option>
                        @foreach($dependencias as $dependencia)
                            <option value="{{ $dependencia->id }}" {{ old('dependencia_id') == $dependencia->id ? 'selected' : '' }}>
                                {{ $dependencia->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dependencia_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-file-alt text-success"></i> Tipo de Contrato *
                    </label>
                    <select name="contract_type_id" 
                            id="contractTypeSelect"
                            class="form-select modern-input @error('contract_type_id') is-invalid @enderror" 
                            required
                            disabled>
                        <option value="">Primero selecciona una dependencia...</option>
                    </select>
                    @error('contract_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-map-marker-alt"></i> Ubicación
                </h5>
                <p class="section-subtitle">Sede donde se ejecuta el contrato</p>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-building text-success"></i> Centro *
                    </label>
                    <select id="centroSelect" 
                            class="form-select modern-input" 
                            required>
                        <option value="">Seleccionar centro...</option>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}" {{ old('centro_id') == $centro->id ? 'selected' : '' }}>
                                {{ $centro->nom_centro }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-map-marker-alt text-success"></i> Sede *
                    </label>
                    <select name="sede_id" 
                            id="sedeSelect"
                            class="form-select modern-input @error('sede_id') is-invalid @enderror" 
                            required
                            disabled>
                        <option value="">Primero selecciona un centro...</option>
                    </select>
                    @error('sede_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Información Financiera y Fechas -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-calendar-alt"></i> Fechas y Valores
                </h5>
                <p class="section-subtitle">Información temporal y financiera</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-check text-success"></i> Fecha de Inicio *
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   class="form-control modern-input @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-times text-success"></i> Fecha de Terminación *
                            </label>
                            <input type="date" 
                                   name="initial_end_date" 
                                   class="form-control modern-input @error('initial_end_date') is-invalid @enderror" 
                                   value="{{ old('initial_end_date') }}"
                                   required>
                            @error('initial_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-plus text-success"></i> Fecha de Prórroga
                    </label>
                    <input type="date" 
                           name="extension_date" 
                           class="form-control modern-input @error('extension_date') is-invalid @enderror" 
                           value="{{ old('extension_date') }}">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Opcional - Solo si hay prórroga
                    </small>
                    @error('extension_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-dollar-sign text-success"></i> Valor Inicial *
                            </label>
                            <input type="number" 
                                   name="initial_value" 
                                   class="form-control modern-input @error('initial_value') is-invalid @enderror" 
                                   value="{{ old('initial_value') }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   required>
                            @error('initial_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-plus-circle text-success"></i> Valor Adicional
                            </label>
                            <input type="number" 
                                   name="addition_value" 
                                   class="form-control modern-input @error('addition_value') is-invalid @enderror" 
                                   value="{{ old('addition_value', 0) }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Opcional
                            </small>
                            @error('addition_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-2"></i>Guardar Contrato
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
    /* Estilos específicos para contracts-create */
    .contracts-create .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contracts-create .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .contracts-create .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .contracts-create .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .contracts-create .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 8px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4cd137;
    }

    .contracts-create .section-title i {
        color: #4cd137;
        margin-right: 8px;
    }

    .contracts-create .section-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .contracts-create .form-label {
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .contracts-create .form-label i {
        margin-right: 5px;
    }

    .contracts-create .modern-input {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .contracts-create .modern-input:focus {
        border-color: #4cd137;
        box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.25);
    }

    .contracts-create .modern-input.is-invalid {
        border-color: #dc3545;
    }

    .contracts-create .modern-input.is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .contracts-create .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 24px;
        transition: all 0.3s ease;
        border: none;
        margin-right: 15px;
    }

    .contracts-create .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
    }

    .contracts-create .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .contracts-create .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .contracts-create .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    .contracts-create .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: white;
    }

    .contracts-create .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .contracts-create .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .contracts-create .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #e74c3c;
    }

    .contracts-create .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .contracts-create .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('contracts-create');

    // Cargar sedes cuando se selecciona un centro
    document.getElementById('centroSelect').addEventListener('change', function() {
        const centroId = this.value;
        const sedeSelect = document.getElementById('sedeSelect');
        
        sedeSelect.innerHTML = '<option value="">Cargando sedes...</option>';
        sedeSelect.disabled = true;
        
        if (!centroId) {
            sedeSelect.innerHTML = '<option value="">Primero selecciona un centro...</option>';
            return;
        }
        
        fetch(`/contracts/sedes/centro/${centroId}`)
            .then(response => response.json())
            .then(sedes => {
                sedeSelect.innerHTML = '<option value="">Seleccionar sede...</option>';
                sedes.forEach(sede => {
                    const option = document.createElement('option');
                    option.value = sede.id;
                    option.textContent = sede.nom_sede;
                    sedeSelect.appendChild(option);
                });
                sedeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error al cargar sedes:', error);
                sedeSelect.innerHTML = '<option value="">Error al cargar sedes</option>';
            });
    });

    // Cargar tipos de contrato cuando se selecciona una dependencia
    document.getElementById('dependenciaSelect').addEventListener('change', function() {
        const dependenciaId = this.value;
        const typeSelect = document.getElementById('contractTypeSelect');
        
        typeSelect.innerHTML = '<option value="">Cargando tipos...</option>';
        typeSelect.disabled = true;
        
        if (!dependenciaId) {
            typeSelect.innerHTML = '<option value="">Primero selecciona una dependencia...</option>';
            return;
        }
        
        fetch(`/contracts/types/dependencia/${dependenciaId}`)
            .then(response => response.json())
            .then(types => {
                typeSelect.innerHTML = '<option value="">Seleccionar tipo...</option>';
                types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = type.type_name;
                    if (type.description) {
                        option.title = type.description;
                    }
                    typeSelect.appendChild(option);
                });
                typeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error al cargar tipos:', error);
                typeSelect.innerHTML = '<option value="">Error al cargar tipos</option>';
            });
    });

    // Validación de fechas
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="initial_end_date"]');
    const extensionDateInput = document.querySelector('input[name="extension_date"]');

    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                alert('⚠️ La fecha de terminación debe ser posterior a la fecha de inicio');
                endDateInput.value = '';
            }
        }
    });

    extensionDateInput.addEventListener('change', function() {
        if (endDateInput.value && extensionDateInput.value) {
            if (new Date(extensionDateInput.value) <= new Date(endDateInput.value)) {
                alert('⚠️ La fecha de prórroga debe ser posterior a la fecha de terminación inicial');
                extensionDateInput.value = '';
            }
        }
    });
</script>
@endpush