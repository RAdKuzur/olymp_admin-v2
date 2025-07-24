<?php

namespace App\Components;

use App\Components\Dictionaries\SubjectDictionary;
use App\Repositories\ApplicationRepository;
use App\Services\ApplicationService;
use DateTime;
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
        $config = SubjectDictionary::SUBJECTS[$subject];
        $templatePath = resource_path($config['filepath']);
        if (!file_exists($templatePath)) {
            throw new \Exception('Шаблонный файл не найден');
        }
        $spreadsheet = IOFactory::load($templatePath);
        //заполняем данными
        self::fillRegisterList($data, $spreadsheet, $config);
        self::fillAuditoriumList($data, $spreadsheet, $config);
        self::fillAttendanceList($data, $spreadsheet, $config);
        //
        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $config['name'] . '_' . date('d-m-Y') . '.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
    public static function fillRegisterList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['registerList']['name']);

        foreach ($data as $event) {
            $column = Coordinate::columnIndexFromString($config['registerList']['startCell'][0]);
            foreach($event['participants'] as $index => $participant){
                $sheet->setCellValueByColumnAndRow($column, $index + $config['registerList']['startCell'][1], $participant['person']->surname);
                $sheet->setCellValueByColumnAndRow($column + 1, $index + $config['registerList']['startCell'][1], $participant['person']->firstname);
                $sheet->setCellValueByColumnAndRow($column + 2, $index + $config['registerList']['startCell'][1], $participant['person']->patronymic);
                $sheet->setCellValueByColumnAndRow($column + 3, $index + $config['registerList']['startCell'][1], $participant['person']->birthdate);
                $sheet->setCellValueByColumnAndRow($column + 4, $index + $config['registerList']['startCell'][1], $event['event']->class_number);
                $sheet->setCellValueByColumnAndRow($column + 5, $index + $config['registerList']['startCell'][1],$participant['person']->participantAPI->schoolAPI->name);
            }
        }
    }
    public static function fillAuditoriumList($data, $spreadsheet, $config)
    {
        $sheet = $spreadsheet->getSheetByName($config['auditoriumList']['name']);
        foreach ($data as $event) {
            $column = Coordinate::columnIndexFromString($config['auditoriumList']['startCell'][0]);
            foreach($event['participants'] as $index => $participant){
                $sheet->setCellValueByColumnAndRow($column + 1, $index + $config['auditoriumList']['startCell'][1], $participant['person']->surname);
                $sheet->setCellValueByColumnAndRow($column + 2, $index + $config['auditoriumList']['startCell'][1], $participant['person']->firstname);
                $sheet->setCellValueByColumnAndRow($column + 3, $index + $config['auditoriumList']['startCell'][1], $participant['person']->patronymic);
                $sheet->setCellValueByColumnAndRow($column + 4, $index + $config['auditoriumList']['startCell'][1], $participant['person']->birthdate);
                $sheet->setCellValueByColumnAndRow($column + 5, $index + $config['auditoriumList']['startCell'][1], $event['event']->class_number);
            }
        }
    }
    public static function fillAttendanceList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['attendanceList']['name']);
        foreach ($data as $event) {
            $column = Coordinate::columnIndexFromString($config['attendanceList']['startCell'][0]);
            foreach($event['participants'] as $index => $participant){
                $sheet->setCellValueByColumnAndRow($column, $index + $config['attendanceList']['startCell'][1], $participant['person']->surname);
                $sheet->setCellValueByColumnAndRow($column + 1, $index + $config['attendanceList']['startCell'][1], $participant['person']->firstname);
                $sheet->setCellValueByColumnAndRow($column + 2, $index + $config['attendanceList']['startCell'][1], $participant['person']->patronymic);
                $sheet->setCellValueByColumnAndRow($column + 3, $index + $config['attendanceList']['startCell'][1], $participant['person']->birthdate);
                $sheet->setCellValueByColumnAndRow($column + 4, $index + $config['attendanceList']['startCell'][1], $event['event']->class_number);
                $sheet->setCellValueByColumnAndRow($column + 5, $index + $config['attendanceList']['startCell'][1], $participant['application']->code);
                $sheet->setCellValueByColumnAndRow($column + 6, $index + $config['attendanceList']['startCell'][1], $participant['attendance']->status - 1 );
            }
        }
    }
    public static function fillResultLists($data, $spreadsheet, $config){

    }
    public static function fillRatingList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['ratingList']['name']);

    }

    public static function fillProtocolList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['formApplicationList']['name']);

    }
    public static function fillFacelessProtocolList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['formProtocolList']['name']);

    }
    public static function fillFormEsuList($data, $spreadsheet, $config){
        $sheet = $spreadsheet->getSheetByName($config['formESUList']['name']);

    }
}
