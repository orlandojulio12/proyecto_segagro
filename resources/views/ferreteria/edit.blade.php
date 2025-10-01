{{-- resources/views/inventories/edit.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Editar Inventario')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Edición de Inventario Sede</h2>
            <p class="text-muted">Modifica la información del inventario seleccionado</p>
        </div>
        <a href="{{ route('ferreteria.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ferreteria.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex gap-4">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                    <p class="section-subtitle">Datos básicos para identificar y clasificar la necesidad</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia responsable *</label>
                        <input type="text" name="responsible_department" class="form-control modern-input"
                            value="{{ old('responsible_department', $inventory->responsible_department) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Nombre del funcionario *</label>
                        <select name="staff_name" class="form-select modern-input" required>
                            <option value="">Seleccionar funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('staff_name', $inventory->staff_name) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Centro de formación *</label>
                        <select name="centro_id" id="centroSelect" class="form-select modern-input" required>
                            <option value="">Seleccionar centro</option>
                            @foreach ($centros as $centro)
                                <option value="{{ $centro->id }}"
                                    {{ $inventory->sede->centro_id == $centro->id ? 'selected' : '' }}>
                                    {{ $centro->nom_centro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de formación *</label>
                        <select name="sede_id" id="sedeSelect" class="form-select modern-input" required>
                            <option value="">Seleccionar sede</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}"
                                    {{ $inventory->sede_id == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nom_sede }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Detalles de la necesidad</h5>
                    <p class="section-subtitle">Detalles relacionados con la necesidad</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Imagen actual</label><br>
                        @if ($inventory->image_inventory)
                            <img src="{{ asset('storage/' . $inventory->image_inventory) }}"
                                class="img-fluid rounded mb-2 shadow-sm" style="max-height: 150px;">
                        @endif
                        <input type="file" name="image_inventory" class="form-control modern-input mt-2"
                            accept="image/*">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Descripción *</label>
                        <textarea name="inventory_description" class="form-control modern-input" rows="5" required>{{ old('inventory_description', $inventory->inventory_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materiales -->
        <div class="content-card mb-4">
            <h5 class="section-title"><i class="fas fa-boxes"></i> Información materiales</h5>
            <p class="section-subtitle">Materiales necesarios para la necesidad</p>

            <div class="mb-3">
                <button type="button" class="btn btn-primary shadow-sm" onclick="addMaterial()">
                    <i class="fas fa-plus"></i> Agregar Material
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle"id="materialsTable">
                    <thead class="table-success">
                        <tr>
                            <th>Nombre del material</th>
                            <th>Cantidad</th>
                            <th>Tipo</th>
                            <th>Precio Unitario</th>
                            <th>IVA</th>
                            <th>Total sin IVA</th>
                            <th>Total con IVA</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventory->materials as $i => $material)
                            <tr>
                                <td><input type="text" name="materials[{{ $i }}][material_name]"
                                        class="form-control modern-input" value="{{ $material->material_name }}" required>
                                </td>

                                <td><input type="number" name="materials[{{ $i }}][material_quantity]"
                                        class="form-control modern-input" value="{{ $material->material_quantity }}"
                                        required></td>

                                <td>
                                    <select name="materials[{{ $i }}][material_type]"
                                        class="form-control modern-input">
                                        <option value="Consumible"
                                            {{ $material->material_type == 'Consumible' ? 'selected' : '' }}>Consumible
                                        </option>
                                        <option value="Herramienta"
                                            {{ $material->material_type == 'Herramienta' ? 'selected' : '' }}>Herramienta
                                        </option>
                                    </select>
                                </td>

                                <td><input type="number" name="materials[{{ $i }}][material_price]"
                                        class="form-control modern-input" value="{{ $material->material_price }}"
                                        step="0.01" required></td>

                                <!-- Select IVA -->
                                <td>
                                    <select name="materials[{{ $i }}][iva_percentage]"
                                        class="form-control modern-input">
                                        <option value="0" {{ $material->iva_percentage == 0 ? 'selected' : '' }}>0%
                                        </option>
                                        <option value="5" {{ $material->iva_percentage == 5 ? 'selected' : '' }}>5%
                                        </option>
                                        <option value="12" {{ $material->iva_percentage == 12 ? 'selected' : '' }}>12%
                                        </option>
                                        <option value="19" {{ $material->iva_percentage == 19 ? 'selected' : '' }}>19%
                                        </option>
                                    </select>
                                </td>

                                <!-- Totales calculados -->
                                <td><input type="text" name="materials[{{ $i }}][total_without_tax]"
                                        class="form-control modern-input" value="{{ $material->total_without_tax }}"
                                        readonly></td>

                                <td><input type="text" name="materials[{{ $i }}][total_with_tax]"
                                        class="form-control modern-input" value="{{ $material->total_with_tax }}"
                                        readonly></td>

                                <td><input type="text" name="materials[{{ $i }}][observations]"
                                        class="form-control modern-input" value="{{ $material->observations }}"></td>

                                <td>
                                    <button type="button" class="btn btn-outline-danger btn-sm shadow-sm"
                                        onclick="removeMaterial(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="form-footer">
            <a href="{{ route('ferreteria.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .content-card {
            background: #fff;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            margin-bottom: 28px;
        }

        .content-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        /* Encabezados */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2f9e44;
            margin-bottom: 6px;
        }

        .section-subtitle {
            font-size: 14px;
            color: #868e96;
            margin-bottom: 22px;
        }

        /* Inputs modernos */
        .modern-input {
            border-radius: 10px;
            border: 1px solid #dee2e6;
            padding: 10px 14px;
            font-size: 15px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .modern-input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.25rem rgba(76, 209, 55, 0.2);
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* Tabla moderna */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }

        .table-modern thead {
            background: #4cd137;
            color: #fff;
            border-radius: 10px;
        }

        .table-modern thead th {
            padding: 14px;
            font-size: 15px;
            text-align: center;
            font-weight: 600;
        }

        .table-modern tbody tr {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table-modern tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-modern td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .table-modern input {
            text-align: center;
        }

        /* Botones */
        .btn-lg {
            padding: 12px 26px;
            font-size: 16px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-outline-danger {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #e63946;
            color: #fff;
            transform: scale(1.05);
        }

        /* Footer botones */
        .form-footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-footer {
                flex-direction: column;
            }

            .form-footer a,
            .form-footer button {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let materialIndex = {{ count($inventory->materials) }};

        function addMaterial() {
            const tbody = document.querySelector('#materialsTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="text" name="materials[${materialIndex}][material_name]" class="form-control modern-input" required></td>
            <td><input type="number" name="materials[${materialIndex}][material_quantity]" class="form-control modern-input" required></td>
            <td><input type="text" name="materials[${materialIndex}][material_type]" class="form-control modern-input"></td>
            <td><input type="number" name="materials[${materialIndex}][material_price]" class="form-control modern-input" step="0.01"></td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm" onclick="removeMaterial(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
            tbody.appendChild(row);
            materialIndex++;
        }

        function removeMaterial(button) {
            button.closest('tr').remove();
        }
    </script>
@endpush
