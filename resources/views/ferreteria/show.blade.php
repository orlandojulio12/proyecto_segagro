@extends('layouts.dashboard')

@section('page-title', 'Detalle Inventario')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Detalle del Inventario</h2>
            <p class="text-muted">Consulta la información registrada del inventario</p>
        </div>
        <a href="{{ route('ferreteria.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row">
        <!-- Información General -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                <p class="section-subtitle">Datos básicos del inventario</p>
                
                <ul class="list-unstyled">
                    <li><strong>Dependencia responsable:</strong> {{ $inventory->responsible_department }}</li>
                    <li><strong>Funcionario:</strong> {{ $inventory->staff->name ?? 'No asignado' }}</li>
                    <li><strong>Centro:</strong> {{ $inventory->sede->centro->nom_centro ?? 'N/A' }}</li>
                    <li><strong>Sede:</strong> {{ $inventory->sede->nom_sede ?? 'N/A' }}</li>
                    <li><strong>Fecha de registro:</strong> {{ $inventory->record_date->format('d/m/Y H:i') }}</li>
                </ul>
            </div>
        </div>

        <!-- Detalles de la necesidad -->
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Detalles de la necesidad</h5>
                <p class="section-subtitle">Descripción y evidencia de la necesidad</p>

                @if ($inventory->image_inventory)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $inventory->image_inventory) }}"
                             class="img-fluid rounded shadow-sm" alt="Imagen inventario">
                    </div>
                @endif
                <p>{{ $inventory->inventory_description }}</p>
            </div>
        </div>
    </div>

    <!-- Información materiales -->
    <div class="content-card mb-4">
        <h5 class="section-title"><i class="fas fa-boxes"></i> Materiales</h5>
        <p class="section-subtitle">Listado de materiales asociados a la necesidad</p>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventory->materials as $material)
                        <tr>
                            <td>{{ $material->material_name }}</td>
                            <td>{{ $material->material_quantity }}</td>
                            <td>{{ $material->material_type ?? '-' }}</td>
                            <td>${{ number_format($material->material_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No se han registrado materiales</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('ferreteria.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
            <i class="fas fa-times"></i> Cancelar
        </a>
        <a href="{{ route('ferreteria.edit', $inventory->id) }}" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
@endsection

@push('styles')
<style>
    .content-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease-in-out;
    }

    .content-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #2f9e44;
        margin-bottom: 6px;
    }

    .section-subtitle {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .btn-lg {
        padding: 10px 22px;
        font-size: 15px;
        border-radius: 8px;
    }
</style>
@endpush
