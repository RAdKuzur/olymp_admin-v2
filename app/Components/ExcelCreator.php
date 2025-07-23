<?php

namespace App\Components;

use App\Components\Dictionaries\SubjectDictionary;
use App\Repositories\ApplicationRepository;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\App;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelCreator
{
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    public function __construct(
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService
    )
    {
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
    }

    public static function createList($subject, $data)
    {

        $templatePath = resource_path(SubjectDictionary::SUBJECTS[$subject]['filepath']);
        if (!file_exists($templatePath)) {
            throw new \Exception('Шаблонный файл не найден');
        }
        $spreadsheet = IOFactory::load($templatePath);
        //$sheet = $spreadsheet->getSheetByName(self::RUSSIAN['lists']['auditoriumList']);
        //заполняем данными
        self::fillRegisterList($data);
        //
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
    public static function fillRegisterList($data){

    }
    public static function fillAuditoriumList($data)
    {

    }
    public static function fillAttendanceList($data){

    }
    public static function fillResultLists($data){

    }
    public static function fillRatingList($data){

    }

    public static function fillProtocolList($data){

    }
    public static function fillFacelessProtocolList($data){

    }
    public static function fillFormEsuList($data){

    }
}
