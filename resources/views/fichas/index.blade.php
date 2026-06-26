@extends('layouts.dashboard')

@section('page-title', 'Fichas')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Gestión de Fichas</h2>
        <p>Programas de formación registrados por ficha SENA</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('exports.fichas') }}" class="sg-btn sg-btn-secondary" title="Exportar Excel">
            <i class="fas fa-file-excel"></i> Excel
        </a>
        <button onclick="openDrawer('fichaCreateDrawer')" class="sg-btn sg-btn-primary" type="button">
            <i class="fas fa-plus"></i> Nueva Ficha
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ══ DRAWER CREAR FICHA ══ --}}
<div class="sg-drawer-overlay" id="fichaCreateDrawerOverlay"></div>
<div class="sg-drawer" id="fichaCreateDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-file-alt drawer-icon"></i> Nueva Ficha</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('fichaCreateDrawer')"><i class="fas fa-times"></i></button>
    </div>
    <div class="sg-drawer-body">
        <form id="fichaCreateForm" action="{{ route('fichas.store') }}" method="POST">
            @csrf
            @include('fichas._form', ['ficha' => null, 'centros' => $centros, 'instructores' => $instructores, 'mode' => 'create', 'selectorPrefix' => 'fica'])
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('fichaCreateDrawer')"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" form="fichaCreateForm" class="sg-btn sg-btn-primary"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

{{-- ══ DRAWER EDITAR FICHA ══ --}}
<div class="sg-drawer-overlay" id="fichaEditDrawerOverlay"></div>
<div class="sg-drawer" id="fichaEditDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-pen drawer-icon"></i> Editar Ficha</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('fichaEditDrawer')"><i class="fas fa-times"></i></button>
    </div>
    <div class="sg-drawer-body">
        <form id="fichaEditForm" method="POST">
            @csrf @method('PUT')
            @include('fichas._form', ['ficha' => null, 'centros' => $centros, 'instructores' => $instructores, 'mode' => 'edit', 'selectorPrefix' => 'fice'])
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('fichaEditDrawer')"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" form="fichaEditForm" class="sg-btn sg-btn-primary"><i class="fas fa-save"></i> Actualizar</button>
    </div>
</div>

<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:flex-end;">
    <div>
        <label style="font-size:12px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Estado</label>
        <select id="filterEstado" class="form-control form-control-sm" style="min-width:160px;">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\Ficha\Ficha::ESTADOS as $key => $info)
                <option value="{{ $info['label'] }}">{{ $info['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="font-size:12px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Nivel</label>
        <select id="filterNivel" class="form-control form-control-sm" style="min-width:180px;">
            <option value="">Todos los niveles</option>
            @foreach(\App\Models\Ficha\Ficha::NIVELES as $key => $label)
                <option value="{{ $label }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="font-size:12px;font-weight:600;color:#6b7280;display:block;margin-bottom:4px;">Jornada</label>
        <select id="filterJornada" class="form-control form-control-sm" style="min-width:150px;">
            <option value="">Todas las jornadas</option>
            @foreach(\App\Models\Ficha\Ficha::JORNADAS as $key => $label)
                <option value="{{ $label }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button onclick="limpiarFiltrosFichas()" class="sg-btn sg-btn-ghost" style="height:34px;font-size:12px;">
        <i class="fas fa-times me-1"></i>Limpiar
    </button>
</div>

<div class="sg-card">
    <div id="fichasSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i=0;$i<5;$i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:200px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:90px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="fichasTableWrapper" style="display:none">
        <table id="fichasTable" class="sg-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> Ficha</th>
                    <th><i class="fas fa-book"></i> Programa</th>
                    <th><i class="fas fa-layer-group"></i> Nivel</th>
                    <th><i class="fas fa-circle"></i> Estado</th>
                    <th><i class="fas fa-clock"></i> Jornada</th>
                    <th><i class="fas fa-university"></i> Centro / Sede</th>
                    <th><i class="fas fa-users"></i> Aprendices</th>
                    <th><i class="fas fa-calendar"></i> Fechas</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fichas as $ficha)
                <tr>
                    <td><span class="sg-badge sg-badge-gray fw-bold">#{{ $ficha->numero_ficha }}</span></td>
                    <td>
                        <div style="font-weight:600;color:#111827;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $ficha->nombre_programa }}">{{ $ficha->nombre_programa }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ \App\Models\Ficha\Ficha::MODALIDADES[$ficha->modalidad] ?? $ficha->modalidad }}</div>
                    </td>
                    <td><span style="font-size:12px;color:#374151;">{{ \App\Models\Ficha\Ficha::NIVELES[$ficha->nivel_formacion] ?? $ficha->nivel_formacion }}</span></td>
                    <td>
                        @php $estadoInfo = \App\Models\Ficha\Ficha::ESTADOS[$ficha->estado] ?? ['label'=>$ficha->estado,'badge'=>'sg-badge-gray']; @endphp
                        <span class="sg-badge {{ $estadoInfo['badge'] }}">{{ $estadoInfo['label'] }}</span>
                    </td>
                    <td><span style="font-size:12px;">{{ \App\Models\Ficha\Ficha::JORNADAS[$ficha->jornada] ?? $ficha->jornada }}</span></td>
                    <td>
                        <div style="font-size:12.5px;font-weight:600;">{{ $ficha->centro->nom_centro ?? '—' }}</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $ficha->sede->nom_sede ?? '—' }}</div>
                    </td>
                    <td><span class="sg-badge sg-badge-blue">{{ $ficha->numero_aprendices }}</span></td>
                    <td>
                        <div style="font-size:11.5px;color:#374151;">{{ $ficha->fecha_inicio?->format('d/m/Y') }}</div>
                        <div style="font-size:11px;color:#9ca3af;">→ {{ $ficha->fecha_fin?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('horarios.index') }}?ficha_id={{ $ficha->id }}" class="sg-btn sg-btn-secondary" title="Ver Horario" style="font-size:11px;padding:5px 8px;">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                            <button type="button" class="sg-btn sg-btn-warning" title="Editar" onclick="openFichaEditDrawer({{ $ficha->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('fichas.destroy', $ficha->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta ficha?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="sg-btn sg-btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
var fichasTable;
$(document).ready(function() {
    setTimeout(function() {
        $('#fichasSkeleton').hide();
        $('#fichasTableWrapper').show();
        fichasTable = $('#fichasTable').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
            order: [[0, 'desc']],
            pageLength: 25,
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
        });

        $('#filterEstado').on('change', function() {
            fichasTable.column(3).search(this.value).draw();
        });
        $('#filterNivel').on('change', function() {
            fichasTable.column(2).search(this.value).draw();
        });
        $('#filterJornada').on('change', function() {
            fichasTable.column(4).search(this.value).draw();
        });
    }, 300);
});

function limpiarFiltrosFichas() {
    document.getElementById('filterEstado').value = '';
    document.getElementById('filterNivel').value = '';
    document.getElementById('filterJornada').value = '';
    if (fichasTable) {
        fichasTable.column(2).search('').column(3).search('').column(4).search('').draw();
    }
}

function openFichaEditDrawer(id) {
    fetch('/fichas/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(f => {
            document.getElementById('fichaEditForm').action = '/fichas/' + f.id;
            setVal('edit_numero_ficha',        f.numero_ficha       ?? '');
            setVal('edit_nombre_programa',     f.nombre_programa    ?? '');
            setVal('edit_numero_aprendices',   f.numero_aprendices  ?? 0);
            setVal('edit_fecha_inicio',        f.fecha_inicio ? f.fecha_inicio.split('T')[0] : '');
            setVal('edit_fecha_fin',           f.fecha_fin    ? f.fecha_fin.split('T')[0]    : '');
            setOpt('edit_nivel_formacion',     f.nivel_formacion);
            setOpt('edit_modalidad',           f.modalidad);
            setOpt('edit_estado',              f.estado);
            setOpt('edit_jornada',             f.jornada);
            setOpt('edit_instructor_id',       f.instructor_id);

            // Populate centros-sedes-selector (prefix: fice)
            document.getElementById('fice_centro_id').value          = f.centro_id  ?? '';
            document.getElementById('fice_centroSeleccionado').value  = f.centro?.nom_centro ?? '';
            document.getElementById('fice_sede_id').value             = f.sede_id   ?? '';
            document.getElementById('fice_sedeSeleccionada').value    = f.sede?.nom_sede ?? '';
            if (f.centro_id) {
                document.getElementById('fice_sedeSeleccionada').disabled = false;
                document.getElementById('fice_sedeSeleccionada').placeholder = 'Seleccione una sede...';
                // Preload sedes list so user can change if needed
                fetch(`/centros/${f.centro_id}/sedes`).then(r=>r.json()).then(sedes=>{
                    const list = document.getElementById('fice_listaSedes');
                    list.innerHTML = '';
                    sedes.forEach(s => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>${s.nom_sede}</td><td><button type="button" class="btn btn-sm btn-success seleccionar-sede" data-id="${s.id}" data-nombre="${s.nom_sede}" data-prefix="fice">Seleccionar</button></td>`;
                        list.appendChild(tr);
                    });
                });
            }

            openDrawer('fichaEditDrawer');
        })
        .catch(() => alert('Error al cargar la ficha'));
}

function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
}

function setOpt(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    for (let o of el.options) { o.selected = String(o.value) === String(val ?? ''); }
}
</script>
@endpush

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
