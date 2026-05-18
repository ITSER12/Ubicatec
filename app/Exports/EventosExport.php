<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class EventosExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    public function collection()
    {
        return DB::table('eventos')
            ->select('id_evento', 'titulo', 'fecha_evento', 'descripcion')
            ->orderBy('fecha_evento')
            ->get();
    }

    public function headings(): array
    {
        return ['ID Evento', 'Título', 'Fecha', 'Descripción'];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY, // columna fecha
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(50);

        // Encabezado
        $sheet->getStyle('A1:D1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'border' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Estilo filas datos
        $eventos = $this->collection();
        $ultimaFila = $eventos->count() + 1;

        if ($ultimaFila > 1) {
            $sheet->getStyle("A2:D{$ultimaFila}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'border' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            // Filas alternadas
            for ($i = 2; $i <= $ultimaFila; $i++) {
                if (($i - 2) % 2 == 0) {
                    $sheet->getStyle("A{$i}:D{$i}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('F9F9F9');
                }
            }
        }

        $sheet->freezePane('A2');

        return [];
    }
}
