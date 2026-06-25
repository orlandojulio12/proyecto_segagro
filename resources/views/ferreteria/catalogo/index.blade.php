@extends('layouts.dashboard')

@section('page-title', 'Catálogo de Productos')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Catálogo de Productos</h2>
        <p>Listado completo del inventario de ferretería</p>
    </div>
    {{-- Filtro de tipo --}}
    <div style="display:flex;align-items:center;gap:10px;">
        <label style="font-size:13px;font-weight:600;color:#374151;white-space:nowrap;">Tipo:</label>
        <select id="filterType" class="form-select" style="width:160px;border-radius:8px;border:1px solid #e5e7eb;font-size:13px;">
            <option value="">Todos</option>
        </select>
    </div>
</div>

<div class="sg-card">
    {{-- Skeleton --}}
    <div id="catalogSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i = 0; $i < 8; $i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:40px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:90px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:220px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:120px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:180px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="catalogTableWrapper" style="display:none">
        <table class="sg-table" id="catalogTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-sort-numeric-up"></i> Consecutivo</th>
                    <th><i class="fas fa-barcode"></i> SKU</th>
                    <th><i class="fas fa-box"></i> Descripción</th>
                    <th><i class="fas fa-layer-group"></i> Familia</th>
                    <th><i class="fas fa-tag"></i> Clase</th>
                    <th><i class="fas fa-align-left"></i> Segmento</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $.get("{{ route('catalogo.filters') }}", function (data) {
                data.types.forEach(t => {
                    let label = t == 1 ? 'Devoluciones' : 'Consumos';
                    $('#filterType').append(`<option value="${t}">${label}</option>`);
                });
            });

            $('#catalogSkeleton').hide();
            $('#catalogTableWrapper').show();

            let table = $('#catalogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('catalogo.data') }}",
                    data: d => { d.type = $('#filterType').val(); }
                },
                language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
                columns: [
                    { data: 'id', width: '50px', render: d => `<span class="sg-badge sg-badge-gray">#${d}</span>` },
                    { data: 'consecutive', width: '100px' },
                    { data: 'sku', width: '110px', render: d => d ? `<code style="background:#f3f4f6;padding:2px 7px;border-radius:5px;font-size:11px;letter-spacing:.5px;">${d}</code>` : '—' },
                    { data: 'element_description', render: (d, type) => {
                        if (!d) return '—';
                        const clean = d.charAt(0).toUpperCase() + d.slice(1).toLowerCase();
                        if (type === 'display' && clean.length > 80) {
                            return `<span title="${d}" style="display:block;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;">${clean}</span>`;
                        }
                        return `<span style="font-size:13px;">${clean}</span>`;
                    }},
                    { data: 'family_name', render: (d, type) => {
                        if (!d) return '—';
                        const label = d.charAt(0).toUpperCase() + d.slice(1).toLowerCase();
                        return type === 'display' ? `<span class="sg-badge sg-badge-blue" style="font-size:10px;white-space:normal;max-width:130px;display:inline-block;line-height:1.3;">${label}</span>` : d;
                    }},
                    { data: 'class_name', render: (d, type) => {
                        if (!d) return '—';
                        const label = d.charAt(0).toUpperCase() + d.slice(1).toLowerCase();
                        return type === 'display' ? `<span class="sg-badge sg-badge-green" style="font-size:10px;white-space:normal;max-width:130px;display:inline-block;line-height:1.3;">${label}</span>` : d;
                    }},
                    { data: 'segment_description', render: (d, type) => {
                        if (!d) return '—';
                        const clean = d.charAt(0).toUpperCase() + d.slice(1).toLowerCase();
                        if (type === 'display' && clean.length > 50) {
                            return `<span title="${d}" style="display:block;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px;color:#6b7280;">${clean}</span>`;
                        }
                        return `<span style="font-size:13px;color:#6b7280;">${clean}</span>`;
                    }},
                ],
                pageLength: 15,
                order: [[0, 'asc']],
                dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
            });

            $('#filterType').on('change', () => table.ajax.reload());
        });
    </script>
@endpush
