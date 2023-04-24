<?php
declare(strict_types=1);

namespace Bot\Application\User\GetUserById;

use Bot\Domain\Shared\Bus\Query\Query;

class GetUserByIdQuery implements Query
{


    public function __construct(
        public readonly string $actionClientId,
        public readonly string $userId
    )
    {
    }
}