@extends('layouts.dashboard')

@section('page-title', 'Editar Necesidad de Infraestructura')

@section('dashboard-content')

<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Editar Necesidad #{{ $infraestructura->id }}</h2>
        <p class="text-muted">Actualiza los datos de la necesidad de infraestructura</p>
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

<form action="{{ route('infraestructura.update', $infraestructura) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div class="col-md-7">

            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-id-badge"></i> Información General</h5>
                <p class="section-subtitle">Unidad y funcionario responsable de la solicitud</p>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Unidad Responsable <span class="text-danger">*</span></label>
                    <select name="unidad_id" id="unidadSelect" class="form-select modern-input" required>
                        <option value="">Seleccione una unidad</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->dependency_unit_id }}"
                                {{ $infraestructura->unidad_id == $unit->dependency_unit_id ? 'selected' : '' }}>
                                {{ $unit->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Subunidad Responsable <span class="text-danger">*</span></label>
                    <select name="subunidad_id" id="subunidadSelect" class="form-select modern-input" required>
                        <option value="">Seleccione una subunidad</option>
                        @foreach ($units as $unit)
                            @foreach ($unit->subunits as $sub)
                                <option value="{{ $sub->subunit_id }}"
                                    {{ $infraestructura->subunidad_id == $sub->subunit_id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label text-success fw-semibold">Funcionario Responsable <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select modern-input" required>
                        <option value="">Seleccione un funcionario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $infraestructura->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Ubicación</h5>
                <p class="section-subtitle">Centro, sede, área y ambiente donde se presenta la necesidad</p>

                <x-centros-sedes-selector
                    :centros="$centros"
                    prefix="inicial"
                    :centro-id="$infraestructura->centro_id"
                    :sede-id="$infraestructura->sede_id"
                    :centro-nombre="$infraestructura->centro->nom_centro ?? ''"
                    :sede-nombre="$infraestructura->sede->nom_sede ?? ''" />

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Área <span class="text-danger">*</span></label>
                    <select name="area_id" id="areaSelect" class="form-select modern-input" required>
                        <option value="">Seleccione un área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ $infraestructura->area_necesidad == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label text-success fw-semibold">Ambiente / Salón <span class="text-danger">*</span></label>
                    <select name="ambiente" id="roomSelect" class="form-select modern-input" required>
                        <option value="">Seleccione un ambiente</option>
                        @if($infraestructura->room)
                            <option value="{{ $infraestructura->room->id }}" selected>{{ $infraestructura->room->name }}</option>
                        @endif
                    </select>
                </div>
            </div>

        </div>

        {{-- ─── COLUMNA DERECHA ─── --}}
        <div class="col-md-5">

            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-clipboard-check"></i> Características de la Necesidad</h5>
                <p class="section-subtitle">Clasifica la necesidad según su tipo y nivel de urgencia</p>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Motivo de la Necesidad <span class="text-danger">*</span></label>
                    <select name="motivo_necesidad" class="form-select modern-input" required>
                        <option value="">Seleccione el motivo</option>
                        @foreach(['Falla de equipo','Actualización de infraestructura','Cumplimiento normativo','Solicitud de usuario','Mantenimiento preventivo programado','Emergencia / urgencia'] as $motivo)
                            <option value="{{ $motivo }}" {{ $infraestructura->motivo_necesidad == $motivo ? 'selected' : '' }}>{{ $motivo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Tipo de Necesidad <span class="text-danger">*</span></label>
                    <select name="tipo_necesidad" class="form-select modern-input" required>
                        <option value="">Seleccione el tipo</option>
                        @foreach(['Reparación de instalaciones','Instalación de nuevo equipamiento','Mantenimiento preventivo','Reemplazo de componentes','Mejora de infraestructura'] as $tipo)
                            <option value="{{ $tipo }}" {{ $infraestructura->tipo_necesidad == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Riesgo <span class="text-danger">*</span></label>
                        <select name="nivel_riesgo" class="form-select modern-input" required>
                            <option value="1" {{ $infraestructura->nivel_riesgo == 1 ? 'selected' : '' }}>🟢 Bajo</option>
                            <option value="2" {{ $infraestructura->nivel_riesgo == 2 ? 'selected' : '' }}>🟡 Medio</option>
                            <option value="3" {{ $infraestructura->nivel_riesgo == 3 ? 'selected' : '' }}>🔴 Alto</option>
                        </select>
                        <small class="text-muted">Impacto en seguridad</small>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Complejidad <span class="text-danger">*</span></label>
                        <select name="nivel_complejidad" class="form-select modern-input" required>
                            <option value="1" {{ $infraestructura->nivel_complejidad == 1 ? 'selected' : '' }}>Bajo</option>
                            <option value="2" {{ $infraestructura->nivel_complejidad == 2 ? 'selected' : '' }}>Medio</option>
                            <option value="3" {{ $infraestructura->nivel_complejidad == 3 ? 'selected' : '' }}>Alto</option>
                        </select>
                        <small class="text-muted">Recursos requeridos</small>
                    </div>
                </div>

                <div class="toggle-box">
                    <input type="hidden" name="requiere_traslado" value="0">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="requiere_traslado" value="1"
                            id="requiereTraslado" {{ $infraestructura->requiere_traslado ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="requiereTraslado">
                            ¿Requiere traslado de equipos o personal?
                        </label>
                    </div>
                </div>
            </div>

            <div id="trasladoDestinos" style="{{ $infraestructura->requiere_traslado ? '' : 'display:none;' }}">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-map-pin"></i> Destino del Traslado</h5>
                    <p class="section-subtitle">Centro y sede de destino para el traslado</p>
                    <x-centros-sedes-selector :centros="$centros" prefix="final" :required="false"
                        :centro-id="$infraestructura->centro_final_id"
                        :sede-id="$infraestructura->sede_final_id"
                        :centro-nombre="$infraestructura->centroFinal->nom_centro ?? ''"
                        :sede-nombre="$infraestructura->sedeFinal->nom_sede ?? ''" />
                </div>
            </div>

        </div>
    </div>

    <div class="content-card mb-4">
        <h5 class="section-title"><i class="fas fa-file-alt"></i> Detalles de la Necesidad</h5>
        <p class="section-subtitle">Adjunta evidencia fotográfica y describe con detalle la situación</p>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label text-success fw-semibold">Evidencia Fotográfica</label>
                <input type="file" name="imagen" class="form-control modern-input" id="imagenInput" accept="image/*">
                <small class="text-muted">Sube una nueva imagen para reemplazar la existente</small>
                <div id="imagenPreview" class="mt-3">
                    @if($infraestructura->imagen)
                        <img src="{{ asset('storage/' . $infraestructura->imagen) }}" alt="Imagen actual"
                            style="max-width:100%;max-height:200px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.1);object-fit:cover;">
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-success fw-semibold">Descripción Detallada <span class="text-danger">*</span></label>
                <textarea name="descripcion" rows="6" class="form-control modern-input" required
                    placeholder="Describa con detalle la necesidad de infraestructura...">{{ old('descripcion', $infraestructura->descripcion) }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('infraestructura.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
            <i class="fas fa-times"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-save"></i> Actualizar Necesidad
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
    .content-card { background:#fff; padding:24px 28px; border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.07); border:1px solid #f0fdf4; transition:box-shadow .25s ease; }
    .content-card:hover { box-shadow:0 8px 22px rgba(0,0,0,.13); }
    .section-title { font-size:15px; font-weight:700; color:#16a34a; margin-bottom:4px; }
    .section-subtitle { font-size:12.5px; color:#9ca3af; margin-bottom:20px; }
    .modern-input { border-radius:8px !important; border:1.5px solid #e5e7eb !important; padding:10px 14px !important; font-size:14px !important; transition:border-color .2s, box-shadow .2s; width:100%; }
    .modern-input:focus { border-color:#22c55e !important; box-shadow:0 0 0 3px rgba(34,197,94,.12) !important; outline:none !important; }
    .toggle-box { background:#f0fdf4; border:1.5px solid #d1fae5; border-radius:10px; padding:14px 16px; }
    .toggle-box .form-check-input:checked { background-color:#16a34a; border-color:#16a34a; }
    .btn-success { background:linear-gradient(135deg,#16a34a,#22c55e) !important; border:none !important; }
    .btn-success:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(22,163,74,.4) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const unitsData = @json($units->mapWithKeys(fn($u) => [$u->dependency_unit_id => $u->subunits]));

    document.getElementById('unidadSelect').addEventListener('change', function () {
        const uid = this.value;
        const sub = document.getElementById('subunidadSelect');
        sub.innerHTML = '<option value="">Seleccione una subunidad</option>';
        if (uid && unitsData[uid]) {
            unitsData[uid].forEach(s => {
                const o = document.createElement('option');
                o.value = s.subunit_id; o.textContent = s.name;
                sub.appendChild(o);
            });
        }
    });

    document.getElementById('requiereTraslado').addEventListener('change', function () {
        document.getElementById('trasladoDestinos').style.display = this.checked ? 'block' : 'none';
    });

    // Imagen preview
    document.getElementById('imagenInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagenPreview');
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" style="max-width:100%;max-height:200px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.1);">`; };
            reader.readAsDataURL(file);
        }
    });

    // Área → Rooms (load rooms for pre-selected area)
    const areaSelect = document.getElementById('areaSelect');
    const roomSelect = document.getElementById('roomSelect');
    const currentRoom = '{{ $infraestructura->ambiente }}';

    function loadRooms(areaId, preselect = null) {
        if (!areaId) { roomSelect.innerHTML = '<option value="">Seleccione un ambiente</option>'; return; }
        fetch(`/areas/${areaId}/rooms`)
            .then(r => r.json())
            .then(data => {
                roomSelect.innerHTML = '<option value="">Seleccione un ambiente</option>';
                data.forEach(r => {
                    const o = document.createElement('option');
                    o.value = r.id; o.textContent = r.name;
                    if (preselect && String(r.id) === String(preselect)) o.selected = true;
                    roomSelect.appendChild(o);
                });
            });
    }

    // On area change
    areaSelect.addEventListener('change', function () { loadRooms(this.value); });

    // On page load: load rooms for pre-selected area
    if (areaSelect.value) { loadRooms(areaSelect.value, currentRoom); }

    // Centros/Sedes modal handlers
    window.openModal  = id => { document.getElementById(id).classList.add('show'); document.body.classList.add('modal-open'); };
    window.closeModal = id => { document.getElementById(id).classList.remove('show'); document.body.classList.remove('modal-open'); };
    window.openModalSede = prefix => {
        if (!document.getElementById(prefix + '_centro_id').value) { alert('Primero debe seleccionar un centro'); return; }
        openModal(prefix + '_sedeModal');
    };

    document.querySelectorAll('.seleccionar-centro').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            const p = this.dataset.prefix;
            document.getElementById(p + '_centro_id').value = this.dataset.id;
            document.getElementById(p + '_centroSeleccionado').value = this.dataset.nombre;
            const sedeEl = document.getElementById(p + '_sedeSeleccionada');
            document.getElementById(p + '_sede_id').value = '';
            sedeEl.value = ''; sedeEl.placeholder = 'Cargando sedes...'; sedeEl.disabled = true;
            closeModal(p + '_centroModal');
            fetch(`/centros/${this.dataset.id}/sedes`).then(r => r.json()).then(sedes => {
                const lista = document.getElementById(p + '_listaSedes');
                lista.innerHTML = '';
                sedes.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.nom_sede}</td><td><button type="button" class="seleccionar-sede" data-id="${s.id}" data-nombre="${s.nom_sede}" data-prefix="${p}">Seleccionar</button></td>`;
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
        }
    });
});
</script>
@endpush
