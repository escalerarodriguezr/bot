<?php
declare(strict_types=1);

namespace Bot\Application\User\CreateUser;

use Bot\Domain\Shared\Bus\Command\Command;

class CreateUserCommand implements Command
{


    public function __construct(
        public readonly string $actionClientId,
        public readonly string $userId,
        public readonly string $name,
        public readonly string $lastName,
        public readonly string $category,
        public readonly string $location,
        public readonly int $age,
        public readonly bool $active
    )
    {
    }
}