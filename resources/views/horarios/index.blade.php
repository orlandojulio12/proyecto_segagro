@extends('layouts.dashboard')

@section('page-title', 'Horarios')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Horarios de Salones</h2>
        <p>Disponibilidad de ambientes por ficha y día de la semana</p>
    </div>
    <div style="display:flex;gap:10px;">
        <button onclick="document.getElementById('importHorarioModal').style.display='flex'" class="sg-btn sg-btn-secondary" type="button">
            <i class="fas fa-file-csv"></i> Importar CSV
        </button>
        <button onclick="openDrawer('horarioCreateDrawer')" class="sg-btn sg-btn-primary" type="button">
            <i class="fas fa-plus"></i> Nuevo Horario
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

{{-- ══ DRAWER CREAR HORARIO ══ --}}
<div class="sg-drawer-overlay" id="horarioCreateDrawerOverlay"></div>
<div class="sg-drawer" id="horarioCreateDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-calendar-plus drawer-icon"></i> Nuevo Horario</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('horarioCreateDrawer')"><i class="fas fa-times"></i></button>
    </div>
    <div class="sg-drawer-body">
        <form id="horarioCreateForm" action="{{ route('horarios.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Ficha <span class="text-danger">*</span></label>
                <select name="ficha_id" id="h_ficha_id" class="form-control" required>
                    <option value="">Seleccione una ficha</option>
                    @foreach($fichas as $f)
                    <option value="{{ $f->id }}">#{{ $f->numero_ficha }} — {{ Str::limit($f->nombre_programa, 40) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Área <span class="text-danger">*</span></label>
                <select id="h_area_id" class="form-control" required>
                    <option value="">Seleccione un área</option>
                    @foreach($areas as $a)
                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Salón <span class="text-danger">*</span></label>
                <select name="room_id" id="h_room_id" class="form-control" required disabled>
                    <option value="">Seleccione primero un área</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Día de la Semana <span class="text-danger">*</span></label>
                <select name="dia_semana" id="h_dia_semana" class="form-control" required>
                    <option value="">Seleccione un día</option>
                    @foreach(\App\Models\Horario\Horario::DIAS as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label>Hora Inicio <span class="text-danger">*</span></label>
                    <input type="time" name="hora_inicio" id="h_hora_inicio" class="form-control" required min="06:00" max="21:00">
                </div>
                <div class="col-6">
                    <label>Hora Fin <span class="text-danger">*</span></label>
                    <input type="time" name="hora_fin" id="h_hora_fin" class="form-control" required min="06:00" max="21:00">
                </div>
            </div>
            <div class="mb-3">
                <label>Competencia / Materia</label>
                <input type="text" name="competencia" class="form-control" placeholder="Ej: Desarrollo de Software">
            </div>
            <div class="mb-3">
                <label>Instructor</label>
                <select name="instructor_id" class="form-control">
                    <option value="">Sin asignar</option>
                    @foreach($instructores as $instr)
                    <option value="{{ $instr->id }}">{{ $instr->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label style="font-size:13px;font-weight:600;color:#374151;">Color del bloque</label>
                <div class="color-picker-grid">
                    @php
                        $colorLabels = [
                            '#16a34a' => 'Verde',
                            '#2563eb' => 'Azul',
                            '#dc2626' => 'Rojo',
                            '#d97706' => 'Naranja',
                            '#7c3aed' => 'Morado',
                            '#0891b2' => 'Cian',
                            '#be185d' => 'Rosa',
                            '#65a30d' => 'Lima',
                        ];
                    @endphp
                    @foreach(\App\Models\Horario\Horario::COLORES as $i => $color)
                    <label class="color-option {{ $i === 0 ? 'selected' : '' }}">
                        <input type="radio" name="color" value="{{ $color }}" {{ $i === 0 ? 'checked' : '' }}>
                        <span class="color-dot" style="background:{{ $color }};"></span>
                        <span class="color-name">{{ $colorLabels[$color] ?? '' }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('horarioCreateDrawer')"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" form="horarioCreateForm" class="sg-btn sg-btn-primary"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

{{-- ══ MODAL IMPORTAR CSV ══ --}}
<div id="importHorarioModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;width:92%;max-width:520px;box-shadow:0 8px 40px rgba(0,0,0,.2);overflow:hidden;">
        <div style="padding:18px 22px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom:1.5px solid #d1fae5;display:flex;align-items:center;justify-content:space-between;">
            <h5 style="margin:0;font-size:15px;font-weight:700;"><i class="fas fa-file-csv" style="color:#16a34a;margin-right:8px;"></i> Importar Horarios CSV</h5>
            <button type="button" onclick="document.getElementById('importHorarioModal').style.display='none'" style="background:none;border:1.5px solid #d1d5db;width:30px;height:30px;border-radius:7px;cursor:pointer;font-size:16px;color:#6b7280;">&times;</button>
        </div>
        <div style="padding:22px;">
            <p style="font-size:13px;color:#6b7280;margin-bottom:14px;">
                Columnas requeridas:<br>
                <code style="font-size:11px;background:#f3f4f6;padding:4px 8px;border-radius:5px;">ficha_id, room_id, dia_semana, hora_inicio, hora_fin, competencia, instructor_id, color</code>
            </p>
            <a href="{{ route('horarios.template') }}" class="sg-btn sg-btn-secondary" style="font-size:12px;margin-bottom:16px;display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-download"></i> Descargar plantilla
            </a>
            <form action="{{ route('horarios.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label style="font-size:13px;font-weight:600;color:#374151;">Archivo CSV</label>
                    <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('importHorarioModal').style.display='none'" class="sg-btn sg-btn-secondary">Cancelar</button>
                    <button type="submit" class="sg-btn sg-btn-primary"><i class="fas fa-upload"></i> Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ FILTROS GRILLA ══ --}}
<div class="hor-filter-card mb-4">
    <div class="hor-filter-header">
        <i class="fas fa-filter"></i> Filtrar disponibilidad de salón
    </div>
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="hor-filter-label"><i class="fas fa-university"></i> Centro</label>
            <select id="g_centro_id" class="form-select hor-select">
                <option value="">Todos los centros</option>
                @foreach($centros as $c)
                <option value="{{ $c->id }}">{{ $c->nom_centro }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="hor-filter-label"><i class="fas fa-layer-group"></i> Área</label>
            <select id="g_area_id" class="form-select hor-select" disabled>
                <option value="">Seleccione un centro</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="hor-filter-label"><i class="fas fa-door-open"></i> Salón</label>
            <select id="g_room_id" class="form-select hor-select" disabled>
                <option value="">Seleccione un área</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="btnCargarGrilla" class="hor-btn-ver w-100" disabled onclick="cargarGrilla()">
                <i class="fas fa-eye"></i> Ver grilla
            </button>
        </div>
    </div>
</div>

{{-- ══ GRILLA SEMANAL ══ --}}
<div id="grillaSemanal" style="display:none;">
    <div class="hor-grid-card">
        <div class="hor-grid-header">
            <div>
                <span class="hor-grid-title"><i class="fas fa-calendar-week"></i> Disponibilidad Semanal</span>
                <span class="hor-grid-room" id="grillaRoomName"></span>
            </div>
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <div class="hor-legend"><span class="hor-legend-dot" style="background:#f0fdf4;border:1.5px solid #86efac;"></span> Disponible</div>
                <div class="hor-legend"><span class="hor-legend-dot" style="background:#16a34a;"></span> Ocupado</div>
                <span class="sg-badge sg-badge-green" style="font-size:11px;">En tiempo real</span>
            </div>
        </div>
        <div class="hor-scroll">
            <table class="hor-table" id="grillaTable">
                <thead>
                    <tr>
                        <th class="hor-th-time">Hora</th>
                        <th>Lunes</th>
                        <th>Martes</th>
                        <th>Miércoles</th>
                        <th>Jueves</th>
                        <th>Viernes</th>
                        <th>Sábado</th>
                    </tr>
                </thead>
                <tbody id="grillaBody"></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ── Filter card ── */
.hor-filter-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 1.5px solid #d1fae5;
    overflow: hidden;
}
.hor-filter-header {
    background: linear-gradient(135deg,#f0fdf4,#dcfce7);
    padding: 12px 22px;
    font-size: 13.5px;
    font-weight: 700;
    color: #15803d;
    border-bottom: 1.5px solid #d1fae5;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hor-filter-card .row { padding: 18px 18px 20px; }
.hor-filter-label {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
    display: block;
}
.hor-filter-label i { color: #16a34a; margin-right: 5px; }
.hor-select {
    border: 1.5px solid #d1d5db;
    border-radius: 10px;
    font-size: 13px;
    padding: 8px 12px;
    transition: border-color .2s, box-shadow .2s;
}
.hor-select:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.12); outline: none; }
.hor-select:disabled { background: #f9fafb; color: #9ca3af; }
.hor-btn-ver {
    background: linear-gradient(135deg,#16a34a,#22c55e);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
}
.hor-btn-ver:hover:not(:disabled) { box-shadow: 0 4px 14px rgba(22,163,74,.35); transform: translateY(-1px); }
.hor-btn-ver:disabled { background: #d1d5db; color: #9ca3af; cursor: default; transform: none; box-shadow: none; }

/* ── Grid card ── */
.hor-grid-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 1.5px solid #d1fae5;
    overflow: hidden;
    margin-bottom: 24px;
}
.hor-grid-header {
    padding: 14px 22px;
    background: linear-gradient(135deg,#f8fafc,#f0fdf4);
    border-bottom: 1.5px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.hor-grid-title { font-size: 14px; font-weight: 700; color: #15803d; }
.hor-grid-title i { margin-right: 7px; }
.hor-grid-room { font-size: 13px; font-weight: 600; color: #374151; margin-left: 10px; }
.hor-legend { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #6b7280; }
.hor-legend-dot { display: inline-block; width: 13px; height: 13px; border-radius: 3px; }

/* ── Table ── */
.hor-scroll { overflow-x: auto; }
.hor-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 680px;
    table-layout: fixed;
}
.hor-table th {
    background: linear-gradient(135deg,#16a34a,#22c55e);
    color: #fff;
    padding: 11px 8px;
    font-size: 12.5px;
    font-weight: 700;
    text-align: center;
    border: none;
    position: sticky;
    top: 0;
    z-index: 2;
}
.hor-table th.hor-th-time {
    background: #1f2937;
    width: 72px;
    min-width: 68px;
}
.hor-table td {
    border: 1px solid #f0fdf4;
    vertical-align: top;
    padding: 0;
    height: 46px;
}
.hor-table td.hor-time-cell {
    background: #f8fafc;
    text-align: center;
    vertical-align: middle;
    padding: 4px 6px;
    border-right: 2px solid #e5e7eb;
}
.hor-time-label { font-size: 11.5px; font-weight: 700; color: #374151; display: block; }
.hor-time-sub { font-size: 10px; color: #9ca3af; display: block; }

/* ── Slots ── */
.hor-slot-disp {
    height: 100%;
    background: #f9fffe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #d1fae5;
    cursor: pointer;
    transition: background .15s, color .15s;
    min-height: 44px;
}
.hor-slot-disp:hover { background: #dcfce7; color: #16a34a; }

.hor-slot-ocupado {
    height: 100%;
    padding: 7px 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 2px;
    cursor: pointer;
    border-radius: 0;
    border-left: 4px solid transparent;
    background: #f8fafc;
    transition: filter .15s;
    min-height: 44px;
    overflow: hidden;
}
.hor-slot-ocupado:hover { filter: brightness(0.96); }
.hor-ficha-num { font-size: 11.5px; font-weight: 800; color: #111827; }
.hor-comp { font-size: 11px; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.hor-inst { font-size: 10.5px; color: #6b7280; }
.hor-time-range { font-size: 10px; color: #9ca3af; margin-top: 2px; }

/* ── Color picker ── */
.color-picker-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
.color-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    padding: 6px 4px;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: border-color .15s, background .15s;
}
.color-option input[type="radio"] { display: none; }
.color-option.selected, .color-option:has(input:checked) {
    border-color: #111827;
    background: #f3f4f6;
}
.color-dot {
    display: block;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    box-shadow: 0 1px 4px rgba(0,0,0,.15);
}
.color-name { font-size: 10px; color: #6b7280; }
</style>
@endpush

@push('scripts')
<script>
const allAreas = @json($areas->map(fn($a) => ['id'=>$a->id,'name'=>$a->name]));

// ── Drawer area → room ──
document.getElementById('h_area_id').addEventListener('change', function() {
    loadRoomsIntoSelect(this.value, 'h_room_id');
});

// ── Color picker ──
document.querySelectorAll('.color-option').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input').checked = true;
    });
});

// ── Grid filters ──
document.getElementById('g_centro_id').addEventListener('change', function() {
    const centroId = this.value;
    const areaSel  = document.getElementById('g_area_id');
    const roomSel  = document.getElementById('g_room_id');
    areaSel.innerHTML = '<option value="">Seleccione un área</option>';
    roomSel.innerHTML = '<option value="">Seleccione un área primero</option>';
    areaSel.disabled  = !centroId;
    roomSel.disabled  = true;
    document.getElementById('btnCargarGrilla').disabled = true;
    document.getElementById('grillaSemanal').style.display = 'none';
    if (!centroId) return;

    fetch(`/horarios/areas-by-centro?centro_id=${centroId}`)
        .then(r => r.json())
        .then(areas => {
            areas.forEach(a => {
                const o = document.createElement('option');
                o.value = a.id; o.textContent = a.name;
                areaSel.appendChild(o);
            });
            areaSel.disabled = areas.length === 0;
        })
        .catch(() => {
            allAreas.forEach(a => {
                const o = document.createElement('option');
                o.value = a.id; o.textContent = a.name;
                areaSel.appendChild(o);
            });
            areaSel.disabled = false;
        });
});

document.getElementById('g_area_id').addEventListener('change', function() {
    const areaId = this.value;
    const roomSel = document.getElementById('g_room_id');
    roomSel.innerHTML = '<option value="">Cargando...</option>';
    roomSel.disabled  = true;
    document.getElementById('btnCargarGrilla').disabled = true;
    document.getElementById('grillaSemanal').style.display = 'none';
    if (!areaId) return;
    loadRoomsIntoSelect(areaId, 'g_room_id', () => {
        document.getElementById('btnCargarGrilla').disabled = false;
    });
});

document.getElementById('g_room_id').addEventListener('change', function() {
    document.getElementById('btnCargarGrilla').disabled = !this.value;
});

function loadRoomsIntoSelect(areaId, selectId, callback) {
    const sel = document.getElementById(selectId);
    fetch(`/horarios/salones?area_id=${areaId}`)
        .then(r => r.json())
        .then(rooms => {
            sel.innerHTML = '<option value="">Seleccione un salón</option>';
            rooms.forEach(r => {
                const o = document.createElement('option');
                o.value       = r.id;
                o.textContent = `${r.name}${r.code ? ' (' + r.code + ')' : ''} — Cap. ${r.capacity ?? '?'}`;
                sel.appendChild(o);
            });
            sel.disabled = rooms.length === 0;
            if (callback) callback();
        });
}

// ── Cargar grilla ──
function cargarGrilla() {
    const roomId   = document.getElementById('g_room_id').value;
    const roomName = document.getElementById('g_room_id').selectedOptions[0]?.textContent ?? '';
    if (!roomId) return;
    document.getElementById('grillaRoomName').textContent = '— ' + roomName;
    fetch(`/horarios/por-salon?room_id=${roomId}`)
        .then(r => r.json())
        .then(horarios => {
            renderGrilla(horarios);
            document.getElementById('grillaSemanal').style.display = 'block';
        });
}

// ── Render con rowspan ──
function renderGrilla(horarios) {
    const DIAS      = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
    const START     = 6;
    const END       = 21;
    const tbody     = document.getElementById('grillaBody');
    tbody.innerHTML = '';

    // skipMap[h][dia] = true → this cell is covered by a rowspan above
    const skipMap  = {};
    const blockMap = {};
    for (let h = START; h < END; h++) { skipMap[h] = {}; blockMap[h] = {}; }

    horarios.forEach(hor => {
        const [sh, sm] = hor.hora_inicio.split(':').map(Number);
        const [eh, em] = hor.hora_fin.split(':').map(Number);
        const bStart   = sh * 60 + sm;
        const bEnd     = eh * 60 + em;

        let first = null, last = null;
        for (let h = START; h < END; h++) {
            const sStart = h * 60, sEnd = (h + 1) * 60;
            if (bStart < sEnd && bEnd > sStart) {
                if (first === null) first = h;
                last = h;
            }
        }
        if (first === null) return;

        blockMap[first][hor.dia_semana] = { hor, rowspan: last - first + 1 };
        for (let h = first + 1; h <= last; h++) {
            skipMap[h][hor.dia_semana] = true;
        }
    });

    for (let h = START; h < END; h++) {
        const tr      = document.createElement('tr');
        const timeTd  = document.createElement('td');
        timeTd.className = 'hor-time-cell';
        timeTd.innerHTML = `<span class="hor-time-label">${pad(h)}:00</span><span class="hor-time-sub">↓ ${pad(h+1)}:00</span>`;
        tr.appendChild(timeTd);

        DIAS.forEach(dia => {
            if (skipMap[h][dia]) return; // covered by parent rowspan

            const td    = document.createElement('td');
            const block = blockMap[h][dia];

            if (block) {
                td.rowSpan = block.rowspan;
                const { hor } = block;
                const color   = hor.color ?? '#16a34a';
                const instrName = hor.instructor ? (hor.instructor.nombre_completo ?? hor.instructor.name ?? '') : '';
                td.innerHTML = `
                    <div class="hor-slot-ocupado" style="border-left-color:${color};" onclick="confirmarEliminar(${hor.id})" title="Clic para eliminar">
                        <div class="hor-ficha-num">#${hor.ficha?.numero_ficha ?? '—'}</div>
                        <div class="hor-comp">${hor.competencia ?? hor.ficha?.nombre_programa ?? ''}</div>
                        ${instrName ? `<div class="hor-inst"><i class="fas fa-user" style="font-size:9px;margin-right:3px;color:${color};"></i>${instrName}</div>` : ''}
                        <div class="hor-time-range">${hor.hora_inicio.substring(0,5)} – ${hor.hora_fin.substring(0,5)}</div>
                    </div>`;
            } else {
                td.innerHTML = `<div class="hor-slot-disp"><i class="fas fa-plus" style="font-size:10px;"></i></div>`;
            }

            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    }
}

function pad(n) { return String(n).padStart(2, '0'); }

function confirmarEliminar(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Eliminar bloque?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed) eliminarHorario(id);
        });
    } else {
        if (confirm('¿Desea eliminar este bloque de horario?')) eliminarHorario(id);
    }
}

function eliminarHorario(id) {
    fetch(`/horarios/${id}`, {
        method:  'DELETE',
        headers: {
            'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
    }).then(r => r.json()).then(() => cargarGrilla());
}
</script>
@endpush
