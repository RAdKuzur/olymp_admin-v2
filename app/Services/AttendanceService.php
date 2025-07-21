<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use App\Repositories\UserRepository;

class AttendanceService
{
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    private UserService $userService;
    private UserRepository $userRepository;
    public function __construct(
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        UserService $userService,
        UserRepository $userRepository

    )
    {
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
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
}
