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
                            <i class="fas fa-wallet text-success"></i> Presupuesto Total *
                        </label>
                        <input type="text" name="total_budget" id="total_budget"
                            class="form-control modern-input @error('total_budget') is-invalid @enderror"
                            value="{{ number_format($budget->total_budget, 0, ',', '.') }}" required>
                        <small class="text-muted">Debe coincidir con la suma de dependencias</small>
                        @error('total_budget')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                </div>
            </div>
        </div>
        <br>
        <button type="button" class="btn btn-success btn-sm mt-3" onclick="addDepartment()">
            <i class="fas fa-plus me-2"></i>Agregar Dependencia
        </button>

        <div id="departmentsContainer">
            <!-- Dependencias existentes -->
            @foreach ($budget->departmentBudgets as $index => $deptBudget)
                <div class="department-card" data-index="{{ $index }}" data-id="{{ $deptBudget->id }}">
                    <div class="department-header">
                        <div class="d-flex align-items-sedes gap-3">
                            <div class="department-number">{{ $index + 1 }}</div>
                            <h6 class="mb-0">{{ $deptBudget->department->nombre ?? 'Dependencia ' . ($index + 1) }}
                            </h6>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="removeDepartment({{ $index }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-sitemap text-success"></i> Dependencia *
                                </label>
                                <input type="hidden" name="departments[{{ $index }}][id]"
                                    value="{{ $deptBudget->id }}">
                                <select name="departments[{{ $index }}][department_id]"
                                    class="form-select modern-input" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}"
                                            {{ $deptBudget->department_id == $dep->id ? 'selected' : '' }}>
                                            {{ $dep->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-wallet text-success"></i> Presupuesto *
                                </label>
                                <input type="text" name="departments[{{ $index }}][total_budget]"
                                    class="form-control modern-input department-budget"
                                    value="{{ number_format($deptBudget->total_budget, 0, ',', '.') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user-tie text-success"></i> Responsable *
                                </label>
                                <select name="departments[{{ $index }}][manager_id]"
                                    class="form-select modern-input" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            {{ $deptBudget->manager_id == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota:</strong> El presupuesto total se calculará automáticamente sumando todos los presupuestos
            asignados a las dependencias.
        </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex justify-content-end gap-2 mt-4">
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
            margin-right: 10px;
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
    </style>
@endpush

@push('scripts')
    <script>
        document.body.classList.add('budgets-edit');

        let departmentCount = {{ $budget->departmentBudgets->count() }};
        const departments = @json($departments);

        function formatMoney(value) {
            if (!value) return "";
            value = value.replace(/[^\d]/g, "");
            let number = parseFloat(value);
            if (isNaN(number)) return "";
            return number.toLocaleString("es-CO");
        }

        function cleanMoney(value) {
            return value.replace(/[^\d]/g, "");
        }

        function addDepartment() {
            departmentCount++;
            const container = document.getElementById('departmentsContainer');

            const departmentHtml = `
                <div class="department-card" data-index="${departmentCount}">
                    <div class="department-header">
                        <div class="d-flex align-items-sedes gap-3">
                            <div class="department-number">${departmentCount + 1}</div>
                            <h6 class="mb-0">Nueva Dependencia</h6>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeDepartment(${departmentCount})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-sitemap text-success"></i> Dependencia *
                                </label>
                                <select name="departments[${departmentCount}][id]" 
                                        class="form-select modern-input" required>
                                    <option value="">Seleccionar...</option>
                                    ${departments.map(dep => `<option value="${dep.id}">${dep.nombre}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-wallet text-success"></i> Presupuesto *
                                </label>
                                <input type="text" 
                                       name="departments[${departmentCount}][total_budget]"
                                       class="form-control modern-input department-budget"
                                       placeholder="0"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', departmentHtml);
            attachMoneyFormatting();
            updateBudgetSummary();
        }

        function removeDepartment(index) {
            const card = document.querySelector(`[data-index="${index}"]`);
            if (card) {
                if (confirm('¿Estás seguro de eliminar esta dependencia del presupuesto?')) {
                    card.remove();
                    updateBudgetSummary();
                }
            }
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
            const raw = cleanMoney(input.value);
            input.value = formatMoney(raw);
            input.setSelectionRange(cursorPos, cursorPos);
            updateBudgetSummary();
        }

        function updateBudgetSummary() {
            let total = 0;
            document.querySelectorAll('.department-budget').forEach(input => {
                const value = parseInt(cleanMoney(input.value)) || 0;
                total += value;
            });

            document.getElementById('totalBudget').textContent = '$' + formatMoney(total.toString());
            document.getElementById('availableBudget').textContent = '$' + formatMoney(total.toString());
            document.getElementById('percentageAssigned').textContent = '100%';
        }

        document.getElementById('budgetForm').addEventListener('submit', function() {
            document.querySelectorAll('.department-budget').forEach(input => {
                input.value = cleanMoney(input.value);
            });
        });

        // Inicializar el formateo y el resumen
        attachMoneyFormatting();
        updateBudgetSummary();
    </script>
@endpush
