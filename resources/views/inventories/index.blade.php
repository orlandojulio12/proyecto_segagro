{{-- resources/views/inventories/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Inventarios')

@section('dashboard-content')
<div class="section-header">
    <div>
        <h2>Gestión de Inventarios</h2>
        <p>Administra los inventarios de las sedes</p>
    </div>
    <a href="{{ route('inventories.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Crear Inventario
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="content-card">
    <table class="table table-striped" id="inventoriesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sede</th>
                <th>Centro</th>
                <th>Responsable</th>
                <th>Funcionario</th>
                <th>Fecha</th>
                <th>Materiales</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
            <tr>
                <td>{{ $inventory->id }}</td>
                <td>{{ $inventory->sede->nom_sede ?? 'N/A' }}</td>
                <td>{{ $inventory->sede->centro->nom_centro ?? 'N/A' }}</td>
                <td>{{ $inventory->responsible_department }}</td>
                <td>{{ $inventory->staff->name ?? 'N/A' }}</td>
                <td>{{ $inventory->record_date ? $inventory->record_date->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $inventory->materials->count() }}</td>
                <td>
                    <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="deleteInventory({{ $inventory->id }})">
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#inventoriesTable').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json' }
    });
});

function deleteInventory(id) {
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
                url: `/inventories/${id}`,
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