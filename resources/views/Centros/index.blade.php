{{-- resources/views/centros/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Centros')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Centros</h2>
        <p class="text-muted">Administra los centros de formación</p>
    </div>
    <button class="btn btn-success shadow-sm" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i>Agregar Centro
    </button>
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
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Centros</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="centrosTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-building me-1"></i>Nombre Centro</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Municipio</th>
                    <th><i class="fas fa-map me-1"></i>Departamento</th>
                    <th><i class="fas fa-home me-1"></i>Dirección</th>
                    <th><i class="fas fa-globe me-1"></i>Regional</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centros as $centro)
                <tr>
                    <td><strong>#{{ $centro->id }}</strong></td>
                    <td>{{ $centro->nom_centro }}</td>
                    <td>
                        <span class="badge bg-info">{{ $centro->id_municipio }}</span>
                    </td>
                    <td>{{ $centro->departamento }}</td>
                    <td>{{ $centro->direc_centro ?? '-' }}</td>
                    <td>
                        <span class="badge bg-success">{{ $centro->id_regional }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-info" onclick="editCentro({{ $centro->id }})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteCentro({{ $centro->id }})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay centros registrados</h5>
                        <p class="text-muted mb-3">Comienza registrando tu primer centro de formación</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div class="modal fade" id="centroModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Agregar Centro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="centroForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Nombre Centro *</label>
                                <input type="text" name="nom_centro" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Municipio *</label>
                                <input type="text" name="id_municipio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Barrio</label>
                                <input type="text" name="barrio_centro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Dirección</label>
                                <input type="text" name="direc_centro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Extensión</label>
                                <input type="text" name="extension" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Regional</label>
                                <input type="text" name="id_regional" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Departamento</label>
                                <input type="text" name="departamento" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success shadow-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* Estilos adaptados de salida_ferreteria */
    .centros-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .centros-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .centros-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .centros-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .centros-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .centros-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .centros-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .centros-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .centros-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .centros-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .centros-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .centros-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .centros-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .centros-index .table-modern .empty-state {
        background: #fafafa;
    }

    .centros-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción */
    .centros-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .centros-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .centros-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .centros-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
    }

    .centros-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .centros-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .centros-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .centros-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .centros-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    .centros-index .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .centros-index .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #4b5156 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    /* Badges */
    .centros-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .centros-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .centros-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    /* Alerts */
    .centros-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .centros-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Modal */
    .centros-index .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .centros-index .modal-header {
        border-bottom: 2px solid #4cd137;
    }

    .centros-index .modal-title {
        color: #2c3e50;
        font-weight: 600;
    }

    .centros-index .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .centros-index .form-control:focus {
        border-color: #4cd137;
        box-shadow: 0 0 8px rgba(76, 209, 55, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .centros-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .centros-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .centros-index .action-buttons .btn {
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
    document.body.classList.add('centros-index');

    $(document).ready(function() {
        $('#centrosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true
        });
    });

    function openCreateModal() {
        isEdit = false;
        editId = null;
        $('#modalTitle').text('Agregar Centro');
        $('#centroForm')[0].reset();
        $('#centroModal').modal('show');
    }

    function editCentro(id) {
        isEdit = true;
        editId = id;
        $('#modalTitle').text('Editar Centro');
        
        fetch(`/centros/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            $('[name="nom_centro"]').val(data.nom_centro);
            $('[name="id_municipio"]').val(data.id_municipio);
            $('[name="barrio_centro"]').val(data.barrio_centro);
            $('[name="direc_centro"]').val(data.direc_centro);
            $('[name="extension"]').val(data.extension);
            $('[name="id_regional"]').val(data.id_regional);
            $('[name="departamento"]').val(data.departamento);
            $('#centroModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo cargar la información del centro', 'error');
        });
    }

    function deleteCentro(id) {
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
                fetch(`/centros/${id}`, {
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
                        Swal.fire('Error', data.message || 'No se pudo eliminar el centro', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar el centro', 'error');
                });
            }
        });
    }

    $('#centroForm').submit(function(e) {
        e.preventDefault();
        
        let url = isEdit ? `/centros/${editId}` : '/centros';
        let method = isEdit ? 'PUT' : 'POST';
        
        let formData = new FormData(this);
        if (isEdit) formData.append('_method', 'PUT');
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#centroModal').modal('hide');
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
                Swal.fire('Error', data.message || 'Error al guardar el centro', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'Error al guardar el centro';
            if (error.responseJSON && error.responseJSON.errors) {
                errorMessage = Object.values(error.responseJSON.errors).join('\n');
            }
            Swal.fire('Error', errorMessage, 'error');
        });
    });
</script>
@endpush