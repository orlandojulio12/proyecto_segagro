@extends('layouts.dashboard')

@section('page-title', 'PQRS')

@section('dashboard-content')

    <style>
        /* ============================= */
        /* 🎯 LAYOUT GENERAL */
        /* ============================= */

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grid-4 {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
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

        /* ============================= */
        /* 🎯 BOTONES */
        /* ============================= */

        .btn-outline-secondary {
            border: 1px solid #d1d5db;
            color: #4b5563;
            background: #fff;
            border-radius: 8px;
            padding: .5rem 1rem;
            font-size: 14px;
            transition: .2s;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-success {
            background: linear-gradient(135deg, #4cd137, #3db32a);
            padding: 10px 20px;
            color: #fff;
            border-radius: 8px;
            transition: .25s;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.4);
        }

        /* ============================= */
        /* 🎯 CARD BASE */
        /* ============================= */

        .card.custom-card {
            border-radius: 16px;
            border: 1px solid #eef0f3;
            background: #fff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
        }

        .card.custom-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        /* ============================= */
        /* 🎯 HEADER */
        /* ============================= */

        .card-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 18px 6px;
        }

        .card-date {
            font-size: 11px;
            color: #9ca3af;
        }

        /* BADGE */

        .card-type-badge {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 999px;
            color: #fff;
        }

        .card-type-badge.pqr {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .card-type-badge.tutela {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        /* ============================= */
        /* 🎯 BODY */
        /* ============================= */

        .card-body {
            padding: 10px 18px 16px;
            flex: 1;
        }

        .card-tags {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
        }

        .tag {
            font-size: 10px;
            padding: 5px 10px;
            border-radius: 999px;
            font-weight: 600;
        }

        .tag--frequency {
            background: rgba(245, 158, 11, .12);
            color: #b45309;
        }

        .tag--type {
            background: rgba(59, 130, 246, .12);
            color: #1d4ed8;
        }

        .title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 6px;
        }

        .excerpt {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }

        /* ============================= */
        /* 🎯 SLA */
        /* ============================= */

        .sla-section {
            padding: 14px 18px;
            background: linear-gradient(#fafafa, #f8fafc);
            border-top: 1px solid #f1f5f9;
        }

        .sla-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .sla-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sla-remaining {
            font-size: 15px;
            font-weight: 700;
        }

        .sla-text small {
            font-size: 11px;
            color: #6b7280;
        }

        .sla-bar {
            height: 5px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .sla-fill {
            height: 100%;
            border-radius: 999px;
            transition: width .5s ease;
        }

        .sla-tooltip {
            position: fixed;
            background: #111827;
            color: #fff;
            font-size: 12px;
            padding: 8px 10px;
            border-radius: 8px;
            pointer-events: none;
            opacity: 0;
            transform: translate(-50%, -120%);
            transition: all .15s ease;
            white-space: nowrap;
            z-index: 999;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .25);
        }

        .sla-tooltip.show {
            opacity: 1;
        }

        .sla-completed {
            font-weight: 600;
            color: #10b981;
        }

        /* ============================= */
        /* 🎯 FOOTER */
        /* ============================= */

        .card-footer.custom-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            border-top: 1px solid #f3f4f6;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .responsible {
            font-size: 12px;
            font-weight: 600;
        }

        /* ============================= */
        /* 🎯 ACTIONS */
        /* ============================= */

        .card-actions {
            display: flex;
            gap: 6px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            transition: all .25s cubic-bezier(.4, 0, .2, 1);
            position: relative;
            overflow: hidden;
        }

        /* Hover base (todos) */
        .btn-action:hover {
            background: #ffffff;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        /* Efecto glow sutil */
        .btn-action::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            opacity: 0;
            transition: opacity .25s;
        }

        .btn-action:hover::after {
            opacity: 1;
        }

        /* ---------- VARIANTES ---------- */

        /* EDITAR */
        .btn-action:not(.danger):not(.success):hover {
            color: #2563eb;
        }

        .btn-action:not(.danger):not(.success)::after {
            background: radial-gradient(circle at center, rgba(37, 99, 235, 0.15), transparent 70%);
        }

        /* ELIMINAR */
        .btn-action.danger:hover {
            background: #fff;
            color: #dc2626;
            box-shadow: 0 8px 18px rgba(220, 38, 38, 0.25);
        }

        .btn-action.danger::after {
            background: radial-gradient(circle at center, rgba(220, 38, 38, 0.18), transparent 70%);
        }

        /* ESTADO / CHECK */
        .btn-action.success:hover {
            background: #fff;
            color: #16a34a;
            box-shadow: 0 8px 18px rgba(22, 163, 74, 0.25);
        }

        .btn-action.success::after {
            background: radial-gradient(circle at center, rgba(22, 163, 74, 0.18), transparent 70%);
        }

        /* CLICK (feedback táctil) */
        .btn-action:active {
            transform: scale(0.95);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-action i {
            transition: transform .2s ease;
        }

        .btn-action:hover i {
            transform: scale(1.15);
        }

        /* ============================= */
        /* 🎯 ESTADOS */
        /* ============================= */

        .urgent {
            border: 1px solid rgba(239, 68, 68, .3);
        }

        /* ===========================
                   BOTONES PREMIUM FILTROS/ORDENAR
                =========================== */
        .btn-premium-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border: 2px solid #6b7280;
            border-radius: 14px;
            font-weight: 600;
            color: #374151;
            background: transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(.25, .8, .25, 1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-premium-outline:hover {
            color: #4cd137;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(76, 209, 55, 0.2);
        }

        /* El contenido del botón siempre por encima del pseudo-elemento */
        .btn-premium-outline i,
        .btn-premium-outline span {
            position: relative;
            z-index: 1;
        }

        /* Fondo animado al hacer hover */
        .btn-premium-outline::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(76, 209, 55, 0.3), rgba(76, 209, 55, 0.1));
            transition: all 0.5s ease;
            border-radius: 14px;
            z-index: 0;
        }

        /* Botón de éxito + hover premium (para “Añadir queja”) */
        .btn-premium-success {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 14px;
            font-weight: 600;
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(76, 209, 55, 0.3);
        }

        /* Hover con ligero brillo */
        .btn-premium-success::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(45deg) translateX(-100%);
            transition: all 0.5s ease;
        }

        .btn-premium-success:hover::after {
            transform: rotate(45deg) translateX(0);
        }

        .btn-premium-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 209, 55, 0.4);
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 10px;
            margin-top: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(76, 209, 55, 0.3);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(76, 209, 55, 0.4);
        }

        .btn-premium-light {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #d1d5db;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px
        }

        .btn-premium-light:hover {
            background: #e5e7eb;
            border-color: #9ca3af;
            transform: translateY(-1px);
        }

        /* ===========================
                       SELECT PREMIUM
                    =========================== */
        .form-select-premium {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            background: white;
            font-size: 14px;
            color: #374151;
            transition: all 0.3s ease;
        }

        .form-select-premium:focus {
            outline: none;
            border-color: #4cd137;
            box-shadow: 0 0 0 3px rgba(76, 209, 55, 0.2);
        }

        /* ===========================
                       MODALES PREMIUM
                    =========================== */
        .custom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .custom-modal.show {
            display: flex;
        }

        .custom-modal-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 28px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 0.4s ease forwards;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #111827;
            text-align: center;
        }

        @keyframes modalFadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================= */
        /* 🎯 TOAST */
        /* ============================= */

        .toast-warning {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #111827;
            color: #fff;
            padding: 12px 16px;
            border-radius: 10px;
        }

        .confirm-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }

        .confirm-modal.show {
            display: flex;
        }

        /* 🔥 NUEVO BOX PREMIUM */
        .confirm-box {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .18);
            animation: modalIn .25s cubic-bezier(.4, 0, .2, 1);
            text-align: left;
        }

        /* HEADER */
        .confirm-box h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #0f172a;
        }

        /* INPUTS */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            display: block;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            transition: all .2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
        }

        /* BOTONES */
        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-cancel {
            padding: 10px 14px;
            border-radius: 10px;
            background: #f3f4f6;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .btn-delete {
            padding: 10px 16px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .btn-delete:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, .3);
        }

        /* ANIMACIÓN */
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* TOAST */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #111827;
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: all .3s ease;
            z-index: 9999;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <!-- HEADER -->
    <div class="section-header mb-4">
        <div>
            <p class="text-muted">Listado completo de PQRS((Peticiones, Quejas, Reclamos y Solicitudes))</p>
        </div>
        <br>

        <div class="d-flex gap-2 flex-wrap">

            <!-- BOTÓN ORDENAR -->
            <button class="btn-premium-outline" onclick="openOrderModal()">
                <i class="fas fa-sort"></i> Ordenar
            </button>

            <!-- BOTÓN FILTRAR -->
            <button class="btn-premium-outline" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> Filtrar
            </button>

            <a href="{{ route('pqr.create') }}" class="btn-premium-success">
                <i class="fas fa-plus"></i> Añadir queja
            </a>
        </div>

        <!-- MODAL FILTRAR -->
        <div id="filterModal" class="custom-modal">
            <div class="custom-modal-content">
                <h4 class="modal-title">Filtrar PQR</h4>

                <label class="fw-bold">Dependencia</label>
                <select id="filterDependency" name="dependency" class="form-select-premium mb-3">
                    <option value="">-- Todas las dependencias --</option>
                    @foreach ($dependencies as $dep)
                        <option value="{{ $dep->id_dependencia }}"
                            {{ request('dependency') == $dep->id_dependencia ? 'selected' : '' }}>
                            {{ $dep->name }}
                        </option>
                    @endforeach
                </select>

                <label class="fw-bold">Estado</label>
                <select id="filterStatus" class="form-select-premium mb-3">
                    <option value="">Todos</option>
                    <option value="verde">En tiempo</option>
                    <option value="amarillo">Por vencer</option>
                    <option value="rojo">Urgente</option>
                    <option value="vencido">Vencido</option>
                </select>

                <button class="btn-premium-primary w-100 mb-2" onclick="applyFilters()">Aplicar filtros</button>
                <button class="btn-premium-light w-100" onclick="closeFilterModal()">Cerrar</button>
            </div>
        </div>

        <!-- MODAL ORDENAR -->
        <div id="orderModal" class="custom-modal">
            <div class="custom-modal-content">
                <h4 class="modal-title">Ordenar PQR</h4>

                <label class="fw-bold">Ordenar por</label>
                <select id="orderColor" class="form-select-premium mb-3">
                    <option value="">Por fecha</option>
                    <option value="1" {{ request('order_color') == 1 ? 'selected' : '' }}>Por estado/días restantes
                    </option>
                </select>

                <button class="btn-premium-primary w-100 mb-2" onclick="applyFilters()">Aplicar orden</button>
                <button class="btn-premium-light w-100" onclick="closeOrderModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- GRID DONDE SE PINTAN LOS RESULTADOS -->
    <div id="pqrGrid" class="grid-4">
        @include('Complaint.partials.cards', ['pqr' => $pqr])
    </div>

    <!-- 🔥 TOOLTIP GLOBAL -->
    <div id="slaTooltip" class="sla-tooltip"></div>

    <!-- 🔥 MODAL GLOBAL -->
    <div id="confirmModal" class="confirm-modal">
        <div class="confirm-box">
            <h3>Eliminar PQR</h3>
            <p>¿Seguro que deseas eliminar esta PQR? Esta acción no se puede deshacer.</p>

            <div class="confirm-actions">
                <button id="cancelDelete" class="btn-cancel">Cancelar</button>
                <button id="acceptDelete" class="btn-delete">Eliminar</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="confirm-modal">
        <div class="confirm-box" style="max-width:600px;">
            <h3>Actualizar PQR</h3>

            <form id="editForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="editId">

                <div class="form-group">
                    <label>Estado</label>
                    <select id="editState" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Finalizado</option>
                    </select>
                </div>

                <div class="confirm-actions">
                    <button type="button" onclick="closeEditModal()" class="btn-cancel">Cancelar</button>
                    <button type="button" onclick="updatePqr()" class="btn-delete">Guardar</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // =========================
            // MODALES
            // =========================
            window.openFilterModal = () => {
                document.getElementById('filterModal').style.display = 'flex';
            };

            window.closeFilterModal = () => {
                document.getElementById('filterModal').style.display = 'none';
            };

            window.openOrderModal = () => {
                document.getElementById('orderModal').style.display = 'flex';
            };

            window.closeOrderModal = () => {
                document.getElementById('orderModal').style.display = 'none';
            };

            // =========================
            // FILTROS AJAX
            // =========================
            window.applyFilters = () => {

                closeFilterModal();
                closeOrderModal();

                const dependency = document.getElementById('filterDependency').value;
                const status = document.getElementById('filterStatus').value;
                const order_color = document.getElementById('orderColor').value;

                let url = "{{ route('pqr.index') }}";

                fetch(url + "?" + new URLSearchParams({
                        dependency,
                        status,
                        order_color
                    }))
                    .then(res => res.text())
                    .then(html => {

                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, "text/html");
                        const newGrid = doc.querySelector('#pqrGrid');

                        if (newGrid) {
                            document.getElementById('pqrGrid').innerHTML = newGrid.innerHTML;
                        } else {
                            console.error("❌ No se encontró #pqrGrid");
                        }
                    });
            };

            // =========================
            // TOAST
            // =========================
            window.notify = (msg) => {
                const toast = document.createElement('div');
                toast.className = 'toast-warning';
                toast.innerText = msg;

                document.body.appendChild(toast);

                setTimeout(() => toast.remove(), 4000);
            };

            // =========================
            // TOOLTIP SLA (🔥 FIX REAL)
            // =========================
            const tooltip = document.getElementById('slaTooltip');

            document.addEventListener('mousemove', (e) => {

                const bar = e.target.closest('.sla-fill');

                if (!bar) {
                    tooltip.classList.remove('show');
                    return;
                }

                const elapsed = parseFloat(bar.dataset.elapsed);
                const total = parseFloat(bar.dataset.total);
                const unit = bar.dataset.unit;

                const percent = Math.round((elapsed / total) * 100);

                tooltip.innerHTML = `
            <div style="font-weight:600">
                ${elapsed.toFixed(1)} ${unit}
            </div>
            <div style="font-size:11px; opacity:.7">
                de ${total} ${unit} (${percent}%)
            </div>
        `;

                tooltip.style.left = e.clientX + 'px';
                tooltip.style.top = e.clientY + 'px';

                tooltip.classList.add('show');
            });

        });
        document.getElementById('editModal').addEventListener('click', (e) => {
            if (e.target.id === 'editModal') {
                closeEditModal();
            }
        });
    </script>

@endsection
