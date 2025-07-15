<?php

namespace App\Http\Controllers;

use App\Components\Dictionaries\ClassDictionary;
use App\Components\Dictionaries\CountryDictionary;
use App\Components\Dictionaries\DisabilityDictionary;
use App\Components\RabbitMQHelper;
use App\Http\Requests\ParticipantRequest;
use App\Repositories\ParticipantRepository;
use App\Repositories\SchoolRepository;
use App\Services\ParticipantService;
use App\Services\RabbitMQService;
use App\Services\SchoolService;

class ParticipantController extends Controller
{
    private ParticipantRepository $participantRepository;
    private ParticipantService $participantService;
    private RabbitMQService $rabbitMQService;
    private SchoolRepository $schoolRepository;
    private SchoolService $schoolService;
    public function __construct(
        ParticipantService $participantService,
        ParticipantRepository $participantRepository,
        RabbitMQService $rabbitMQService,
        SchoolRepository $schoolRepository,
        SchoolService $schoolService
    )
    {
        $this->participantService = $participantService;
        $this->participantRepository = $participantRepository;
        $this->rabbitMQService = $rabbitMQService;
        $this->schoolRepository = $schoolRepository;
        $this->schoolService = $schoolService;
    }

    public function index($page = 1)
    {
        $disabilities = DisabilityDictionary::getList();
        $countries = CountryDictionary::getList();
        $classes = ClassDictionary::getList();
        $participantsJson = $this->participantRepository->getByApiAll($page);
        $participantsAmount = $this->participantRepository->getCount();
        $participants = $this->participantService->transform($participantsJson);
        return view('participant/index', [
            'disabilities' => $disabilities,
            'countries' => $countries,
            'classes' => $classes,
            'participants' => $participants,
            'participantsAmount' => $participantsAmount
        ]);
    }
    public function create(){
        $disabilities = DisabilityDictionary::getList();
        $countries = CountryDictionary::getList();
        $classes = ClassDictionary::getList();
        $schools = $this->schoolService->transform($this->schoolRepository->getByApiAll());
        return view('participant/create', [
            'disabilities' => $disabilities,
            'countries' => $countries,
            'classes' => $classes,
            'schools' => $schools
        ]);

    }
    public function store(ParticipantRequest $request){
        $data = $request->validated();
        $this->rabbitMQService->publish(
            [RabbitMQHelper::AUTH_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::CREATE,
            RabbitMQHelper::PARTICIPANT_TABLE,
            array_diff_key($data, ['id' => null]),
        );
        return redirect()->route('participant.index');
    }
    public function show($id){
        $disabilities = DisabilityDictionary::getList();
        $countries = CountryDictionary::getList();
        $classes = ClassDictionary::getList();
        $modelJson = $this->participantRepository->getByApiId($id);
        $model = $this->participantService->transformModel($modelJson);
        return view('participant/show', [
            'disabilities' => $disabilities,
            'countries' => $countries,
            'classes' => $classes,
            'model' => $model
        ]);
    }
    public function edit($id){
        $disabilities = DisabilityDictionary::getList();
        $countries = CountryDictionary::getList();
        $classes = ClassDictionary::getList();
        $schools = $this->schoolService->transform($this->schoolRepository->getByApiAll());
        $modelJson = $this->participantRepository->getByApiId($id);
        $model = $this->participantService->transformModel($modelJson);
        return view('participant/edit', [
            'disabilities' => $disabilities,
            'countries' => $countries,
            'classes' => $classes,
            'model' => $model,
            'schools' => $schools
        ]);
    }
    public function update(ParticipantRequest $request, $id){
        $data = $request->validated();
        $this->rabbitMQService->publish(
            [RabbitMQHelper::AUTH_QUEUE_NAME],
            RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::UPDATE,
            RabbitMQHelper::PARTICIPANT_TABLE,
            array_diff_key($data, ['id' => null]),
            ['id' => $id]
        );

        return redirect()->route('participant.index');
    }
    public function delete($id){
        $this->rabbitMQService->publish(
            [RabbitMQHelper::AUTH_QUEUE_NAME],
           RabbitMQHelper::QUEUE_NAME,
            RabbitMQHelper::DELETE,
            RabbitMQHelper::PARTICIPANT_TABLE,
            [],
            ['id' => $id]
        );
        return redirect()->route('participant.index');
    }
}
