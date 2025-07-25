<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use App\Repositories\EventRepository;
use App\Repositories\TaskAttendanceRepository;
use App\Repositories\UserRepository;

class ReportService
{
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    private EventRepository $eventRepository;
    private EventService $eventService;
    private AttendanceService $attendanceService;
    private UserService $userService;
    private UserRepository $userRepository;
    private TaskAttendanceRepository $taskAttendanceRepository;

    public function __construct(
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        EventRepository $eventRepository,
        EventService $eventService,
        AttendanceService $attendanceService,
        UserRepository $userRepository,
        UserService $userService,
        TaskAttendanceRepository $taskAttendanceRepository
    )
    {
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
        $this->eventRepository = $eventRepository;
        $this->eventService = $eventService;
        $this->attendanceService = $attendanceService;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->taskAttendanceRepository = $taskAttendanceRepository;
    }
    public function prepareData($id)
    {
        $data = [];
        $eventsJson = $this->eventRepository->getByApiAll();
        $events = $this->eventService->filterBySubject($this->eventService->transform($eventsJson), $id);
        usort($events, function($a, $b) {
            return $a->class_number <=> $b->class_number; // Сортировка по возрастанию
        });
        foreach ($events as $event){
            $applicationsJson = $this->applicationRepository->getByEventId($event->id);
            $applications = $this->applicationService->transform($applicationsJson);
            $applications = $this->applicationService->confirmedApplications($applications);
            $attendances = $this->attendanceService->applicationFilter($applications);
            $participantData = [];
            foreach ($attendances as $attendance) {
                $taskAttendances = $this->taskAttendanceRepository->getByAttendancesId(array_column([$attendance], 'id'));
                $applicationJson = $this->applicationRepository->getByApiId($attendance->application_id);
                $application = $this->applicationService->transformModel($applicationJson);
                $userJson = $this->userRepository->getByApiId($application->user_id);
                $person = $this->userService->transformModel($userJson);
                $participantData[] = [
                    'attendance' => $attendance,
                    'application' => $application,
                    'person' => $person,
                    'taskAttendances' => $taskAttendances,
                ];
            }
            $data[] = [
                'event' => $event,
                'participants' => $participantData,
            ];

        }

        return $data;
    }
}
