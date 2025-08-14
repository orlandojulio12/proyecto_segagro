{{-- resources/views/sedes/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Sedes')

@section('dashboard-content')
<div class="section-header">
    <div>
        <h2>Gestión de Sedes</h2>
        <p>Administra las sedes de los centros</p>
    </div>
    <button class="btn btn-success" onclick="openCreateModal()">
        <i class="fas fa-plus"></i> Agregar Sede
    </button>
</div>

<div class="content-card">
    <table class="table table-striped" id="sedesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Sede</th>
                <th>Centro</th>
                <th>Localidad</th>
                <th>Dirección</th>
                <th>Barrio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sedes as $sede)
            <tr>
                <td>{{ $sede->id }}</td>
                <td>{{ $sede->nom_sede }}</td>
                <td>{{ $sede->centro->nom_centro ?? 'N/A' }}</td>
                <td>{{ $sede->localidad }}</td>
                <td>{{ $sede->direc_sede }}</td>
                <td>{{ $sede->barrio_sede }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="editSede({{ $sede->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteSede({{ $sede->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
                                <label>Nombre Sede *</label>
                                <input type="text" name="nom_sede" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Centro *</label>
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
                                <label>Matrícula Inmobiliaria</label>
                                <input type="text" name="matricula_inmobiliario" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Localidad</label>
                                <input type="text" name="localidad" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Barrio</label>
                                <input type="text" name="barrio_sede" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Dirección</label>
                                <input type="text" name="direc_sede" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
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
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}
.btn-success { background: #4cd137; color: white; }
.btn-primary { background: #007bff; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-secondary { background: #6c757d; color: white; }
.btn-sm { padding: 4px 8px; font-size: 12px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#sedesTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
        }
    });
});

let isEdit = false;
let editId = null;

function openCreateModal() {
    isEdit = false;
    $('#modalTitle').text('Agregar Sede');
    $('#sedeForm')[0].reset();
    $('#sedeModal').modal('show');
}

function editSede(id) {
    isEdit = true;
    editId = id;
    $('#modalTitle').text('Editar Sede');
    
    $.get(`/sedes/${id}`, function(data) {
        $('[name="nom_sede"]').val(data.nom_sede);
        $('[name="centro_id"]').val(data.centro_id);
        $('[name="matricula_inmobiliario"]').val(data.matricula_inmobiliario);
        $('[name="localidad"]').val(data.localidad);
        $('[name="barrio_sede"]').val(data.barrio_sede);
        $('[name="direc_sede"]').val(data.direc_sede);
        $('[name="descripcion"]').val(data.descripcion);
        $('#sedeModal').modal('show');
    });
}

function deleteSede(id) {
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
                url: `/sedes/${id}`,
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

$('#sedeForm').submit(function(e) {
    e.preventDefault();
    
    let url = isEdit ? `/sedes/${editId}` : '/sedes';
    let method = isEdit ? 'PUT' : 'POST';
    
    let formData = new FormData(this);
    if (isEdit) formData.append('_method', 'PUT');
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#sedeModal').modal('hide');
            Swal.fire('Éxito', response.message, 'success');
            location.reload();
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let errorMessage = '';
            for (let field in errors) {
                errorMessage += errors[field][0] + '\n';
            }
            Swal.fire('Error', errorMessage, 'error');
        }
    });
});
</script>
@endpush