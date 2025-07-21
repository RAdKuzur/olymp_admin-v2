<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Components\Dictionaries\SubjectDictionary;
use App\Repositories\ApplicationRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\EventRepository;
use App\Repositories\TaskAttendanceRepository;
use App\Repositories\TaskRepository;
use App\Services\ApplicationService;
use App\Services\AttendanceService;
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
    private AttendanceService $attendanceService;
    public function __construct(
        EventService $eventService,
        EventRepository $eventRepository,
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        AttendanceRepository $attendanceRepository,
        TaskRepository $taskRepository,
        TaskAttendanceRepository $taskAttendanceRepository,
        AttendanceService $attendanceService
    )
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
        $this->applicationRepository = $applicationRepository;
        $this->applicationService = $applicationService;
        $this->attendanceRepository = $attendanceRepository;
        $this->taskRepository = $taskRepository;
        $this->taskAttendanceRepository = $taskAttendanceRepository;
        $this->attendanceService = $attendanceService;
    }

    public function index($page = 1){
        $eventsJson = $this->eventRepository->getByApiAll($page);
        $events = $this->eventService->transform($eventsJson);
        $eventsAmount = $this->eventRepository->getCount();
        $subjects = SubjectDictionary::getList();
        return view('event/index', compact('events', 'eventsAmount', 'subjects'));
    }
    public function show($id){
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $subjects = SubjectDictionary::getList();
        return view('event/show', compact('event', 'subjects'));
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
        $applicationsJson = $this->applicationRepository->getByEventId($event->id);
        $applications = $this->applicationService->transform($applicationsJson);
        $attendanceStatuses = AttendanceDictionary::getList();
        $attendances = $this->attendanceRepository->getAll();
        $data = $this->attendanceService->createTable($attendances);
        return view('event/attendance', compact('applications', 'event' , 'attendanceStatuses', 'data'));
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

        $applicationsJson = $this->applicationRepository->getByEventId($id);
        $applications = $this->applicationService->transform($applicationsJson);
        $this->eventService->synchronize($applications, $id);
        return redirect()->route('event.attendance', ['id' => $id]);
    }
    public function addTask(Request $request, $id)
    {
        return redirect()->route('event.task', ['id' => $id]);
    }
    public function changeAttendance(Request $request){
        try {
            $attendance = $this->attendanceRepository->get($request->attendance_id);
            if ($attendance) {
                $attendance->status = $request->status;
                $attendance->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Статус успешно обновлен'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Запись посещения не найдена'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка сервера: ' . $e->getMessage()
            ], 500);
        }
    }
}
