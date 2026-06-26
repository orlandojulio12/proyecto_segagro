@extends('layouts.dashboard')

@section('page-title', 'Instructores')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Instructores</h2>
        <p>Docentes y facilitadores vinculados al centro de formación</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('exports.instructores') }}" class="sg-btn sg-btn-secondary" title="Exportar Excel">
            <i class="fas fa-file-excel"></i> Excel
        </a>
        <button onclick="document.getElementById('importModal').style.display='flex'" class="sg-btn sg-btn-secondary" type="button">
            <i class="fas fa-file-csv"></i> Importar CSV
        </button>
        <button onclick="openDrawer('instructorCreateDrawer')" class="sg-btn sg-btn-primary" type="button">
            <i class="fas fa-plus"></i> Nuevo Instructor
        </button>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->has('import'))
<div class="alert alert-danger">{{ $errors->first('import') }}</div>
@endif

{{-- ══ DRAWER CREAR ══ --}}
<div class="sg-drawer-overlay" id="instructorCreateDrawerOverlay"></div>
<div class="sg-drawer" id="instructorCreateDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-user-tie drawer-icon"></i> Nuevo Instructor</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('instructorCreateDrawer')"><i class="fas fa-times"></i></button>
    </div>
    <div class="sg-drawer-body">
        <form id="instructorCreateForm" action="{{ route('instructores.store') }}" method="POST">
            @csrf
            @include('instructores._form', ['instructor' => null, 'prefix' => 'c'])
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('instructorCreateDrawer')"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" form="instructorCreateForm" class="sg-btn sg-btn-primary"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

{{-- ══ DRAWER EDITAR ══ --}}
<div class="sg-drawer-overlay" id="instructorEditDrawerOverlay"></div>
<div class="sg-drawer" id="instructorEditDrawer">
    <div class="sg-drawer-header">
        <h5><i class="fas fa-user-edit drawer-icon"></i> Editar Instructor</h5>
        <button type="button" class="sg-drawer-close" onclick="closeDrawer('instructorEditDrawer')"><i class="fas fa-times"></i></button>
    </div>
    <div class="sg-drawer-body">
        <form id="instructorEditForm" method="POST">
            @csrf @method('PUT')
            @include('instructores._form', ['instructor' => null, 'prefix' => 'e'])
        </form>
    </div>
    <div class="sg-drawer-footer">
        <button type="button" class="sg-btn sg-btn-secondary" onclick="closeDrawer('instructorEditDrawer')"><i class="fas fa-times"></i> Cancelar</button>
        <button type="submit" form="instructorEditForm" class="sg-btn sg-btn-primary"><i class="fas fa-save"></i> Actualizar</button>
    </div>
</div>

{{-- ══ MODAL IMPORTAR ══ --}}
<div id="importModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:14px;width:92%;max-width:520px;box-shadow:0 8px 40px rgba(0,0,0,.2);overflow:hidden;">
        <div style="padding:18px 22px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom:1.5px solid #d1fae5;display:flex;align-items:center;justify-content:space-between;">
            <h5 style="margin:0;font-size:15px;font-weight:700;color:#111827;"><i class="fas fa-file-csv" style="color:#16a34a;margin-right:8px;"></i> Importar Instructores CSV</h5>
            <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background:none;border:1.5px solid #d1d5db;width:30px;height:30px;border-radius:7px;cursor:pointer;font-size:16px;color:#6b7280;">&times;</button>
        </div>
        <div style="padding:22px;">
            <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">
                Suba un archivo <strong>CSV</strong> con las columnas:<br>
                <code style="font-size:11px;background:#f3f4f6;padding:4px 8px;border-radius:5px;">nombre, apellido, documento, email, telefono, especialidad, tipo_contrato</code><br>
                <small style="color:#9ca3af;">tipo_contrato: planta | contrato | hora_catedra</small>
            </p>
            <a href="{{ route('instructores.template') }}" class="sg-btn sg-btn-secondary" style="font-size:12px;margin-bottom:16px;display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-download"></i> Descargar plantilla
            </a>
            <form action="{{ route('instructores.importar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label style="font-size:13px;font-weight:600;color:#374151;">Archivo CSV</label>
                    <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="sg-btn sg-btn-secondary">Cancelar</button>
                    <button type="submit" class="sg-btn sg-btn-primary"><i class="fas fa-upload"></i> Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="sg-card">
    <div id="instrSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i=0;$i<6;$i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:170px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:110px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:130px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:90px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="instrTableWrapper" style="display:none;">
        <table id="instrTable" class="sg-table">
            <thead>
                <tr>
                    <th><i class="fas fa-user"></i> Nombre</th>
                    <th><i class="fas fa-id-card"></i> Documento</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-phone"></i> Teléfono</th>
                    <th><i class="fas fa-book-open"></i> Especialidad</th>
                    <th><i class="fas fa-briefcase"></i> Contrato</th>
                    <th><i class="fas fa-circle"></i> Estado</th>
                    <th><i class="fas fa-book"></i> Fichas</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instructores as $instr)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#111827;">{{ $instr->nombre_completo }}</div>
                    </td>
                    <td><span class="sg-badge sg-badge-gray">{{ $instr->documento }}</span></td>
                    <td style="font-size:12.5px;color:#374151;">{{ $instr->email ?? '—' }}</td>
                    <td style="font-size:12.5px;">{{ $instr->telefono ?? '—' }}</td>
                    <td style="font-size:12.5px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $instr->especialidad }}">{{ $instr->especialidad ?? '—' }}</td>
                    <td>
                        @php
                            $badgeMap = ['planta'=>'sg-badge-green','contrato'=>'sg-badge-blue','hora_catedra'=>'sg-badge-yellow'];
                        @endphp
                        <span class="sg-badge {{ $badgeMap[$instr->tipo_contrato] ?? 'sg-badge-gray' }}">
                            {{ \App\Models\Instructor\Instructor::TIPOS_CONTRATO[$instr->tipo_contrato] ?? $instr->tipo_contrato }}
                        </span>
                    </td>
                    <td>
                        @if($instr->activo)
                            <span class="sg-badge sg-badge-green">Activo</span>
                        @else
                            <span class="sg-badge sg-badge-red">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <span class="sg-badge sg-badge-blue">{{ $instr->fichas_count }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="action-icon edit" title="Editar" onclick="openInstrEditDrawer({{ $instr->id }})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('instructores.destroy', $instr->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este instructor?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-icon delete" title="Eliminar"><i class="fas fa-trash"></i></button>
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

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#instrSkeleton').hide();
        $('#instrTableWrapper').show();
        $('#instrTable').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
            order: [[0, 'asc']],
            pageLength: 25,
            columnDefs: [{ orderable: false, targets: [8] }],
        });
    }, 300);
});

function openInstrEditDrawer(id) {
    fetch('/instructores/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(instr => {
            document.getElementById('instructorEditForm').action = '/instructores/' + instr.id;
            setVal('e_nombre',        instr.nombre       ?? '');
            setVal('e_apellido',      instr.apellido     ?? '');
            setVal('e_documento',     instr.documento    ?? '');
            setVal('e_email',         instr.email        ?? '');
            setVal('e_telefono',      instr.telefono     ?? '');
            setVal('e_especialidad',  instr.especialidad ?? '');
            setSelectVal('e_tipo_contrato', instr.tipo_contrato);
            document.getElementById('e_activo').checked = instr.activo == 1;
            openDrawer('instructorEditDrawer');
        })
        .catch(() => alert('Error al cargar el instructor'));
}

function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
}

function setSelectVal(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    for (let o of el.options) { o.selected = o.value === String(val); }
}
</script>
@endpush
