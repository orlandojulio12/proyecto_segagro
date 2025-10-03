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

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <h5 class="section-title mb-1">Información General</h5>
                            <p class="section-subtitle mb-0">Datos básicos para identificar y clasificar la necesidad</p>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-building me-1"></i> Dependencia responsable *
                        </label>
                        <input type="text" name="responsible_department" class="form-control modern-input"
                            value="{{ old('responsible_department', $inventory->responsible_department) }}" 
                            placeholder="Ingrese la dependencia" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-user me-1"></i> Nombre del funcionario *
                        </label>
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
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-school me-1"></i> Centro de formación *
                        </label>
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
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-map-marker-alt me-1"></i> Sede de formación *
                        </label>
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
            <div class="col-lg-6">
                <div class="content-card">
                    <div class="card-header-custom">
                        <i class="fas fa-clipboard-list me-2"></i>
                        <div>
                            <h5 class="section-title mb-1">Detalles de la necesidad</h5>
                            <p class="section-subtitle mb-0">Información adicional y evidencia fotográfica</p>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-image me-1"></i> Imagen de evidencia
                        </label>
                        
                        @if ($inventory->image_inventory)
                            <div class="image-preview-box mb-3">
                                <img src="{{ asset('storage/' . $inventory->image_inventory) }}"
                                    class="img-fluid rounded shadow-sm" alt="Imagen actual">
                                <div class="image-badge">Imagen actual</div>
                            </div>
                        @endif
                        
                        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                            <i class="fas fa-cloud-upload-alt fa-3x text-success mb-2"></i>
                            <p class="mb-0">Haz clic para cargar nueva imagen</p>
                            <small class="text-muted">o arrastra y suelta aquí</small>
                        </div>
                        <input type="file" name="image_inventory" id="fileInput" class="d-none" accept="image/*">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">
                            <i class="fas fa-align-left me-1"></i> Descripción *
                        </label>
                        <textarea name="inventory_description" class="form-control modern-input" rows="6" 
                            placeholder="Describe detalladamente la necesidad..." required>{{ old('inventory_description', $inventory->inventory_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materiales -->
        <div class="content-card">
            <div class="card-header-custom mb-3">
                <i class="fas fa-boxes me-2"></i>
                <div>
                    <h5 class="section-title mb-1">Información de materiales</h5>
                    <p class="section-subtitle mb-0">Materiales necesarios para completar la necesidad</p>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-success shadow-sm" onclick="addMaterial()">
                    <i class="fas fa-plus me-2"></i> Agregar Material
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle" id="materialsTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-tag me-1"></i> Material</th>
                            <th><i class="fas fa-sort-numeric-up me-1"></i> Cantidad</th>
                            <th><i class="fas fa-shapes me-1"></i> Tipo</th>
                            <th><i class="fas fa-dollar-sign me-1"></i> Precio Unit.</th>
                            <th><i class="fas fa-percent me-1"></i> IVA</th>
                            <th><i class="fas fa-calculator me-1"></i> Sin IVA</th>
                            <th><i class="fas fa-money-bill-wave me-1"></i> Con IVA</th>
                            <th><i class="fas fa-comment me-1"></i> Observaciones</th>
                            <th><i class="fas fa-cog me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventory->materials as $i => $material)
                            <tr>
                                <td><input type="text" name="materials[{{ $i }}][material_name]"
                                        class="form-control modern-input-sm" value="{{ $material->material_name }}" 
                                        placeholder="Nombre" required>
                                </td>

                                <td><input type="number" name="materials[{{ $i }}][material_quantity]"
                                        class="form-control modern-input-sm" value="{{ $material->material_quantity }}"
                                        placeholder="0" required></td>

                                <td>
                                    <select name="materials[{{ $i }}][material_type]"
                                        class="form-select modern-input-sm">
                                        <option value="Consumible"
                                            {{ $material->material_type == 'Consumible' ? 'selected' : '' }}>Consumible
                                        </option>
                                        <option value="Herramienta"
                                            {{ $material->material_type == 'Herramienta' ? 'selected' : '' }}>Herramienta
                                        </option>
                                    </select>
                                </td>

                                <td><input type="number" name="materials[{{ $i }}][material_price]"
                                        class="form-control modern-input-sm" value="{{ $material->material_price }}"
                                        step="0.01" placeholder="0.00" required></td>

                                <td>
                                    <select name="materials[{{ $i }}][iva_percentage]"
                                        class="form-select modern-input-sm">
                                        <option value="0" {{ $material->iva_percentage == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="5" {{ $material->iva_percentage == 5 ? 'selected' : '' }}>5%</option>
                                        <option value="12" {{ $material->iva_percentage == 12 ? 'selected' : '' }}>12%</option>
                                        <option value="19" {{ $material->iva_percentage == 19 ? 'selected' : '' }}>19%</option>
                                    </select>
                                </td>

                                <td><input type="text" name="materials[{{ $i }}][total_without_tax]"
                                        class="form-control modern-input-sm bg-light" value="{{ $material->total_without_tax }}"
                                        readonly></td>

                                <td><input type="text" name="materials[{{ $i }}][total_with_tax]"
                                        class="form-control modern-input-sm bg-light" value="{{ $material->total_with_tax }}"
                                        readonly></td>

                                <td><input type="text" name="materials[{{ $i }}][observations]"
                                        class="form-control modern-input-sm" value="{{ $material->observations }}"
                                        placeholder="Opcional"></td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterial(this)"
                                        title="Eliminar material">
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
            <a href="{{ route('ferreteria.index') }}" class="btn btn-light btn-lg shadow-sm">
                <i class="fas fa-times me-2"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save me-2"></i> Actualizar Inventario
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 3px solid #4cd137;
        }

        .card-header-custom i {
            font-size: 28px;
            color: #4cd137;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3436;
            margin: 0;
        }

        .section-subtitle {
            font-size: 13px;
            color: #74b9ff;
            margin: 0;
        }

        .modern-input, .modern-input-sm {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .modern-input-sm {
            padding: 8px 12px;
            font-size: 14px;
        }

        .modern-input:focus, .modern-input-sm:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 4px rgba(76, 209, 55, 0.15);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #2d3436;
        }

        .form-label i {
            color: #4cd137;
        }

        /* Área de carga de imagen */
        .image-preview-box {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
        }

        .image-preview-box img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .image-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(76, 209, 55, 0.9);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .upload-area {
            border: 3px dashed #4cd137;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fff9;
        }

        .upload-area:hover {
            background: #e8f8e9;
            border-color: #3db32a;
            transform: scale(1.02);
        }

        /* Tabla moderna */
        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: #fff;
        }

        .table-modern thead th {
            padding: 16px;
            font-size: 13px;
            text-align: center;
            font-weight: 600;
            border: none;
            white-space: nowrap;
        }

        .table-modern thead th i {
            margin-right: 4px;
        }

        .table-modern tbody tr {
            background: #fff;
            transition: all 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8fff9;
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
        }

        .table-modern td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }

        .table-modern input, .table-modern select {
            text-align: center;
            min-width: 100px;
        }

        /* Botones */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-lg {
            padding: 14px 32px;
            font-size: 16px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            border: none;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 209, 55, 0.4);
        }

        .btn-light {
            background: #fff;
            border: 2px solid #dee2e6;
            color: #495057;
        }

        .btn-light:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }

        /* Footer */
        .form-footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-footer {
                flex-direction: column;
            }

            .table-modern {
                font-size: 12px;
            }

            .card-header-custom i {
                font-size: 20px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let materialIndex = {{ count($inventory->materials) }};

        function addMaterial() {
            const tbody = document.querySelector('#materialsTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="text" name="materials[${materialIndex}][material_name]" class="form-control modern-input-sm" placeholder="Nombre" required></td>
            <td><input type="number" name="materials[${materialIndex}][material_quantity]" class="form-control modern-input-sm" placeholder="0" required></td>
            <td>
                <select name="materials[${materialIndex}][material_type]" class="form-select modern-input-sm">
                    <option value="Consumible">Consumible</option>
                    <option value="Herramienta">Herramienta</option>
                </select>
            </td>
            <td><input type="number" name="materials[${materialIndex}][material_price]" class="form-control modern-input-sm" step="0.01" placeholder="0.00"></td>
            <td>
                <select name="materials[${materialIndex}][iva_percentage]" class="form-select modern-input-sm">
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="12">12%</option>
                    <option value="19">19%</option>
                </select>
            </td>
            <td><input type="text" name="materials[${materialIndex}][total_without_tax]" class="form-control modern-input-sm bg-light" readonly></td>
            <td><input type="text" name="materials[${materialIndex}][total_with_tax]" class="form-control modern-input-sm bg-light" readonly></td>
            <td><input type="text" name="materials[${materialIndex}][observations]" class="form-control modern-input-sm" placeholder="Opcional"></td>
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
            button.closest('tr').remove();
        }

        // Preview de imagen
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewBox = document.querySelector('.image-preview-box');
                    if (previewBox) {
                        previewBox.querySelector('img').src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Filtrar sedes por centro
        document.getElementById('centroSelect').addEventListener('change', function() {
            const centroId = this.value;
            const sedeSelect = document.getElementById('sedeSelect');
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
                    .catch(() => sedeSelect.innerHTML = '<option value="">Error al cargar sedes</option>');
            } else {
                sedeSelect.innerHTML = '<option value="">Primero selecciona un centro</option>';
            }
        });
    </script>
@endpush