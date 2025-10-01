{{-- resources/views/inventories/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Inventario')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Creación de Inventario Sede</h2>
            <p class="text-muted">Completa la información del inventario</p>
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

    <form action="{{ route('ferreteria.store') }}" method="POST" id="inventoryForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Información General</h5>
                    <p class="section-subtitle">Datos básicos para identificar y clasificar la necesidad</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Dependencia responsable *</label>
                        <input type="text" name="responsible_department" class="form-control modern-input"
                            value="{{ old('responsible_department') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Nombre del funcionario *</label>
                        <select name="staff_name" class="form-select modern-input" required>
                            <option value="">Seleccionar funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('staff_name') == $user->id ? 'selected' : '' }}>
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
                                <option value="{{ $centro->id }}" {{ old('centro_id') == $centro->id ? 'selected' : '' }}>
                                    {{ $centro->nom_centro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede de formación *</label>
                        <select name="sede_id" id="sedeSelect" class="form-select modern-input" required>
                            <option value="">Primero selecciona un centro</option>
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
                        <label class="form-label text-success fw-semibold">Imagen de la necesidad</label>
                        <input type="file" name="image_inventory" class="form-control modern-input" accept="image/*">
                        <small class="text-muted">La imagen debe mostrar claramente la necesidad descrita.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Descripción de la necesidad *</label>
                        <textarea name="inventory_description" class="form-control modern-input" rows="5" required>{{ old('inventory_description') }}</textarea>
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
                <table class="table table-modern align-middle" id="materialsTable">
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
                        <tr>
                            <td><input type="text" name="materials[0][material_name]" class="form-control modern-input"
                                    required></td>
                            <td><input type="number" name="materials[0][material_quantity]"
                                    class="form-control modern-input" required></td>
                            <td>
                                <select name="materials[0][material_type]" class="form-control modern-input">
                                    <option value="Consumible">Consumible</option>
                                    <option value="Herramienta">Herramienta</option>
                                </select>
                            </td>
                            <td><input type="number" name="materials[0][material_price]" class="form-control modern-input"
                                    step="0.01" required></td>

                            <!-- Select IVA -->
                            <td>
                                <select name="materials[0][iva_percentage]" class="form-control modern-input">
                                    <option value="0">0%</option>
                                    <option value="5">5%</option>
                                    <option value="12">12%</option>
                                    <option value="19">19%</option>
                                </select>
                            </td>

                            <!-- Totales calculados en JS -->
                            <td><input type="text" name="materials[0][total_without_tax]"
                                    class="form-control modern-input" readonly></td>
                            <td><input type="text" name="materials[0][total_with_tax]" class="form-control modern-input"
                                    readonly></td>

                            <td><input type="text" name="materials[0][observations]" class="form-control modern-input">
                            </td>

                            <td>
                                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm"
                                    onclick="removeMaterial(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('ferreteria.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            margin-bottom: 8px;
        }

        .section-subtitle {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .modern-input {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .modern-input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.25);
        }

        .btn-lg {
            padding: 10px 22px;
            font-size: 15px;
            border-radius: 8px;
        }

        /* Tabla moderna */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 8px;
            width: 100%;
        }

        .table-modern thead {
            background: #4cd137;
            color: #fff;
            border-radius: 8px;
        }

        .table-modern thead th {
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .table-modern tbody tr {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table-modern tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-modern td {
            padding: 10px 12px;
            vertical-align: middle;
            text-align: center;
        }

        .table-modern input {
            text-align: center;
        }

        /* Botones de acciones */
        .btn-outline-danger {
            border-radius: 6px;
            padding: 6px 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #e63946;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
@endpush


@push('scripts')
    <script>
        let materialIndex = 1;

        function addMaterial() {
            const tbody = document.querySelector('#materialsTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="text" name="materials[${materialIndex}][material_name]" class="form-control modern-input" required></td>
            <td><input type="number" name="materials[${materialIndex}][material_quantity]" class="form-control modern-input" required></td>
            <td>
                <select name="materials[${materialIndex}][material_type]" class="form-control modern-input">
                    <option value="Consumible">Consumible</option>
                    <option value="Herramienta">Herramienta</option>
                </select>
            </td>
            <td><input type="number" name="materials[${materialIndex}][material_price]" class="form-control modern-input" step="0.01" required></td>
            <td>
                <select name="materials[${materialIndex}][iva_percentage]" class="form-control modern-input">
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="12">12%</option>
                    <option value="19">19%</option>
                </select>
            </td>
            <td><input type="text" name="materials[${materialIndex}][total_without_tax]" class="form-control modern-input" readonly></td>
            <td><input type="text" name="materials[${materialIndex}][total_with_tax]" class="form-control modern-input" readonly></td>
            <td><input type="text" name="materials[${materialIndex}][observations]" class="form-control modern-input"></td>
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
            updateMaterialTotals(); // recalcular después de borrar
        }

        function updateMaterialTotals() {
            const table = document.getElementById("materialsTable");
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach((row) => {
                const qtyInput = row.querySelector('[name*="[material_quantity]"]');
                const priceInput = row.querySelector('[name*="[material_price]"]');
                const ivaInput = row.querySelector('[name*="[iva_percentage]"]');
                const totalWithoutInput = row.querySelector('[name*="[total_without_tax]"]');
                const totalWithInput = row.querySelector('[name*="[total_with_tax]"]');

                if (!qtyInput || !priceInput || !ivaInput) return;

                const quantity = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const iva = parseFloat(ivaInput.value) || 0;

                const totalWithoutTax = quantity * price;
                const totalWithTax = totalWithoutTax + (totalWithoutTax * iva / 100);

                if (totalWithoutInput) totalWithoutInput.value = totalWithoutTax.toFixed(2);
                if (totalWithInput) totalWithInput.value = totalWithTax.toFixed(2);
            });
        }

        // Ejecutar cuando se cambia cantidad, precio o IVA
        document.addEventListener("input", function(e) {
            if (e.target.closest("#materialsTable")) {
                updateMaterialTotals();
            }
        });
        document.addEventListener("change", function(e) {
            if (e.target.closest("#materialsTable")) {
                updateMaterialTotals();
            }
        });

        // Calcular al cargar la página para la primera fila
        document.addEventListener("DOMContentLoaded", updateMaterialTotals);
    </script>
@endpush
