{{-- resources/views/infraestructura/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Infraestructura')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Infraestructura</h2>
        <p class="text-muted">Administra las necesidades de infraestructura</p>
    </div>
    <a href="{{ route('infraestructura.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Nueva Necesidad
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="content-card">
    <div class="table-header mb-3">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Necesidades</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="infraestructuraTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-building me-1"></i>Dependencia</th>
                    <th><i class="fas fa-user me-1"></i>Funcionario</th>
                    <th><i class="fas fa-university me-1"></i>Centro</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede</th>
                    <th><i class="fas fa-tools me-1"></i>Tipo</th>
                    <th><i class="fas fa-exclamation-triangle me-1"></i>Nivel Riesgo</th>
                    <th><i class="fas fa-cogs me-1"></i>Complejidad</th>
                    <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                    <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($infraestructuras as $infra)
                <tr>
                    <td><strong>#{{ $infra->id }}</strong></td>
                    <td>{{ $infra->dependencia->nombre ?? 'N/A' }}</td>
                    <td>{{ $infra->funcionario->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-info">{{ $infra->centro->nom_centro ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $infra->sede->nom_sede ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $infra->tipo_necesidad }}</td>
                    <td>
                        <span class="badge 
                            @if($infra->nivel_riesgo === 'Alto') bg-danger 
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
                    <td>
                        <span class="badge bg-info">{{ $infra->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('infraestructura.edit', $infra) }}" 
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteInfraestructura({{ $infra->id }})" 
                                    class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="11" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay necesidades registradas</h5>
                        <p class="text-muted mb-3">Comienza registrando tu primera necesidad de infraestructura</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* Estilos adaptados de salida_ferreteria */
    .infraestructura-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .infraestructura-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .infraestructura-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .infraestructura-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .infraestructura-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .infraestructura-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .infraestructura-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .infraestructura-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .infraestructura-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .infraestructura-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .infraestructura-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .infraestructura-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .infraestructura-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .infraestructura-index .table-modern .empty-state {
        background: #fafafa;
    }

    .infraestructura-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción */
    .infraestructura-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .infraestructura-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .infraestructura-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .infraestructura-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
    }

    .infraestructura-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .infraestructura-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .infraestructura-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .infraestructura-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .infraestructura-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    /* Badges */
    .infraestructura-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .infraestructura-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .infraestructura-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .infraestructura-index .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    .infraestructura-index .badge.bg-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    }

    /* Alerts */
    .infraestructura-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .infraestructura-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .infraestructura-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .infraestructura-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .infraestructura-index .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('infraestructura-index');

    $(document).ready(function() {
        $('#infraestructuraTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true
        });
    });

    function deleteInfraestructura(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/infraestructura/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show shadow-sm';
                        alert.innerHTML = `
                            <i class="fas fa-check-circle me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.section-header').after(alert);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar la necesidad', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar la necesidad', 'error');
                });
            }
        });
    }
</script>
@endpush