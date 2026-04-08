{{-- resources/views/Complaint/edit.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Editar PQR')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Editar PQR</h2>
            <p class="text-muted">Modifica la información de la Petición, Queja o Reclamo</p>
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

    <form action="{{ route('pqr.update', $pqr->id) }}" method="POST" id="pqrForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                    <p class="section-subtitle">Datos básicos de la PQR</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Título *</label>
                        <input type="text" name="title" class="form-control modern-input"
                            value="{{ old('title', $pqr->title) }}" required
                            placeholder="Ej: Problema con el servicio de...">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha y hora *</label>
                        <input type="datetime-local" name="date" class="form-control modern-input"
                            value="{{ old('date', \Carbon\Carbon::parse($pqr->date)->format('Y-m-d\TH:i')) }}" required>
                        <small class="text-muted">Fecha y hora de la PQR</small>
                    </div>

                    <div class="form-group mb-3" id="tiempoTutelaContainer"
                        style="{{ $pqr->is_tutela ? 'display:block;' : 'display:none;' }}">
                        <label class="form-label text-success fw-semibold">Tiempo de respuesta (horas) *</label>
                        <input type="number" name="horas_tutela" id="horasTutela" class="form-control modern-input"
                            value="{{ old('horas_tutela', $pqr->horas_tutela) }}" placeholder="Ingresa el tiempo en horas"
                            min="1" step="1">
                        <small class="text-muted">Ingresa el tiempo de respuesta en horas para la tutela.</small>
                    </div>

                    <!-- Dependencia -->
                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia *</label>
                        <select name="dependencia_id" id="dependenciaSelect" class="form-select modern-input" required>
                            <option value="">Seleccionar dependencia</option>
                            @foreach ($dependencias as $dep)
                                <option value="{{ $dep->id_dependencia }}"
                                    {{ optional($pqr->concepto->dependencia)->id_dependencia == $dep->id_dependencia ? 'selected' : '' }}>
                                    {{ $dep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Concepto -->
                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Concepto *</label>
                        <select name="concepto_id" id="conceptoSelect" class="form-select modern-input" required>
                            <option value="">Seleccione una dependencia primero</option>
                            @if ($pqr->concepto)
                                @foreach ($pqr->concepto->dependencia->conceptos as $c)
                                    <option value="{{ $c->id_concepto }}"
                                        {{ $pqr->concepto_id == $c->id_concepto ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">¿Es tutela?</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="isTutela" name="is_tutela" value="1"
                                {{ $pqr->is_tutela ? 'checked' : '' }}>
                            <label class="form-check-label">Sí, es una tutela</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Responsable *</label>
                        <input type="text" name="responsible" class="form-control modern-input"
                            value="{{ old('responsible', $pqr->responsible) }}" required
                            placeholder="Nombre del funcionario responsable">
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
                        <textarea name="description" class="form-control modern-input" rows="8" required
                            placeholder="Describe detalladamente la petición, queja o reclamo...">{{ old('description', $pqr->description) }}</textarea>
                        <small class="text-muted">Sé lo más específico posible para facilitar la gestión.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Documento adjunto (PDF)</label>

                        @if ($pqr->pdf_path)
                            <div class="alert alert-info mb-2">
                                <i class="fas fa-file-pdf"></i>
                                <strong>Archivo actual:</strong>
                                <a href="{{ Storage::url($pqr->pdf_path) }}" target="_blank" class="text-primary">
                                    Ver documento <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endif

                        <input type="file" name="pdf" class="form-control modern-input" accept=".pdf">
                        <small class="text-muted">
                            @if ($pqr->pdf_path)
                                Deja vacío si no deseas cambiar el documento actual.
                            @else
                                Opcional: Adjunta evidencias o documentos de soporte en PDF.
                            @endif
                        </small>
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

            <div id="diasInfo" class="mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-calendar-alt"></i>
                            <strong>Fecha límite:</strong>
                            <span id="fechaLimite">{{ $pqr->deadline_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-hourglass-half"></i>
                            <strong>Días disponibles:</strong>
                            <span id="diasRestantes" class="badge bg-success">{{ $pqr->days_remaining }}
                                día{{ $pqr->days_remaining != 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="fas fa-flag"></i>
                            <strong>Estado:</strong>
                            <span id="estadoColor"
                                class="badge bg-{{ $pqr->status_color }}">{{ $pqr->status_text }}</span>
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
                <i class="fas fa-save"></i> Guardar cambios
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
            font-size: 13px;
        }

        .info-box span {
            font-size: 14px;
            color: #6c757d;
        }

        .alert-info {
            background: #e7f5ff;
            border: 1px solid #74c0fc;
            border-radius: 8px;
        }

        .alert-info a {
            font-weight: 600;
            text-decoration: none;
        }

        .alert-info a:hover {
            text-decoration: underline;
        }

        .badge {
            color: #fff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const dependencias = @json($dependencias);
        const dependenciaSelect = document.getElementById('dependenciaSelect');
        const conceptoSelect = document.getElementById('conceptoSelect');
        const isTutela = document.getElementById('isTutela');
        const tiempoContainer = document.getElementById('tiempoTutelaContainer');
        const horasTutela = document.getElementById('horasTutela');
        const textoTiempo = document.getElementById('textoTiempo');

        function actualizarTiempo() {
            if (isTutela.checked) {
                textoTiempo.innerHTML =
                    'Las <strong>tutelas</strong> tienen un plazo de <strong>72 horas (3 días)</strong>.';
                tiempoContainer.style.display = 'block';
            } else {
                textoTiempo.innerHTML = 'Las <strong>PQR</strong> tienen un plazo de <strong>10 días</strong>.';
                tiempoContainer.style.display = 'none';
                horasTutela.value = '';
            }
        }

        if (isTutela) {
            isTutela.addEventListener('change', function() {
                actualizarTiempo();
                // Auto seleccionar Subdirección si existe
                const subdireccion = dependencias.find(d => d.name === 'Subdirección');
                if (this.checked && subdireccion) {
                    dependenciaSelect.value = subdireccion.id_dependencia;
                    dependenciaSelect.dispatchEvent(new Event('change'));
                }
            });
            actualizarTiempo();
        }

        dependenciaSelect.addEventListener('change', function() {
            const depId = this.value;
            const dep = dependencias.find(d => d.id_dependencia == depId);
            if (!dep) {
                conceptoSelect.innerHTML = `<option value="">Seleccione una dependencia</option>`;
                return;
            }
            let options = `<option value="">Seleccionar concepto</option>`;
            dep.conceptos.forEach(c => {
                options +=
                    `<option value="${c.id_concepto}" ${c.id_concepto == {{ $pqr->concepto_id }} ? 'selected' : ''}>${c.name}</option>`;
            });
            conceptoSelect.innerHTML = options;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="date"]');
            const diasRestantesSpan = document.getElementById('diasRestantes');
            const estadoColorSpan = document.getElementById('estadoColor');
            const fechaLimiteSpan = document.getElementById('fechaLimite');

            function calcularDias() {
                const fecha = dateInput.value;
                if (!fecha) return;
                const fechaInicio = new Date(fecha);
                const fechaLimite = new Date(fechaInicio);

                if (isTutela.checked && horasTutela.value) {
                    fechaLimite.setHours(fechaLimite.getHours() + parseInt(horasTutela.value));
                    diasRestantesSpan.textContent = `${horasTutela.value} horas`;
                    diasRestantesSpan.className = 'badge bg-danger';
                    estadoColorSpan.textContent = 'Tutela';
                    estadoColorSpan.className = 'badge bg-danger';
                } else {
                    const PQR_DIAS = 10; // puedes definirlo arriba
                    fechaLimite.setDate(fechaLimite.getDate() + PQR_DIAS);
                    diasRestantesSpan.textContent = `${PQR_DIAS} días`;
                    diasRestantesSpan.className = 'badge bg-success';
                    estadoColorSpan.textContent = 'Nuevo';
                    estadoColorSpan.className = 'badge bg-success';
                }

                fechaLimiteSpan.textContent = fechaLimite.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            dateInput.addEventListener('change', calcularDias);
            horasTutela.addEventListener('change', calcularDias);
            if (dateInput.value) calcularDias();
        });
    </script>
@endpush
