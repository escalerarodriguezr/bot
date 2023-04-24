<?php
declare(strict_types=1);

namespace Bot\Application\User\DeleteUser;

use Bot\Domain\Shared\Bus\Command\CommandHandler;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Repository\UserRepository;

class DeleteUserCommandHandler implements CommandHandler
{

    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        $userId = new Uuid($command->userId);
        $clientId = new Uuid($command->actionClientId);

        $user = $this->userRepository->findByIdAndClientId(
            $userId,
            $clientId
        );

       $this->userRepository->delete($user);
    }


}