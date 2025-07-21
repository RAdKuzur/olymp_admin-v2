<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Event;
use App\Repositories\AttendanceRepository;
use DateTime;

class EventService
{
    public AttendanceRepository $attendanceRepository;
    public function __construct(
        AttendanceRepository $attendanceRepository
    )
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function transform($data)
    {
        $models = [];
        foreach ($data['data']['data']['events'] as $item) {
            $event = new Event();
            $event->id = $item['id'];
            $event->name = $item['name'];
            $date = DateTime::createFromFormat(DateTime::ATOM,  $item['start_date']);
            $event->start_date  = $date->format('Y-m-d');
            $date = DateTime::createFromFormat(DateTime::ATOM, $item['end_date']);
            $event->end_date = $date->format('Y-m-d');
            $event->event_type = $item['event_type'];
            $event->class_number = $item['class_number'];
            $event->previous_event_id = $item['previous_event_id'];
            $event->subject = $item['subject'];
            $event->additional_info = $item['additional_info'];
            $event->events = $item['events'];

            $models[] = $event;
        }
        return $models;
    }
    public function transformModel($data)
    {
        $item = $data['data']['data'];
        $event = new Event();
        $event->id = $item['id'];
        $event->name = $item['name'];
        $date = DateTime::createFromFormat(DateTime::ATOM,  $item['start_date']);
        $event->start_date  = $date->format('Y-m-d');
        $date = DateTime::createFromFormat(DateTime::ATOM, $item['end_date']);
        $event->end_date = $date->format('Y-m-d');
        $event->event_type = $item['event_type'];
        $event->class_number = $item['class_number'];
        $event->previous_event_id = $item['previous_event_id'];
        $event->subject = $item['subject'];
        $event->additional_info = $item['additional_info'];
        $event->events = $item['events'];
        return $event;
    }
    public function synchronize($applications, $eventId){
        $currentAttendances = $this->attendanceRepository->getAttendancesByEventId($eventId);
        foreach ($applications as $application){
            //создание явок
            if (count($this->attendanceRepository->getByApplicationId($application->id)) == 0){
                $this->attendanceRepository->create($application->id);
            }
        }
        foreach ($currentAttendances as $attendance){
            if(!array_key_exists($attendance->application_id, $applications)){
                $this->attendanceRepository->delete($attendance);
            }
        }


    }


}
