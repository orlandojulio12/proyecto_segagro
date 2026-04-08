{{-- resources/views/Complaint/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear PQR')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Creación de PQR</h2>
            <p class="text-muted">Registra una nueva Petición, Queja o Reclamo</p>
        </div>
        <a href="{{ route('pqr.index') }}" class="btn btn-outline-secondary shadow-sm">
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

    <form action="{{ route('pqr.store') }}" method="POST" id="pqrForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                    <p class="section-subtitle">Datos básicos de la PQR</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Título *</label>
                        <input type="text" name="title" class="form-control modern-input" value="{{ old('title') }}"
                            required placeholder="Ej: Problema con el servicio de...">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha *</label>
                        <input type="date" name="date" class="form-control modern-input"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                        <small class="text-muted">Fecha en que se registra la PQR</small>
                    </div>

                    <div class="form-group mb-3" id="tiempoTutelaContainer" style="display:none;">
                        <label class="form-label text-success fw-semibold">Tiempo de respuesta *</label>
                        <select name="horas_tutela" id="horasTutela" class="form-select modern-input">
                            <option value="">Seleccionar tiempo</option>
                            <option value="24">24 horas</option>
                            <option value="48">48 horas</option>
                            <option value="72">72 horas</option>
                        </select>
                    </div>

                    <!-- Unidad -->
                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia *</label>
                        <select name="dependencia_id" id="dependenciaSelect" class="form-select modern-input" required>
                            <option value="">Seleccionar dependencia</option>
                            @foreach ($dependencias as $dep)
                                <option value="{{ $dep->id_dependencia }}">
                                    {{ $dep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Concepto *</label>
                        <select name="concepto_id" id="conceptoSelect" class="form-select modern-input" required>
                            <option value="">Selecciona primero una dependencia</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">¿Es tutela?</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isTutela" name="is_tutela" value="1">
                            <label class="form-check-label">Sí, es una tutela</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Responsable *</label>
                        <input type="text" name="responsible" class="form-control modern-input"
                            value="{{ old('responsible') }}" required placeholder="Nombre del funcionario responsable">
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Detalles de la PQR</h5>
                    <p class="section-subtitle">Descripción completa de la situación</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Descripción *</label>
                        <textarea name="description" class="form-control modern-input"  minlength="10" rows="8" required
                            placeholder="Describe detalladamente la petición, queja o reclamo...">{{ old('description') }}</textarea>
                        <small class="text-muted">Sé lo más específico posible para facilitar la gestión.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Documento adjunto (PDF)</label>
                        <input type="file" name="pdf" class="form-control modern-input" accept=".pdf">
                        <small class="text-muted">Opcional: Adjunta evidencias o documentos de soporte en PDF.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de tiempo -->
        <div class="content-card mb-4">
            <h5 class="section-title"><i class="fas fa-clock"></i> Información de Tiempo</h5>
            <div id="alertTiempo" class="alert alert-info shadow-sm">
                <i class="fas fa-info-circle"></i>
                <strong>Tiempo de respuesta:</strong>
                <span id="textoTiempo">
                    Todas las PQR tienen un plazo de <strong>12 días</strong>.
                </span>
            </div>

            <div id="diasInfo" class="mt-3" style="display: none;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-calendar-alt"></i>
                            <strong>Fecha límite:</strong>
                            <span id="fechaLimite">--</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-hourglass-half"></i>
                            <strong>Días disponibles:</strong>
                            <span id="diasRestantes" class="badge bg-success">12 días</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-flag"></i>
                            <strong>Estado:</strong>
                            <span id="estadoColor" class="badge bg-success">Nuevo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('pqr.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save"></i> Registrar PQR
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

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2f9e44;
            text-align: center;
        }

        .info-box i {
            font-size: 20px;
            color: #2f9e44;
            margin-bottom: 8px;
            display: block;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #495057;
        }

        .alert-info {
            background: #e7f5ff;
            border: 1px solid #74c0fc;
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const isTutelaInput = document.querySelector('[name="is_tutela"]');
        const textoTiempo = document.getElementById('textoTiempo');

        function actualizarTiempo() {
            if (isTutelaInput && isTutelaInput.checked) {
                textoTiempo.innerHTML =
                    'Las <strong>tutelas</strong> tienen un plazo de <strong>72 horas (3 días)</strong>.';
            } else {
                textoTiempo.innerHTML =
                    'Las <strong>PQR</strong> tienen un plazo de <strong>12 días</strong>.';
            }
        }

        if (isTutelaInput) {
            isTutelaInput.addEventListener('change', actualizarTiempo);
            actualizarTiempo(); // inicial
        }


        const dependencias = @json($dependencias);

        const dependenciaSelect = document.getElementById('dependenciaSelect');
        const conceptoSelect = document.getElementById('conceptoSelect');
        const isTutela = document.getElementById('isTutela');
        const tiempoContainer = document.getElementById('tiempoTutelaContainer');
        const horasTutela = document.getElementById('horasTutela');

        // Cambiar conceptos según dependencia
        dependenciaSelect.addEventListener('change', function() {
            const depId = this.value;

            const dep = dependencias.find(d => d.id_dependencia == depId);

            if (!dep) {
                conceptoSelect.innerHTML = `<option value="">Seleccione una dependencia</option>`;
                return;
            }

            let options = `<option value="">Seleccionar concepto</option>`;

            dep.conceptos.forEach(c => {
                options += `<option value="${c.id_concepto}">${c.name}</option>`;
            });

            conceptoSelect.innerHTML = options;
        });


        // Manejo de tutela
        isTutela.addEventListener('change', function() {

            if (this.checked) {
                tiempoContainer.style.display = 'block';

                // Auto seleccionar Subdirección
                const subdireccion = dependencias.find(d => d.name === 'Subdirección');

                if (subdireccion) {
                    dependenciaSelect.value = subdireccion.id_dependencia;
                    dependenciaSelect.dispatchEvent(new Event('change'));
                }

            } else {
                tiempoContainer.style.display = 'none';
                horasTutela.value = '';
            }

            calcularDias();
        });


        // Cálculo de tiempos
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="date"]');
            const diasInfo = document.getElementById('diasInfo');
            const fechaLimiteSpan = document.getElementById('fechaLimite');
            const diasRestantesSpan = document.getElementById('diasRestantes');
            const estadoColorSpan = document.getElementById('estadoColor');

            function calcularDias() {
                const fecha = dateInput.value;
                if (!fecha) {
                    diasInfo.style.display = 'none';
                    return;
                }

                diasInfo.style.display = 'block';

                const fechaInicio = new Date(fecha);
                let fechaLimite = new Date(fechaInicio);

                if (isTutela.checked && horasTutela.value) {
                    // Tutela → horas
                    const horas = parseInt(horasTutela.value);
                    fechaLimite.setHours(fechaLimite.getHours() + horas);

                    diasRestantesSpan.textContent = `${horas} horas`;
                    diasRestantesSpan.className = 'badge bg-danger';

                    estadoColorSpan.textContent = 'Tutela';
                    estadoColorSpan.className = 'badge bg-danger';

                } else {
                    // Normal → 12 días
                    fechaLimite.setDate(fechaLimite.getDate() + 12);

                    diasRestantesSpan.textContent = '12 días';
                    diasRestantesSpan.className = 'badge bg-success';

                    estadoColorSpan.textContent = 'Nuevo';
                    estadoColorSpan.className = 'badge bg-success';
                }

                const opciones = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                fechaLimiteSpan.textContent = fechaLimite.toLocaleDateString('es-ES', opciones);
            }

            dateInput.addEventListener('change', calcularDias);
            horasTutela.addEventListener('change', calcularDias);

            if (dateInput.value) calcularDias();
        });
    </script>
@endpush
