<?php

namespace App\Exports;

use App\Models\Instructor\Instructor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InstructoresExport implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function query()
    {
        return Instructor::withCount('fichas')->orderBy('apellido')->orderBy('nombre');
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Documento',
            'Email',
            'Teléfono',
            'Especialidad',
            'Tipo Contrato',
            'Fichas Asignadas',
            'Estado',
        ];
    }

    public function map($instructor): array
    {
        return [
            $instructor->nombre,
            $instructor->apellido,
            $instructor->documento,
            $instructor->email,
            $instructor->telefono,
            $instructor->especialidad,
            Instructor::TIPOS_CONTRATO[$instructor->tipo_contrato] ?? $instructor->tipo_contrato,
            $instructor->fichas_count,
            $instructor->activo ? 'Activo' : 'Inactivo',
        ];
    }

    public function title(): string
    {
        return 'Instructores';
    }
}
