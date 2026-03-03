@extends('layouts.dashboard')

@section('page-title', 'Inventario General')

@section('dashboard-content')
<div class="section-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Inventario General</h2>
        <p class="text-muted">Listado completo de inventarios de sedes, incluyendo materiales y semovientes</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="content-card">
    <div class="table-header mb-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado General de Inventarios</h5>

        <div>
            <select id="dependency_filter" class="form-select form-select-sm">
                <option value="">-- Todas las dependencias --</option>
                @foreach($dependencies as $dep)
                    <option value="{{ $dep->dependency_unit_id }}">{{ $dep->short_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern" id="generalInventoryTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede</th>
                    <th><i class="fas fa-building me-1"></i>Centro</th>
                    <th><i class="fas fa-user-tie me-1"></i>Responsable</th>
                    <th><i class="fas fa-user me-1"></i>Funcionario</th>
                    <th><i class="fas fa-boxes me-1"></i>Tipo</th>
                    <th><i class="fas fa-info-circle me-1"></i>Detalle</th>
                    <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                    <th><i class="fas fa-layer-group me-1"></i>Cantidad</th>
                    <th><i class="fas fa-dollar-sign me-1"></i>Valor Aprox.</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- AJAX cargará los datos --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
.inventario-index .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.inventario-index .content-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.inventario-index .table-header {
    padding-bottom: 15px;
    border-bottom: 2px solid #4cd137;
}

.inventario-index .table-header h5 {
    color: #2c3e50;
    font-weight: 600;
}

.inventario-index .table-modern {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin-bottom: 0;
}

.inventario-index .table-modern thead {
    background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
    color: #fff;
}

.inventario-index .table-modern thead th {
    padding: 16px 12px;
    font-size: 13px;
    text-align: center;
    font-weight: 600;
    border: none;
    white-space: nowrap;
    vertical-align: middle;
}

.inventario-index .table-modern tbody tr {
    background: #fff;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.inventario-index .table-modern tbody tr:hover:not(.empty-state) {
    background: #f8fff9;
    transform: scale(1.002);
    box-shadow: 0 2px 8px rgba(76,209,55,0.15);
}

.inventario-index .table-modern tbody td {
    padding: 14px 12px;
    text-align: center;
    vertical-align: middle;
    font-size: 0.9rem;
}

.inventario-index .badge {
    padding: 6px 12px;
    font-size: 0.85rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.inventario-index .badge.bg-success {
    background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
}

.inventario-index .badge.bg-info {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
}

.inventario-index .action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.inventario-index .btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.inventario-index .btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
}

.inventario-index .btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(243,156,18,0.4);
}

.inventario-index .btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

/* Estado vacío moderno */
.inventario-index .table-modern .empty-state {
    background: linear-gradient(135deg, #e9f7ef 0%, #d4f0e6 100%);
    border-radius: 12px;
    text-align: center;
    padding: 60px 20px;
    font-size: 1rem;
    color: #2c3e50;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 15px;
    animation: fadeIn 0.5s ease-in-out;
}

.inventario-index .table-modern .empty-state i {
    font-size: 60px;
    color: #4cd137;
    animation: bounce 1.2s infinite;
}

.inventario-index .table-modern .empty-state h5 {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
}

.inventario-index .table-modern .empty-state p {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

.inventario-index .table-modern .empty-state .btn {
    margin-top: 10px;
}

/* Animaciones */
@keyframes bounce {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes fadeIn {
    from { opacity:0; transform: translateY(10px); }
    to { opacity:1; transform: translateY(0); }
}

@media(max-width:768px) {
    .inventario-index .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .inventario-index .action-buttons {
        flex-direction: column;
        gap: 5px;
    }

    .inventario-index .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
document.body.classList.add('inventario-index');

$(document).ready(function() {
    let table = $('#generalInventoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('inventoriesGen.inventario.index.ajax') }}",
            data: function(d) {
                d.dependency_unit_id = $('#dependency_filter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'sede', name: 'sede' },
            { data: 'centro', name: 'centro' },
            { data: 'responsible_department', name: 'responsible_department' },
            { data: 'staff', name: 'staff' },
            { data: 'tipo', name: 'tipo', orderable: false, searchable: false },
            { data: 'detalle', name: 'detalle', orderable: false, searchable: false },
            { data: 'record_date', name: 'record_date' },
            { data: 'cantidad', name: 'cantidad', orderable: false, searchable: false },
            { data: 'valor', name: 'valor', orderable: false, searchable: false },
            { data: 'acciones', name: 'acciones', orderable: false, searchable: false },
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
            emptyTable: `
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h5>No hay inventarios registrados</h5>
                    <p>Comienza creando tu primer inventario y visualiza tus materiales y semovientes aquí.</p>
                </div>
            `
        },
        order: [[0,'desc']],
        pageLength: 10,
        responsive: true
    });

    $('#dependency_filter').change(function() {
        table.ajax.reload();
    });
});
</script>
@endpush