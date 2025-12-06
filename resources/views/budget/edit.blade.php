{{-- resources/views/budgets/edit.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Editar Presupuesto')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <p class="text-muted">Actualiza la información del presupuesto del año <strong>{{ $budget->year }}</strong>
            </p>
        </div>
        <a href="{{ route('budget.show', $budget) }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    <br>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores en el formulario:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('budget.update', $budget) }}" method="POST" id="budgetForm">
        @csrf
        @method('PUT')
        <!-- Contenedor principal con 2 columnas -->
        <div class="budgets-container">
            <!-- Columna formulario -->
            <div class="budgets-column">
                <div class="content-card">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-wallet text-success"></i> Presupuesto Total
                        </label>
                        <input type="text" id="total_budget" class="form-control modern-input"
                            value="{{ number_format($budget->total_budget, 0, ',', '.') }}" readonly>
                        <small class="text-muted">Este valor se calcula automáticamente sumando los presupuestos de las
                            dependencias.</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar text-success"></i> Año *
                        </label>
                        <input type="number" name="year"
                            class="form-control modern-input @error('year') is-invalid @enderror"
                            value="{{ old('year', $budget->year) }}" min="2020" max="2100" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-alt text-success"></i> Resolución
                        </label>
                        <input type="text" name="resolution"
                            class="form-control modern-input @error('resolution') is-invalid @enderror"
                            value="{{ old('resolution', $budget->resolution) }}" placeholder="Ej: Res. 001-2025">
                        @error('resolution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enableAdjustmentSwitch">
                        <label class="form-check-label fw-semibold" for="enableAdjustmentSwitch">
                            ¿Aplicar un ajuste al presupuesto?
                        </label>
                    </div>

                    <hr class="my-4">
                    <br>
                    <div id="adjustmentSection" class="adjustment-hidden">
                        <h5 class="section-title">
                            <i class="fas fa-exchange-alt"></i> Ajustar Presupuesto General
                        </h5>
                        <p class="text-muted mb-3">
                            Registre un ajuste positivo o negativo al presupuesto general.
                            Este ajuste quedará asociado a su usuario y fecha actual.
                        </p>

                        <br>
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-plus-minus text-success"></i> Monto del Ajuste
                            </label>
                            <input type="text" name="adjustment_amount" id="adjustment_amount"
                                class="form-control modern-input" placeholder="Ej: 2.500.000 o -1.000.000">
                            <small class="text-muted">Puede ingresar valores positivos o negativos.</small>
                        </div>

                        <div class="form-group mb-1">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment text-success"></i> Descripción del Ajuste
                            </label>
                            <textarea name="adjustment_description" class="form-control modern-input" rows="2"
                                placeholder="Motivo del ajuste (requerido si hay ajuste)"></textarea>
                        </div>
                    </div>

                    <p class="budget-warning">
                        <strong>Aviso:</strong> Al actualizar cualquiera de estos datos, usted asume la responsabilidad
                        correspondiente.
                        Toda modificación quedará registrada en el sistema de auditoría del aplicativo.
                    </p>

                    <br><br>
                </div>

            </div>
            <!-- Columna resumen -->
            <div class="budgets-column">
                <div class="content-card">
                    <h5 class="section-title mb-2">
                        <i class="fas fa-sitemap"></i> Presupuestos por Dependencia
                    </h5>
                    <p class="section-subtitle mb-0">Distribuye el presupuesto entre las dependencias</p>

                    <div class="budget-summary-grid">
                        <div class="summary-card">
                            <i class="fas fa-wallet"></i>
                            <div class="summary-value" id="totalBudget">$0</div>
                            <div class="summary-label">Presupuesto Total</div>
                        </div>
                        <div class="summary-card">
                            <i class="fas fa-chart-line"></i>
                            <div class="summary-value" id="availableBudget">$0</div>
                            <div class="summary-label">Disponible para Asignar</div>
                        </div>
                        <div class="summary-card">
                            <i class="fas fa-percentage"></i>
                            <div class="summary-value" id="percentageAssigned">0%</div>
                            <div class="summary-label">Porcentaje Asignado</div>
                        </div>
                        <div class="summary-card">
                            <i class="fas fa-tasks"></i>
                            <div class="summary-value" id="departmentsCount">0</div>
                            <div class="summary-label">Dependencias Asignadas</div>
                        </div>
                    </div>
                    <br>
                    <div id="adjustments-section">
                        <p class="text-muted">Cargando historial...</p>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table-modern" id="departmentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><i class="fas fa-sitemap"></i> Unidad</th>
                        <th><i class="fas fa-sitemap"></i> SubUnidad</th>
                        <th><i class="fas fa-wallet"></i> Presupuesto</th>
                        <th><i class="fas fa-wallet"></i> Presupuesto gastado</th>
                        <th><i class="fas fa-user-tie"></i> Responsable</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budget->departmentBudgets as $index => $deptBudget)
                        <tr data-index="{{ $index }}" data-id="{{ $deptBudget->id }}">
                            <td class="department-number">{{ $index + 1 }}</td>
                            <td>
                                {{-- SELECT UNIDAD --}}
                                <select name="departments[{{ $index }}][unit_id]" class="modern-input unit-select"
                                    data-row="{{ $index }}" required>

                                    <option value="">Seleccionar...</option>

                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->dependency_unit_id }}"
                                            {{ $unit->dependency_unit_id == ($deptBudget->SubUnit->dependency_unit_id ?? null) ? 'selected' : '' }}>
                                            {{ $unit->short_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                {{-- SELECT SUBUNIDAD (DINÁMICO) --}}
                                <select name="departments[{{ $index }}][department_id]"
                                    class="modern-input subunit-select" id="subunit-select-{{ $index }}" required>

                                    <option value="">Seleccionar...</option>

                                    @if ($deptBudget->SubUnit)
                                        @foreach ($deptBudget->SubUnit->dependencyUnit->subunits as $sub)
                                            <option value="{{ $sub->subunit_id }}"
                                                {{ $deptBudget->department_id == $sub->subunit_id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </td>

                            <td>
                                <input type="text" name="departments[{{ $index }}][total_budget]"
                                    class="modern-input department-budget"
                                    value="{{ number_format($deptBudget->total_budget, 0, ',', '.') }}" required>
                            </td>
                            <td>
                                <input type="text" name="departments[{{ $index }}][spent_budget]"
                                    class="modern-input department-spent-budget"
                                    value="{{ number_format($deptBudget->spent_budget, 0, ',', '.') }}" required>
                            </td>

                            <td>
                                <select name="departments[{{ $index }}][manager_id]" class="modern-input"
                                    required>
                                    <option value="">Seleccionar...</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            {{ $deptBudget->manager_id == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="removeDepartment({{ $index }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <button type="button" class="btn btn-success btn-sm mt-3" onclick="addDepartment()">
                <i class="fas fa-plus me-2"></i> Agregar Dependencia
            </button>
        </div>


        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota:</strong> El presupuesto total se calculará automáticamente sumando todos los presupuestos
            asignados a las dependencias.
        </div>
        </div>

        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="{{ route('budget.show', $budget) }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>Actualizar Presupuesto
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        /* Contenedor principal con 2 columnas */
        .budgets-edit .budgets-container {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Columnas */
        .budgets-edit .budgets-column {
            flex: 1;
            min-width: 300px;
        }

        .budgets-edit .budgets-column .content-card {
            width: 100%;
        }

        /* Responsive: 1 columna en móvil */
        @media (max-width: 768px) {
            .budgets-edit .budgets-column {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Tarjetas (form y resumen) */
        .budgets-edit .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        /* Section titles */
        .budgets-edit .section-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 8px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4cd137;
        }

        .budgets-edit .section-title i {
            color: #4cd137;
            margin-right: 8px;
        }

        .budgets-edit .section-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Inputs modernos */
        .budgets-edit .modern-input {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            width: 100%;
            margin-bottom: 10px;
        }

        .budgets-edit .budget-warning {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: -5px;
        }

        .budgets-edit .budget-warning strong {
            color: #28a745;
        }

        .budgets-edit .modern-input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.25);
        }

        /* Grid interno resumen 2x2 */
        .budgets-edit .budget-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        /* Tarjetas resumen */
        .budgets-edit .summary-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .budgets-edit .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .budgets-edit .summary-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #4cd137;
        }

        .budgets-edit .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4cd137;
            margin-bottom: 5px;
        }

        .budgets-edit .summary-label {
            font-size: 0.95rem;
            color: black;
        }

        /* Tarjetas de dependencias */
        .budgets-edit .department-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .budgets-edit .department-card:hover {
            border-color: #4cd137;
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.15);
        }

        .budgets-edit .department-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .budgets-edit .department-number {
            background: #4cd137;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-top: 10px;
            margin-left: 10px
        }

        /* Botones */
        .budgets-edit .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 24px;
            transition: all 0.3s ease;
            border: none;
        }

        .budgets-edit .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
        }

        .budgets-edit .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        .budgets-edit .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
        }

        .budgets-edit .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
        }

        .budgets-edit .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .budgets-edit .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        /* Responsive dependencias */
        @media (max-width: 768px) {
            .budgets-edit .department-fields {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Tabla moderna */
        .budgets-edit .table-modern {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 0;
            table-layout: auto;
            /* Ajusta columnas al contenido */
        }

        /* Encabezado */
        .budgets-edit .table-modern thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: #fff;
        }

        .budgets-edit .table-modern thead th {
            padding: 5px 5px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Iconos del header */
        .budgets-edit .table-modern thead th i {
            margin-right: 6px;
            /* Espacio entre icono y texto */
            color: #fff;
        }

        /* Filas */
        .budgets-edit .table-modern tbody tr {
            background: #fff;
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .budgets-edit .table-modern tbody tr:hover {
            background: #f8fff9;
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
        }

        /* Celdas */
        .budgets-edit .table-modern tbody td {
            padding: 5px 5px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.95rem;
            color: #2c3e50;
        }

        /* Badges de porcentaje */
        .budgets-edit .table-modern tbody .badge {
            font-weight: 600;
            color: #fff;
            padding: 5px 10px;
            border-radius: 12px;
        }

        /* Colores de badges */
        .budgets-edit .badge.bg-success {
            background-color: #2ed573 !important;
        }

        .budgets-edit .badge.bg-warning {
            background-color: #ffa502 !important;
        }

        .budgets-edit .badge.bg-danger {
            background-color: #e84118 !important;
        }

        /* Animación para el ajuste */
        .adjustment-hidden {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.45s ease-in-out;
        }

        .adjustment-visible {
            opacity: 1;
            max-height: 500px;
            margin-top: 10px;
            transition: all 0.45s ease-in-out;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination {
            list-style: none;
            display: flex;
            gap: 6px;
            padding: 0;
        }

        .pagination .page-item {
            display: inline-block;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;

            padding: 6px 12px;
            border-radius: 10px;
            background: #ffffff;

            color: #2c3e50;
            font-weight: 500;
            font-size: 0.95rem;

            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background: #f2fff0;
            border-color: #4cd137;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4cd137, #3db32a);
            color: #fff !important;
            border: none;
            box-shadow: 0 2px 8px rgba(76, 209, 55, 0.35);
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            background: #f5f5f5;
            color: #aaaaaa;
            border: 1px solid #eeeeee;
            cursor: not-allowed;
            transform: none;
        }

        .adjustments-table {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .adjustments-table thead {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
        }

        .adjustments-table th {
            padding: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            text-align: center;
        }

        .adjustments-table tbody tr {
            transition: all 0.2s ease;
        }

        .adjustments-table tbody tr:nth-child(even) {
            background: #f9fdf9;
        }

        .adjustments-table tbody tr:hover {
            background: #eefbe9;
            transform: scale(1.005);
            box-shadow: 0 2px 6px rgba(76, 209, 55, 0.15);
        }

        .adjustments-table td {
            padding: 10px;
            font-size: 0.95rem;
            vertical-align: middle;
            text-align: center;
            color: #2c3e50;
        }

        /* Badges */
        .adjustments-table .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
            font-weight: 600;
            border-radius: 12px;
        }

        .badge.bg-success {
            background-color: #2ecc71 !important;
        }

        .badge.bg-danger {
            background-color: #e74c3c !important;
        }

        .budgets-edit .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-right: 5px
        }


        /* Responsivo */
        @media (max-width: 768px) {
            .budgets-edit .table-modern thead {
                display: none;
                /* Se puede ocultar el header y usar tarjetas en móvil */
            }

            .budgets-edit .table-modern tbody td {
                display: block;
                text-align: right;
                padding: 10px;
            }

            .budgets-edit .table-modern tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                text-transform: uppercase;
                color: #2c3e50;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.body.classList.add('budgets-edit');

        let departmentCount = {{ $budget->departmentBudgets->count() }};
        const units = @json($units);
        const managers = @json($managers);

        // Helper: obtiene el id de la unidad soportando dependency_unit_id o id
        const getUnitId = (u) => (u.dependency_unit_id ?? u.id ?? u.dependencyUnitId ?? null);

        // Carga subunidades según la unidad seleccionada
        function loadSubunits(unitSelect) {
            const row = unitSelect.dataset.row;
            const subunitSelect = document.getElementById('subunit-select-' + row);

            // si no existe el select de subunidad, salir
            if (!subunitSelect) return;

            const unitId = parseInt(unitSelect.value) || null;

            // limpiar opciones
            subunitSelect.innerHTML = `<option value="">Seleccionar...</option>`;

            if (!unitId) return;

            const unit = units.find(u => parseInt(getUnitId(u)) === unitId);

            if (!unit || !Array.isArray(unit.subunits)) return;

            unit.subunits.forEach(sub => {
                const option = document.createElement('option');
                // tomar sub.subunit_id o sub.id si depende del model
                const subId = sub.subunit_id ?? sub.id ?? sub.subUnitId ?? sub.id_subunit ?? null;
                option.value = subId;
                option.textContent = sub.name;
                subunitSelect.appendChild(option);
            });

            // Si el row tiene un valor seleccionado (data attribute), seleccionarlo
            // data-selected-sub lo ponemos cuando inicializamos filas existentes
            const selectedSub = subunitSelect.dataset.selected;
            if (selectedSub) {
                subunitSelect.value = selectedSub;
            }
        }

        function addDepartment() {
            departmentCount++;
            const tableBody = document.querySelector('#departmentsTable tbody');

            const newRow = document.createElement('tr');
            newRow.setAttribute('data-index', departmentCount);

            newRow.innerHTML = `
        <td class="department-number">${departmentCount + 1}</td>

        <td>
            <select name="departments[${departmentCount}][unit_id]" 
                    class="modern-input unit-select" 
                    data-row="${departmentCount}" required>
                <option value="">Seleccionar...</option>
                ${units.map(u => `<option value="${getUnitId(u)}">${u.short_name}</option>`).join('')}
            </select>
        </td>

        <td>
            <select name="departments[${departmentCount}][department_id]" 
                    class="modern-input subunit-select"
                    id="subunit-select-${departmentCount}" data-selected="" required>
                <option value="">Seleccionar...</option>
            </select>
        </td>

        <td>
            <input type="text" name="departments[${departmentCount}][total_budget]" 
                   class="modern-input department-budget" placeholder="0" required>
        </td>
        <td>
            <select name="departments[${departmentCount}][manager_id]" class="modern-input" required>
                <option value="">Seleccionar...</option>
                ${managers.map(manager => `<option value="${manager.id}">${manager.name}</option>`).join('')}
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDepartment(${departmentCount})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

            tableBody.appendChild(newRow);

            // event listener para la unidad de la nueva fila
            const unitSelect = newRow.querySelector('.unit-select');
            unitSelect.addEventListener('change', function() {
                // antes de cargar subunidades borrar posible data-selected
                const subSel = document.getElementById('subunit-select-' + this.dataset.row);
                if (subSel) subSel.dataset.selected = '';
                loadSubunits(this);
            });

            attachMoneyFormatting();
            updateBudgetSummary();
        }

        function removeDepartment(index) {
            const row = document.querySelector(`#departmentsTable tr[data-index="${index}"]`);
            if (row && confirm('¿Estás seguro de eliminar esta dependencia del presupuesto?')) {
                row.remove();
                updateBudgetSummary();
            }
        }

        function cleanMoney(value) {
            return value.replace(/[^\d]/g, '');
        }

        function formatMoney(value) {
            if (!value) return "";
            let number = parseInt(value);
            if (isNaN(number)) return "";
            return number.toLocaleString("es-CO");
        }

        function attachMoneyFormatting() {
            document.querySelectorAll('.department-budget').forEach(input => {
                input.removeEventListener('input', handleMoneyInput);
                input.addEventListener('input', handleMoneyInput);
            });
        }

        function handleMoneyInput(e) {
            const input = e.target;
            const cursorPos = input.selectionStart;
            let raw = cleanMoney(input.value);

            const totalBudget = parseInt(cleanMoney(document.getElementById('total_budget').value)) || 0;

            let assignedExceptCurrent = 0;
            document.querySelectorAll('.department-budget').forEach(el => {
                if (el !== input) {
                    assignedExceptCurrent += parseInt(cleanMoney(el.value)) || 0;
                }
            });

            let maxValue = totalBudget - assignedExceptCurrent;
            if (parseInt(raw) > maxValue) {
                alert(
                    `La sumatoria de los presupuestos de las dependencias es superior al presupuesto general.\n` +
                    `Número ingresado: ${formatMoney(raw)}`
                );
                raw = maxValue;
            }

            input.value = formatMoney(raw);
            input.setSelectionRange(cursorPos, cursorPos);
            updateBudgetSummary();
        }

        function updateBudgetSummary() {
            let total = parseInt(cleanMoney(document.getElementById('total_budget').value)) || 0;
            let assigned = 0;
            let rows = document.querySelectorAll('#departmentsTable tbody tr');

            rows.forEach(row => {
                const input = row.querySelector('.department-budget');
                let value = parseInt(cleanMoney(input.value)) || 0;
                assigned += value;
            });

            document.getElementById('totalBudget').textContent = '$' + formatMoney(total.toString());
            document.getElementById('availableBudget').textContent = '$' + formatMoney((total - assigned).toString());
            let percent = total > 0 ? Math.round((assigned / total) * 100) : 0;
            document.getElementById('percentageAssigned').textContent = percent + '%';
            document.getElementById('departmentsCount').textContent = rows.length;
        }

        // Inicialización y bindings al cargar la página
        document.addEventListener("DOMContentLoaded", function() {

            // 1) Para cada fila existente: enlazar evento unit-select y cargar subunits si hay unidad seleccionada
            document.querySelectorAll('tr[data-index]').forEach(row => {
                const idx = row.getAttribute('data-index');
                const unitSelect = row.querySelector('.unit-select');
                const subSelect = row.querySelector('.subunit-select');

                if (unitSelect) {
                    // bind change
                    unitSelect.addEventListener('change', function() {
                        // limpiar data-selected para evitar selección antigua
                        if (subSelect) subSelect.dataset.selected = '';
                        loadSubunits(this);
                    });

                    // si ya tiene unidad seleccionada, cargar sus subunidades y marcar la subunid. existente
                    const unitId = parseInt(unitSelect.value) || null;
                    if (unitId && subSelect) {
                        // guardar el valor actual de department_id para seleccionarlo después
                        const currentSub = subSelect.getAttribute('data-existing') || subSelect.value ||
                            subSelect.dataset.selected || null;
                        // preferimos usar atributo data-existing si lo pusiste en blade; si no, usamos el value actual
                        subSelect.dataset.selected = currentSub;
                        loadSubunits(unitSelect);
                    }
                }
            });

            // 2) Inicializar otras cosas
            attachMoneyFormatting();
            updateBudgetSummary();

            // 3) ajustes AJAX (mantengo tu código)
            loadAdjustmentsInitial();

            function loadAdjustmentsInitial(url = "{{ route('budget.adjustments', $budget->id) }}") {
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById("adjustments-section").innerHTML = data.html;
                        document.querySelectorAll('#adjustments-section .pagination a').forEach(link => {
                            link.addEventListener('click', function(e) {
                                e.preventDefault();
                                loadAdjustmentsInitial(this.href);
                            });
                        });
                    });
            }
        });

        // Limpiar el input antes de enviar el formulario
        document.getElementById('budgetForm').addEventListener('submit', function() {
            document.querySelectorAll('.department-budget').forEach(input => {
                input.value = cleanMoney(input.value);
            });
        });

        document.getElementById('enableAdjustmentSwitch').addEventListener('change', function() {
            const section = document.getElementById('adjustmentSection');

            if (this.checked) {
                section.classList.remove('adjustment-hidden');
                section.classList.add('adjustment-visible');
            } else {
                section.classList.remove('adjustment-visible');
                section.classList.add('adjustment-hidden');
            }
        });
    </script>
@endpush
