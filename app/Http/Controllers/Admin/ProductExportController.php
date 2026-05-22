<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Genera plantilla Excel vacía y exporta el catálogo actual a Excel.
 *
 * Plantilla → /admin/products/import-template
 * Export    → /admin/products/export
 */
class ProductExportController extends Controller
{
    /** Columnas con orden y ancho */
    private const COLS = [
        'Referencia'        => 14,
        'Nombre'            => 50,
        'Categoría'         => 28,
        'Descripción'       => 60,
        'PV Centro de Exp'  => 18,
        'Venta'             => 14,
        'Stock'             => 10,
        'Activo'            => 10,
    ];

    /**
     * Descarga plantilla vacía con 3 filas de ejemplo.
     */
    public function template(): StreamedResponse
    {
        $sheet = $this->newSheetWithHeader();

        // Filas de ejemplo
        $examples = [
            ['EJ-001', 'Sérum facial vitamina C 30 ml',  'Skincare',   'Sérum iluminador con vitamina C estabilizada y rosa mosqueta.', 720.00, 580.00, 100, 'Sí'],
            ['EJ-002', 'Crema hidratante karité 50 ml',  'Skincare',   'Crema 24h con manteca de karité y ácido hialurónico.',           520.00, 420.00, 100, 'Sí'],
            ['EJ-003', 'Set Ritual Esencial',            'Sets',       'Set de 3 piezas: tónico + crema + sérum, en estuche regalo.',   1380.00, 1200.00, 50, 'Sí'],
        ];

        $row = 2;
        foreach ($examples as $r) {
            $col = 'A';
            foreach ($r as $val) {
                $sheet->setCellValue($col.$row, $val);
                $col++;
            }
            $row++;
        }

        return $this->stream($sheet, 'plantilla_productos_belleza_aurea.xlsx');
    }

    /**
     * Exporta todos los productos actuales.
     */
    public function export(): StreamedResponse
    {
        $sheet = $this->newSheetWithHeader();

        $products = Product::with('category')->orderBy('sort_order')->get();

        $row = 2;
        foreach ($products as $p) {
            $sheet->setCellValueExplicit('A'.$row, (string) $p->internal_code, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B'.$row, $p->name);
            $sheet->setCellValue('C'.$row, $p->category?->name ?? '');
            $sheet->setCellValue('D'.$row, strip_tags($p->description ?? ''));
            $sheet->setCellValue('E'.$row, (float) ($p->compare_price ?? 0));
            $sheet->setCellValue('F'.$row, (float) $p->price);
            $sheet->setCellValue('G'.$row, (int) $p->stock);
            $sheet->setCellValue('H'.$row, $p->is_active ? 'Sí' : 'No');
            $row++;
        }

        // Formato moneda en columnas de precios
        if ($row > 2) {
            $sheet->getStyle('E2:F'.($row - 1))->getNumberFormat()
                ->setFormatCode('"$"#,##0.00');
        }

        $filename = 'productos_belleza_aurea_'.now()->format('Y-m-d_His').'.xlsx';

        return $this->stream($sheet, $filename);
    }

    /**
     * Crea spreadsheet con header estilizado.
     */
    private function newSheetWithHeader()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos');

        $col = 'A';
        foreach (self::COLS as $name => $width) {
            $sheet->setCellValue($col.'1', $name);
            $sheet->getColumnDimension($col)->setWidth($width);
            $col++;
        }

        // Estilo header
        $lastCol = chr(ord('A') + count(self::COLS) - 1);
        $sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '2E2A26'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FBF4E6']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D9B56D']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->freezePane('A2');

        return $sheet;
    }

    /**
     * Stream XLSX como respuesta de descarga.
     */
    private function stream($sheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($sheet) {
            $writer = new Xlsx($sheet->getParent());
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
