<?php
declare(strict_types=1);

namespace Bot\Application\User\SearchUser;

use Bot\Domain\Shared\Bus\Query\Query;
use Bot\Domain\User\Repository\SearchUserFilters;

class SearchUserQuery implements Query
{


    public function __construct(
        public readonly string $actionClientId,
        public readonly SearchUserFilters $searchUserFilters
    )
    {
    }
}