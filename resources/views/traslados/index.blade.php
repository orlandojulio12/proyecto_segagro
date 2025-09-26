@extends('layouts.dashboard')

@section('page-title', 'Necesidades de Traslado')

@section('dashboard-content')
<div class="section-header">
    <div>
        <h2>Gestión de Traslados</h2>
        <p>Administra las solicitudes de traslado</p>
    </div>
    <a href="{{ route('traslados.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Crear Traslado
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="content-card">
    <table class="table table-striped" id="trasladosTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Centro Inicial</th>
                <th>Sede Inicial</th>
                <th>Centro Final</th>
                <th>Sede Final</th>
                <th>Funcionario</th>
                <th>Fechas</th>
                <th>Materiales</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($traslados as $traslado)
            <tr>
                <td>{{ $traslado->id }}</td>
                <td>{{ $traslado->centroInicial->nom_centro ?? 'N/A' }}</td>
                <td>{{ $traslado->sedeInicial->nom_sede ?? 'N/A' }}</td>
                <td>{{ $traslado->centroFinal->nom_centro ?? 'N/A' }}</td>
                <td>{{ $traslado->sedeFinal->nom_sede ?? 'N/A' }}</td>
                <td>{{ $traslado->user->name ?? 'N/A' }}</td>
                <td>
                    {{ $traslado->fecha_inicio ? $traslado->fecha_inicio->format('d/m/Y') : 'N/A' }}
                    -
                    {{ $traslado->fecha_fin ? $traslado->fecha_fin->format('d/m/Y') : 'N/A' }}
                </td>
                <td>{{ $traslado->materiales->count() }}</td>
                <td>
                    <a href="{{ route('traslados.edit', $traslado->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="deleteTraslado({{ $traslado->id }})">
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
    .btn { padding: 8px 16px; border-radius: 6px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#trasladosTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' }
    });
});

function deleteTraslado(id) {
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
                url: `/traslados/${id}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
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
