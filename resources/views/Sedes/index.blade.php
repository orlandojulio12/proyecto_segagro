{{-- resources/views/sedes/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Sedes')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Gestión de Sedes</h2>
        <p class="text-muted">Administra las sedes de los centros</p>
    </div>
    <button class="btn btn-success shadow-sm" onclick="openCreateModal()">
        <i class="fas fa-plus me-2"></i>Agregar Sede
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
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Listado de Sedes</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-modern" id="sedesTable">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th><i class="fas fa-building me-1"></i>Nombre Sede</th>
                    <th><i class="fas fa-university me-1"></i>Centro</th>
                    <th><i class="fas fa-map-marker-alt me-1"></i>Localidad</th>
                    <th><i class="fas fa-home me-1"></i>Dirección</th>
                    <th><i class="fas fa-map-signs me-1"></i>Barrio</th>
                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sedes as $sede)
                <tr>
                    <td><strong>#{{ $sede->id }}</strong></td>
                    <td>{{ $sede->nom_sede }}</td>
                    <td>
                        <span class="badge bg-info">{{ $sede->centro->nom_centro ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $sede->localidad ?? '-' }}</span>
                    </td>
                    <td>{{ $sede->direc_sede ?? '-' }}</td>
                    <td>{{ $sede->barrio_sede ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-info" onclick="editSede({{ $sede->id }})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteSede({{ $sede->id }})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay sedes registradas</h5>
                        <p class="text-muted mb-3">Comienza registrando tu primera sede</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div class="modal fade" id="sedeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Agregar Sede</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sedeForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Nombre Sede *</label>
                                <input type="text" name="nom_sede" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Centro *</label>
                                <select name="centro_id" class="form-control" required>
                                    <option value="">Seleccionar Centro</option>
                                    @foreach($centros as $centro)
                                    <option value="{{ $centro->id }}">{{ $centro->nom_centro }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Matrícula Inmobiliaria</label>
                                <input type="text" name="matricula_inmobiliario" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Localidad</label>
                                <input type="text" name="localidad" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Barrio</label>
                                <input type="text" name="barrio_sede" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Dirección</label>
                                <input type="text" name="direc_sede" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"></textarea>
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
    .sedes-index .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sedes-index .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .sedes-index .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .sedes-index .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .sedes-index .table-header {
        padding-bottom: 15px;
        border-bottom: 2px solid #4cd137;
    }

    .sedes-index .table-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .sedes-index .table-header h5 i {
        color: #4cd137;
    }

    /* Tabla moderna */
    .sedes-index .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .sedes-index .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .sedes-index .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    .sedes-index .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .sedes-index .table-modern tbody tr:hover:not(.empty-state) {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .sedes-index .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .sedes-index .table-modern .empty-state {
        background: #fafafa;
    }

    .sedes-index .table-modern .empty-state:hover {
        background: #fafafa;
        transform: none;
        box-shadow: none;
    }

    /* Botones de acción */
    .sedes-index .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .sedes-index .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .sedes-index .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .sedes-index .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        padding: 10px 20px;
        font-weight: 500;
    }

    .sedes-index .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .sedes-index .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .sedes-index .btn-info:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    .sedes-index .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .sedes-index .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    .sedes-index .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .sedes-index .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #4b5156 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    /* Badges */
    .sedes-index .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .sedes-index .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .sedes-index .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    /* Alerts */
    .sedes-index .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .sedes-index .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Modal */
    .sedes-index .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .sedes-index .modal-header {
        border-bottom: 2px solid #4cd137;
    }

    .sedes-index .modal-title {
        color: #2c3e50;
        font-weight: 600;
    }

    .sedes-index .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .sedes-index .form-control:focus {
        border-color: #4cd137;
        box-shadow: 0 0 8px rgba(76, 209, 55, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sedes-index .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .sedes-index .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .sedes-index .action-buttons .btn {
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
    document.body.classList.add('sedes-index');

    $(document).ready(function() {
        $('#sedesTable').DataTable({
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
        $('#modalTitle').text('Agregar Sede');
        $('#sedeForm')[0].reset();
        $('#sedeModal').modal('show');
    }

    function editSede(id) {
        isEdit = true;
        editId = id;
        $('#modalTitle').text('Editar Sede');
        
        fetch(`/sedes/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            $('[name="nom_sede"]').val(data.nom_sede);
            $('[name="centro_id"]').val(data.centro_id);
            $('[name="matricula_inmobiliario"]').val(data.matricula_inmobiliario);
            $('[name="localidad"]').val(data.localidad);
            $('[name="barrio_sede"]').val(data.barrio_sede);
            $('[name="direc_sede"]').val(data.direc_sede);
            $('[name="descripcion"]').val(data.descripcion);
            $('#sedeModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo cargar la información de la sede', 'error');
        });
    }

    function deleteSede(id) {
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
                fetch(`/sedes/${id}`, {
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
                        Swal.fire('Error', data.message || 'No se pudo eliminar la sede', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar la sede', 'error');
                });
            }
        });
    }

    $('#sedeForm').submit(function(e) {
        e.preventDefault();
        
        let url = isEdit ? `/sedes/${editId}` : '/sedes';
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
                $('#sedeModal').modal('hide');
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
                Swal.fire('Error', data.message || 'Error al guardar la sede', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'Error al guardar la sede';
            if (error.responseJSON && error.responseJSON.errors) {
                errorMessage = Object.values(error.responseJSON.errors).join('\n');
            }
            Swal.fire('Error', errorMessage, 'error');
        });
    });
</script>
@endpush