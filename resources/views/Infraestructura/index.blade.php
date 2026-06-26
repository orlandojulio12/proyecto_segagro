{{-- resources/views/infraestructura/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Infraestructura')

@section('dashboard-content')

    <div class="sg-page-header">
        <div>
            <h2 class="sg-page-title">Gestión de Infraestructura</h2>
            <p class="sg-page-subtitle">Administra las necesidades de infraestructura</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('exports.infraestructura') }}" class="sg-btn sg-btn-secondary" title="Exportar Excel">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('infraestructura.create') }}" class="sg-btn sg-btn-primary">
                <i class="fas fa-plus"></i> Nueva Necesidad
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="sg-alert sg-alert-success mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="sg-card">
        <div class="sg-card-header">
            <i class="fas fa-list me-2"></i>Listado de Necesidades
        </div>
        <div class="sg-table-wrapper">
            <table class="sg-table" id="infraestructuraTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                        <th><i class="fas fa-building me-1"></i>Dependencia</th>
                        <th><i class="fas fa-user me-1"></i>Funcionario</th>
                        <th><i class="fas fa-university me-1"></i>Centro</th>
                        <th><i class="fas fa-map-marker-alt me-1"></i>Sede</th>
                        <th><i class="fas fa-tools me-1"></i>Tipo</th>
                        <th><i class="fas fa-exclamation-triangle me-1"></i>Riesgo</th>
                        <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                        <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $riesgoMap = ['Alto' => 'sg-badge-red', 'Medio' => 'sg-badge-yellow', 'Bajo' => 'sg-badge-green'];
                        $estadoMap = ['Pendiente' => 'sg-badge-yellow', 'En Proceso' => 'sg-badge-blue', 'Cancelada' => 'sg-badge-red', 'Completada' => 'sg-badge-green'];
                    @endphp
                    @foreach($infraestructuras as $infra)
                        <tr>
                            <td><strong>#{{ $infra->id }}</strong></td>
                            <td>{{ $infra->dependencia?->short_name ?? 'N/A' }}</td>
                            <td>{{ $infra->funcionario?->name ?? 'N/A' }}</td>
                            <td><span class="sg-badge sg-badge-blue">{{ $infra->centro?->nom_centro ?? 'N/A' }}</span></td>
                            <td><span class="sg-badge sg-badge-green">{{ $infra->sede?->nom_sede ?? 'N/A' }}</span></td>
                            <td>{{ $infra->tipo_necesidad }}</td>
                            <td><span class="sg-badge {{ $riesgoMap[$infra->nivel_riesgo] ?? 'sg-badge-gray' }}">{{ $infra->nivel_riesgo }}</span></td>
                            <td><span class="sg-badge {{ $estadoMap[$infra->estado] ?? 'sg-badge-gray' }}">{{ $infra->estado }}</span></td>
                            <td><span class="sg-badge sg-badge-gray">{{ $infra->created_at?->format('d/m/Y') ?? '—' }}</span></td>
                            <td>
                                <div style="display:flex;gap:6px;">
                                    <a href="{{ route('infraestructura.edit', $infra) }}" class="sg-btn sg-btn-warning" title="Editar" style="font-size:11px;padding:5px 10px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteInfraestructura({{ $infra->id }})" class="sg-btn sg-btn-danger" title="Eliminar" style="font-size:11px;padding:5px 10px;">
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
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#infraestructuraTable').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' },
                order: [[0, 'desc']],
                pageLength: 10,
                columnDefs: [{ orderable: false, targets: [9] }],
            });
        });

        function deleteInfraestructura(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/infraestructura/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', data.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Error de conexión', 'error'));
                }
            });
        }
    </script>
@endpush
