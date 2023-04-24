<?php
declare(strict_types=1);

namespace App\Controller\User;

use Bot\Application\User\CreateUser\CreateUserCommand;
use Bot\Domain\Shared\Bus\Command\CommandBus;
use Bot\Domain\Shared\Model\IdentityGenerator;
use Bot\Infrastructure\Ui\Http\Request\DTO\User\CreateUserRequest;
use Bot\Infrastructure\Ui\Http\Service\HttpRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController
{


    public function __construct(
        private readonly HttpRequestService $httpRequestService,
        public readonly IdentityGenerator $identityGenerator,
        private readonly CommandBus $commandBus
    )
    {
    }

    public function __invoke(CreateUserRequest $createUserRequest): Response
    {
        $id = $this->identityGenerator->generateId() ;
        $this->commandBus->dispatch(
            new CreateUserCommand(
                $this->httpRequestService->actionClient->getId()->value,
                $id,
                $createUserRequest->getName(),
                $createUserRequest->getLastName(),
                $createUserRequest->getCategory(),
                $createUserRequest->getLocation(),
                $createUserRequest->getAge(),
                $createUserRequest->getActive()
            )
        );

        return new JsonResponse(
            [HttpRequestService::ID => $id],
            Response::HTTP_CREATED
        );
    }

}