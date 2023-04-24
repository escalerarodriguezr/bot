<?php

namespace Bot\Application\User\SearchUser;

use Bot\Application\User\Response\UserResponse;
use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Domain\Shared\Bus\Query\QueryHandler;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Repository\UserRepository;

class SearchUserQueryHandler implements QueryHandler
{


    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function __invoke(SearchUserQuery $query): mixed
    {
        $clientId = new Uuid($query->actionClientId);
        $response = $this->userRepository->search($clientId,$query->searchUserFilters);

        return array_map(function ($model) use($clientId) {
           return (UserResponse::createFromModel($model,$clientId))->toArray();
       },$response);


    }


}