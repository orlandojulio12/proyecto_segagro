@php
    $colorMap = [
        '#22c55e' => 'green', '#16a34a' => 'green',
        '#4cd137' => 'green', '#3db32a' => 'green',
        '#f59e0b' => 'yellow','#f97316' => 'yellow',
        '#ef4444' => 'red',   '#dc2626' => 'red',
        '#6b7280' => 'gray',
    ];
@endphp

<div class="sg-table-wrapper">
    <table class="sg-table" id="pqr-table">
        <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> ID</th>
                <th><i class="fas fa-tag"></i> Tipo</th>
                <th><i class="fas fa-sitemap"></i> Dependencia</th>
                <th><i class="fas fa-folder"></i> Concepto</th>
                <th><i class="fas fa-heading"></i> Título</th>
                <th><i class="fas fa-user"></i> Responsable</th>
                <th><i class="fas fa-calendar"></i> Fecha</th>
                <th><i class="fas fa-circle"></i> Estado</th>
                <th style="max-width:220px"><i class="fas fa-align-left"></i> Descripción</th>
                <th><i class="fas fa-clock"></i> Tiempo</th>
                <th><i class="fas fa-cogs"></i> Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pqr as $item)
                @php $c = $colorMap[$item->color_status] ?? 'gray'; @endphp
                <tr data-id="{{ $item->id }}">
                    <td><span class="sg-badge sg-badge-gray">#{{ $item->id }}</span></td>
                    <td>
                        <span class="sg-badge {{ $item->is_tutela ? 'sg-badge-red' : 'sg-badge-blue' }}">
                            {{ $item->is_tutela ? 'Tutela' : 'PQR' }}
                        </span>
                    </td>
                    <td>{{ optional(optional($item->concepto)->dependencia)->name ?? '—' }}</td>
                    <td>{{ optional($item->concepto)->name ?? '—' }}</td>
                    <td style="font-weight:600;color:#111827;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $item->title }}">
                        {{ $item->title }}
                    </td>
                    <td>{{ $item->responsible }}</td>
                    <td>
                        <span class="sg-badge sg-badge-gray">{{ $item->date->format('d M Y') }}</span>
                    </td>
                    <td>
                        @if($item->state == 1)
                            <span class="sg-badge sg-badge-green">Terminada</span>
                        @else
                            <span class="sg-badge sg-badge-{{ $c }}">{{ $item->status_text }}</span>
                        @endif
                    </td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#6b7280;font-size:13px;" title="{{ $item->description }}">
                        {{ $item->description }}
                    </td>
                    <td>
                        @if($item->state == 1)
                            <span class="sg-badge sg-badge-green"><i class="fas fa-check"></i> Finalizada</span>
                        @elseif($item->is_tutela)
                            <span class="sg-badge sg-badge-{{ $c }}">
                                <i class="fas fa-clock"></i> {{ intval($item->days_remaining) }}h
                            </span>
                        @else
                            <span class="sg-badge sg-badge-{{ $c }}">
                                <i class="fas fa-calendar-alt"></i> {{ intval($item->days_remaining) }}d
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;">
                            <a href="{{ route('pqr.edit', $item->id) }}" class="sg-btn sg-btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="toggleState({{ $item->id }})" class="sg-btn sg-btn-info" title="Cambiar estado">
                                @if($item->state == 1)
                                    <i class="fas fa-undo"></i>
                                @else
                                    <i class="fas fa-check"></i>
                                @endif
                            </button>
                            <button onclick="confirmDelete({{ $item->id }})" class="sg-btn sg-btn-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="padding:48px 20px;text-align:center;border:none;">
                        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#16a34a,#22c55e);
                                    display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="fas fa-comment-slash" style="font-size:24px;color:white;"></i>
                        </div>
                        <div style="font-size:16px;font-weight:700;color:#374151;margin-bottom:4px;">Sin PQR registradas</div>
                        <div style="font-size:13px;color:#9ca3af;">No hay registros que coincidan con los filtros actuales</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
@if($pqr->lastPage() > 1)
<div style="display:flex;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap;">
    @for($i = 1; $i <= $pqr->lastPage(); $i++)
        <button onclick="goToPage({{ $i }})"
            style="min-width:36px;height:36px;padding:0 10px;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;transition:all .2s;
                   border:1.5px solid {{ $pqr->currentPage() == $i ? '#16a34a' : '#e5e7eb' }};
                   background:{{ $pqr->currentPage() == $i ? 'linear-gradient(135deg,#16a34a,#22c55e)' : 'white' }};
                   color:{{ $pqr->currentPage() == $i ? 'white' : '#374151' }};">
            {{ $i }}
        </button>
    @endfor
</div>
@endif
