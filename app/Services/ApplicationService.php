<?php

namespace App\Services;

use App\Models\Application;
use App\Repositories\EventRepository;
use App\Repositories\UserRepository;

class ApplicationService
{
    public UserService $userService;
    public EventService $eventService;
    public UserRepository $userRepository;
    public EventRepository $eventRepository;
    public function __construct(
        UserService $userService,
        EventService $eventService,
        UserRepository $userRepository,
        EventRepository $eventRepository
    )
    {
        $this->userService = $userService;
        $this->eventService = $eventService;
        $this->userRepository = $userRepository;
        $this->eventRepository = $eventRepository;
    }

    public function transform($data)
    {
        $models = [];
        foreach ($data['data']['data'] as $item) {
            $model = new Application();
            $model->id = $item['id'];
            $model->user_id = $item['userId'];
            $model->code = $item['code'];
            $model->reason = $item['reason'];
            $model->event_id = $item['eventId'];
            $model->status = $item['status'];
            $model->userAPI = $this->userService->transformModel(($this->userRepository->getByApiId($model->user_id)));
            $model->eventAPI = $this->eventService->transformModel(($this->eventRepository->getByApiId($model->event_id)));
            $models[] = $model;
        }
        return $models;
    }
    public function transformModel($data)
    {
        $item = $data['data']['data'];
        $model = new Application();
        $model->id = $item['id'];
        $model->user_id = $item['userId'];
        $model->code = $item['code'];
        $model->reason = $item['reason'];
        $model->event_id = $item['eventId'];
        $model->status = $item['status'];
        $model->userAPI = $this->userService->transformModel(($this->userRepository->getByApiId($model->user_id)));
        $model->eventAPI = $this->eventService->transformModel(($this->eventRepository->getByApiId($model->event_id)));
        return $model;
    }
}
