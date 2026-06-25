@extends('layouts.dashboard')

@section('page-title', 'Crear Traslado')

@section('dashboard-content')

<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Crear Traslado</h2>
        <p class="text-muted">Registra una necesidad de traslado de personal o materiales</p>
    </div>
    <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary shadow-sm">
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

<form action="{{ route('traslados.store') }}" method="POST" id="trasladoForm">
    @csrf

    <div class="row g-4">

        {{-- ─── COLUMNA IZQUIERDA ─── --}}
        <div class="col-md-6">

            {{-- Información General --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-id-badge"></i> Información General</h5>
                <p class="section-subtitle">Unidad y funcionario responsable del traslado</p>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Unidad Responsable <span class="text-danger">*</span></label>
                    <select name="unidad_id" id="unidadSelect" class="form-select modern-input" required>
                        <option value="">Seleccione una unidad</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->dependency_unit_id }}">{{ $unit->short_name }}</option>
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
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Origen del Traslado --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Origen del Traslado</h5>
                <p class="section-subtitle">Centro y sede desde donde se origina el traslado</p>
                <x-centros-sedes-selector :centros="$centros" prefix="inicial" />
            </div>

            {{-- Destino del Traslado --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-map-pin"></i> Destino del Traslado</h5>
                <p class="section-subtitle">Centro y sede a donde se dirigirá el traslado</p>
                <x-centros-sedes-selector :centros="$centros" prefix="final" />
            </div>

        </div>

        {{-- ─── COLUMNA DERECHA ─── --}}
        <div class="col-md-6">

            {{-- Características --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-clipboard-check"></i> Características de la Necesidad</h5>
                <p class="section-subtitle">Clasifica el traslado según su nivel de riesgo y complejidad</p>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Riesgo</label>
                        <select name="nivel_riesgo" class="form-select modern-input">
                            <option value="1">🟢 Bajo</option>
                            <option value="2">🟡 Medio</option>
                            <option value="3">🔴 Alto</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Nivel de Complejidad</label>
                        <select name="nivel_complejidad" class="form-select modern-input">
                            <option value="1">Bajo</option>
                            <option value="2">Medio</option>
                            <option value="3">Alto</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Presupuesto Solicitado</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:#e5e7eb;background:#f9fafb;font-size:13px;">$</span>
                        <input type="number" name="presupuesto_solicitado" class="form-control modern-input" placeholder="0.00" min="0" step="0.01">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-success fw-semibold">Presupuesto Aceptado</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:#e5e7eb;background:#f9fafb;font-size:13px;">$</span>
                        <input type="number" name="presupuesto_aceptado" class="form-control modern-input" placeholder="Se definirá tras aprobación" disabled>
                    </div>
                    <small class="text-muted">Se completará una vez aprobado el traslado</small>
                </div>

                <div class="toggle-box mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="chk-personas" name="requiere_personal" value="1">
                        <label class="form-check-label fw-semibold" for="chk-personas">
                            <i class="fas fa-users me-1 text-success"></i> Requiere trasladar personas
                        </label>
                    </div>
                </div>

                <div class="toggle-box">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="chk-materiales" name="requiere_materiales" value="1">
                        <label class="form-check-label fw-semibold" for="chk-materiales">
                            <i class="fas fa-boxes me-1 text-success"></i> Requiere trasladar materiales
                        </label>
                    </div>
                </div>
            </div>

            {{-- Fechas y Descripción --}}
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-calendar-alt"></i> Fechas y Descripción</h5>
                <p class="section-subtitle">Período estimado y descripción detallada del traslado</p>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Fecha de Inicio <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_inicio" class="form-control modern-input" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-success fw-semibold">Fecha de Finalización <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_fin" class="form-control modern-input" required>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label text-success fw-semibold">Descripción <span class="text-danger">*</span></label>
                    <textarea name="descripcion" class="form-control modern-input" rows="5"
                        placeholder="Describe los detalles y justificación del traslado..." required></textarea>
                </div>
            </div>

        </div>
    </div>

    {{-- Personal (condicional) --}}
    <div id="personas-section" style="display:none;" class="mb-4">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="section-title mb-1"><i class="fas fa-users"></i> Personal Necesitado</h5>
                    <p class="section-subtitle mb-0">Agrega las personas que participarán en el traslado</p>
                </div>
                <button type="button" class="btn btn-success shadow-sm" onclick="agregarPersona()">
                    <i class="fas fa-user-plus"></i> Agregar Persona
                </button>
            </div>
            <div class="table-responsive">
                <table class="sg-table" id="personasTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Nombre</th>
                            <th><i class="fas fa-id-card"></i> Documento</th>
                            <th><i class="fas fa-briefcase"></i> Cargo</th>
                            <th style="width:80px;"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Materiales (condicional) --}}
    <div id="materiales-section" style="display:none;" class="mb-4">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="section-title mb-1"><i class="fas fa-boxes"></i> Materiales Necesitados</h5>
                    <p class="section-subtitle mb-0">Selecciona los materiales del inventario que se trasladarán</p>
                </div>
                <button type="button" class="btn btn-success shadow-sm" onclick="abrirModalMateriales()">
                    <i class="fas fa-plus"></i> Agregar Material
                </button>
            </div>
            <div class="table-responsive">
                <table class="sg-table" id="materialesTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box"></i> Nombre Material</th>
                            <th><i class="fas fa-tag"></i> Tipo</th>
                            <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                            <th style="width:80px;"><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Botones --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('traslados.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
            <i class="fas fa-times"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-save"></i> Guardar Traslado
        </button>
    </div>
</form>

{{-- ─── Modal: Buscar Materiales ─── --}}
<div class="modal fade" id="modalMateriales" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom:1.5px solid #d1fae5;">
                <h5 class="modal-title" style="color:#16a34a;font-weight:700;">
                    <i class="fas fa-search me-2"></i>Buscar Material del Inventario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 position-relative">
                    <i class="fas fa-search position-absolute" style="top:50%;left:14px;transform:translateY(-50%);color:#9ca3af;"></i>
                    <input type="text" id="searchMaterial" class="form-control modern-input" placeholder="Buscar por nombre o tipo de material..."
                        style="padding-left:40px !important;">
                </div>
                <div class="table-responsive">
                    <table class="sg-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-box"></i> Material</th>
                                <th><i class="fas fa-tag"></i> Tipo</th>
                                <th><i class="fas fa-layer-group"></i> Cantidad</th>
                                <th><i class="fas fa-map-marker-alt"></i> Sede</th>
                                <th><i class="fas fa-check"></i> Acción</th>
                            </tr>
                        </thead>
                        <tbody id="materialesModalBody">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="paginationContainer" class="mt-3 d-flex justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

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
        padding:13px 16px;
    }
    .toggle-box .form-check-input:checked { background-color:#16a34a; border-color:#16a34a; }

    .btn-success { background:linear-gradient(135deg,#16a34a,#22c55e) !important; border:none !important; }
    .btn-success:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(22,163,74,.4) !important; }

    /* Inputs in dynamic persona rows */
    #personasTable .form-control { border-radius:7px; border:1.5px solid #e5e7eb; padding:7px 10px; font-size:13px; }
    #personasTable .form-control:focus { border-color:#22c55e; box-shadow:0 0 0 3px rgba(34,197,94,.12); outline:none; }

    .input-group .input-group-text { border-radius:8px 0 0 8px !important; border:1.5px solid #e5e7eb; }
    .input-group .modern-input { border-radius:0 8px 8px 0 !important; border-left:none !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let personaIndex = 0;
    let materialIndex = 0;
    let materialesSeleccionados = [];
    let searchTimeout;

    // Toggle secciones condicionales
    function toggleSections() {
        document.getElementById('personas-section').style.display   = document.getElementById('chk-personas').checked ? 'block' : 'none';
        document.getElementById('materiales-section').style.display = document.getElementById('chk-materiales').checked ? 'block' : 'none';
    }
    document.getElementById('chk-personas').addEventListener('change', toggleSections);
    document.getElementById('chk-materiales').addEventListener('change', toggleSections);
    toggleSections();

    // Agregar fila de persona
    function agregarPersona() {
        const tbody = document.querySelector('#personasTable tbody');
        const row   = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="personas[${personaIndex}][nombre]" class="form-control" placeholder="Nombre completo" required></td>
            <td><input type="text" name="personas[${personaIndex}][documento]" class="form-control" placeholder="Documento" required></td>
            <td><input type="text" name="personas[${personaIndex}][cargo]" class="form-control" placeholder="Cargo"></td>
            <td><button type="button" class="sg-btn sg-btn-danger" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button></td>`;
        tbody.appendChild(row);
        personaIndex++;
    }

    // Abrir modal de materiales
    function abrirModalMateriales() {
        const modal = new bootstrap.Modal(document.getElementById('modalMateriales'));
        modal.show();
        cargarMateriales();
    }

    function cargarMateriales(page = 1, search = '') {
        const tbody = document.getElementById('materialesModalBody');
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4">
            <div class="spinner-border text-success" role="status"><span class="visually-hidden">Cargando...</span></div>
        </td></tr>`;

        fetch(`{{ route('traslados.buscar-materiales') }}?page=${page}&search=${encodeURIComponent(search)}`)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                if (!data.data?.length) {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5" style="color:#9ca3af;">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No se encontraron materiales
                    </td></tr>`;
                    return;
                }
                data.data.forEach(mat => {
                    const ya  = materialesSeleccionados.includes(mat.id);
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><strong>${mat.material_name}</strong></td>
                        <td><span class="sg-badge sg-badge-blue">${mat.material_type || 'N/A'}</span></td>
                        <td><span class="sg-badge sg-badge-green">${mat.material_quantity} uds.</span></td>
                        <td style="font-size:12px;color:#6b7280;">${mat.inventory?.sede?.nom_sede || 'N/A'}</td>
                        <td>${ya
                            ? '<span class="sg-badge sg-badge-green"><i class="fas fa-check me-1"></i>Seleccionado</span>'
                            : `<button type="button" class="sg-btn sg-btn-primary" style="font-size:12px;padding:6px 12px;" onclick="seleccionarMaterial(event,${mat.id},'${mat.material_name.replace(/'/g,"\\'")}','${mat.material_type||'N/A'}')"><i class="fas fa-plus"></i> Seleccionar</button>`
                        }</td>`;
                    tbody.appendChild(row);
                });
                renderizarPaginacion(data);
            })
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle me-2"></i>Error al cargar materiales</td></tr>`;
            });
    }

    function seleccionarMaterial(event, id, nombre, tipo) {
        event.stopPropagation();
        if (materialesSeleccionados.includes(id)) { mostrarNotificacion('Este material ya fue agregado', 'warning'); return; }

        const tbody = document.querySelector('#materialesTable tbody');
        const row   = document.createElement('tr');
        row.setAttribute('data-material-id', id);
        row.innerHTML = `
            <td><strong>${nombre}</strong><input type="hidden" name="materiales[${materialIndex}][inventory_material_id]" value="${id}"></td>
            <td><span class="sg-badge sg-badge-blue">${tipo}</span></td>
            <td><input type="number" name="materiales[${materialIndex}][cantidad]" class="form-control" placeholder="0" min="1" required style="width:100px;border-radius:7px;border:1.5px solid #e5e7eb;padding:6px 10px;font-size:13px;"></td>
            <td><button type="button" class="sg-btn sg-btn-danger" onclick="eliminarMaterial(this,${id})"><i class="fas fa-trash"></i></button></td>`;
        tbody.appendChild(row);
        materialIndex++;
        materialesSeleccionados.push(id);
        bootstrap.Modal.getInstance(document.getElementById('modalMateriales')).hide();
        mostrarNotificacion('Material agregado correctamente', 'success');
    }

    function eliminarMaterial(button, materialId) {
        materialesSeleccionados = materialesSeleccionados.filter(x => x !== materialId);
        button.closest('tr').remove();
    }

    document.getElementById('searchMaterial').addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => cargarMateriales(1, e.target.value), 500);
    });

    function renderizarPaginacion(data) {
        const container = document.getElementById('paginationContainer');
        container.innerHTML = '';
        if (data.last_page <= 1) return;
        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';

        const prev = document.createElement('li');
        prev.className = `page-item ${data.current_page===1?'disabled':''}`;
        prev.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();cargarMateriales(${data.current_page-1},document.getElementById('searchMaterial').value)">Anterior</a>`;
        ul.appendChild(prev);

        for (let i = 1; i <= data.last_page; i++) {
            if (i===1 || i===data.last_page || (i>=data.current_page-1 && i<=data.current_page+1)) {
                const li = document.createElement('li');
                li.className = `page-item ${i===data.current_page?'active':''}`;
                li.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();cargarMateriales(${i},document.getElementById('searchMaterial').value)">${i}</a>`;
                ul.appendChild(li);
            } else if (i===data.current_page-2 || i===data.current_page+2) {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link">...</span>';
                ul.appendChild(li);
            }
        }

        const next = document.createElement('li');
        next.className = `page-item ${data.current_page===data.last_page?'disabled':''}`;
        next.innerHTML = `<a class="page-link" href="#" onclick="event.preventDefault();cargarMateriales(${data.current_page+1},document.getElementById('searchMaterial').value)">Siguiente</a>`;
        ul.appendChild(next);
        container.appendChild(ul);
    }

    function mostrarNotificacion(msg, tipo = 'success') {
        const div = document.createElement('div');
        div.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
        div.style.cssText = 'top:20px;right:20px;z-index:99999;min-width:280px;border-radius:10px;';
        div.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }

    // Unidades → Subunidades
    const unidades = @json($units);
    document.getElementById('unidadSelect').addEventListener('change', function () {
        const uid      = parseInt(this.value);
        const subSelect = document.getElementById('subunidadSelect');
        subSelect.innerHTML = '<option value="">Seleccione una subunidad</option>';
        if (!uid) return;
        const unidad = unidades.find(u => u.dependency_unit_id === uid);
        if (unidad?.subunits?.length) {
            unidad.subunits.forEach(sub => {
                const o = document.createElement('option');
                o.value = sub.subunit_id; o.textContent = sub.name;
                subSelect.appendChild(o);
            });
        }
    });

    // Centros/Sedes modal handlers (reutilizados por ambos componentes)
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
</script>
@endpush
