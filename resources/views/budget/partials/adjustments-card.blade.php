<div class="content-card mt-4">
    <h5 class="section-title mb-2">
        <i class="fas fa-history"></i> Historial de Ajustes del Presupuesto
    </h5>
    <p class="section-subtitle">
        A continuación se muestran todos los ajustes aplicados al presupuesto general.
    </p>

    @if ($adjustments->isEmpty())
        <div class="info-box">
            No se han realizado ajustes a este presupuesto.
        </div>
    @else
        <div class="table-container">
            <table class="table-modern adjustments-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Monto</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adjustments as $index => $adj)
                        <tr>
                            <td>{{ ($adjustments->currentPage() - 1) * $adjustments->perPage() + $index + 1 }}</td>
                            <td>
                                <span class="badge {{ $adj->amount >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ number_format($adj->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>{{ $adj->description }}</td>
                            <td>{{ $adj->user->name ?? 'Usuario Eliminado' }}</td>
                            <td>{{ $adj->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper mt-3">
            {{ $adjustments->onEachSide(1)->links('vendor.pagination.simple-green') }}
        </div>
    @endif
</div>
