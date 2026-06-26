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
                    <div class="sub-actions d-flex gap-1">
                        <button type="button" class="action-icon edit sm"
                            data-room-id="{{ $room->id }}"
                            data-room-name="{{ $room->name }}"
                            data-room-code="{{ $room->code ?? '' }}"
                            data-room-capacity="{{ $room->capacity ?? '' }}"
                            data-room-type="{{ $room->type ?? 'classroom' }}"
                            data-room-area="{{ $room->area_id }}"
                            data-room-active="{{ $room->active ? '1' : '0' }}"
                            onclick="openRoomEditDrawer(this)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button type="button" class="action-icon sm"
                            style="background:#fee2e2;color:#dc2626;border:none;border-radius:8px;padding:6px 10px;"
                            onclick="deleteRoom({{ $room->id }}, '{{ addslashes($room->name) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
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