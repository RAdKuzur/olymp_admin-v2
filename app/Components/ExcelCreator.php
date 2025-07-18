<?php

namespace App\Components;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelCreator
{
    public const RUSSIAN = [
        'filepath' => '',
        'lists' => [
            'auditoriumList' => [],
            'appearanceList' => [],
            'pointLists' => [],
            'ratingList' => [],
            'formApplicationList' => [],
            'formProtocolList' => [],
            'formESUList' => []
        ]
    ];
    public function createList($filepath)
    {
        $templatePath = self::RUSSIAN['filepath'];
        if (!file_exists($templatePath)) {
            throw new \Exception('Шаблонный файл не найден');
        }
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheetByName(self::RUSSIAN['lists']['auditoriumList']);
        // Заполняем данными
        $sheet->setCellValue('A1', 'Hello');
        $sheet->setCellValue('B1', 'World!');
        $sheet->setCellValue('A2', 'Тест');
        $sheet->setCellValue('B2', 'Русский текст');
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="export.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
