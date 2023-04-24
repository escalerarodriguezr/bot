<?php
declare(strict_types=1);

namespace Bot\Application\User\DeleteUser;

use Bot\Domain\Shared\Bus\Command\Command;

class DeleteUserCommand implements Command
{


    public function __construct(
        public readonly string $actionClientId,
        public readonly string $userId
    )
    {
    }
}