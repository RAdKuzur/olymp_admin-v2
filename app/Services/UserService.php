<?php

namespace App\Services;

use App\Builder\ParticipantBuilder;
use App\Builder\UserBuilder;
use App\Components\Dictionaries\RoleDictionary;
use App\Models\Participant;
use App\Models\User;
use App\Repositories\ParticipantRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\UserRepository;
use DateTime;
use function Illuminate\Events\queueable;

class UserService
{
    private UserRepository $userRepository;
    private UserBuilder $userBuilder;
    private ParticipantRepository $participantRepository;
    private ParticipantBuilder $participantBuilder;
    public function __construct(
        UserRepository $userRepository,
        UserBuilder $userBuilder,
        ParticipantRepository $participantRepository,
        ParticipantBuilder $participantBuilder
    )
    {
        $this->userRepository = $userRepository;
        $this->userBuilder = $userBuilder;
        $this->participantRepository = $participantRepository;
        $this->participantBuilder = $participantBuilder;
    }

    public function find($id)
    {
        $user = $this->userBuilder->build($this->userRepository->getByApiId($id));
        $participant = $this->participantBuilder->build($this->participantRepository->getByApiUserId($id));
        $this->userBuilder->buildParticipant($user, $participant);
        return $user;
    }
    public function findAll($page = NULL){
        $users = [];
        $data = $this->userRepository->getByApiAll($page);
        foreach ($data as $item) {
            $user = $this->userBuilder->build($item);
            $participant = $this->participantBuilder->build($this->participantRepository->getByApiUserId($user->id));
            $this->userBuilder->buildParticipant($user, $participant);
            $users[] = $user;
        }
        return $users;
    }

}
