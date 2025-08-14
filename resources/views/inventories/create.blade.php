{{-- resources/views/inventories/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Inventario')

@section('dashboard-content')
<div class="section-header">
    <div>
        <h2>Creación de Inventario Sede</h2>
        <p>Completa la información del inventario</p>
    </div>
    <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('inventories.store') }}" method="POST" id="inventoryForm">
    @csrf
    <div class="row">
        <!-- Información General -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h4 class="text-success mb-3">Información General</h4>
                <p class="text-muted mb-3">Datos básicos para identificar y clasificar la necesidad de infraestructura en el sistema</p>
                
                <div class="form-group mb-3">
                    <label>Dependencia responsable *</label>
                    <input type="text" name="responsible_department" class="form-control" value="{{ old('responsible_department') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Nombre del funcionario *</label>
                    <select name="staff_name" class="form-control" required>
                        <option value="">Seleccionar funcionario</option>
                        @foreach($users as $user)
                        <option value="{{ $user->user_id }}" {{ old('staff_name') == $user->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Centro de formación *</label>
                    <select name="centro_id" id="centroSelect" class="form-control" required>
                        <option value="">Seleccionar centro</option>
                        @foreach($centros as $centro)
                        <option value="{{ $centro->id }}" {{ old('centro_id') == $centro->id ? 'selected' : '' }}>
                            {{ $centro->nom_centro }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Sede de formación *</label>
                    <select name="sede_id" id="sedeSelect" class="form-control" required>
                        <option value="">Primero selecciona un centro</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Detalles de la necesidad -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h4 class="text-success mb-3">Detalles de la necesidad</h4>
                <p class="text-muted mb-3">Detalles relacionados con la necesidad, incluyendo fechas, especificaciones y otros aspectos</p>
                
                <div class="form-group mb-3">
                    <label>Imagen de la necesidad</label>
                    <input type="file" name="image_inventory" class="form-control" accept="image/*">
                    <small class="text-muted">Imagen de la necesidad que deberá mostrar todo lo descrito en esta misma</small>
                </div>

                <div class="form-group mb-3">
                    <label>Descripción de la necesidad *</label>
                    <textarea name="inventory_description" class="form-control" rows="6" required>{{ old('inventory_description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Información materiales -->
    <div class="content-card mb-4">
        <h4 class="text-success mb-3">Información materiales</h4>
        <p class="text-muted mb-3">Datos básicos para cargar los materiales para la necesidad</p>
        
        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-sm" onclick="addMaterial()">
                <i class="fas fa-plus"></i> Agregar Material
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="materialsTable">
                <thead>
                    <tr>
                        <th>Nombre del material</th>
                        <th>Cantidad de material</th>
                        <th>Tipo de material</th>
                        <th>Precio material</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="materials[0][material_name]" class="form-control" required></td>
                        <td><input type="number" name="materials[0][material_quantity]" class="form-control" required></td>
                        <td><input type="text" name="materials[0][material_type]" class="form-control"></td>
                        <td><input type="number" name="materials[0][material_price]" class="form-control" step="0.01"></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterial(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-success">Guardar</button>
    </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let materialIndex = 1;

function addMaterial() {
    const tbody = document.querySelector('#materialsTable tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="materials[${materialIndex}][material_name]" class="form-control" required></td>
        <td><input type="number" name="materials[${materialIndex}][material_quantity]" class="form-control" required></td>
        <td><input type="text" name="materials[${materialIndex}][material_type]" class="form-control"></td>
        <td><input type="number" name="materials[${materialIndex}][material_price]" class="form-control" step="0.01"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterial(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    materialIndex++;
}

function removeMaterial(button) {
    const row = button.closest('tr');
    row.remove();
}

// Filtrar sedes por centro usando AJAX
document.getElementById('centroSelect').addEventListener('change', function() {
    const centroId = this.value;
    const sedeSelect = document.getElementById('sedeSelect');
    
    // Limpiar sedes
    sedeSelect.innerHTML = '<option value="">Cargando sedes...</option>';
    
    if (centroId) {
        fetch(`/centros/${centroId}/sedes`)
            .then(response => response.json())
            .then(sedes => {
                sedeSelect.innerHTML = '<option value="">Seleccionar sede</option>';
                sedes.forEach(sede => {
                    const option = document.createElement('option');
                    option.value = sede.id;
                    option.textContent = sede.nom_sede;
                    sedeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                sedeSelect.innerHTML = '<option value="">Error al cargar sedes</option>';
            });
    } else {
        sedeSelect.innerHTML = '<option value="">Primero selecciona un centro</option>';
    }
});
</script>
@endpush