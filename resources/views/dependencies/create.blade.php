@extends('layouts.dashboard')

@section('page-title', 'Crear Dependencias')

@section('dashboard-content')

    <div class="dependencies-edit">

        {{-- ===================== HEADER ===================== --}}
        <div class="page-header">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <h3>Gestión de Dependencias</h3>
                    <p>Estructura organizacional del sistema</p>
                </div>
            </div>

            <a href="{{ route('dependencies.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>

        {{-- ===================== ERRORES ===================== --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dependencies.store') }}">
            @csrf

            {{-- ===================== UNIDAD ===================== --}}
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-building"></i>
                    Unidad (Dependencia)
                </h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre Corto *</label>
                        <input type="text" name="short_name" class="modern-input" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="full_name" class="modern-input" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" rows="2" class="modern-input"></textarea>
                    </div>
                </div>
            </div>

            {{-- ===================== SUBUNIDADES ===================== --}}
            <div class="content-card">
                <h5 class="section-title">
                    <i class="fas fa-project-diagram"></i>
                    Subunidades
                </h5>

                <table class="table-modern" id="subunitsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div id="noRowsMessage" class="no-rows-message">
                    <div class="empty-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>

                    <div class="empty-content">
                        <h6>No hay subunidades registradas</h6>
                        <p>
                            Comienza agregando una nueva subunidad usando el botón
                            <span class="highlight">Agregar Subunidad</span>
                        </p>
                    </div>
                </div>

                <button type="button" class="btn btn-add" onclick="addSubunit()">
                    <i class="fas fa-plus"></i>
                    Agregar Subunidad
                </button>
            </div>

            {{-- ===================== ACTIONS ===================== --}}
            <div class="action-buttons">
                <a href="{{ route('dependencies.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i>
                    Guardar Dependencia
                </button>
            </div>

        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* ===================== BASE ===================== */
        .dependencies-edit {
            background: #f4f6f9;
        }

        /* ===================== HEADER ===================== */
        .page-header {
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            color: white;
            padding: 22px 26px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
            margin-bottom: 28px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, .2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        .page-header h3 {
            margin: 0;
            font-weight: 800;
        }

        .page-header p {
            margin: 0;
            opacity: .9;
        }

        .btn-back {
            background: rgba(255, 255, 255, .2);
            color: white;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            transition: .3s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, .35);
            transform: translateY(-2px);
        }

        /* ===================== CARD ===================== */
        .content-card {
            background: white;
            padding: 26px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            transition: .3s;
            margin-top: 10px;
        }

        .content-card:hover {
            transform: translateY(-2px);
        }

        /* ===================== TITLES ===================== */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            border-bottom: 2px solid #4cd137;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .section-title i {
            color: #4cd137;
        }

        /* ===================== INPUTS ===================== */
        .modern-input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            transition: .25s;
        }

        .modern-input:focus {
            outline: none;
            border-color: #4cd137;
            box-shadow: 0 0 0 3px rgba(76, 209, 55, .25);
        }

        /* ===================== TABLE ===================== */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-modern thead th {
            background: #2ecc71;
            color: white;
            padding: 12px;
            border: none;
        }

        .table-modern tbody tr {
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
            transition: .35s;
        }

        .table-modern tbody tr:hover {
            transform: scale(1.01);
        }

        /* ===================== ROW ANIMATIONS ===================== */
        tr.row-enter {
            animation: rowIn .4s ease forwards;
        }

        @keyframes rowIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        tr.row-leave {
            animation: rowOut .35s ease forwards;
        }

        @keyframes rowOut {
            to {
                opacity: 0;
                transform: translateX(20px) scale(.9);
            }
        }

        /* ===================== EMPTY STATE SUBUNIDADES ===================== */
        .dependencies-edit .no-rows-message {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 22px 26px;
            margin-top: 18px;
            border-radius: 16px;
            background: linear-gradient(135deg,
                    rgba(76, 209, 55, 0.15),
                    rgba(76, 209, 55, 0.05));
            border: 1px dashed rgba(76, 209, 55, 0.45);
            color: #2c3e50;
            animation: emptyFadeIn .4s ease;
        }

        /* ICONO */
        .dependencies-edit .no-rows-message .empty-icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 6px 18px rgba(76, 209, 55, 0.45);
            flex-shrink: 0;
        }

        /* CONTENIDO */
        .dependencies-edit .no-rows-message .empty-content h6 {
            margin: 0;
            font-weight: 800;
            font-size: 1rem;
            color: #2c3e50;
        }

        .dependencies-edit .no-rows-message .empty-content p {
            margin: 6px 0 0;
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.4;
        }

        /* HIGHLIGHT */
        .dependencies-edit .no-rows-message .highlight {
            color: #2ecc71;
            font-weight: 700;
        }

        /* ANIMACIÓN */
        @keyframes emptyFadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ===================== BUTTONS ===================== */
        .btn {
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 20px;
            transition: .3s;
            border: none;
        }

        .btn-add {
            background: linear-gradient(135deg, #4cd137, #2ecc71);
            color: white;
            margin-top: 10px;
        }

        .btn-save {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, .25);
        }

        /* ===================== ACTIONS ===================== */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            margin-top: 30px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let subunitCount = 0;

        function updateNoRowsMessage() {
            const tbody = document.querySelector('#subunitsTable tbody');
            document.getElementById('noRowsMessage').style.display =
                tbody.children.length === 0 ? 'flex' : 'none';
        }

        function addSubunit() {
            const tbody = document.querySelector('#subunitsTable tbody');
            const index = subunitCount++;

            const row = document.createElement('tr');
            row.classList.add('row-enter');
            row.dataset.index = index;

            row.innerHTML = `
        <td>${index + 1}</td>
        <td><input name="subunits[${index}][subunit_code]" class="modern-input" required></td>
        <td><input name="subunits[${index}][name]" class="modern-input" required></td>
        <td><input name="subunits[${index}][description]" class="modern-input"></td>
        <td>
            <button type="button" class="btn btn-cancel btn-sm" onclick="removeSubunit(${index})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

            tbody.appendChild(row);
            updateNoRowsMessage();
        }

        function removeSubunit(index) {
            const row = document.querySelector(`#subunitsTable tr[data-index="${index}"]`);
            if (!row) return;

            if (confirm('¿Eliminar esta subunidad?')) {
                row.classList.add('row-leave');
                setTimeout(() => {
                    row.remove();
                    updateNoRowsMessage();
                }, 300);
            }
        }

        updateNoRowsMessage();
    </script>
@endpush
