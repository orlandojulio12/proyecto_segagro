@extends('layouts.dashboard')

@section('page-title', 'Notificaciones')

@section('dashboard-content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Notificaciones</h4>
    @if(auth()->user()->notifications->isNotEmpty())
    <form action="{{ route('notifications.markAllRead') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-check-double me-1"></i> Marcar todas como leídas
        </button>
    </form>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifications as $notification)
            @php $data = $notification->data; @endphp
            <div class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3
                {{ is_null($notification->read_at) ? 'bg-light' : '' }}">

                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                    style="width:42px;height:42px;background:{{ $data['color'] ?? '#6c757d' }}">
                    <i class="fas fa-bell"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $data['tipo'] ?? 'PQR' }}: {{ $data['title'] ?? '' }}</strong>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1 text-muted small">{{ $data['mensaje'] ?? '' }}</p>
                    <span class="badge bg-secondary small">Vence: {{ $data['deadline'] ?? '' }}</span>
                    @if(is_null($notification->read_at))
                        <span class="badge bg-primary ms-1 small">Nueva</span>
                    @endif
                </div>

                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="flex-shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            </div>
        @empty
            <div class="list-group-item text-center text-muted py-5">
                <i class="fas fa-bell-slash fa-2x mb-2 d-block opacity-50"></i>
                No tienes notificaciones
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="card-footer">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection
