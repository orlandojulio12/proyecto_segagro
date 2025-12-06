{{-- resources/views/Complaint/partials/cards.blade.php --}}

@if ($pqr->count() > 0)
    @foreach ($pqr as $item)
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-tags">
                    <span class="tag tag--frequency">
                        {{ $item->subunit->subunit_code ?? 'Sin código' }}
                    </span>
                    <span class="tag tag--type">
                        {{ $item->subunit->name ?? 'Sin dependencia' }}
                    </span>
                </div>

                <div class="title">{{ $item->title }}</div>

                <div class="excerpt">
                    {!! nl2br(e(Str::limit($item->description, 160))) !!}
                </div>
            </div>

            <!-- Fecha de creación -->
            <div class="card-datetime">
                {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
            </div>

            <!-- Botones de acción -->
            <div class="card-actions">
                <a href="{{ route('pqr.edit', $item->id) }}" class="btn-action btn-action-edit" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="confirmDelete({{ $item->id }})" class="btn-action btn-action-delete"
                    title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
                <button onclick="toggleState({{ $item->id }})" class="btn-action btn-action-complete"
                    title="{{ $item->state ? 'Marcar como pendiente' : 'Marcar como completada' }}">
                    <i class="fas {{ $item->state ? 'fa-undo' : 'fa-check' }}"></i>
                </button>

            </div>

            <div class="card-footer custom-footer">
                <div class="footer-left">
                    <div class="avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->responsible) }}&background=6366f1&color=fff&bold=true">
                    </div>
                    <!-- Días restantes con color de estado -->
                    <div class="dias-text" style="background: {{ $item->color_status }}; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600; display: inline-block;">
                        @if ($item->days_remaining > 0)
                            {{ intval($item->days_remaining) }} día{{ intval($item->days_remaining) != 1 ? 's' : '' }} restante{{ intval($item->days_remaining) != 1 ? 's' : '' }}
                        @else
                            Vencido
                        @endif
                    </div>
                </div>
            
                <div class="footer-right">
                    <!-- Cartel llamativo del estado -->
                    <div class="status-badge mt-2" data-status="{{ $item->state_text }}">
                        {{ $item->state_text }}
                    </div>
                </div>
            </div>            
        </div>
    @endforeach

    <!-- Formulario oculto para eliminar -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
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
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.dias-text:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    function clearFilters() {
        document.getElementById('filterDependency').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('orderColor').value = '';
        applyFilters();
    }

    function confirmDelete(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta PQR? Esta acción no se puede deshacer.')) {
            const form = document.getElementById('deleteForm');
            form.action = '/pqr/eliminar/' + id;
            form.submit();
        }
    }

    function toggleState(id) {
        fetch('/pqr/' + id + '/toggle-state', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => location.reload())
            .catch(err => alert('Error al cambiar estado'));
    }
</script>
