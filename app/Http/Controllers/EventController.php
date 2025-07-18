<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Repositories\ApplicationRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\EventRepository;
use App\Repositories\TaskAttendanceRepository;
use App\Repositories\TaskRepository;
use App\Services\ApplicationService;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventService $eventService;
    private EventRepository $eventRepository;
    private ApplicationRepository $applicationRepository;
    private ApplicationService $applicationService;
    private AttendanceRepository $attendanceRepository;
    private TaskRepository $taskRepository;
    private TaskAttendanceRepository $taskAttendanceRepository;
    public function __construct(
        EventService $eventService,
        EventRepository $eventRepository,
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        AttendanceRepository $attendanceRepository,
        TaskRepository $taskRepository,
        TaskAttendanceRepository $taskAttendanceRepository
    )
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
        $this->attendanceRepository = $attendanceRepository;
        $this->taskRepository = $taskRepository;
        $this->taskAttendanceRepository = $taskAttendanceRepository;
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
        $tasks = $this->taskRepository->getAll();
        return view('event/task', compact('event', 'tasks'));
    }
    public function attendance($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $applicationsJson = $this->applicationRepository->getConfirmedApplications($id);
        $applications = $this->applicationService->transform($applicationsJson);
        $attendanceStatuses = AttendanceDictionary::getList();
        $attendances = $this->attendanceRepository->getAll();
        return view('event/attendance', compact('applications', 'event' , 'attendanceStatuses', 'attendances'));
    }
    public function point($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $attendances = $this->attendanceRepository->getAttendancesByEventId($id);
        $taskAttendances = $this->taskAttendanceRepository->getByEventId($id);
        $tasks = $this->taskRepository->getByEventId($id);
        return view('event/point', compact('event', 'taskAttendances', 'attendances', 'tasks'));
    }
    public function delete($id){
        return redirect()->route('event.index');
    }
    public function synchronize($id)
    {
        return redirect()->route('event.show', ['id' => $id]);
    }
    public function addTask(Request $request, $id)
    {
        return redirect()->route('event.task', ['id' => $id]);
    }
}
