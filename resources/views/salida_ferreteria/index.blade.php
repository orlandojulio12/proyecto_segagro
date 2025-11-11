{{-- resources/views/salida_ferreteria/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Salidas de Ferretería')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Salidas de Ferretería</h2>
        <p class="text-muted">Administra las salidas de materiales del inventario</p>
    </div>
    <a href="{{ route('salida_ferreteria.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Registrar Salida
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
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Salidas</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="salidasTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                    <th><i class="fas fa-user me-1"></i>Funcionario</th>
                    <th><i class="fas fa-building me-1"></i>Centro</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede</th>
                    <th><i class="fas fa-file-alt me-1"></i>F14</th>
                    <th><i class="fas fa-boxes me-1"></i>Materiales</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salidas as $salida)
                <tr>
                    <td><strong>#{{ $salida->id }}</strong></td>
                    <td>
                        <span class="badge bg-info">
                            <i class="fas fa-calendar-day me-1"></i>
                            {{ $salida->fecha_salida->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>{{ $salida->user->name ?? 'N/A' }}</td>
                    <td>{{ $salida->centro->nom_centro ?? 'N/A' }}</td>
                    <td>{{ $salida->sede->nom_sede ?? 'N/A' }}</td>
                    <td>
                        @if($salida->f14)
                            <span class="badge bg-warning">{{ $salida->f14 }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-success">
                            <i class="fas fa-box me-1"></i>{{ $salida->detalles->count() }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('salida_ferreteria.show', $salida) }}" 
                               class="btn btn-sm btn-info" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('salida_ferreteria.edit', $salida) }}" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteSalida({{ $salida->id }})" 
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
                        <h5 class="text-muted">No hay salidas registradas</h5>
                        <p class="text-muted mb-3">Comienza registrando tu primera salida de materiales</p>
                        {{-- <a href="{{ route('salida_ferreteria.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Registrar Primera Salida
                        </a> --}}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos específicos para salida_ferreteria - No afectan al layout */
    .salida-ferreteria-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .salida-ferreteria-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .salida-ferreteria-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .salida-ferreteria-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .salida-ferreteria-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .salida-ferreteria-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .salida-ferreteria-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .salida-ferreteria-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .salida-ferreteria-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .salida-ferreteria-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .salida-ferreteria-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .salida-ferreteria-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .salida-ferreteria-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .salida-ferreteria-index .table-modern .empty-state {
        background: #fafafa;
    }

    .salida-ferreteria-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción - CON SPACING */
    .salida-ferreteria-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .salida-ferreteria-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .salida-ferreteria-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .salida-ferreteria-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-left: 8px;
        text-decoration: none;    
    }

    .salida-ferreteria-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .salida-ferreteria-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .salida-ferreteria-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .salida-ferreteria-index .btn-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .salida-ferreteria-index .btn-warning:hover {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
    }

    .salida-ferreteria-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .salida-ferreteria-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    /* Badges */
    .salida-ferreteria-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .salida-ferreteria-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .salida-ferreteria-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .salida-ferreteria-index .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    /* Alerts */
    .salida-ferreteria-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .salida-ferreteria-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .salida-ferreteria-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .salida-ferreteria-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .salida-ferreteria-index .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('salida-ferreteria-index');

    function deleteSalida(id) {
        if (!confirm('¿Estás seguro de eliminar esta salida?\n\n⚠️ Las cantidades se devolverán al inventario.')) {
            return;
        }

        fetch(`/salida-ferreteria/${id}`, {
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
                alert('❌ Error al eliminar la salida');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al eliminar la salida');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
            $('#salidasTable').DataTable({
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