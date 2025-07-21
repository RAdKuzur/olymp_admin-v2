<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\ApplicationStatusDictionary;
use App\Components\Dictionaries\ReasonParticipantDictionary;
use App\Components\Dictionaries\SubjectDictionary;
use App\Components\RabbitMQHelper;
use App\Http\Requests\ApplicationRequest;
use App\Repositories\ApplicationRepository;
use App\Repositories\EventRepository;
use App\Repositories\UserRepository;
use App\Services\ApplicationService;
use App\Services\EventService;
use App\Services\RabbitMQService;
use App\Services\UserService;
class ApplicationController extends Controller
{
    private RabbitMQService $rabbitMQService;
    private ApplicationService $applicationService;
    private ApplicationRepository $applicationRepository;
    private UserService $userService;
    private UserRepository $userRepository;
    private EventService $eventService;
    private EventRepository $eventRepository;
    public function __construct(
        RabbitMQService $rabbitMQService,
        ApplicationService $applicationService,
        ApplicationRepository $applicationRepository,
        UserService $userService,
        UserRepository $userRepository,
        EventService $eventService,
        EventRepository $eventRepository
    )
    {
        $this->rabbitMQService = $rabbitMQService;
        $this->applicationService = $applicationService;
        $this->applicationRepository = $applicationRepository;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
    }

    public function index($page = 1){
        $applicationsJson = $this->applicationRepository->getByApiAll($page);
        $applications = $this->applicationService->transform($applicationsJson);
        $applicationsAmount = $this->applicationRepository->getCount();
        $statuses = ApplicationStatusDictionary::getList();
        return view('application.index', compact('applications', 'statuses', 'applicationsAmount'));
    }
    public function create(){
        $statuses = ApplicationStatusDictionary::getList();
        $users = $this->userService->transform($this->userRepository->getByApiAll());
        $events = $this->eventService->transform($this->eventRepository->getByApiAll());
        return view('application.create')->with(compact('users', 'statuses', 'events'));
    }
    public function store(ApplicationRequest $request){
        $data = $request->validated();
        $this->rabbitMQService->publish(
            [RabbitMQHelper::APPLICATION_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::CREATE,
            RabbitMQHelper::APPLICATION_TABLE,
            array_diff_key($data, ['id' => null]),
        );
        return redirect()->route('application.index');
    }
    public function show($id){
        $applicationJson = $this->applicationRepository->getByApiId($id);
        $application = $this->applicationService->transformModel($applicationJson);
        $statuses = ApplicationStatusDictionary::getList();
        return view('application.show', compact('application', 'statuses'));
    }
    public function edit($id){
        $applicationJson = $this->applicationRepository->getByApiId($id);
        $application = $this->applicationService->transformModel($applicationJson);
        $statuses = ApplicationStatusDictionary::getList();
        $users = $this->userService->transform($this->userRepository->getByApiAll());
        $subjects = SubjectDictionary::getList();
        $reasons = ReasonParticipantDictionary::getList();
        $events = $this->eventService->transform($this->eventRepository->getByApiAll());
        return view('application.edit')->with(compact('application', 'users', 'statuses', 'events', 'reasons', 'subjects'));
    }
    public function update(ApplicationRequest $request, $id){
        $data = $request->validated();
        $this->rabbitMQService->publish(
            [RabbitMQHelper::APPLICATION_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::UPDATE,
            RabbitMQHelper::APPLICATION_TABLE,
            array_diff_key($data, ['id' => null]),
            ['id' => $id]
        );
        return redirect()->route('application.index');
    }
    public function destroy($id){
        $this->rabbitMQService->publish(
            [RabbitMQHelper::APPLICATION_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::DELETE,
            RabbitMQHelper::APPLICATION_TABLE,
            [],
            ['id' => $id]
        );
        return redirect()->route('application.index');
    }
    public function confirm($id){
        $this->rabbitMQService->publish(
            [RabbitMQHelper::APPLICATION_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::UPDATE,
            RabbitMQHelper::APPLICATION_TABLE,
            ['status' => ApplicationStatusDictionary::APPROVED],
            ['id' => $id]
        );
        return redirect()->route('application.index');
    }
    public function reject($id){
        $this->rabbitMQService->publish(
            [RabbitMQHelper::APPLICATION_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::UPDATE,
            RabbitMQHelper::APPLICATION_TABLE,
            ['status' => ApplicationStatusDictionary::REJECTED],
            ['id' => $id]
        );
        return redirect()->route('application.index');
    }
}
