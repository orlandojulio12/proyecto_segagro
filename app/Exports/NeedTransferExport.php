<?php

namespace App\Exports;

use App\Models\Traslado\NeedTransfer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NeedTransferExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $status = null,
    ) {}

    public function query()
    {
        $query = NeedTransfer::with([
            'user', 'centroInicial', 'sedeInicial', 'centroFinal', 'sedeFinal'
        ])->latest('fecha_inicio');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Centro Inicial',
            'Sede Inicial',
            'Centro Final',
            'Sede Final',
            'Fecha Inicio',
            'Fecha Fin',
            'Nivel Riesgo',
            'Nivel Complejidad',
            'Presupuesto Solicitado',
            'Presupuesto Aceptado',
            'Requiere Personal',
            'Requiere Materiales',
            'Estado',
            'Responsable',
            'Descripción',
        ];
    }

    public function map($traslado): array
    {
        return [
            $traslado->centroInicial?->name,
            $traslado->sedeInicial?->nom_sede,
            $traslado->centroFinal?->name,
            $traslado->sedeFinal?->nom_sede,
            $traslado->fecha_inicio?->format('d/m/Y'),
            $traslado->fecha_fin?->format('d/m/Y'),
            $traslado->nivel_riesgo,
            $traslado->nivel_complejidad,
            number_format($traslado->presupuesto_solicitado ?? 0, 2, ',', '.'),
            number_format($traslado->presupuesto_aceptado ?? 0, 2, ',', '.'),
            $traslado->requiere_personal ? 'Sí' : 'No',
            $traslado->requiere_materiales ? 'Sí' : 'No',
            ucfirst($traslado->status),
            $traslado->user?->name,
            $traslado->descripcion,
        ];
    }

    public function title(): string
    {
        return 'Traslados';
    }
}
