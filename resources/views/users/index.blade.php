@extends('layouts.dashboard')

@section('page-title', 'Usuarios')

@section('dashboard-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Usuarios</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Sede</th>
                            <th>Centro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($user->sedes->isNotEmpty())
                                        {{ $user->sedes->pluck('nom_sede')->join(', ') }}
                                    @else
                                        <span class="text-muted">Sin sede asignada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->sedes->isNotEmpty())
                                        {{ $user->sedes->pluck('centro.nom_centro')->unique()->join(', ') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $user->state ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->state ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Desactivar" 
                                                    onclick="return confirm('¿Desactivar este usuario?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
.btn-warning { 
    background: #ffc107; 
    color: #000; 
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
.bg-danger { background: #dc3545; color: white; }
.bg-success { background: #28a745; color: white; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush