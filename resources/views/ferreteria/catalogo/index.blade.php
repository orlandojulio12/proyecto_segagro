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

        {{-- FILTROS --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Tipo de Catálogo</label>
                <select id="filterType" class="form-select">
                    <option value="">Todos</option>
                </select>
            </div>
        </div>


        {{-- TABLA --}}
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
                        <th>Descripción Elemento</th>
                        <th>Familia</th>
                        <th>Clase</th>
                        <th>Descripción Segmento</th>
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

        body.catalogo-index .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.body.classList.add('catalogo-index');

        $(document).ready(function() {

            /** ================================
             * CARGAR OPCIONES DE FILTROS
             * ================================
             */
            $.get("{{ route('catalogo.filters') }}", function(data) {

                data.types.forEach(t => {
                    let label = t == 1 ? 'Devoluciones' : 'Consumos';
                    $('#filterType').append(`<option value="${t}">${label}</option>`);
                });

            });

            /** ================================
             * DATATABLE
             * ================================
             */
            let table = $('#catalogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('catalogo.data') }}",
                    data: function(d) {
                        d.type = $('#filterType').val(); // 👈 SOLO ESTE FILTRA
                    }
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'consecutive'
                    },
                    {
                        data: 'sku'
                    },
                    {
                        data: 'element_description'
                    },
                    {
                        data: 'family_name'
                    },
                    {
                        data: 'class_name'
                    },
                    {
                        data: 'segment_description'
                    },
                ],
                pageLength: 10,
                order: [
                    [0, 'desc']
                ]
            });

            // ACTUALIZA SOLO ESTE FILTRO
            $('#filterType').on('change', function() {
                table.ajax.reload();
            });


            // RECARGAR CUANDO CAMBIA UN FILTRO
            $('#filterFamily, #filterClass, #filterSegment').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
