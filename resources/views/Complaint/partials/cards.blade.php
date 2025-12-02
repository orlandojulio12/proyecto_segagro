{{-- resources/views/Complaint/partials/cards.blade.php --}}

@if($pqr->count() > 0)
    @foreach ($pqr as $item)
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-tags">
                    <span class="tag tag--frequency">PQR</span>
                    <span class="tag tag--type">{{ $item->dependency }}</span>
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
                <button onclick="confirmDelete({{ $item->id }})" class="btn-action btn-action-delete" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="card-footer custom-footer">
                <div class="footer-left">
                    <div class="avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item->responsible) }}&background=6366f1&color=fff&bold=true">
                    </div>
                    <div class="responsible-name">
                        {{ $item->responsible }}
                    </div>
                </div>

                <div class="footer-right">
                    <div class="dias-text">
                        @if ($item->days_remaining > 0)
                            {{ intval($item->days_remaining) }} día{{ intval($item->days_remaining) != 1 ? 's' : '' }}
                            restante{{ intval($item->days_remaining) != 1 ? 's' : '' }}
                        @else
                            Vencido
                        @endif
                    </div>
                    <div class="state-dot" style="background: {{ $item->color_status }};"></div>
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
    <div class="no-results">
        <i class="fas fa-search"></i>
        <h3>No se encontraron resultados</h3>
        <p>No hay PQR que coincidan con los filtros seleccionados.<br>Intenta ajustar tus criterios de búsqueda.</p>
        <a href="{{ route('pqr.index') }}" class="btn" onclick="clearFilters(); return false;">
            <i class="fas fa-redo"></i> Limpiar filtros
        </a>
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
</script>