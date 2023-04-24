<?php

namespace Bot\Application\User\CreateUser;

use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Domain\Shared\Bus\Command\CommandHandler;
use Bot\Domain\Shared\Model\Value\Age;
use Bot\Domain\Shared\Model\Value\LastName;
use Bot\Domain\Shared\Model\Value\Location;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\User;
use Bot\Domain\User\Model\Value\UserCategory;
use Bot\Domain\User\Repository\UserRepository;

class CreateUserCommandHandler implements CommandHandler
{

    public function __construct(
        private readonly ClientRepository $clientRepository,
        private readonly UserRepository $userRepository

    )
    {
    }

    public function __invoke(CreateUserCommand $command): void
    {

        $client = $this->clientRepository->findById(
            new Uuid($command->actionClientId)
        );

        $user = new User(
            new Uuid($command->userId),
            $client,
            new Name($command->name),
            new LastName($command->lastName),
            new Age($command->age),
            new UserCategory($command->category),
            new Location($command->location),
            $command->active
        );

        $this->userRepository->save($user);

    }


}