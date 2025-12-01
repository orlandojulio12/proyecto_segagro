{{-- resources/views/ferreteria/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Gestión de Inventarios')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Inventarios</h2>
        <p class="text-muted">Administra los inventarios de las sedes</p>
    </div>
    <a href="{{ route('ferreteria.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Crear Inventario
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
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Inventarios</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="inventoriesTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede</th>
                    <th><i class="fas fa-building me-1"></i>Centro</th>
                    <th><i class="fas fa-user-tie me-1"></i>Responsable</th>
                    <th><i class="fas fa-user me-1"></i>Funcionario</th>
                    <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                    <th><i class="fas fa-boxes me-1"></i>Materiales</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inventory)
                <tr>
                    <td><strong>#{{ $inventory->id }}</strong></td>
                    <td>{{ $inventory->sede->nom_sede ?? 'N/A' }}</td>
                    <td>{{ $inventory->sede->centro->nom_centro ?? 'N/A' }}</td>
                    <td>{{ $inventory->responsible_department }}</td>
                    <td>{{ $inventory->staff->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-info">
                            <i class="fas fa-calendar-day me-1"></i>
                            {{ $inventory->record_date ? $inventory->record_date->format('d/m/Y') : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">
                            <i class="fas fa-box me-1"></i>{{ $inventory->materials->count() }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('ferreteria.edit', $inventory) }}" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteInventory({{ $inventory->id }})" 
                                    class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay inventarios registrados</h5>
                        <p class="text-muted mb-3">Comienza creando tu primer inventario</p>
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
<style>
    /* Estilos específicos para ferreteria - No afectan al layout */
    .ferreteria-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ferreteria-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .ferreteria-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .ferreteria-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .ferreteria-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .ferreteria-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .ferreteria-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .ferreteria-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .ferreteria-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .ferreteria-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .ferreteria-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .ferreteria-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .ferreteria-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .ferreteria-index .table-modern .empty-state {
        background: #fafafa;
    }

    .ferreteria-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción - CON SPACING */
    .ferreteria-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .ferreteria-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .ferreteria-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .ferreteria-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-left: 8px;
        text-decoration: none;    
    }

    .ferreteria-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .ferreteria-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .ferreteria-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .ferreteria-index .btn-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .ferreteria-index .btn-warning:hover {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
    }

    .ferreteria-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .ferreteria-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    /* Badges */
    .ferreteria-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ferreteria-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .ferreteria-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .ferreteria-index .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    /* Alerts */
    .ferreteria-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .ferreteria-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ferreteria-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .ferreteria-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .ferreteria-index .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('ferreteria-index');

    function deleteInventory(id) {
        if (!confirm('¿Estás seguro de eliminar este inventario?\n\nEsta acción no se puede deshacer.')) {
            return;
        }

        fetch(`/ferreteria/${id}`, {
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
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.section-header').after(alert);
                
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('❌ Error al eliminar el inventario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al eliminar el inventario');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
            $('#inventoriesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                order: [[0, 'desc']],
                pageLength: 10,
                responsive: true
            });
        }
    });
</script>
@endpush