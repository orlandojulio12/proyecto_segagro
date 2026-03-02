{{-- resources/views/budgets/create.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Crear Presupuesto')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <p class="text-muted">Registra un nuevo presupuesto general</p>
        </div>
        <a href="{{ route('budget.index') }}" class="btn btn-secondary shadow-sm">
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

    <form action="{{ route('budget.store') }}" method="POST" id="budgetForm">
        @csrf
        <!-- Contenedor principal con 2 columnas -->
        <div class="budgets-container">
            <!-- Columna formulario -->
            <div class="budgets-column">
                <div class="content-card">
                    <input type="hidden" name="sede_id" value="{{ $sedes->id }}">
                    <input type="hidden" name="manager_id" value="{{ $user->id }}">

                    <div class="form-group mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-wallet text-success"></i> Presupuesto Total *
                        </label>
                        <input type="text" name="total_budget" id="total_budget"
                            class="form-control modern-input @error('total_budget') is-invalid @enderror"
                            value="{{ old('total_budget') }}" required>
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
                            value="{{ old('year', date('Y')) }}" min="2020" max="2100" required>
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
                            value="{{ old('resolution') }}" placeholder="Ej: Res. 001-2025">
                        @error('resolution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <p class="budget-warning">
                        <strong>Aviso:</strong> Al crear un nuevo presupuesto, usted asume la responsabilidad sobre la
                        información registrada.
                        Toda acción quedará almacenada y será visible en el sistema de auditoría del aplicativo.
                    </p>
                    <br>
                    <br>
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
                </div>
            </div>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table-modern" id="departmentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Unidad</th>
                        <th>Subunidad</th>
                        <th>Presupuesto</th>
                        <th>Responsable</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div id="noRowsMessage" class="no-rows-message">
                <div class="icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="text">
                    <strong>¡No hay dependencias!</strong><br>
                    Haz clic en <span class="highlight">"Agregar Dependencia"</span> para registrar una.
                </div>
            </div>

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

        <!-- Botones de acción -->
        <div class="action-buttons">
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

        .no-rows-message {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 20px;
            background-color: #4cd1373a;
            /* color suave azul */
            border: 1px solid #b6d4fe;
            border-radius: 10px;
            color: #0c5460;
            font-size: 16px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin-top: 15px;
        }

        .no-rows-message .icon {
            font-size: 30px;
            color: #4cd137;
        }

        .no-rows-message .highlight {
            color: #4cd137;
            font-weight: bold;
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

        let departmentCount = 0;
        const units = @json($units);
        const managers = @json([$user]); // solo el manager actual para create

        function updateNoRowsMessage() {
            const tableBody = document.querySelector('#departmentsTable tbody');
            const message = document.getElementById('noRowsMessage');

            if (tableBody.children.length === 0) {
                message.style.display = 'block';
            } else {
                message.style.display = 'none';
            }
        }


        function addDepartment() {
            const tableBody = document.querySelector('#departmentsTable tbody');
            const index = departmentCount++;

            const newRow = document.createElement('tr');
            newRow.setAttribute('data-index', index);

            // Crear options para UNIDADES
            const unitOptions = units
                .map(u => `<option value="${u.dependency_unit_id}">${u.short_name}</option>`)
                .join('');

            newRow.innerHTML = `
        <td class="department-number">${index + 1}</td>

        <!-- Select Unidad -->
        <td>
            <select class="modern-input unit-select" 
                    name="departments[${index}][unit_id]" 
                    onchange="loadSubunits(${index})"
                    required>
                <option value="">Seleccionar...</option>
                ${unitOptions}
            </select>
        </td>

        <!-- Select Subunidad (dinámico) -->
        <td>
            <select class="modern-input subunit-select"
                    name="departments[${index}][id]"
                    required>
                <option value="">Seleccione una unidad primero</option>
            </select>
        </td>

        <!-- Presupuesto -->
        <td>
            <input type="text" name="departments[${index}][total_budget]"
                   class="modern-input department-budget"
                   placeholder="0" required>
        </td>

        <!-- Responsable -->
        <td>
            <input type="text" class="modern-input" value="{{ $user->name }}" disabled>
        </td>

        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDepartment(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

            tableBody.appendChild(newRow);
            attachMoneyFormatting();
            updateBudgetSummary();
            updateNoRowsMessage();
        }


        function removeDepartment(index) {
            const row = document.querySelector(`#departmentsTable tr[data-index="${index}"]`);
            if (row && confirm('¿Estás seguro de eliminar esta dependencia del presupuesto?')) {
                row.remove();
                updateBudgetSummary();
                updateNoRowsMessage();
            }
        }

        function loadSubunits(index) {
            const unitSelect = document.querySelector(`tr[data-index="${index}"] .unit-select`);
            const subunitSelect = document.querySelector(`tr[data-index="${index}"] .subunit-select`);

            const selectedUnit = units.find(u => u.dependency_unit_id == unitSelect.value);

            if (!selectedUnit) {
                subunitSelect.innerHTML = '<option value="">Seleccione una unidad primero</option>';
                return;
            }

            subunitSelect.innerHTML = selectedUnit.subunits
                .map(s => `<option value="${s.subunit_id}">${s.subunit_code} - ${s.name}</option>`)
                .join('');
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

        attachMoneyFormatting();
        updateBudgetSummary();

        document.getElementById('budgetForm').addEventListener('submit', function(e) {
            const totalBudget = parseInt(cleanMoney(document.getElementById('total_budget').value)) || 0;
            let departmentsTotal = 0;

            document.querySelectorAll('.department-budget').forEach(input => {
                departmentsTotal += parseInt(cleanMoney(input.value)) || 0;
                input.value = cleanMoney(input.value);
            });

            if (totalBudget !== departmentsTotal) {
                e.preventDefault();
                alert(
                    `Error: El presupuesto total (${formatMoney(totalBudget.toString())}) no coincide con la suma de dependencias (${formatMoney(departmentsTotal.toString())})`
                );
            }
        });

        document.getElementById('total_budget').addEventListener('input', function(e) {
            const cursorPos = this.selectionStart;
            const raw = cleanMoney(this.value);
            this.value = formatMoney(raw);
            this.setSelectionRange(cursorPos, cursorPos);
            updateBudgetSummary();
        });

        // Agregar primera dependencia por defecto
        addDepartment();
        updateNoRowsMessage();
    </script>
@endpush
