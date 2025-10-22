{{-- resources/views/salida_ferreteria/show.blade.php --}}
@extends('layouts.dashboard')

@section('page-title', 'Detalle de Salida')

@section('dashboard-content')
    <div class="section-header mb-4">
        <div>
            <h2 class="fw-bold">Detalle de Salida #{{ $salidaFerreteria->id }}</h2>
            <p class="text-muted">Información completa de la salida de materiales</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('salida_ferreteria.index') }}" class="btn btn-outline-secondary shadow-sm me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
            <a href="{{ route('salida_ferreteria.edit', $salidaFerreteria) }}" class="btn btn-warning shadow-sm me-2">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <button onclick="deleteSalida({{ $salidaFerreteria->id }})" class="btn btn-danger shadow-sm">
                <i class="fas fa-trash me-2"></i>Eliminar
            </button>
        </div>
    </div>

    <div class="row">
        {{-- Información General --}}
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>Información General
                </h5>
                
                <div class="info-group">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-hashtag me-2"></i>ID de Salida
                        </span>
                        <span class="info-value">
                            <span class="badge bg-primary">#{{ $salidaFerreteria->id }}</span>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user me-2"></i>Funcionario Responsable
                        </span>
                        <span class="info-value">{{ $salidaFerreteria->user->name ?? 'N/A' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-building me-2"></i>Centro de Formación
                        </span>
                        <span class="info-value">{{ $salidaFerreteria->centro->nom_centro ?? 'N/A' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Sede
                        </span>
                        <span class="info-value">{{ $salidaFerreteria->sede->nom_sede ?? 'N/A' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar me-2"></i>Fecha de Salida
                        </span>
                        <span class="info-value">
                            <span class="badge bg-info">
                                {{ $salidaFerreteria->fecha_salida->format('d/m/Y') }}
                            </span>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-file-alt me-2"></i>Formato F14
                        </span>
                        <span class="info-value">
                            @if($salidaFerreteria->f14)
                                <span class="badge bg-warning">{{ $salidaFerreteria->f14 }}</span>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Observaciones y Resumen --}}
        <div class="col-md-6">
            <div class="content-card mb-4">
                <h5 class="section-title">
                    <i class="fas fa-comment me-2"></i>Observaciones y Resumen
                </h5>

                <div class="info-group">
                    <div class="info-item-vertical">
                        <span class="info-label">
                            <i class="fas fa-comment-dots me-2"></i>Observaciones Generales
                        </span>
                        <div class="info-value-box">
                            @if($salidaFerreteria->observaciones)
                                {{ $salidaFerreteria->observaciones }}
                            @else
                                <span class="text-muted">Sin observaciones</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item mt-3">
                        <span class="info-label">
                            <i class="fas fa-boxes me-2"></i>Total de Materiales
                        </span>
                        <span class="info-value">
                            <span class="badge bg-success">{{ $salidaFerreteria->detalles->count() }} items</span>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-clock me-2"></i>Fecha de Registro
                        </span>
                        <span class="info-value text-muted">
                            {{ $salidaFerreteria->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Materiales de la Salida --}}
    <div class="content-card mb-4">
        <h5 class="section-title mb-3">
            <i class="fas fa-boxes me-2"></i>Materiales de la Salida
        </h5>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID</th>
                        <th><i class="fas fa-box me-1"></i>Material</th>
                        <th><i class="fas fa-tag me-1"></i>Tipo</th>
                        <th><i class="fas fa-arrow-right me-1"></i>Cantidad</th>
                        <th><i class="fas fa-comment me-1"></i>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salidaFerreteria->detalles as $detalle)
                    <tr>
                        <td><strong>#{{ $detalle->id }}</strong></td>
                        <td class="text-start">
                            <strong>{{ $detalle->material->material_name ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $detalle->material->material_type ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ number_format($detalle->cantidad, 2) }}
                            </span>
                        </td>
                        <td class="text-start">
                            @if($detalle->observacion)
                                {{ $detalle->observacion }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No hay materiales registrados en esta salida
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Acciones Footer --}}
    <div class="actions-footer">
        <a href="{{ route('salida_ferreteria.index') }}" class="btn btn-light btn-lg shadow-sm me-2">
            <i class="fas fa-arrow-left me-2"></i>Volver al Listado
        </a>
        <a href="{{ route('salida_ferreteria.edit', $salidaFerreteria) }}" class="btn btn-warning btn-lg shadow-sm me-2">
            <i class="fas fa-edit me-2"></i>Editar Salida
        </a>
        <button onclick="deleteSalida({{ $salidaFerreteria->id }})" class="btn btn-danger btn-lg shadow-sm">
            <i class="fas fa-trash me-2"></i>Eliminar Salida
        </button>
    </div>
@endsection

@push('styles')
<style>
    /* Estilos específicos para salida_ferreteria show - No afectan al layout */
    .salida-ferreteria-show .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .salida-ferreteria-show .section-header h2 {
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .salida-ferreteria-show .section-header p {
        color: #6c757d;
        margin: 5px 0 0 0;
    }

    .salida-ferreteria-show .header-actions {
        display: flex;
        gap: 0;
    }

    .salida-ferreteria-show .content-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .salida-ferreteria-show .section-title {
        font-size: 1.1rem;
        color: #4cd137;
        margin-bottom: 20px;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    /* Info Groups */
    .salida-ferreteria-show .info-group {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .salida-ferreteria-show .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .salida-ferreteria-show .info-item:hover {
        background: #f8fff9;
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.1);
    }

    .salida-ferreteria-show .info-item-vertical {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .salida-ferreteria-show .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .salida-ferreteria-show .info-value {
        font-weight: 500;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .salida-ferreteria-show .info-value-box {
        padding: 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        min-height: 80px;
        font-size: 0.9rem;
        color: #495057;
        line-height: 1.6;
    }

    /* Tabla moderna */
    .salida-ferreteria-show .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 0;
    }

    .salida-ferreteria-show .table-modern thead {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%);
        color: #fff;
    }

    .salida-ferreteria-show .table-modern thead th {
        padding: 16px 12px;
        font-size: 13px;
        text-align: center;
        font-weight: 600;
        border: none;
        white-space: nowrap;
    }

    .salida-ferreteria-show .table-modern tbody tr {
        background: #fff;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .salida-ferreteria-show .table-modern tbody tr:hover {
        background: #f8fff9;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(76, 209, 55, 0.15);
    }

    .salida-ferreteria-show .table-modern tbody td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    /* Botones */
    .salida-ferreteria-show .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .salida-ferreteria-show .btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }

    .salida-ferreteria-show .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: white;
    }

    .salida-ferreteria-show .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .salida-ferreteria-show .btn-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }

    .salida-ferreteria-show .btn-warning:hover {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
    }

    .salida-ferreteria-show .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    }

    .salida-ferreteria-show .btn-danger:hover {
        background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
    }

    .salida-ferreteria-show .btn-light {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }

    .salida-ferreteria-show .btn-light:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-2px);
    }

    /* Badges */
    .salida-ferreteria-show .badge {
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .salida-ferreteria-show .badge.bg-primary {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .salida-ferreteria-show .badge.bg-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    }

    .salida-ferreteria-show .badge.bg-success {
        background: linear-gradient(135deg, #4cd137 0%, #3db32a 100%) !important;
    }

    .salida-ferreteria-show .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    }

    /* Actions Footer */
    .salida-ferreteria-show .actions-footer {
        margin-top: 30px;
        padding: 25px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .salida-ferreteria-show .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .salida-ferreteria-show .header-actions {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }

        .salida-ferreteria-show .header-actions .btn {
            width: 100%;
            margin: 0 !important;
        }

        .salida-ferreteria-show .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .salida-ferreteria-show .actions-footer {
            flex-direction: column;
            gap: 10px;
        }

        .salida-ferreteria-show .actions-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Agregar clase al body para scope de estilos
    document.body.classList.add('salida-ferreteria-show');

    function deleteSalida(id) {
        if (!confirm('¿Estás seguro de eliminar esta salida?\n\n⚠️ Las cantidades se devolverán al inventario.')) {
            return;
        }

        fetch(`/salida-ferreteria/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                window.location.href = '{{ route("salida_ferreteria.index") }}';
            } else {
                alert('❌ Error al eliminar la salida');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error al eliminar la salida');
        });
    }

    // Confirmación antes de salir si hay cambios sin guardar
    window.addEventListener('beforeunload', function(e) {
        // Solo si estás en modo edición
        return;
    });
</script>
@endpush