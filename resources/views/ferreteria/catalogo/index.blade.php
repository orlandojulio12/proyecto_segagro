@extends('layouts.dashboard')

@section('page-title', 'Catálogo de Productos')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Catálogo de Productos</h2>
        <p class="text-muted">Listado completo del inventario de ferretería</p>
    </div>
</div>

<div class="content-card">
    <div class="table-header mb-3">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Listado del Catálogo</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-modern" id="catalogTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Consecutivo</th>
                    <th>SKU</th>
                    <th>Descripcion Elemento</th>
                    <th>Familia</th>
                    <th>Clase</th>
                    <th>Descripcion Segmento</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /**********************************
     *   MISMO ESTILO DE SEDES
     **********************************/
    body.catalogo-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    body.catalogo-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    body.catalogo-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    body.catalogo-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    body.catalogo-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: white;
    }

    body.catalogo-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    body.catalogo-index .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    body.catalogo-index .table-modern tbody tr:hover {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }
</style>
@endpush


@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
document.body.classList.add('catalogo-index');

$(document).ready(function () {
    $('#catalogTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('catalogo.data') }}",
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
        columns: [
            { data: 'id' },
            { data: 'sku' },
            { data: 'consecutive' },
            { data: 'element_description' },
            { data: 'family_name' },
            { data: 'class_name' },
            { data: 'segment_description' },
        ],
        pageLength: 25,
        order: [[0, 'desc']]
    });
});
</script>
@endpush
