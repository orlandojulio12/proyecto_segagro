@extends('layouts.dashboard')

@section('page-title', 'Semovientes')

@section('dashboard-content')
    <div class="section-header">
        <div>
            <h2>Gestión de Semovientes</h2>
            <p>Administra los registros de nacimientos de semovientes</p>
        </div>
        <a href="{{ route('semoviente.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Semoviente
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="content-card">
        <table class="table table-striped" id="semovientesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sede</th>
                    <th>Centro</th>
                    <th>Funcionario</th>
                    <th>Fecha Nacimiento</th>
                    <th>Tipo</th>
                    <th>Raza</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($semovientes as $semoviente)
                    <tr>
                        <td>{{ $semoviente->id }}</td>
                        <td>{{ $semoviente->sede->nom_sede ?? 'N/A' }}</td>
                        <td>{{ $semoviente->sede->centro->nom_centro ?? 'N/A' }}</td>
                        <td>{{ $semoviente->staff->name ?? 'N/A' }}</td>
                        <td>
                            @if ($semoviente->birth_date && $semoviente->birth_time)
                                {{ $semoviente->birth_date->format('d/m/Y') }} {{ $semoviente->birth_time->format('H:i') }}
                            @else
                                N/A
                            @endif
                        </td>

                        <td>{{ $semoviente->animal_type }}</td>
                        <td>{{ $semoviente->breed }}</td>
                        <td>
                            <span class="badge bg-{{ $semoviente->estado_color }}">
                                {{ $semoviente->estado_texto }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('semoviente.edit', $semoviente) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm" onclick="deleteSemoviente({{ $semoviente->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
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
            $('#semovientesTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
                }
            });
        });

        function deleteSemoviente(id) {
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
                        url: `/semoviente/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Eliminado', response.message, 'success');
                            location.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush
