<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class MaterialesTemplateExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    public function array(): array
    {
        return [
            ['PLANTILLA DE IMPORTACIÓN DE MATERIALES - INVENTARIO FERRETERÍA'], // Fila 1
            ['📋 INSTRUCCIONES DE USO'], // Fila 2
            ['1. Complete los datos a partir de la fila 11'], 
            ['2. Campos obligatorios: Material/Descripción, Cantidad, Tipo de material, Precio unitario'],
            ['3. El % IVA debe ser: 0, 5, 12 o 19'],
            ['4. Tipos de material disponibles:'],
            ['   • Consumible  |  Herramienta'],
            ['5. Los totales se calcularán automáticamente al importar'],
            [''], // Fila 9
            [''], // Fila 10
            // Fila 11: Encabezados
            ['Material/Descripción', 'Cantidad', 'Tipo de material', 'Precio unitario', '% IVA', 'Observaciones'],
            // Ejemplos
            ['Cemento gris 50kg', '100', 'Consumible', '25000', '19', 'Para construcción'],
            ['Taladro percutor', '5', 'Herramienta', '350000', '19', 'Con estuche'],
            ['Pintura vinilo blanca', '50', 'Consumible', '45000', '19', ''],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo SEGAGRO');
        // Ajusta la ruta según tu proyecto
        $logoPath = public_path('img/logo.png');
        
        // Verificar si existe el logo, si no, no lo agregamos
        if (file_exists($logoPath)) {
            $drawing->setPath($logoPath);
            $drawing->setHeight(80);
            $drawing->setCoordinates('G2');
            return $drawing;
        }
        
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Título (Fila 1)
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4cd137']], // Verde
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Instrucciones título (Fila 2)
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F8E9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Contenido instrucciones (Filas 3-8)
        for ($i = 3; $i <= 8; $i++) {
            $sheet->mergeCells("A{$i}:F{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FFF9']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Filas 9-10 (separadores con color instrucciones)
        for ($i = 9; $i <= 10; $i++) {
            $sheet->mergeCells("A{$i}:F{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FFF9']],
            ]);
        }

        // Encabezados (Fila 11)
        $sheet->getStyle('A11:F11')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4cd137']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getRowDimension(11)->setRowHeight(30);

        // Ejemplos y bordes (desde fila 12 hasta 200)
        $sheet->getStyle('A12:F200')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Lista desplegable: % IVA (columna E, desde fila 12)
        for ($row = 12; $row <= 200; $row++) {
            $validation = $sheet->getCell("E{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo puede seleccionar 0, 5, 12 o 19 de la lista');
            $validation->setShowDropDown(true);
            $validation->setFormula1('"0,5,12,19"');
        }

        // Lista desplegable: Tipo de material (columna C)
        for ($row = 12; $row <= 200; $row++) {
            $validation = $sheet->getCell("C{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Seleccione Consumible o Herramienta de la lista');
            $validation->setShowDropDown(true);
            $validation->setFormula1('"Consumible,Herramienta"');
        }

        // Anchos de columnas
        $sheet->getColumnDimension('A')->setWidth(30); // Material/Descripción
        $sheet->getColumnDimension('B')->setWidth(12); // Cantidad
        $sheet->getColumnDimension('C')->setWidth(18); // Tipo de material
        $sheet->getColumnDimension('D')->setWidth(16); // Precio unitario
        $sheet->getColumnDimension('E')->setWidth(10); // % IVA
        $sheet->getColumnDimension('F')->setWidth(30); // Observaciones

        return [];
    }

    public function title(): string
    {
        return 'Materiales';
    }
}