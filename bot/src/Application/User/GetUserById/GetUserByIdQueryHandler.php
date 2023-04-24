<?php
declare(strict_types=1);

namespace Bot\Application\User\GetUserById;

use Bot\Application\User\Response\UserResponse;
use Bot\Domain\Shared\Bus\Query\QueryHandler;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Repository\UserRepository;

class GetUserByIdQueryHandler implements QueryHandler
{


    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function __invoke(GetUserByIdQuery $query): UserResponse
    {
        $clientId = new Uuid($query->actionClientId);
        $user = $this->userRepository->findByIdAndClientId(
            new Uuid($query->userId),
            $clientId
        );

        return UserResponse::createFromModel($user,$clientId);
    }


}