{{-- resources/views/salida_ferreteria/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Salidas de Ferretería')

@section('dashboard-content')

<div class="sg-page-header">
    <div>
        <h2>Salidas de Ferretería</h2>
        <p>Administra las salidas de materiales del inventario</p>
    </div>
    <a href="{{ route('salida_ferreteria.create') }}" class="sg-btn sg-btn-primary">
        <i class="fas fa-plus"></i> Registrar Salida
    </a>
</div>

@if(session('success'))
<div class="sg-alert sg-alert-success mb-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="sg-card">
    {{-- Skeleton --}}
    <div id="salidasSkeleton">
        <div class="sg-skeleton sg-skeleton-header"></div>
        @for($i = 0; $i < 6; $i++)
        <div class="sg-skeleton-row">
            <div class="sg-skeleton sg-skeleton-cell" style="width:40px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:110px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:150px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:160px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:140px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:80px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:70px"></div>
            <div class="sg-skeleton sg-skeleton-cell" style="width:100px"></div>
        </div>
        @endfor
    </div>

    <div class="sg-table-wrapper" id="salidasTableWrapper" style="display:none">
        <table class="sg-table" id="salidasTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-calendar"></i> Fecha</th>
                    <th><i class="fas fa-user"></i> Funcionario</th>
                    <th><i class="fas fa-university"></i> Centro</th>
                    <th><i class="fas fa-map-marker-alt"></i> Sede</th>
                    <th><i class="fas fa-file-alt"></i> F14</th>
                    <th><i class="fas fa-boxes"></i> Materiales</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salidas as $salida)
                <tr>
                    <td><span class="sg-badge sg-badge-gray">#{{ $salida->id }}</span></td>
                    <td>
                        <span class="sg-badge sg-badge-blue">
                            <i class="fas fa-calendar-day" style="margin-right:4px;font-size:11px;"></i>
                            {{ $salida->fecha_salida->format('d/m/Y') }}
                        </span>
                    </td>
                    <td style="font-weight:500;">{{ $salida->user->name ?? '—' }}</td>
                    <td>{{ $salida->centro->nom_centro ?? '—' }}</td>
                    <td>{{ $salida->sede->nom_sede ?? '—' }}</td>
                    <td>
                        @if($salida->f14)
                            <span class="sg-badge sg-badge-yellow">{{ $salida->f14 }}</span>
                        @else
                            <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="sg-badge sg-badge-green">
                            <i class="fas fa-box" style="margin-right:4px;font-size:11px;"></i>
                            {{ $salida->detalles->count() }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('salida_ferreteria.show', $salida) }}" class="sg-btn sg-btn-info" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('salida_ferreteria.edit', $salida) }}" class="sg-btn sg-btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteSalida({{ $salida->id }})" class="sg-btn sg-btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
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
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .sg-alert { display:flex;align-items:center;gap:10px;padding:14px 18px;border-radius:10px;font-size:14px;font-weight:500; }
        .sg-alert-success { background:#d1fae5;color:#065f46;border-left:4px solid #16a34a; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#salidasSkeleton').hide();
                $('#salidasTableWrapper').show();
                if ($.fn.DataTable) {
                    $('#salidasTable').DataTable({
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json',
                            emptyTable: '<div style="padding:32px 0;text-align:center;">' +
                                '<div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">' +
                                '<i class="fas fa-inbox" style="font-size:22px;color:white;"></i></div>' +
                                '<div style="font-size:15px;font-weight:700;color:#374151;margin-bottom:4px;">Sin salidas registradas</div>' +
                                '<div style="font-size:13px;color:#9ca3af;">Comienza registrando tu primera salida</div></div>',
                        },
                        order: [[0, 'desc']],
                        pageLength: 15,
                        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-6"i><"col-sm-6"p>>',
                    });
                }
            }, 400);
        });

        function deleteSalida(id) {
            Swal.fire({
                title: '¿Eliminar salida?',
                text: 'Las cantidades se devolverán al inventario. Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/salida-ferreteria/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        Swal.fire({ title: data.success ? 'Eliminado' : 'Error', text: data.message, icon: data.success ? 'success' : 'error', timer: 1500, showConfirmButton: false });
                        if (data.success) setTimeout(() => location.reload(), 1500);
                    });
                }
            });
        }
    </script>
@endpush
