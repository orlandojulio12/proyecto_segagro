@php $p = $mode === 'edit' ? 'edit_' : 'create_'; @endphp

<div class="mb-3">
    <label>Número de Ficha <span class="text-danger">*</span></label>
    <input type="text" name="numero_ficha" id="{{ $p }}numero_ficha" class="form-control"
        value="{{ old('numero_ficha', $ficha?->numero_ficha) }}" required placeholder="Ej: 2850367">
</div>
<div class="mb-3">
    <label>Nombre del Programa <span class="text-danger">*</span></label>
    <input type="text" name="nombre_programa" id="{{ $p }}nombre_programa" class="form-control"
        value="{{ old('nombre_programa', $ficha?->nombre_programa) }}" required>
</div>
<div class="row g-2 mb-3">
    <div class="col-6">
        <label>Nivel de Formación <span class="text-danger">*</span></label>
        <select name="nivel_formacion" id="{{ $p }}nivel_formacion" class="form-control" required>
            @foreach(\App\Models\Ficha\Ficha::NIVELES as $val => $label)
            <option value="{{ $val }}" {{ old('nivel_formacion', $ficha?->nivel_formacion) == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6">
        <label>Modalidad <span class="text-danger">*</span></label>
        <select name="modalidad" id="{{ $p }}modalidad" class="form-control" required>
            @foreach(\App\Models\Ficha\Ficha::MODALIDADES as $val => $label)
            <option value="{{ $val }}" {{ old('modalidad', $ficha?->modalidad) == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-6">
        <label>Estado <span class="text-danger">*</span></label>
        <select name="estado" id="{{ $p }}estado" class="form-control" required>
            @foreach(\App\Models\Ficha\Ficha::ESTADOS as $val => $info)
            <option value="{{ $val }}" {{ old('estado', $ficha?->estado ?? 'en_convocatoria') == $val ? 'selected' : '' }}>{{ $info['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6">
        <label>Jornada <span class="text-danger">*</span></label>
        <select name="jornada" id="{{ $p }}jornada" class="form-control" required>
            @foreach(\App\Models\Ficha\Ficha::JORNADAS as $val => $label)
            <option value="{{ $val }}" {{ old('jornada', $ficha?->jornada) == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Centro / Sede via modal selector --}}
<x-centros-sedes-selector
    :centros="$centros"
    :prefix="$selectorPrefix"
    :centroId="old($selectorPrefix.'_centro_id', $ficha?->centro_id)"
    :sedeId="old($selectorPrefix.'_sede_id', $ficha?->sede_id)"
    :centroNombre="$ficha?->centro?->nom_centro ?? ''"
    :sedeNombre="$ficha?->sede?->nom_sede ?? ''"
/>

<div class="mb-3">
    <label>Instructor Responsable</label>
    <select name="instructor_id" id="{{ $p }}instructor_id" class="form-control">
        <option value="">Sin asignar</option>
        @foreach($instructores as $instr)
        <option value="{{ $instr->id }}" {{ old('instructor_id', $ficha?->instructor_id) == $instr->id ? 'selected' : '' }}>
            {{ $instr->nombre_completo }}
        </option>
        @endforeach
    </select>
</div>
<div class="row g-2 mb-3">
    <div class="col-6">
        <label>Fecha Inicio <span class="text-danger">*</span></label>
        <input type="date" name="fecha_inicio" id="{{ $p }}fecha_inicio" class="form-control"
            value="{{ old('fecha_inicio', $ficha?->fecha_inicio?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-6">
        <label>Fecha Fin <span class="text-danger">*</span></label>
        <input type="date" name="fecha_fin" id="{{ $p }}fecha_fin" class="form-control"
            value="{{ old('fecha_fin', $ficha?->fecha_fin?->format('Y-m-d')) }}" required>
    </div>
</div>
<div class="mb-3">
    <label>N° Aprendices <span class="text-danger">*</span></label>
    <input type="number" name="numero_aprendices" id="{{ $p }}numero_aprendices" class="form-control"
        value="{{ old('numero_aprendices', $ficha?->numero_aprendices ?? 0) }}" min="0" max="50" required>
</div>
