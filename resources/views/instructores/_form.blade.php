<div class="row g-3">
    <div class="col-6">
        <label>Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" id="{{ $prefix }}_nombre" class="form-control"
            value="{{ old('nombre', $instructor?->nombre) }}" required placeholder="Ej: Juan">
    </div>
    <div class="col-6">
        <label>Apellido <span class="text-danger">*</span></label>
        <input type="text" name="apellido" id="{{ $prefix }}_apellido" class="form-control"
            value="{{ old('apellido', $instructor?->apellido) }}" required placeholder="Ej: Pérez">
    </div>
</div>
<div class="mb-3 mt-3">
    <label>Número de Documento <span class="text-danger">*</span></label>
    <input type="text" name="documento" id="{{ $prefix }}_documento" class="form-control"
        value="{{ old('documento', $instructor?->documento) }}" required placeholder="Ej: 12345678">
</div>
<div class="mb-3">
    <label>Correo Electrónico</label>
    <input type="email" name="email" id="{{ $prefix }}_email" class="form-control"
        value="{{ old('email', $instructor?->email) }}" placeholder="instructor@sena.edu.co">
</div>
<div class="mb-3">
    <label>Teléfono</label>
    <input type="text" name="telefono" id="{{ $prefix }}_telefono" class="form-control"
        value="{{ old('telefono', $instructor?->telefono) }}" placeholder="Ej: 3001234567">
</div>
<div class="mb-3">
    <label>Especialidad / Área de Conocimiento</label>
    <input type="text" name="especialidad" id="{{ $prefix }}_especialidad" class="form-control"
        value="{{ old('especialidad', $instructor?->especialidad) }}" placeholder="Ej: Tecnología de Software">
</div>
<div class="mb-3">
    <label>Tipo de Contrato <span class="text-danger">*</span></label>
    <select name="tipo_contrato" id="{{ $prefix }}_tipo_contrato" class="form-control" required>
        @foreach(\App\Models\Instructor\Instructor::TIPOS_CONTRATO as $val => $label)
        <option value="{{ $val }}" {{ old('tipo_contrato', $instructor?->tipo_contrato ?? 'contrato') == $val ? 'selected' : '' }}>
            {{ $label }}
        </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="activo" id="{{ $prefix }}_activo" value="1"
            {{ old('activo', $instructor?->activo ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $prefix }}_activo">Instructor Activo</label>
    </div>
</div>
