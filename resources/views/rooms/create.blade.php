@extends('layouts.dashboard')

@section('page-title', 'Nuevo Room')

@section('dashboard-content')

<div class="content-card">

    <h5>Crear Room</h5>

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label>Area <span class="text-danger">*</span></label>
            <select name="area_id" class="form-control" required>
                <option value="">Seleccione un área</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">
                        {{ $area->name }} – {{ $area->sede->nom_sede }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Nombre del Room <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Código</label>
            <input type="text" name="code" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Capacidad</label>
            <input type="number" name="capacity" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Tipo</label>
            <select name="type" class="form-control">
                <option value="classroom">Salon de clases</option>
                <option value="lab">Laboratorio</option>
                <option value="auditorium">Auditorio</option>
            </select>
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="active" value="1" checked>
            <label class="form-check-label">Activo</label>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('rooms.index') }}" class="btn-modern btn-cancel me-2">Cancelar</a>
            <button class="btn-modern btn-save">Guardar</button>
        </div>

    </form>
</div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h5 {
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #43a047;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.1);
            outline: none;
        }

        .btn-modern {
            min-width: 140px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-save {
            background: #4cd137;
            color: #fff;
        }

        .btn-save:hover {
            background: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(76, 209, 55, 0.4);
        }

        .btn-cancel {
            background-color: #fff;
            border: 1px solid #43a047;
            color: #43a047;
        }

        .btn-cancel:hover {
            background: #43a047;
            color: #fff;
            transform: translateY(-2px);
        }

        #imagenPreview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }
    </style>
@endpush