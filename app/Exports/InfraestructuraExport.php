<?php

namespace App\Exports;

use App\Models\Infraestructura\Infraestructura;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InfraestructuraExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function query()
    {
        return Infraestructura::with(['dependencia', 'funcionario', 'centro', 'sede'])->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Dependencia',
            'Funcionario',
            'Centro',
            'Sede',
            'Tipo Necesidad',
            'Área Necesidad',
            'Nivel Riesgo',
            'Nivel Complejidad',
            'Descripción',
            'Presupuesto Solicitado',
            'Presupuesto Aceptado',
            'Estado',
            'Fecha Registro',
        ];
    }

    public function map($infra): array
    {
        return [
            $infra->id,
            $infra->dependencia?->short_name,
            $infra->funcionario?->name,
            $infra->centro?->nom_centro,
            $infra->sede?->nom_sede,
            $infra->tipo_necesidad,
            $infra->area_necesidad,
            $infra->nivel_riesgo,
            $infra->nivel_complejidad,
            $infra->descripcion,
            $infra->presupuesto_solicitado,
            $infra->presupuesto_aceptado,
            $infra->estado ?? 'Pendiente',
            $infra->created_at?->format('d/m/Y'),
        ];
    }

    public function title(): string
    {
        return 'Infraestructura';
    }
}
