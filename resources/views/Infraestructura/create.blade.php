@extends('layouts.dashboard')

@section('page-title', 'Nueva Necesidad de Infraestructura')

@section('dashboard-content')

<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Nueva Necesidad de Infraestructura</h2>
        <p class="text-muted">Registra una solicitud o necesidad de infraestructura física</p>
    </div>
    <a href="{{ route('infraestructura.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger shadow-sm rounded mb-4">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('infraestructura.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div class="col-md-7">

            {{-- Información General --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-id-badge"></i> Información General</h5>
                <p class="section-subtitle">Unidad y funcionario responsable de la solicitud</p>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Unidad Responsable <span class="text-danger">*</span></label>
                    <select name="unidad_id" id="unidadSelect" class="form-select modern-input" required>
                        <option value="">Seleccione una unidad</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->dependency_unit_id }}">{{ $unit->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Subunidad Responsable <span class="text-danger">*</span></label>
                    <select name="subunidad_id" id="subunidadSelect" class="form-select modern-input" required>
                        <option value="">Seleccione una subunidad</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label text-success fw-semibold">Funcionario Responsable <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select modern-input" required>
                        <option value="">Seleccione un funcionario</option>
                        @foreach ($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación</h5>
                <p class="section-subtitle">Centro, sede, área y ambiente donde se presenta la necesidad</p>

                <x-centros-sedes-selector :centros="$centros" prefix="inicial" />

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Área <span class="text-danger">*</span></label>
                    <select name="area_id" id="areaSelect" class="form-select modern-input" required>
                        <option value="">Seleccione un área</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label text-success fw-semibold">Ambiente / Salón <span class="text-danger">*</span></label>
                    <select name="ambiente" id="roomSelect" class="form-select modern-input" required>
                        <option value="">Seleccione un ambiente</option>
                    </select>
                </div>
            </div>

        </div>

        {{-- ─── COLUMNA DERECHA ─── --}}
        <div class="col-md-5">

            {{-- Características --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-clipboard-check"></i> Características de la Necesidad</h5>
                <p class="section-subtitle">Clasifica la necesidad según su tipo y nivel de urgencia</p>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Motivo de la Necesidad <span class="text-danger">*</span></label>
                    <select name="motivo_necesidad" class="form-select modern-input" required>
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
                    <label class="form-label text-success fw-semibold">Tipo de Necesidad <span class="text-danger">*</span></label>
                    <select name="tipo_necesidad" class="form-select modern-input" required>
                        <option value="">Seleccione el tipo</option>
                        <option value="Reparación de instalaciones">Reparación de instalaciones</option>
                        <option value="Instalación de nuevo equipamiento">Instalación de nuevo equipamiento</option>
                        <option value="Mantenimiento preventivo">Mantenimiento preventivo</option>
                        <option value="Reemplazo de componentes">Reemplazo de componentes</option>
                        <option value="Mejora de infraestructura">Mejora de infraestructura</option>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Riesgo <span class="text-danger">*</span></label>
                        <select name="nivel_riesgo" class="form-select modern-input" required>
                            <option value="">Seleccione</option>
                            <option value="1">🟢 Bajo</option>
                            <option value="2">🟡 Medio</option>
                            <option value="3">🔴 Alto</option>
                        </select>
                        <small class="text-muted">Impacto en seguridad</small>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Complejidad <span class="text-danger">*</span></label>
                        <select name="nivel_complejidad" class="form-select modern-input" required>
                            <option value="">Seleccione</option>
                            <option value="1">Bajo</option>
                            <option value="2">Medio</option>
                            <option value="3">Alto</option>
                        </select>
                        <small class="text-muted">Recursos requeridos</small>
                    </div>
                </div>

                <div class="toggle-box">
                    <input type="hidden" name="requiere_traslado" value="0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="requiere_traslado" value="1" id="requiereTraslado">
                        <label class="form-check-label fw-semibold" for="requiereTraslado">
                            ¿Requiere traslado de equipos o personal?
                        </label>
                    </div>
                </div>
            </div>

            {{-- Destino del Traslado (condicional) --}}
            <div id="trasladoDestinos" style="display:none;">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-map-pin"></i> Destino del Traslado</h5>
                    <p class="section-subtitle">Centro y sede de destino para el traslado</p>
                    <x-centros-sedes-selector :centros="$centros" prefix="final" :required="false" />
                </div>
            </div>

        </div>
    </div>

    {{-- Detalles de la Necesidad --}}
    <div class="content-card mb-4">
        <h5 class="section-title"><i class="fas fa-file-alt"></i> Detalles de la Necesidad</h5>
        <p class="section-subtitle">Adjunta evidencia fotográfica y describe con detalle la situación</p>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label text-success fw-semibold">Evidencia Fotográfica</label>
                <input type="file" name="imagen" class="form-control modern-input" id="imagenInput" accept="image/*">
                <small class="text-muted">Adjunte una imagen que evidencie la necesidad</small>
                <div id="imagenPreview" class="mt-3"></div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-success fw-semibold">Descripción Detallada <span class="text-danger">*</span></label>
                <textarea name="descripcion" rows="6" class="form-control modern-input" required
                    placeholder="Describa con detalle la necesidad de infraestructura..."></textarea>
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('infraestructura.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
            <i class="fas fa-times"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-save"></i> Guardar Necesidad
        </button>
    </div>
</form>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .section-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0; }
    .section-header h2 { font-size:24px; font-weight:700; color:#111827; margin:0; }
    .section-header p { font-size:13px; color:#6b7280; margin:4px 0 0; }

    .content-card {
        background:#fff;
        padding:24px 28px;
        border-radius:14px;
        box-shadow:0 2px 10px rgba(0,0,0,.07);
        border:1px solid #f0fdf4;
        transition:all .25s ease;
    }
    .content-card:hover { box-shadow:0 8px 22px rgba(0,0,0,.13); }

    .section-title { font-size:15px; font-weight:700; color:#16a34a; margin-bottom:4px; }
    .section-subtitle { font-size:12.5px; color:#9ca3af; margin-bottom:20px; }

    .modern-input {
        border-radius:8px !important;
        border:1.5px solid #e5e7eb !important;
        padding:10px 14px !important;
        font-size:14px !important;
        transition:border-color .2s, box-shadow .2s;
        width:100%;
    }
    .modern-input:focus {
        border-color:#22c55e !important;
        box-shadow:0 0 0 3px rgba(34,197,94,.12) !important;
        outline:none !important;
    }

    .toggle-box {
        background:#f0fdf4;
        border:1.5px solid #d1fae5;
        border-radius:10px;
        padding:14px 16px;
    }
    .toggle-box .form-check-input:checked { background-color:#16a34a; border-color:#16a34a; }

    #imagenPreview img {
        max-width:100%; max-height:200px;
        border-radius:10px;
        box-shadow:0 2px 8px rgba(0,0,0,.1);
        object-fit:cover;
    }

    .btn-success { background:linear-gradient(135deg,#16a34a,#22c55e) !important; border:none !important; }
    .btn-success:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(22,163,74,.4) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Imagen: Vista previa
    document.getElementById('imagenInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagenPreview');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`; };
            reader.readAsDataURL(file);
        } else { preview.innerHTML = ''; }
    });

    // 2. Unidades → Subunidades
    const unitsData = @json($units->mapWithKeys(fn($u) => [$u->dependency_unit_id => $u->subunits]));
    const unidadSelect = document.getElementById('unidadSelect');
    const subunidadSelect = document.getElementById('subunidadSelect');

    unidadSelect.addEventListener('change', function () {
        const uid = this.value;
        subunidadSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';
        if (uid && unitsData[uid]) {
            unitsData[uid].forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.subunit_id; opt.textContent = s.name;
                subunidadSelect.appendChild(opt);
            });
        }
    });

    // 3. Toggle traslado destinos
    document.getElementById('requiereTraslado').addEventListener('change', function () {
        document.getElementById('trasladoDestinos').style.display = this.checked ? 'block' : 'none';
    });

    // 4. Centro → Sede → Área
    const centroInput = document.getElementById('inicial_centro_id');
    const sedeInput   = document.getElementById('inicial_sede_id');
    const areaSelect  = document.getElementById('areaSelect');
    const roomSelect  = document.getElementById('roomSelect');

    sedeInput.addEventListener('input', function () {
        const centroId = centroInput.value, sedeId = this.value;
        areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
        roomSelect.innerHTML = '<option value="">Seleccione un ambiente</option>';
        if (!centroId || !sedeId) return;

        fetch(`/centros/${centroId}/sedes-centro?sede_id=${sedeId}`)
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(data => {
                areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                if (!data?.length) { areaSelect.innerHTML = '<option value="">No hay áreas disponibles</option>'; return; }
                data.forEach(a => { const o = document.createElement('option'); o.value = a.id; o.textContent = a.name; areaSelect.appendChild(o); });
            })
            .catch(() => { areaSelect.innerHTML = '<option value="">Error cargando áreas</option>'; });
    });

    // 5. Área → Rooms
    areaSelect.addEventListener('change', function () {
        const areaId = this.value;
        roomSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!areaId) { roomSelect.innerHTML = '<option value="">Seleccione un ambiente</option>'; return; }

        fetch(`/areas/${areaId}/rooms`)
            .then(r => r.json())
            .then(data => {
                roomSelect.innerHTML = '<option value="">Seleccione un ambiente</option>';
                if (!data?.length) { roomSelect.innerHTML = '<option value="">No hay ambientes disponibles</option>'; return; }
                data.forEach(r => { const o = document.createElement('option'); o.value = r.id; o.textContent = r.name; roomSelect.appendChild(o); });
            })
            .catch(() => { roomSelect.innerHTML = '<option value="">Error cargando ambientes</option>'; });
    });

    // 6. Centros/Sedes modal handlers
    window.openModal  = id => { document.getElementById(id).classList.add('show'); document.body.classList.add('modal-open'); };
    window.closeModal = id => { document.getElementById(id).classList.remove('show'); document.body.classList.remove('modal-open'); };
    window.openModalSede = prefix => {
        if (!document.getElementById(prefix + '_centro_id').value) { alert('Primero debe seleccionar un centro'); return; }
        openModal(prefix + '_sedeModal');
    };

    document.querySelectorAll('.seleccionar-centro').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            const prefix = this.dataset.prefix;
            document.getElementById(prefix + '_centro_id').value = this.dataset.id;
            document.getElementById(prefix + '_centroSeleccionado').value = this.dataset.nombre;
            const sedeEl = document.getElementById(prefix + '_sedeSeleccionada');
            document.getElementById(prefix + '_sede_id').value = '';
            sedeEl.value = ''; sedeEl.placeholder = 'Cargando sedes...'; sedeEl.disabled = true;
            closeModal(prefix + '_centroModal');

            fetch(`/centros/${this.dataset.id}/sedes`).then(r => r.json()).then(sedes => {
                const lista = document.getElementById(prefix + '_listaSedes');
                lista.innerHTML = '';
                sedes.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.nom_sede}</td><td><button type="button" class="seleccionar-sede" data-id="${s.id}" data-nombre="${s.nom_sede}" data-prefix="${prefix}">Seleccionar</button></td>`;
                    lista.appendChild(tr);
                });
                sedeEl.placeholder = 'Seleccione una sede...'; sedeEl.disabled = false;
            });
        });
    });

    document.body.addEventListener('click', e => {
        if (e.target?.classList.contains('seleccionar-sede')) {
            const p = e.target.dataset.prefix;
            document.getElementById(p + '_sede_id').value = e.target.dataset.id;
            document.getElementById(p + '_sedeSeleccionada').value = e.target.dataset.nombre;
            closeModal(p + '_sedeModal');
            document.getElementById(p + '_sede_id').dispatchEvent(new Event('input'));
        }
    });
});
</script>
@endpush
