<?php

namespace App\Services;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Repositories\ApplicationRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\TaskAttendanceRepository;
use App\Repositories\UserRepository;

class AttendanceService
{
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    private UserService $userService;
    private UserRepository $userRepository;
    private AttendanceRepository $attendanceRepository;
    private TaskAttendanceRepository $taskAttendanceRepository;
    public function __construct(
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        UserService $userService,
        UserRepository $userRepository,
        AttendanceRepository $attendanceRepository,
        TaskAttendanceRepository $taskAttendanceRepository

    )
    {
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->taskAttendanceRepository = $taskAttendanceRepository;
    }

    public function createTable($attendances)
    {
        $data = [];
        foreach ($attendances as $attendance) {
            $applicationJson = $this->applicationRepository->getByApiId($attendance->application_id);
            $application = $this->applicationService->transformModel($applicationJson);
            $userJson = $this->userRepository->getByApiId($application->user_id);
            $person = $this->userService->transformModel($userJson);
            $data[] = [
                'attendance' => $attendance,
                'person' => $person
            ];
        }
        return $data;
    }
    public function createExtraTable($attendances)
    {
        $data = [];
        foreach ($attendances as $attendance) {
            $taskAttendances = $this->taskAttendanceRepository->getByAttendancesId(array_column([$attendance], 'id'));
            $applicationJson = $this->applicationRepository->getByApiId($attendance->application_id);
            $application = $this->applicationService->transformModel($applicationJson);
            $userJson = $this->userRepository->getByApiId($application->user_id);
            $person = $this->userService->transformModel($userJson);
            $data[] = [
                'attendance' => $attendance,
                'application' => $application,
                'person' => $person,
                'taskAttendances' => $taskAttendances,
            ];
        }
        return $data;
    }
    public function applicationFilter($applications){
        $data = $this->attendanceRepository->getByApplicationsId(array_column($applications,'id'));
        return $data;
    }
    public function attendanceFilter($attendances)
    {
        return $attendances->filter(function ($attendance) {
            return $attendance->status == AttendanceDictionary::ATTENDANCE;
        });
    }

    public function delete()
    {

    }
}
