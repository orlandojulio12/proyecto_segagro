@foreach ($sedes as $sede)
<div class="tree-node">

    <div class="dependency-header" data-toggle="tree">
        <div class="dep-left">
            <div class="dep-icon">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <strong>{{ $sede->nom_sede }}</strong>
                <div class="text-muted small">Centro: {{ $sede->centro->nom_centro ?? '-' }}</div>
            </div>
        </div>

        <div class="badge-area-count">
            <span class="badge bg-success">{{ $sede->areas->count() }} Áreas</span>
        </div>
    </div>

    <div class="subunit-list">
        <div class="tree-line"></div>
        @forelse($sede->areas as $area)
        <div class="subunit-item">
            <div class="sub-left">
                <div class="sub-icon"><i class="fas fa-door-open"></i></div>
                <span>{{ $area->name }}</span>
                <span class="badge bg-info">{{ $area->code }}</span>
                <span class="badge bg-secondary">{{ $area->salones_count ?? 0 }} Salones</span>
            </div>
            <a href="{{ route('areas.edit', $area) }}" class="action-icon edit sm">
                <i class="fas fa-pen"></i>
            </a>
        </div>
        @empty
        <div class="empty-subunit"><i class="fas fa-info-circle me-1"></i>No hay áreas</div>
        @endforelse
    </div>
</div>
@endforeach