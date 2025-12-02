@extends('layouts.dashboard')

@section('page-title', 'Quejas')

@section('dashboard-content')

    <style>
        /* —— ESTILOS MEJORADOS BASADOS EN FIGMA —— */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-outline-secondary {
            border: 1px solid #d1d5db;
            color: #4b5563;
            background: #fff;
            border-radius: 8px;
            padding: .5rem 1rem;
            font-weight: 500;
            font-size: 14px;
            transition: all .2s;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-outline-secondary i {
            margin-right: 6px;
            font-size: 13px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-left: 8px;
            text-decoration: none;
            color: #fff
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        /* Tarjetas - Estilo Figma */
        .card.custom-card {
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            min-height: 240px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #ffffff;
            position: relative;
            transition: all .25s ease;
            cursor: pointer;
        }

        .card.custom-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-color: #d1d5db;
        }

        .card-body {
            padding: 18px;
            flex: 1;
        }

        /* Etiquetas */
        .card-tags {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
        }

        .tag {
            padding: 4px 10px;
            font-size: 11px;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .tag--frequency {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .tag--type {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* Título */
        .card-body .title {
            font-size: 16px;
            margin: 8px 0 10px;
            font-weight: 700;
            color: #111827;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Descripción */
        .card-body .excerpt {
            color: #4b5563;
            font-size: 13px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 12px;
        }

        /* Lista de items */
        .card-body .excerpt ul {
            margin: 0;
            padding-left: 18px;
        }

        .card-body .excerpt li {
            margin-bottom: 4px;
        }

        /* Footer */
        .card-footer.custom-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-top: 1px solid #f3f4f6;
            background: #fafbfc;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .responsible-name {
            font-weight: 600;
            font-size: 13px;
            color: #111827;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Fecha/hora */
        .card-datetime {
            position: absolute;
            right: 18px;
            bottom: 60px;
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
        }

        /* Estado y días */
        .dias-text {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .state-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.8);
        }

        /* GRID */
        .grid-4 {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        /* ------------------------------ */
        /* MODALES MEJORADOS EXCLUSIVAMENTE */
        /* ------------------------------ */

        .custom-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            /* fondo más elegante */
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 200;
            backdrop-filter: blur(4px);
            /* efecto blur elegante */
            animation: modalFade .15s ease-out;
        }

        @keyframes modalFade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .custom-modal-content {
            width: 380px;
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 26px;
            box-shadow:
                0 20px 35px rgba(0, 0, 0, 0.12),
                0 0 0 1px rgba(0, 0, 0, 0.04);
            animation: modalPopup .25s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }

        @keyframes modalPopup {
            0% {
                opacity: 0;
                transform: scale(0.9) translateY(10px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* TITULO DEL MODAL */
        .custom-modal-content h4 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 18px;
            text-align: center;
        }

        /* LABELS */
        .custom-modal-content label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        /* SELECTS */
        .custom-modal-content .form-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 10px 14px;
            font-size: 14px;
            transition: all .25s;
        }

        .custom-modal-content .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* BOTÓN PRINCIPAL */
        .custom-modal-content button.btn-primary {
            border-radius: 10px;
            padding: 11px;
            font-size: 15px;
            font-weight: 600;
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            border: none;
            transition: all .2s ease;
            color: white
        }

        .custom-modal-content button.btn-primary:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #4ed839 0%, #2d9e24 100%);
        }

        /* BOTÓN SECUNDARIO */
        .custom-modal-content button.btn-light {
            border-radius: 10px;
            padding: 11px;
            font-size: 15px;
            font-weight: 500;
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            transition: all .2s ease;
        }

        .custom-modal-content button.btn-light:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        /* ESPACIADO ENTRE BOTONES */
        .custom-modal-content button+button {
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (min-width: 1200px) {
            .grid-4 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1600px) {
            .grid-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Header mejorado */
        .section-header h2 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 4px;
        }

        .section-header p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }
    </style>

    <!-- HEADER -->
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Gestión de Quejas</h2>
            <p class="text-muted">Listado completo de PQR</p>
        </div>

        <div class="d-flex gap-2">

            <!-- BOTÓN ORDENAR -->
            <button class="btn btn-outline-secondary" onclick="openOrderModal()">
                <i class="fas fa-sort"></i> Ordenar
            </button>

            <!-- BOTÓN FILTRAR -->
            <button class="btn btn-outline-secondary" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> Filtrar
            </button>

            <a href="{{ route('pqr.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Añadir queja
            </a>
        </div>
    </div>

    <!-- GRID DONDE SE PINTAN LOS RESULTADOS -->
    <div id="pqrGrid" class="grid-4">
        @include('Complaint.partials.cards', ['pqr' => $pqr])
    </div>

    <!-- FILTROS -->
    <div id="filterModal" class="custom-modal">
        <div class="custom-modal-content">
            <h4 class="mb-3">Filtrar</h4>

            <label class="fw-bold">Dependencia</label>
            <select id="filterDependency" class="form-select mb-3">
                <option value="">Todas</option>
                @foreach ($dependencies as $dep)
                    <option value="{{ $dep }}">{{ $dep }}</option>
                @endforeach
            </select>
<br>
            <label class="fw-bold">Estado</label>
            <select id="filterStatus" class="form-select mb-3">
                <option value="">Todos</option>
                <option value="verde">En tiempo</option>
                <option value="amarillo">Por vencer</option>
                <option value="rojo">Urgente</option>
                <option value="vencido">Vencido</option>
            </select>

            <br>
            <button class="btn btn-primary w-100" onclick="applyFilters()">Aplicar filtros</button>
            <button class="btn btn-light w-100 mt-2" onclick="closeFilterModal()">Cerrar</button>
        </div>
    </div>

    <!-- ORDENAR -->
    <div id="orderModal" class="custom-modal">
        <div class="custom-modal-content">
            <h4 class="mb-3">Ordenar</h4>

            <label class="fw-bold">Tipo de orden</label>
            <select id="orderColor" class="form-select mb-3">
                <option value="">Normal</option>
                <option value="1">Orden por color</option>
            </select>

            <button class="btn btn-primary w-100" onclick="applyFilters()">Aplicar orden</button>
            <button class="btn btn-light w-100 mt-2" onclick="closeOrderModal()">Cerrar</button>
        </div>
    </div>

    <script>
        // abrir modales
        function openFilterModal() {
            document.getElementById('filterModal').style.display = 'flex';
        }

        function closeFilterModal() {
            document.getElementById('filterModal').style.display = 'none';
        }

        function openOrderModal() {
            document.getElementById('orderModal').style.display = 'flex';
        }

        function closeOrderModal() {
            document.getElementById('orderModal').style.display = 'none';
        }

        // APLICAR FILTROS AJAX
        function applyFilters() {

            closeFilterModal();
            closeOrderModal();

            const dependency = document.getElementById('filterDependency').value;
            const status = document.getElementById('filterStatus').value;
            const order_color = document.getElementById('orderColor').value;

            let url = "{{ route('pqr.index') }}";

            fetch(url + "?" + new URLSearchParams({
                    dependency: dependency,
                    status: status,
                    order_color: order_color
                }))
                .then(res => res.text())
                .then(html => {

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, "text/html");

                    const newGrid = doc.querySelector('#pqrGrid');

                    if (newGrid) {
                        document.getElementById('pqrGrid').innerHTML = newGrid.innerHTML;
                    } else {
                        console.error("❌ No se encontró #pqrGrid en la respuesta AJAX");
                    }
                });
        }


        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($pqr as $item)
                @if ($item->is_expired)
                    alert("⚠️ La PQR '{{ $item->title }}' ha superado los 12 días y está vencida.");
                @endif
            @endforeach
        });
    </script>

@endsection
