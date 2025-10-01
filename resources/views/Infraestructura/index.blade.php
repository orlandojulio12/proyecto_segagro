{{-- resources/views/infraestructura/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Infraestructura')

@section('dashboard-content')
    <div class="section-header">
        <div>
            <h2>Gestión de Infraestructura</h2>
            <p>Administra las necesidades de infraestructura</p>
        </div>
        <a href="{{ route('infraestructura.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Necesidad
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="infraestructuraTable" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dependencia</th>
                            <th>Funcionario</th>
                            <th>Centro</th>
                            <th>Sede</th>
                            <th>Tipo</th>
                            <th>Nivel Riesgo</th>
                            <th>Complejidad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infraestructuras as $infra)
                            <tr>
                                <td>{{ $infra->id }}</td>
                                <td>{{ $infra->dependencia->nombre ?? 'N/A' }}</td>
                                <td>{{ $infra->funcionario->name ?? 'N/A' }}</td>

                                <td>{{ $infra->centro->nom_centro ?? 'N/A' }}</td>
                                <td>{{ $infra->sede->nom_sede ?? 'N/A' }}</td>
                                <td>{{ $infra->tipo_necesidad }}</td>
                                <td>
                                    <span
                                        class="badge 
        @if ($infra->nivel_riesgo === 'Alto') bg-danger
        @elseif($infra->nivel_riesgo === 'Medio') bg-warning
        @else bg-success @endif">
                                        {{ $infra->nivel_riesgo }}
                                    </span>
                                </td>
                                <td>{{ $infra->nivel_complejidad }}</td>
                                <td>
                                    <span class="badge {{ $infra->estado == 'Pendiente' ? 'bg-warning' : 'bg-success' }}">
                                        {{ $infra->estado }}
                                    </span>
                                </td>
                                <td>{{ $infra->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('infraestructura.edit', $infra) }}"
                                            class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteInfraestructura({{ $infra->id }})"
                                            class="btn btn-sm btn-danger" title="Eliminar">
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
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-success {
            background: #4cd137;
            color: white;
        }

        .btn-success:hover {
            background: #3db32a;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #000;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#infraestructuraTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
                },
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });
        });

        function deleteInfraestructura(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/infraestructura/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Eliminado', response.message, 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
