<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\AttendanceDictionary;
use App\Components\Dictionaries\SubjectDictionary;
use App\Http\Requests\TaskRequest;
use App\Repositories\ApplicationRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\EventRepository;
use App\Repositories\TaskAttendanceRepository;
use App\Repositories\TaskRepository;
use App\Services\ApplicationService;
use App\Services\AttendanceService;
use App\Services\EventService;
use App\Services\TaskService;
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
    private TaskService $taskService;
    public function __construct(
        EventService $eventService,
        EventRepository $eventRepository,
        ApplicationRepository $applicationRepository,
        ApplicationService $applicationService,
        AttendanceRepository $attendanceRepository,
        TaskRepository $taskRepository,
        TaskAttendanceRepository $taskAttendanceRepository,
        AttendanceService $attendanceService,
        TaskService $taskService
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
        $this->taskService = $taskService;
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
        $tasks = $this->taskRepository->getByEventId($id);
        return view('event/task', compact('event', 'tasks'));
    }
    public function attendance($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $applicationsJson = $this->applicationRepository->getByEventId($event->id);
        $applications = $this->applicationService->transform($applicationsJson);
        $applications = $this->applicationService->confirmedApplications($applications);
        $attendanceStatuses = AttendanceDictionary::getList();
        $attendances = $this->attendanceService->applicationFilter($applications);
        $data = $this->attendanceService->createTable($attendances);
        return view('event/attendance', compact('applications', 'event' , 'attendanceStatuses', 'data'));
    }
    public function point($id)
    {
        $eventsJson = $this->eventRepository->getByApiId($id);
        $event = $this->eventService->transformModel($eventsJson);
        $applicationsJson = $this->applicationRepository->getByEventId($event->id);
        $applications = $this->applicationService->transform($applicationsJson);
        $applications = $this->applicationService->confirmedApplications($applications);
        $attendances = $this->attendanceService->attendanceFilter($this->attendanceService->applicationFilter($applications));
        $table = $this->attendanceService->createExtraTable($attendances);
        $tasks = $this->taskRepository->getByEventId($id);
        return view('event/point', compact('event', 'tasks', 'table'));
    }
    public function delete($id){
        return redirect()->route('event.index');
    }
    public function synchronize($id)
    {
        $applicationsJson = $this->applicationRepository->getByEventId($id);
        $eventApplications = $this->applicationService->transform($applicationsJson);
        $applications = $this->applicationService->confirmedApplications($eventApplications);
        $this->eventService->synchronize($applications, $eventApplications);
        return redirect()->route('event.attendance', ['id' => $id]);
    }
    public function addTask(TaskRequest $request, $id)
    {
        $data = $request->validated();
        $this->taskService->createTasks($data, $id);
        return redirect()->route('event.task', ['id' => $id]);
    }
    public function deleteTask($id)
    {
        $task = $this->taskRepository->get($id);
        $this->taskService->delete($task);
        return redirect()->route('event.task', ['id' => $task->event_id]);
    }
    public function changeAttendance(Request $request){
        try {
            $attendance = $this->attendanceRepository->get($request->attendance_id);
            if ($attendance) {
                $tasks = $this->taskRepository->getByEventId($request->eventId);
                if($request->status == AttendanceDictionary::NO_ATTENDANCE){
                    $taskAttendances = $this->taskAttendanceRepository->getByAttendanceId($attendance->id);
                    foreach ($taskAttendances as $taskAttendance) {
                        $this->taskAttendanceRepository->delete($taskAttendance);
                    }
                }
                else {
                    foreach ($tasks as $task) {
                        $this->taskAttendanceRepository->create($attendance->id, $task->id, 0);
                    }
                 }
                $attendance->status = $request->status;
                $this->attendanceRepository->save($attendance);
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
    public function changeScore(Request $request)
    {
        try {
            $taskAttendance = $this->taskAttendanceRepository->get($request->task_attendance_id);
            if ($taskAttendance) {
                $taskAttendance->points = $request->points;
                $this->taskAttendanceRepository->save($taskAttendance);
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
