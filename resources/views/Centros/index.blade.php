{{-- resources/views/centros/index.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Centros')

@section('dashboard-content')
<div class="section-header">
    <div>
        <h2>Gestión de Centros</h2>
        <p>Administra los centros de formación</p>
    </div>
    <button class="btn btn-success" onclick="openCreateModal()">
        <i class="fas fa-plus"></i> Agregar Centro
    </button>
</div>

<div class="content-card">
    <table class="table table-striped" id="centrosTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Centro</th>
                <th>Municipio</th>
                <th>Departamento</th>
                <th>Dirección</th>
                <th>Regional</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centros as $centro)
            <tr>
                <td>{{ $centro->id }}</td>
                <td>{{ $centro->nom_centro }}</td>
                <td>{{ $centro->id_municipio }}</td>
                <td>{{ $centro->departamento }}</td>
                <td>{{ $centro->direc_centro }}</td>
                <td>{{ $centro->id_regional }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="editCentro({{ $centro->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCentro({{ $centro->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
                                <label>Nombre Centro *</label>
                                <input type="text" name="nom_centro" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Municipio *</label>
                                <input type="text" name="id_municipio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Barrio</label>
                                <input type="text" name="barrio_centro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Dirección</label>
                                <input type="text" name="direc_centro" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Extensión</label>
                                <input type="text" name="extension" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Regional</label>
                                <input type="text" name="id_regional" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Departamento</label>
                                <input type="text" name="departamento" class="form-control">
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
    $('#centrosTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json'
        }
    });
});

let isEdit = false;
let editId = null;

function openCreateModal() {
    isEdit = false;
    $('#modalTitle').text('Agregar Centro');
    $('#centroForm')[0].reset();
    $('#centroModal').modal('show');
}

function editCentro(id) {
    isEdit = true;
    editId = id;
    $('#modalTitle').text('Editar Centro');
    
    $.get(`/centros/${id}`, function(data) {
        $('[name="nom_centro"]').val(data.nom_centro);
        $('[name="id_municipio"]').val(data.id_municipio);
        $('[name="barrio_centro"]').val(data.barrio_centro);
        $('[name="direc_centro"]').val(data.direc_centro);
        $('[name="extension"]').val(data.extension);
        $('[name="id_regional"]').val(data.id_regional);
        $('[name="departamento"]').val(data.departamento);
        $('#centroModal').modal('show');
    });
}

function deleteCentro(id) {
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
                url: `/centros/${id}`,
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

$('#centroForm').submit(function(e) {
    e.preventDefault();
    
    let url = isEdit ? `/centros/${editId}` : '/centros';
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
            $('#centroModal').modal('hide');
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