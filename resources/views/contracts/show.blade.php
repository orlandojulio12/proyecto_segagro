{{-- resources/views/contracts/show.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Detalle del Contrato')

@section('dashboard-content')
<div class="section-header mb-4">
    <div>
        <h2 class="fw-bold">Detalle del Contrato</h2>
        <p class="text-muted">Información completa del contrato <strong>{{ $contract->contract_number }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
        <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning shadow-sm">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Estado del Contrato -->
<div class="status-banner mb-4">
    <div class="status-content">
        <div class="status-icon {{ $contract->status_badge_class }}">
            @if($contract->is_active)
                <i class="fas fa-check-circle"></i>
            @elseif($contract->is_pending)
                <i class="fas fa-clock"></i>
            @else
                <i class="fas fa-times-circle"></i>
            @endif
        </div>
        <div class="status-info">
            <h4 class="mb-1">Estado: <span class="badge {{ $contract->status_badge_class }}">{{ $contract->status }}</span></h4>
            @if($contract->is_active)
                <p class="mb-0">
                    <i class="fas fa-calendar-day"></i> Días restantes: <strong>{{ $contract->days_remaining }}</strong>
                    @if($contract->days_remaining <= 30)
                        <span class="text-warning ms-2">
                            <i class="fas fa-exclamation-triangle"></i> Próximo a vencer
                        </span>
                    @endif
                </p>
            @elseif($contract->is_pending)
                <p class="mb-0">
                    <i class="fas fa-hourglass-start"></i> El contrato iniciará el {{ $contract->start_date->format('d/m/Y') }}
                </p>
            @else
                <p class="mb-0">
                    <i class="fas fa-calendar-times"></i> Vencido desde el {{ $contract->final_end_date->format('d/m/Y') }}
                </p>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Información General -->
    <div class="col-md-6">
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-file-contract"></i> Información del Contrato
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-hashtag text-success"></i> Número de Contrato:</span>
                    <span class="detail-value">{{ $contract->contract_number }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-handshake text-success"></i> Modalidad:</span>
                    <span class="detail-value">
                        <span class="badge bg-info">
                            {{ $contract->hiringModality->modality_name ?? 'N/A' }}
                        </span>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-sitemap text-success"></i> Dependencia:</span>
                    <span class="detail-value">{{ $contract->contractType->dependencia->nombre ?? 'N/A' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-file-alt text-success"></i> Tipo de Contrato:</span>
                    <span class="detail-value">{{ $contract->contractType->type_name ?? 'N/A' }}</span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label"><i class="fas fa-align-left text-success"></i> Objeto del Contrato:</span>
                    <span class="detail-value text-justify">{{ $contract->contract_object }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Contratista -->
    <div class="col-md-6">
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-user-tie"></i> Información del Contratista
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-user text-success"></i> Nombre:</span>
                    <span class="detail-value">{{ $contract->contractor_name }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-id-card text-success"></i> NIT:</span>
                    <span class="detail-value">{{ $contract->contractor_nit }}</span>
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-map-marker-alt"></i> Ubicación
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-building text-success"></i> Centro:</span>
                    <span class="detail-value">{{ $contract->sede->centro->nom_centro ?? 'N/A' }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-map-marker-alt text-success"></i> Sede:</span>
                    <span class="detail-value">{{ $contract->sede->nom_sede ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de Fechas -->
    <div class="col-md-6">
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-calendar-alt"></i> Fechas del Contrato
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-check text-success"></i> Fecha de Inicio:</span>
                    <span class="detail-value">
                        <span class="badge bg-secondary">
                            {{ $contract->start_date->format('d/m/Y') }}
                        </span>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-times text-success"></i> Fecha de Terminación Inicial:</span>
                    <span class="detail-value">
                        <span class="badge bg-secondary">
                            {{ $contract->initial_end_date->format('d/m/Y') }}
                        </span>
                    </span>
                </div>

                @if($contract->extension_date)
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-plus text-success"></i> Fecha de Prórroga:</span>
                    <span class="detail-value">
                        <span class="badge bg-warning">
                            {{ $contract->extension_date->format('d/m/Y') }}
                        </span>
                    </span>
                </div>
                @endif

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-day text-success"></i> Fecha Final del Contrato:</span>
                    <span class="detail-value">
                        <span class="badge bg-info">
                            {{ $contract->final_end_date->format('d/m/Y') }}
                        </span>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-hourglass-half text-success"></i> Duración Total:</span>
                    <span class="detail-value">
                        {{ $contract->start_date->diffInDays($contract->final_end_date) }} días
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Financiera -->
    <div class="col-md-6">
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-dollar-sign"></i> Información Financiera
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-money-bill text-success"></i> Valor Inicial:</span>
                    <span class="detail-value text-primary fw-bold">
                        ${{ number_format($contract->initial_value, 2, ',', '.') }}
                    </span>
                </div>

                @if($contract->addition_value > 0)
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-plus-circle text-success"></i> Valor Adicional:</span>
                    <span class="detail-value text-warning fw-bold">
                        ${{ number_format($contract->addition_value, 2, ',', '.') }}
                    </span>
                </div>
                @endif

                <div class="detail-item highlight">
                    <span class="detail-label"><i class="fas fa-calculator text-success"></i> Valor Total del Contrato:</span>
                    <span class="detail-value text-success fw-bold fs-5">
                        ${{ number_format($contract->total_value, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Auditoría -->
        <div class="content-card mb-4">
            <h5 class="section-title">
                <i class="fas fa-history"></i> Auditoría
            </h5>

            <div class="detail-group">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-plus text-success"></i> Fecha de Creación:</span>
                    <span class="detail-value">{{ $contract->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-calendar-edit text-success"></i> Última Actualización:</span>
                    <span class="detail-value">{{ $contract->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones -->
{{-- <div class="actions-footer mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
        </a>
        <div class="d-flex gap-2">
          
            <button onclick="deleteContract()" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Eliminar Contrato
            </button>
        </div>
    </div>
</div> --}}
@endsection

@push('styles')
<style>
    /* Estilos específicos para contracts-show */
    .contracts-show .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contracts-show .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .contracts-show .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .contracts-show .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 19px;
        margin-top: 22px;

    }

    .contracts-show .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4cd137;
    }

    .contracts-show .section-title i {
        color: #4cd137;
        margin-right: 8px;
    }

    /* Banner de Estado */
    .contracts-show .status-banner {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #4cd137;
    }

    .contracts-show .status-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .contracts-show .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }

    .contracts-show .status-icon.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
    }

    .contracts-show .status-icon.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .contracts-show .status-icon.bg-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .contracts-show .status-info h4 {
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
    }

    .contracts-show .status-info p {
        color: #6c757d;
        font-size: 0.95rem;
            margin-top: 19px;
    }

    /* Detalles */
    .contracts-show .detail-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contracts-show .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: start;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .contracts-show .detail-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .contracts-show .detail-item.highlight {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #4cd137;
    }

    .contracts-show .detail-item.full-width {
        flex-direction: column;
        gap: 8px;
    }

    .contracts-show .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        min-width: 200px;
    }

    .contracts-show .detail-label i {
        margin-right: 5px;
    }

    .contracts-show .detail-value {
        color: #2c3e50;
        font-size: 0.95rem;
        text-align: right;
        flex: 1;
    }

    .contracts-show .text-justify {
        text-align: justify;
    }

    /* Badges */
    .contracts-show .badge {
        padding: 8px 14px;
        color: white;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .contracts-show .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .contracts-show .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .contracts-show .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    .contracts-show .badge.bg-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    }

    .contracts-show .badge.bg-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%) !important;
    }

    /* Botones */
    .contracts-show .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.3s ease;
        border: none;
        margin-right: 20px
    }

    .contracts-show .btn-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }

    .contracts-show .btn-warning:hover {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
    }

    .contracts-show .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .contracts-show .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    .contracts-show .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: white;
        margin-right: 22px
    }

    .contracts-show .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    /* Footer de Acciones */
    .contracts-show .actions-footer {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    /* Alerts */
    .contracts-show .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
    }

    .contracts-show .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #4cd137;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .contracts-show .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .contracts-show .status-content {
            flex-direction: column;
        }

        .contracts-show .detail-item {
            flex-direction: column;
            gap: 8px;
        }

        .contracts-show .detail-label {
            min-width: auto;
        }

        .contracts-show .detail-value {
            text-align: left;
        }

        .contracts-show .actions-footer .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        .contracts-show .actions-footer .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('contracts-show');

    function deleteContract() {
        if (!confirm('¿Estás seguro de eliminar este contrato?\n\nContrato: {{ $contract->contract_number }}\n\n⚠️ Esta acción no se puede deshacer.')) {
            return;
        }

        fetch('{{ route('contracts.destroy', $contract) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('contracts.index') }}';
            } else {
                alert('❌ Error al eliminar el contrato');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al eliminar el contrato');
        });
    }
</script>
@endpush