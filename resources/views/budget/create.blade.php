{{-- resources/views/budgets/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Presupuesto')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Nuevo Presupuesto General</h2>
            <p class="text-muted">Registra un nuevo presupuesto general y asigna presupuestos por dependencia</p>
        </div>
        <a href="{{ route('budget.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

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

    <form action="{{ route('budget.store') }}" method="POST" id="budgetForm">
        @csrf

        <!-- Información General del Presupuesto -->
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-wallet"></i> Información General del Presupuesto
            </h5>
            <p class="section-subtitle">Datos básicos del presupuesto general</p>

            <input type="hidden" name="sede_id" value="{{ $sedes->id }}">
            <input type="hidden" name="manager_id" value="{{ $user->id }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-building text-success"></i> Sede
                        </label>
                        <input type="text" class="form-control modern-input" value="{{ $sedes->nom_sede }}" disabled>
                        <small class="text-muted">Sede asignada a tu usuario</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar text-success"></i> Año *
                        </label>
                        <input type="number" name="year"
                            class="form-control modern-input @error('year') is-invalid @enderror"
                            value="{{ old('year', date('Y')) }}" min="2020" max="2100" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-alt text-success"></i> Resolución
                        </label>
                        <input type="text" name="resolution"
                            class="form-control modern-input @error('resolution') is-invalid @enderror"
                            value="{{ old('resolution') }}" placeholder="Ej: Res. 001-2025">
                        @error('resolution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-tie text-success"></i> Responsable
                        </label>
                        <input type="text" class="form-control modern-input" value="{{ $user->name }}" disabled>
                        <small class="text-muted">Usuario autenticado</small>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label fw-semibold">
                    <i class="fas fa-wallet text-success"></i> Presupuesto Total *
                </label>
                <input type="text" name="total_budget" id="total_budget"
                    class="form-control modern-input @error('total_budget') is-invalid @enderror"
                    value="{{ old('total_budget') }}" placeholder="0" required>
                <small class="text-muted">Este valor debe coincidir con la suma de los presupuestos de las
                    dependencias</small>
                @error('total_budget')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Presupuestos por Dependencia -->
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-sedes mb-3">
                <div>
                    <h5 class="section-title mb-2">
                        <i class="fas fa-sitemap"></i> Presupuestos por Dependencia
                    </h5>
                    <p class="section-subtitle mb-0">Distribuye el presupuesto entre las dependencias</p>
                </div>
                <button type="button" class="btn btn-success btn-sm" onclick="addDepartment()">
                    <i class="fas fa-plus me-2"></i>Agregar Dependencia
                </button>
            </div>

            <!-- Resumen del presupuesto -->
            <div class="budget-summary mb-4">
                <div class="row text-sedes">
                    <div class="col-md-4">
                        <div class="summary-card bg-primary">
                            <i class="fas fa-wallet"></i>
                            <div class="summary-value" id="totalBudget">$0</div>
                            <div class="summary-label">Presupuesto Total</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card bg-success">
                            <i class="fas fa-chart-line"></i>
                            <div class="summary-value" id="availableBudget">$0</div>
                            <div class="summary-label">Disponible para Asignar</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card bg-warning">
                            <i class="fas fa-percentage"></i>
                            <div class="summary-value" id="percentageAssigned">0%</div>
                            <div class="summary-label">Porcentaje Asignado</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="departmentsContainer">
                <!-- Las dependencias se agregarán aquí dinámicamente -->
            </div>

            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Nota:</strong> La suma de los presupuestos de todas las dependencias debe coincidir con el
                presupuesto total ingresado arriba.
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('budget.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i>Guardar Presupuesto
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .budgets-create .section-header {
            display: flex;
            justify-content: space-between;
            align-items: sedes;
        }

        .budgets-create .section-header h2 {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .budgets-create .section-header p {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 0.95rem;
        }

        .budgets-create .content-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .budgets-create .section-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 8px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4cd137;
        }

        .budgets-create .section-title i {
            color: #4cd137;
            margin-right: 8px;
        }

        .budgets-create .section-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .budgets-create .modern-input {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .budgets-create .modern-input:focus {
            border-color: #4cd137;
            box-shadow: 0 0 0 0.2rem rgba(76, 209, 55, 0.25);
        }

        #budgetForm i {
            color: #28a745 !important;
        }

        /* Resumen de presupuesto */
        .budgets-create .budget-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .budgets-create .summary-card {
            padding: 20px;
            border-radius: 10px;
            color: white;
        }

        .budgets-create .summary-card i {
            font-size: 2rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .budgets-create .summary-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .budgets-create .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Tarjetas de dependencia */
        .budgets-create .department-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .budgets-create .department-card:hover {
            border-color: #4cd137;
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.15);
        }

        .budgets-create .department-header {
            display: flex;
            justify-content: space-between;
            align-items: sedes;
            margin-bottom: 15px;
        }

        .budgets-create .department-number {
            background: #4cd137;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: sedes;
            justify-content: sedes;
            font-weight: 700;
        }

        .budgets-create .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 24px;
            transition: all 0.3s ease;
            border: none;
        }

        .budgets-create .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        }

        .budgets-create .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        .budgets-create .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .budgets-create .btn-danger:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.body.classList.add('budgets-create');

        let departmentCount = 0;
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
                            <div class="department-number">${departmentCount}</div>
                            <h6 class="mb-0">Dependencia ${departmentCount}</h6>
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
                card.remove();
                updateBudgetSummary();
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

            const totalBudgetInput = parseInt(cleanMoney(document.getElementById('total_budget').value)) || 0;
            const difference = totalBudgetInput - total;
            const percentage = totalBudgetInput > 0 ? (total / totalBudgetInput) * 100 : 0;

            document.getElementById('totalBudget').textContent = formatMoney(totalBudgetInput.toString());
            document.getElementById('availableBudget').textContent = formatMoney(Math.abs(difference).toString());
            document.getElementById('percentageAssigned').textContent = percentage.toFixed(1) + '%';


            // Cambiar colores según el estado
            const availableCard = document.getElementById('availableBudget').closest('.summary-card');
            const percentageCard = document.getElementById('percentageAssigned').closest('.summary-card');

            if (difference < 0) {
                availableCard.className = 'summary-card bg-danger';
                document.getElementById('availableBudget').previousElementSibling.innerHTML =
                    '<i class="fas fa-exclamation-triangle"></i>';
            } else if (difference > 0) {
                availableCard.className = 'summary-card bg-warning';
                document.getElementById('availableBudget').previousElementSibling.innerHTML =
                '<i class="fas fa-clock"></i>';
            } else {
                availableCard.className = 'summary-card bg-success';
                document.getElementById('availableBudget').previousElementSibling.innerHTML =
                    '<i class="fas fa-check-circle"></i>';
            }

            if (percentage > 100) {
                percentageCard.className = 'summary-card bg-danger';
            } else if (percentage < 100) {
                percentageCard.className = 'summary-card bg-warning';
            } else {
                percentageCard.className = 'summary-card bg-success';
            }
        }

        document.getElementById('budgetForm').addEventListener('submit', function(e) {
            const totalBudget = parseInt(cleanMoney(document.getElementById('total_budget').value)) || 0;
            let departmentsTotal = 0;

            document.querySelectorAll('.department-budget').forEach(input => {
                departmentsTotal += parseInt(cleanMoney(input.value)) || 0;
            });

            if (totalBudget !== departmentsTotal) {
                e.preventDefault();
                alert(
                    `Error: El presupuesto total (${formatMoney(totalBudget.toString())}) no coincide con la suma de dependencias (${formatMoney(departmentsTotal.toString())})`);
                return false;
            }

            // Limpiar formato antes de enviar
            document.getElementById('total_budget').value = cleanMoney(document.getElementById('total_budget')
                .value);
            document.querySelectorAll('.department-budget').forEach(input => {
                input.value = cleanMoney(input.value);
            });
        });

        // Actualizar resumen cuando cambia el presupuesto total
        document.getElementById('total_budget').addEventListener('input', function(e) {
            const cursorPos = this.selectionStart;
            const raw = cleanMoney(this.value);
            this.value = formatMoney(raw);
            this.setSelectionRange(cursorPos, cursorPos);
            updateBudgetSummary();
        });

        // Agregar primera dependencia automáticamente
        addDepartment();
    </script>
@endpush
