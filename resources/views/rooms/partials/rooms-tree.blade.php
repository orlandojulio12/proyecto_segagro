@forelse($areas as $area)
    <div class="tree-node">
        <div class="dependency-header" data-toggle="tree">
            <div class="dep-left">
                <div class="dep-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <strong>{{ $area->name }}</strong>
                    <div class="text-muted small">{{ $area->sede->nom_sede }}</div>
                </div>
            </div>
            @if($area->rooms->count() > 0)
                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
            @endif
        </div>

        <div class="subunit-list">
            <div class="tree-line"></div>

            @forelse($area->rooms as $room)
                <div class="subunit-item">
                    <div class="sub-left">
                        <div class="sub-icon"><i class="fas fa-door-closed"></i></div>
                        <div class="sub-info">
                            <span class="sub-name">{{ $room->name }}</span>
                            <span class="sub-capacity">Capacidad: {{ $room->capacity ?? 'N/A' }} personas</span>
                        </div>
                    </div>
                    <div class="sub-actions">
                        <a href="{{ route('rooms.edit', $room) }}" class="action-icon edit sm"><i class="fas fa-pen"></i></a>
                    </div>
                </div>
            @empty
                <div class="empty-subunit">
                    <i class="fas fa-info-circle me-1"></i>No hay salones
                </div>
            @endforelse
        </div>
    </div>
@empty
    <div class="empty-state">
        <div class="empty-card">
            <div class="empty-icon"><i class="fas fa-info-circle"></i></div>
            <h5>No hay áreas para este centro/sede</h5>
        </div>
    </div>
@endforelse