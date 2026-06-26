<?php

namespace App\Exports;

use App\Models\Ficha\Ficha;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FichasExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function __construct(private readonly ?string $estado = null) {}

    public function query()
    {
        $query = Ficha::with(['centro', 'sede', 'instructor'])->orderBy('numero_ficha');

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Número Ficha',
            'Programa',
            'Nivel',
            'Modalidad',
            'Estado',
            'Jornada',
            'Centro',
            'Sede',
            'Instructor',
            'Aprendices',
            'Fecha Inicio',
            'Fecha Fin',
        ];
    }

    public function map($ficha): array
    {
        return [
            $ficha->numero_ficha,
            $ficha->nombre_programa,
            Ficha::NIVELES[$ficha->nivel_formacion] ?? $ficha->nivel_formacion,
            Ficha::MODALIDADES[$ficha->modalidad] ?? $ficha->modalidad,
            Ficha::ESTADOS[$ficha->estado]['label'] ?? $ficha->estado,
            Ficha::JORNADAS[$ficha->jornada] ?? $ficha->jornada,
            $ficha->centro?->nom_centro,
            $ficha->sede?->nom_sede,
            $ficha->instructor?->nombre_completo,
            $ficha->numero_aprendices,
            $ficha->fecha_inicio?->format('d/m/Y'),
            $ficha->fecha_fin?->format('d/m/Y'),
        ];
    }

    public function title(): string
    {
        return 'Fichas';
    }
}
