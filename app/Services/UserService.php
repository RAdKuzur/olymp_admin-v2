<?php

namespace App\Services;

use App\Components\Dictionaries\RoleDictionary;
use App\Models\Participant;
use App\Models\User;
use App\Repositories\ParticipantRepository;
use App\Repositories\SchoolRepository;
use DateTime;
use function Illuminate\Events\queueable;

class UserService
{
    private SchoolService $schoolService;
    private SchoolRepository $schoolRepository;
    private ParticipantRepository $participantRepository;
    public function __construct(
        SchoolRepository $schoolRepository,
        SchoolService $schoolService,
        ParticipantRepository $participantRepository
    )
    {
        $this->schoolRepository = $schoolRepository;
        $this->schoolService = $schoolService;
        $this->participantRepository = $participantRepository;
    }

    public function transform($data, $participantFlag = true)
    {
        $models = [];
        foreach ($data['data']['data'] as $item) {
            $model = new User();
            $model->id = $item['id'];
            $model->email = $item['email'];
            $model->firstname = $item['firstname'];
            $model->surname = $item['surname'];
            $model->patronymic = $item['patronymic'];
            $model->phone_number = $item['phone_number'];
            $model->gender = $item['gender'];
            $model->role = $item['role'];
            $model->birthdate = $item['birthdate'];
            if ($participantFlag){
               $model->participantAPI = $this->transformWithoutUser($this->participantRepository->getByApiUserId($model->id));
            }
            $models[] = $model;
        }
        return $models;
    }
    public function transformModel($data)
    {
        $item = $data['data']['data'];
        $model = new User();
        $model->id = $item['id'];
        $model->email = $item['email'];
        $model->firstname = $item['firstname'];
        $model->surname = $item['surname'];
        $model->patronymic = $item['patronymic'];
        $model->phone_number = $item['phone_number'];
        $model->gender = $item['gender'];
        $model->role = $item['role'];
        $date = DateTime::createFromFormat('Y-m-d H:i:s O e', $item['birthdate']);
        $model->participantAPI = $this->transformWithoutUser($this->participantRepository->getByApiUserId($model->id));
        $model->birthdate = $date->format('Y-m-d');
        return $model;
    }
    public function transformWithoutUser($data)
    {
        $item = $data['data']['data'];
        $model = new Participant();
        $model->id = $item['id'];
        $model->citizenship = $item['citizenship'];
        $model->disability = $item['disability'];
        $model->class = $item['class_number'];
        $model->user_id = $item['user_id'];
        $model->school_id = $item['school_id'];
        $model->schoolAPI = $this->schoolService->transformModel(($this->schoolRepository->getByApiId($model->school_id)));
        return $model;
    }
    public function filterParticipantUsers($users)
    {
        return array_filter($users, function ($user){
            return $user->role == RoleDictionary::PARTICIPANT;
        });
    }
}
