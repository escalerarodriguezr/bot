<?php

namespace Bot\Application\User\UpdateUser;

use Bot\Domain\Shared\Bus\Command\CommandHandler;
use Bot\Domain\Shared\Model\Value\Age;
use Bot\Domain\Shared\Model\Value\LastName;
use Bot\Domain\Shared\Model\Value\Location;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\Value\UserCategory;
use Bot\Domain\User\Repository\UserRepository;

class UpdateUserCommandHandler implements CommandHandler
{

    public function __construct(
        private readonly UserRepository $userRepository

    )
    {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $userId = new Uuid($command->userId);
        $clientId = new Uuid($command->actionClientId);
        $user = $this->userRepository->findByIdAndClientId(
            $userId,
            $clientId
        );

        if($command->name !== null){
            $user->setName(new Name($command->name));
        }
        if($command->lastName !== null){
            $user->setLastName(new LastName($command->lastName));
        }
        if($command->location !== null){
            $user->setLocation(new Location($command->location));
        }
        if($command->age !== null){
            $user->setAge(new Age($command->age));
        }

        if($command->category !== null){
            $user->setCategory(new UserCategory($command->category));
        }
        if($command->active !== null){
            $user->setActive($command->active);
        }

        $this->userRepository->save($user);

    }


}