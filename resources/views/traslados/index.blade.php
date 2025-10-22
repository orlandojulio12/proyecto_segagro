{{-- resources/views/traslados/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Necesidades de Traslado')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Traslados</h2>
        <p class="text-muted">Administra las solicitudes de traslado</p>
    </div>
    <a href="{{ route('traslados.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus me-2"></i>Crear Traslado
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
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Traslados</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="trasladosTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-university me-1"></i>Centro Inicial</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede Inicial</th>
                    <th><i class="fas fa-university me-1"></i>Centro Final</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Sede Final</th>
                    <th><i class="fas fa-user me-1"></i>Funcionario</th>
                    <th><i class="fas fa-calendar me-1"></i>Fechas</th>
                    <th><i class="fas fa-boxes me-1"></i>Materiales</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($traslados as $traslado)
                <tr>
                    <td><strong>#{{ $traslado->id }}</strong></td>
                    <td>
                        <span class="badge bg-info">{{ $traslado->centroInicial->nom_centro ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $traslado->sedeInicial->nom_sede ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $traslado->centroFinal->nom_centro ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $traslado->sedeFinal->nom_sede ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $traslado->user->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-warning">
                            {{ $traslado->fecha_inicio ? $traslado->fecha_inicio->format('d/m/Y') : 'N/A' }}
                            -
                            {{ $traslado->fecha_fin ? $traslado->fecha_fin->format('d/m/Y') : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">
                            <i class="fas fa-box me-1"></i>{{ $traslado->materiales->count() }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('traslados.edit', $traslado->id) }}" 
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="deleteTraslado({{ $traslado->id }})" 
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="9" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay traslados registrados</h5>
                        <p class="text-muted mb-3">Comienza registrando tu primera solicitud de traslado</p>
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
    .traslados-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .traslados-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .traslados-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .traslados-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .traslados-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .traslados-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .traslados-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .traslados-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .traslados-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .traslados-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .traslados-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .traslados-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .traslados-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .traslados-index .table-modern .empty-state {
        background: #fafafa;
    }

    .traslados-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción */
    .traslados-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .traslados-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .traslados-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .traslados-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
    }

    .traslados-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .traslados-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .traslados-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .traslados-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .traslados-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    /* Badges */
    .traslados-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .traslados-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .traslados-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .traslados-index .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    /* Alerts */
    .traslados-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .traslados-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .traslados-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .traslados-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .traslados-index .action-buttons .btn {
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
    document.body.classList.add('traslados-index');

    $(document).ready(function() {
        $('#trasladosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true
        });
    });

    function deleteTraslado(id) {
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
                fetch(`/traslados/${id}`, {
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
                        Swal.fire('Error', data.message || 'No se pudo eliminar el traslado', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar el traslado', 'error');
                });
            }
        });
    }
</script>
@endpush