{{-- resources/views/salida_ferreteria/edit.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Editar Salida de Ferretería')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Editar Salida #{{ $salidaFerreteria->id }}</h2>
            <p class="text-muted">Modifica la información de la salida de materiales</p>
        </div>
        <a href="{{ route('salida_ferreteria.show', $salidaFerreteria) }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores de validación:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salida_ferreteria.update', $salidaFerreteria) }}" method="POST" id="salidaForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- Columna izquierda - Información General --}}
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Información General</h5>
                    <p class="section-subtitle">Datos básicos de la salida de ferretería</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Funcionario responsable *</label>
                        <select name="user_id" class="form-select modern-input" required>
                            <option value="">Seleccionar funcionario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $salidaFerreteria->user_id) == $user->id ? 'selected' : '' }}>
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
                                <option value="{{ $centro->id }}" {{ old('centro_id', $salidaFerreteria->centro_id) == $centro->id ? 'selected' : '' }}>
                                    {{ $centro->nom_centro }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Sede *</label>
                        <select name="sede_id" id="sedeSelect" class="form-select modern-input" required>
                            <option value="">Seleccionar sede</option>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}" 
                                    data-centro="{{ $sede->centro_id }}" 
                                    {{ old('sede_id', $salidaFerreteria->sede_id) == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nom_sede }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Fecha de salida *</label>
                        <input type="date" name="fecha_salida" class="form-control modern-input" 
                            value="{{ old('fecha_salida', $salidaFerreteria->fecha_salida->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            {{-- Columna derecha - Datos adicionales --}}
            <div class="col-md-6">
                <div class="content-card mb-4">
                    <h5 class="section-title"><i class="fas fa-clipboard-list me-2"></i>Información Adicional</h5>
                    <p class="section-subtitle">Datos complementarios de la salida</p>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Formato F14 (opcional)</label>
                        <input type="text" name="f14" class="form-control modern-input" 
                            value="{{ old('f14', $salidaFerreteria->f14) }}" placeholder="Número de F14">
                        <small class="text-muted">Formato de salida institucional</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-success fw-semibold">Observaciones generales</label>
                        <textarea name="observaciones" class="form-control modern-input" rows="7" 
                            placeholder="Observaciones sobre la salida de materiales...">{{ old('observaciones', $salidaFerreteria->observaciones) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Materiales de Salida --}}
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="section-title mb-1"><i class="fas fa-boxes me-2"></i>Materiales de Salida</h5>
                    <p class="section-subtitle mb-0">Modifique los materiales y cantidades de esta salida</p>
                </div>
                <button type="button" class="btn btn-success shadow-sm" onclick="addMaterial()">
                    <i class="fas fa-plus me-2"></i>Agregar Material
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-modern" id="materialsTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box me-1"></i>Material</th>
                            <th><i class="fas fa-tag me-1"></i>Tipo</th>
                            <th><i class="fas fa-warehouse me-1"></i>Stock Actual</th>
                            <th><i class="fas fa-arrow-right me-1"></i>Cantidad Salida</th>
                            <th><i class="fas fa-comment me-1"></i>Observación</th>
                            <th><i class="fas fa-trash me-1"></i>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salidaFerreteria->detalles as $index => $detalle)
                        <tr>
                            <td>
                                <select name="materiales[{{ $index }}][inventory_material_id]" 
                                    class="form-select modern-input" required onchange="updateMaterialInfo(this)">
                                    <option value="">Seleccionar material</option>
                                    @foreach ($materiales as $mat)
                                        <option value="{{ $mat->id }}" 
                                            data-tipo="{{ $mat->material_type }}" 
                                            data-stock="{{ $mat->material_quantity }}"
                                            {{ $detalle->inventory_material_id == $mat->id ? 'selected' : '' }}>
                                            {{ $mat->material_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <span class="badge bg-info material-type">{{ $detalle->material->material_type ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success material-stock">{{ $detalle->material->material_quantity ?? 0 }}</span>
                            </td>
                            <td>
                                <input type="number" name="materiales[{{ $index }}][cantidad]" 
                                    class="form-control modern-input" min="0.01" step="0.01" required 
                                    value="{{ $detalle->cantidad }}" onchange="validateStock(this)">
                            </td>
                            <td>
                                <input type="text" name="materiales[{{ $index }}][observacion]" 
                                    class="form-control modern-input" 
                                    value="{{ $detalle->observacion }}" placeholder="Opcional">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterial(this)">
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
            <a href="{{ route('salida_ferreteria.show', $salidaFerreteria) }}" class="btn btn-light btn-lg shadow-sm me-3">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-save me-2"></i>Actualizar Salida
            </button>
        </div>
    </form>
@endsection

@push('styles')
<style>
    /* Estilos específicos para salida_ferreteria edit - No afectan al layout */
    .salida-ferreteria-edit .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .salida-ferreteria-edit .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .salida-ferreteria-edit .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
    }

    .salida-ferreteria-edit .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .salida-ferreteria-edit .content-card:hover {
        box-shadow: 0 4px 20px rgba(76, 209, 55, 0.15);
    }

    .salida-ferreteria-edit .section-title {
        font-size: 1.1rem;
        color: #4cd137;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .salida-ferreteria-edit .section-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .salida-ferreteria-edit .modern-input {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .salida-ferreteria-edit .modern-input:focus {
        border-color: #4cd137;
        box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.15);
        outline: none;
    }

    .salida-ferreteria-edit .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    /* Tabla moderna */
    .salida-ferreteria-edit .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 0;
    }

    .salida-ferreteria-edit .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .salida-ferreteria-edit .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
    }

    .salida-ferreteria-edit .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
    }

    .salida-ferreteria-edit .table-modern tbody tr:hover {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .salida-ferreteria-edit .table-modern tbody td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .salida-ferreteria-edit .form-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 0;
    }

    .salida-ferreteria-edit .btn {
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .salida-ferreteria-edit .btn-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        border: none;
    }

    .salida-ferreteria-edit .btn-success:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
    }

    .salida-ferreteria-edit .btn-light {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }

    .salida-ferreteria-edit .btn-light:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-2px);
    }

    .salida-ferreteria-edit .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
    }

    .salida-ferreteria-edit .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    .salida-ferreteria-edit .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    /* Badges */
    .salida-ferreteria-edit .badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .salida-ferreteria-edit .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .salida-ferreteria-edit .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .salida-ferreteria-edit .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    /* Alerts */
    .salida-ferreteria-edit .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .salida-ferreteria-edit .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #e74c3c;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .salida-ferreteria-edit .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .salida-ferreteria-edit .form-footer {
            flex-direction: column;
            gap: 10px;
        }

        .salida-ferreteria-edit .form-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('salida-ferreteria-edit');

    let materialIndex = {{ $salidaFerreteria->detalles->count() }};
    let materialesDisponibles = @json($materiales);

    // Filtrar sedes según centro seleccionado
    document.getElementById('centroSelect').addEventListener('change', function() {
        const centroId = this.value;
        const sedeSelect = document.getElementById('sedeSelect');
        const options = sedeSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else {
                option.style.display = option.dataset.centro == centroId ? 'block' : 'none';
            }
        });
        
        sedeSelect.value = '';
    });

    function addMaterial() {
        const tbody = document.querySelector('#materialsTable tbody');
        const row = document.createElement('tr');
        
        // Crear select de materiales
        let materialesOptions = '<option value="">Seleccionar material</option>';
        materialesDisponibles.forEach(mat => {
            if (mat.material_quantity > 0) {
                materialesOptions += `<option value="${mat.id}" data-tipo="${mat.material_type || 'N/A'}" data-stock="${mat.material_quantity}">
                    ${mat.material_name}
                </option>`;
            }
        });
        
        row.innerHTML = `
            <td>
                <select name="materiales[${materialIndex}][inventory_material_id]" class="form-select modern-input" required onchange="updateMaterialInfo(this)">
                    ${materialesOptions}
                </select>
            </td>
            <td>
                <span class="badge bg-info material-type">-</span>
            </td>
            <td>
                <span class="badge bg-success material-stock">-</span>
            </td>
            <td>
                <input type="number" name="materiales[${materialIndex}][cantidad]" 
                    class="form-control modern-input" min="0.01" step="0.01" required 
                    placeholder="0.00" onchange="validateStock(this)">
            </td>
            <td>
                <input type="text" name="materiales[${materialIndex}][observacion]" 
                    class="form-control modern-input" placeholder="Opcional">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterial(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
        materialIndex++;
    }

    function updateMaterialInfo(select) {
        const row = select.closest('tr');
        const selectedOption = select.options[select.selectedIndex];
        
        const tipo = selectedOption.dataset.tipo || '-';
        const stock = selectedOption.dataset.stock || '-';
        
        row.querySelector('.material-type').textContent = tipo;
        row.querySelector('.material-stock').textContent = stock;
        
        // Actualizar límite del input de cantidad
        const cantidadInput = row.querySelector('input[name*="[cantidad]"]');
        cantidadInput.max = stock;
    }

    function validateStock(input) {
        const row = input.closest('tr');
        const stockText = row.querySelector('.material-stock').textContent;
        const stock = parseFloat(stockText);
        const cantidad = parseFloat(input.value);
        
        if (cantidad > stock) {
            alert(`⚠️ La cantidad no puede ser mayor al stock disponible (${stock})`);
            input.value = stock;
        }
    }

    function removeMaterial(button) {
        if (confirm('¿Eliminar este material de la salida?')) {
            const tbody = document.querySelector('#materialsTable tbody');
            button.closest('tr').remove();
            
            // Verificar si quedan filas
            if (tbody.children.length === 0) {
                const row = document.createElement('tr');
                row.className = 'empty-state';
                row.innerHTML = `
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay materiales. Haz clic en "Agregar Material" para agregar.</p>
                    </td>
                `;
                tbody.appendChild(row);
            }
        }
    }

    // Validación antes de enviar
    document.getElementById('salidaForm').addEventListener('submit', function(e) {
        const tbody = document.querySelector('#materialsTable tbody');
        const emptyState = tbody.querySelector('.empty-state');
        
        if (emptyState || tbody.children.length === 0) {
            e.preventDefault();
            alert('⚠️ Debe agregar al menos un material');
        }
    });

    // Advertencia al salir sin guardar
    let formChanged = false;
    const form = document.getElementById('salidaForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    form.addEventListener('submit', function() {
        formChanged = false;
    });
</script>
@endpush