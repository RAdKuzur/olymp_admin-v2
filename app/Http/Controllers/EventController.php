<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Repositories\ApplicationRepository;
use App\Repositories\EventRepository;
use App\Services\ApplicationService;
use App\Services\EventService;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private RabbitMQService $rabbitMQService;
    private EventService $eventService;
    private EventRepository $eventRepository;
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    public function __construct(
        RabbitMQService $rabbitMQService,
        EventService $eventService,
        EventRepository $eventRepository,
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService
    )
    {
        $this->rabbitMQService = $rabbitMQService;
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
    }

    public function index($page = 1){
        $eventsJson = $this->eventRepository->getByApiAll($page);
        $events = $this->eventService->transform($eventsJson);
        $eventsAmount = $this->eventRepository->getCount();
        return view('event/index', compact('events', 'eventsAmount'));
    }
    public function show($id){
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        return view('event/show', compact('event'));
    }
    public function task($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        return view('event/task', compact('event'));
    }
    public function attendance($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $applicationsJson = $this->applicationRepository->getConfirmedApplications($id);
        $applications = $this->applicationService->transform($applicationsJson);
        $attendanceStatuses = AttendanceDictionary::getList();
        return view('event/attendance', compact('applications', 'event' , 'attendanceStatuses'));
    }
    public function point($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        return view('event/point', compact('event'));
    }
    public function delete($id){

    }
}
