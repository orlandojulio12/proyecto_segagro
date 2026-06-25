<?php

namespace App\Exports;

use App\Models\Complaint\pqr;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PqrExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $status = null,
        private readonly ?int $dependencyId = null,
    ) {}

    public function query()
    {
        $query = Pqr::with(['user', 'concepto.dependencia'])->orderByDesc('date');

        if ($this->status) {
            $query->byStatus($this->status);
        }

        if ($this->dependencyId) {
            $query->whereHas('concepto', fn($q) => $q->where('dependencia_id', $this->dependencyId));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Título',
            'Tipo',
            'Dependencia',
            'Concepto',
            'Responsable',
            'Registrado por',
            'Fecha Registro',
            'Fecha Límite',
            'Horas Tutela',
            'Estado',
            'Tiempo Restante',
        ];
    }

    public function map($pqr): array
    {
        return [
            $pqr->title,
            $pqr->is_tutela ? 'Tutela' : 'PQR',
            $pqr->concepto?->dependencia?->name,
            $pqr->concepto?->nombre,
            $pqr->responsible,
            $pqr->user?->name,
            $pqr->date?->format('d/m/Y H:i'),
            $pqr->deadline_date?->format('d/m/Y H:i'),
            $pqr->is_tutela ? ($pqr->horas_tutela . 'h') : '-',
            $pqr->state ? 'Finalizada' : $pqr->status_text,
            $pqr->state ? '-' : $pqr->time_formatted,
        ];
    }

    public function title(): string
    {
        return 'PQR';
    }
}
