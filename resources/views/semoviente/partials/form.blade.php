<div class="container">
    <form action="{{ route('semoviente.update', $semoviente->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- ================== COLUMNA IZQUIERDA ================== -->
            <div class="col-md-6">
                <!-- Información General -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Información General</h5>
                        <small class="text-muted">Datos básicos para identificar y clasificar la necesidad</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Dependencia responsable*</label>
                            <input type="text" name="responsible_department" class="form-control"
                                value="{{ old('responsible_department', $semoviente->responsible_department) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre del funcionario*</label>
                            <input type="text" name="staff_id" class="form-control"
                                value="{{ old('staff_id', $semoviente->staff_id) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Centro de formación*</label>
                            <select name="training_center" class="form-select">
                                <option value="">Seleccione un centro</option>
                                @foreach($centros as $centro)
                                    <option value="{{ $centro->id }}" {{ $semoviente->training_center == $centro->id ? 'selected' : '' }}>
                                        {{ $centro->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sede de formación*</label>
                            <input type="text" name="sede" class="form-control"
                                value="{{ old('sede', $semoviente->sede) }}">
                        </div>
                    </div>
                </div>

                <!-- Información calendario -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-success">Información calendario</h5>
                        <small class="text-muted">Fechas de inicio y finalización de la necesidad</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Fecha de nacimiento*</label>
                            <input type="date" name="birth_date" class="form-control"
                                value="{{ old('birth_date', $semoviente->birth_date) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hora de nacimiento*</label>
                            <input type="time" name="birth_time" class="form-control"
                                value="{{ old('birth_time', $semoviente->birth_time) }}">
                        </div>
                    </div>
                </div>

                <!-- Detalles de la necesidad -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-success">Detalles de la necesidad</h5>
                        <small class="text-muted">Información complementaria</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Imagen de la necesidad</label>
                            <input type="file" name="image" class="form-control">
                            @if($semoviente->image)
                                <img src="{{ asset('storage/' . $semoviente->image) }}" alt="Imagen" class="img-fluid mt-2" width="150">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================== COLUMNA DERECHA ================== -->
            <div class="col-md-6">
                <!-- Características Adicionales -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Características Adicionales</h5>
                        <small class="text-muted">Información técnica y específica</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Área de nacimiento*</label>
                            <select name="birth_area" class="form-select">
                                <option value="Rural" {{ $semoviente->birth_area == 'Rural' ? 'selected' : '' }}>Rural</option>
                                <option value="Urbano" {{ $semoviente->birth_area == 'Urbano' ? 'selected' : '' }}>Urbano</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ambiente de formación*</label>
                            <input type="text" name="training_environment" class="form-control"
                                value="{{ old('training_environment', $semoviente->training_environment) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Género del nacimiento*</label>
                            <select name="gender" class="form-select">
                                <option value="Macho" {{ $semoviente->gender == 'Macho' ? 'selected' : '' }}>Macho</option>
                                <option value="Femenino" {{ $semoviente->gender == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de nacimiento*</label>
                            <select name="birth_type" class="form-select">
                                <option value="Natural" {{ $semoviente->birth_type == 'Natural' ? 'selected' : '' }}>Natural</option>
                                <option value="Cesárea" {{ $semoviente->birth_type == 'Cesárea' ? 'selected' : '' }}>Cesárea</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de semoviente*</label>
                            <select name="animal_type" class="form-select">
                                <option value="Vaca" {{ $semoviente->animal_type == 'Vaca' ? 'selected' : '' }}>Vaca</option>
                                <option value="Toro" {{ $semoviente->animal_type == 'Toro' ? 'selected' : '' }}>Toro</option>
                                <option value="Becerro" {{ $semoviente->animal_type == 'Becerro' ? 'selected' : '' }}>Becerro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Raza del semoviente*</label>
                            <input type="text" name="breed" class="form-control"
                                value="{{ old('breed', $semoviente->breed) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Peso del semoviente*</label>
                            <input type="text" name="weight" class="form-control"
                                value="{{ old('weight', $semoviente->weight) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Color del semoviente*</label>
                            <input type="text" name="color" class="form-control"
                                value="{{ old('color', $semoviente->color) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paquete de la madre*</label>
                            <input type="text" name="mother_package" class="form-control"
                                value="{{ old('mother_package', $semoviente->mother_package) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Valor aproximado*</label>
                            <input type="number" name="estimated_value" class="form-control"
                                value="{{ old('estimated_value', $semoviente->estimated_value) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado del semoviente*</label>
                            <select name="status" class="form-select">
                                <option value="En venta" {{ $semoviente->status == 'En venta' ? 'selected' : '' }}>En venta</option>
                                <option value="Vivo" {{ $semoviente->status == 'Vivo' ? 'selected' : '' }}>Vivo</option>
                                <option value="Muerto" {{ $semoviente->status == 'Muerto' ? 'selected' : '' }}>Muerto</option>
                                <option value="Sacrificio" {{ $semoviente->status == 'Sacrificio' ? 'selected' : '' }}>Sacrificio</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('semoviente.index') }}" class="btn btn-outline-success">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>



@push('styles')
<style>
    /* Card contenedor */
    .content-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    /* Títulos de sección */
    h4.text-success {
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 15px;
        border-left: 5px solid #28a745;
        padding-left: 10px;
        color: #28a745 !important;
    }

    /* Labels */
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    /* Inputs */
    .form-control {
        border-radius: 6px;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ced4da;
        transition: all 0.2s ease-in-out;
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.25);
    }

    /* Botones */
    .btn-success {
        background: #28a745;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
    }
    .btn-success:hover {
        background: #218838;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }

    /* Imagen previa */
    .img-thumbnail {
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    /* Espaciado entre columnas */
    .row > .col-md-6 {
        margin-bottom: 20px;
    }
</style>
@endpush
