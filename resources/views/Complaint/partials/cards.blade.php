@if ($pqr->count() > 0)
    @foreach ($pqr as $item)
        @php
            $isTutela = $item->is_tutela;

            // Máximo dinámico
            $max = $isTutela ? $item->horas_tutela ?? 72 : 12;

            // Calculamos progreso en %
            $remaining = $item->days_remaining;
            $progress = $max > 0 ? 100 - ($remaining / $max) * 100 : 100;

            // Unidad para mostrar
            $unit = $isTutela ? 'horas' : 'días';
        @endphp

        <div class="card custom-card">

            {{-- HEADER --}}
            <div class="card-header-top">
                <div class="card-type-badge {{ $isTutela ? 'tutela' : 'pqr' }}">
                    {{ $isTutela ? 'Tutela' : 'PQR' }}
                </div>

                <div class="card-date">
                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                <div class="card-tags">
                    <span class="tag tag--frequency">
                        {{ optional($item->concepto)->name ?? 'Sin concepto' }}
                    </span>

                    <span class="tag tag--type">
                        {{ optional(optional($item->concepto)->dependencia)->name ?? 'Sin dependencia' }}
                    </span>
                </div>

                <div class="title">
                    {{ $item->title }}
                </div>

                <div class="excerpt">
                    {!! nl2br(e(Str::limit($item->description, 140))) !!}
                </div>

            </div>

            {{-- SLA (AHORA PROTAGONISTA) --}}
            <div class="sla-section">
                <div class="sla-info">

                    <div class="sla-text">
                        @if ($item->state)
                            {{-- <-- asumiendo state = 1 es finalizada --}}
                            <span class="sla-completed">Finalizada</span>
                        @else
                            @if ($item->is_expired)
                                <span class="sla-expired">Vencido</span>
                            @else
                                <span class="sla-remaining">{{ $item->time_formatted }}</span>
                                <span class="sla-label">restantes</span>
                            @endif
                            <small>
                                {{ $isTutela ? "Tiempo límite: {$item->horas_tutela} horas" : 'Tiempo límite: 12 días' }}
                            </small>
                        @endif
                    </div>

                    <div class="status-badge" data-status="{{ $item->state_text }}">
                        {{ $item->state_text }}
                    </div>

                </div>

                @if (!$item->state)
                    <div class="sla-bar">
                        <div class="sla-fill" data-elapsed="{{ $max - $remaining }}" data-total="{{ $max }}"
                            data-unit="{{ $unit }}"
                            style="width: {{ max(0, min(100, $progress)) }}%;
            background: {{ $item->color_status }}">
                        </div>
                    </div>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="card-footer custom-footer">

                <div class="footer-left">
                    <div class="avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->responsible) }}">
                    </div>

                    <div class="responsible">
                        {{ $item->responsible }}
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('pqr.edit', $item->id) }}" class="btn-action">
                        <i class="fas fa-edit"></i>
                    </a>

                    <button onclick="confirmDelete({{ $item->id }})" class="btn-action danger">
                        <i class="fas fa-trash"></i>
                    </button>

                    <button class="btn-action warning btn-edit" data-id="{{ $item->id }}"
                        data-state="{{ $item->state }}">
                        <i class="fas {{ $item->state ? 'fa-rotate-left' : 'fa-check' }}"></i>
                    </button>
                </div>

            </div>
        </div>
    @endforeach
@else
    <div class="empty-state-wrapper">
        <div class="empty-state-card">
            <div class="empty-state-icon">
                <div class="icon-circle">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>

            <h3 class="empty-state-title">No hay PQR registradas</h3>

            <p class="empty-state-description">
                @if (request('dependency') || request('status'))
                    No se encontraron PQR que coincidan con los filtros seleccionados.
                    <br>Intenta ajustar tus criterios de búsqueda.
                @else
                    Aún no has registrado ninguna Petición, Queja o Reclamo.
                    <br>Comienza creando tu primera PQR.
                @endif
            </p>

            <div class="empty-state-actions">
                @if (request('dependency') || request('status'))
                    <button onclick="clearFilters()" class="btn-empty-primary">
                        <i class="fas fa-redo-alt"></i>
                        Limpiar filtros
                    </button>
                @else
                    <a href="{{ route('pqr.create') }}" class="btn-empty-primary">
                        <i class="fas fa-plus-circle"></i>
                        Crear primera PQR
                    </a>
                @endif


            </div>

            <!-- Ilustración decorativa -->
            <div class="empty-state-illustration">
                <svg width="200" height="160" viewBox="0 0 200 160" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <!-- Documento principal -->
                    <rect x="40" y="30" width="120" height="100" rx="8" fill="#f0fdf4" stroke="#4cd137"
                        stroke-width="2" />

                    <!-- Líneas de texto -->
                    <line x1="60" y1="50" x2="140" y2="50" stroke="#86efac" stroke-width="3"
                        stroke-linecap="round" />
                    <line x1="60" y1="65" x2="120" y2="65" stroke="#86efac" stroke-width="3"
                        stroke-linecap="round" />
                    <line x1="60" y1="80" x2="130" y2="80" stroke="#86efac" stroke-width="3"
                        stroke-linecap="round" />

                    <!-- Ícono de búsqueda -->
                    <circle cx="100" cy="105" r="12" fill="white" stroke="#4cd137" stroke-width="2" />
                    <line x1="109" y1="114" x2="118" y2="123" stroke="#4cd137" stroke-width="2"
                        stroke-linecap="round" />

                    <!-- Partículas decorativas -->
                    <circle cx="30" cy="40" r="3" fill="#86efac" opacity="0.6" />
                    <circle cx="170" cy="50" r="4" fill="#4ade80" opacity="0.5" />
                    <circle cx="180" cy="100" r="3" fill="#86efac" opacity="0.6" />
                    <circle cx="25" cy="100" r="4" fill="#4ade80" opacity="0.5" />
                </svg>
            </div>
        </div>
    </div>
@endif

<style>
    /* Botones de acción en las tarjetas */
    .card-actions {
        position: absolute;
        top: 18px;
        right: 18px;
        display: flex;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 10;
    }

    .card.custom-card:hover .card-actions {
        opacity: 1;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-action-edit {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: white;
        text-decoration: none;
    }

    .btn-action-edit:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(76, 209, 55, 0.3);
    }

    .btn-action-delete {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .btn-action-delete:hover {
        background: linear-gradient(135deg, #ee5a52 0%, #e74c3c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
    }

    /* ================================ */
    /* EMPTY STATE MEJORADO - VERDE */
    /* ================================ */

    .empty-state-wrapper {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 500px;
        padding: 40px 20px;
    }

    .empty-state-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        border-radius: 24px;
        padding: 48px 40px;
        max-width: 560px;
        width: 100%;
        box-shadow:
            0 10px 40px rgba(76, 209, 55, 0.08),
            0 0 0 1px rgba(76, 209, 55, 0.1);
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .empty-state-icon {
        margin-bottom: 24px;
        display: flex;
        justify-content: center;
    }

    .icon-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 8px 24px rgba(76, 209, 55, 0.3),
            0 0 0 12px rgba(76, 209, 55, 0.1);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow:
                0 8px 24px rgba(76, 209, 55, 0.3),
                0 0 0 12px rgba(76, 209, 55, 0.1);
        }

        50% {
            transform: scale(1.05);
            box-shadow:
                0 12px 32px rgba(76, 209, 55, 0.4),
                0 0 0 16px rgba(76, 209, 55, 0.15);
        }
    }

    .icon-circle i {
        font-size: 48px;
        color: white;
    }

    .empty-state-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
        line-height: 1.2;
    }

    .empty-state-description {
        font-size: 16px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 32px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-state-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-empty-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 209, 55, 0.3);
    }

    .btn-empty-primary:hover {
        background: linear-gradient(135deg, #3db32a 0%, #2d9e24 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 209, 55, 0.4);
        color: white;
    }

    .btn-empty-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: white;
        color: #4cd137;
        border: 2px solid #4cd137;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-empty-secondary:hover {
        background: #f0fdf4;
        border-color: #3db32a;
        color: #3db32a;
        transform: translateY(-2px);
    }

    .empty-state-illustration {
        margin-top: 40px;
        opacity: 0.9;
        animation: float 3s ease-in-out infinite;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 12px;
        text-align: center;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    /* Colores según el estado */
    .status-badge:before {
        content: '';
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
        background: currentColor;
    }

    /* Estado Pendiente */
    .status-badge:empty[data-status="Pendiente"],
    .status-badge[data-status="Pendiente"] {
        background: #f59e0b;
        /* naranja */
    }

    /* Estado Completada */
    .status-badge:empty[data-status="Completada"],
    .status-badge[data-status="Completada"] {
        background: #10b981;
        /* verde */
    }

    /* Estado Vencido */
    .status-badge:empty[data-status="Vencido"],
    .status-badge[data-status="Vencido"] {
        background: #ef4444;
        /* rojo */
    }

    /* Estado Urgente */
    .status-badge:empty[data-status="Urgente"],
    .status-badge[data-status="Urgente"] {
        background: #f97316;
        /* naranja oscuro */
    }

    /* Animación ligera */
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
    }

    .dias-text {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dias-text:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }



    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .empty-state-card {
            padding: 32px 24px;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
        }

        .icon-circle i {
            font-size: 40px;
        }

        .empty-state-title {
            font-size: 24px;
        }

        .empty-state-description {
            font-size: 14px;
        }

        .empty-state-actions {
            flex-direction: column;
        }

        .btn-empty-primary,
        .btn-empty-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        let deleteId = null;

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
        // 🔥 CORE: REFRESH GRID
        // =========================
        window.refreshGrid = () => {

            const dependency = document.getElementById('filterDependency').value;
            const status = document.getElementById('filterStatus').value;
            const order_color = document.getElementById('orderColor').value;

            fetch(`{{ route('pqr.index') }}?` + new URLSearchParams({
                    dependency,
                    status,
                    order_color
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('pqrGrid').innerHTML = html;
                });
        };

        // =========================
        // FILTROS
        // =========================
        window.applyFilters = () => {
            closeFilterModal();
            closeOrderModal();
            refreshGrid();
        };

        window.clearFilters = () => {
            document.getElementById('filterDependency').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('orderColor').value = '';
            refreshGrid();
        };

        // =========================
        // DELETE AJAX 🔥
        // =========================
        window.confirmDelete = (id) => {
            deleteId = id;
            document.getElementById('confirmModal').classList.add('show');
        };

        document.getElementById('cancelDelete').addEventListener('click', () => {
            document.getElementById('confirmModal').classList.remove('show');
            deleteId = null;
        });

        document.getElementById('acceptDelete').addEventListener('click', () => {
            if (!deleteId) return;

            fetch(`/pqr/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(() => {

                    const modal = document.getElementById('confirmModal');
                    modal.classList.remove('show');

                    deleteId = null;

                    notify("PQR eliminada correctamente");

                    // 🔥 pequeño delay para UX
                    setTimeout(() => {
                        refreshGrid();
                    }, 150);

                })
                .catch(() => alert('Error al eliminar'));
        });

        // =========================
        // TOGGLE ESTADO AJAX 🔥
        // =========================
        window.toggleState = (id) => {
            fetch(`/pqr/${id}/toggle-state`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(() => {
                    notify("Estado actualizado");
                    refreshGrid(); // 🔥 sin reload
                })
                .catch(() => alert('Error al cambiar estado'));
        };

        // =========================
        // TOAST PRO
        // =========================
        window.notify = (msg) => {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerText = msg;

            document.body.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 50);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };

        // =========================
        // TOOLTIP SLA
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

        // =========================
        // 🔥 AUTO-REFRESH (TIEMPO REAL SIMPLE)
        // =========================
        setInterval(() => {

            const isModalOpen =
                document.getElementById('confirmModal')?.classList.contains('show') ||
                document.getElementById('editModal')?.classList.contains('show');

            if (!isModalOpen) {
                refreshGrid();
            }

        }, 15000);

        window.openEditModal = (id, state, isTutela) => {
            document.getElementById('editId').value = id;
            document.getElementById('editState').value = state ? 1 : 0;

            document.getElementById('editModal').classList.add('show');
        };

        window.closeEditModal = () => {
            document.getElementById('editModal').classList.remove('show');
        };

        window.updatePqr = () => {
            const id = document.getElementById('editId').value;
            const state = document.getElementById('editState').value;

            fetch(`/pqr/${id}/toggle-state`, { // <-- ruta correcta
                    method: 'PATCH', // PATCH ya es el método correcto
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ // solo enviamos state
                        state: state
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Error al actualizar');
                    return res.json();
                })
                .then(() => {
                    // Cierra el modal
                    closeEditModal();

                    // Notificación
                    notify("PQR actualizada correctamente");

                    // Refresca la grilla
                    setTimeout(() => {
                        refreshGrid();
                    }, 150);
                })
                .catch(() => alert('Error al actualizar'));
        };

        document.addEventListener('click', (e) => {

            const btn = e.target.closest('.btn-edit');
            if (!btn) return;

            openEditModal(
                btn.dataset.id,
                btn.dataset.state,
            );

        });

    });
</script>
