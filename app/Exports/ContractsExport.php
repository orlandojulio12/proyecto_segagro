<?php

namespace App\Exports;

use App\Models\Contract\Contract;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContractsExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $status = null,
        private readonly ?int $sedeId = null,
    ) {}

    public function query()
    {
        $query = Contract::with(['hiringModality', 'contractType', 'sede.centro'])->latest('start_date');

        if ($this->status === 'active') {
            $query->active();
        } elseif ($this->status === 'expired') {
            $query->expired();
        } elseif ($this->status === 'pending') {
            $query->pending();
        }

        if ($this->sedeId) {
            $query->where('sede_id', $this->sedeId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'N° Contrato',
            'Contratista',
            'NIT',
            'Objeto',
            'Modalidad',
            'Tipo',
            'Sede',
            'Centro',
            'Fecha Inicio',
            'Fecha Fin',
            'Prórroga',
            'Valor Inicial',
            'Adición',
            'Valor Total',
            'Estado',
        ];
    }

    public function map($contract): array
    {
        return [
            $contract->contract_number,
            $contract->contractor_name,
            $contract->contractor_nit,
            $contract->contract_object,
            $contract->hiringModality?->name,
            $contract->contractType?->type_name,
            $contract->sede?->nom_sede,
            $contract->sede?->centro?->name,
            $contract->start_date?->format('d/m/Y'),
            $contract->initial_end_date?->format('d/m/Y'),
            $contract->extension_date?->format('d/m/Y'),
            number_format($contract->initial_value, 2, ',', '.'),
            number_format($contract->addition_value ?? 0, 2, ',', '.'),
            number_format($contract->total_value, 2, ',', '.'),
            $contract->status,
        ];
    }

    public function title(): string
    {
        return 'Contratos';
    }
}
