@extends('layouts.dashboard')


@section('page-title', 'Creación de necesidad infraestructura')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mt-4">Creación de necesidad infraestructura</h2>
    <form action="{{ route('infraestructura.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="row">
            {{-- Información General --}}
            <div class="col-md-6">
                <h5 class="mb-3">Información General</h5>

                <div class="mb-3">
                    <label class="form-label">Dependencia responsable*</label>
                    <select name="dependencia_responsable" class="form-select" required>
                        <option value="">Seleccione...</option>
                        {{-- Opciones dinámicas --}}
                    </select>
                </div>

              <div class="form-group mb-3">
                    <label>Nombre del funcionario *</label>
                    <select name="staff_name" class="form-control" required>
                        <option value="">Seleccionar funcionario</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('staff_name') == $user->id ? 'selected' : '' }}>
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

                <div class="mb-3">
                    <label class="form-label">Área de la necesidad*</label>
                    <select name="area_necesidad" class="form-select" required>
                        <option value="">Seleccione...</option>
                    </select>
                </div>
            </div>

            {{-- Características adicionales --}}
            <div class="col-md-6">
                <h5 class="mb-3">Características adicionales</h5>

                <div class="mb-3">
                    <label class="form-label">Nivel de riesgo*</label>
                    <select name="nivel_riesgo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option>Bajo</option>
                        <option>Medio</option>
                        <option>Alto</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nivel de complejidad*</label>
                    <select name="nivel_complejidad" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option>Bajo</option>
                        <option>Medio</option>
                        <option>Alto</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de necesidad*</label>
                    <select name="tipo_necesidad" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option>Eléctrica</option>
                        <option>Refrigeración</option>
                        <option>Infraestructura</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Motivo de la necesidad*</label>
                    <select name="motivo_necesidad" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option>Mantenimiento</option>
                        <option>Instalación</option>
                        <option>Reparación</option>
                    </select>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="requiere_traslado" id="requiereTraslado">
                    <label class="form-check-label" for="requiereTraslado">¿Requiere traslado?</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sede de formación*</label>
                    <select name="sede_formacion_secundaria" class="form-select">
                        <option value="">Seleccione...</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Detalles de la necesidad --}}
        <div class="mt-4">
            <h5>Detalles de la necesidad</h5>
            <div class="mb-3">
                <label class="form-label">Imagen de la necesidad*</label>
                <input type="file" name="imagen" class="form-control">
                <small class="form-text text-muted">Sube una imagen relacionada con la necesidad.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción de la necesidad*</label>
                <textarea name="descripcion" rows="4" class="form-control" required></textarea>
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('infraestructura.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
@endsection
