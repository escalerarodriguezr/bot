<?php
declare(strict_types=1);

namespace App\Controller\User;

use Bot\Application\User\UpdateUser\UpdateUserCommand;
use Bot\Domain\Shared\Bus\Command\CommandBus;
use Bot\Domain\Shared\Model\IdentityValidator;
use Bot\Infrastructure\Ui\Http\Request\DTO\User\UpdateUserRequest;
use Bot\Infrastructure\Ui\Http\Service\HttpRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserController
{

    public function __construct(
        private readonly HttpRequestService $httpRequestService,
        private readonly IdentityValidator $identityValidator,
        private readonly CommandBus $commandBus
    )
    {
    }

    public function __invoke(
        string $id,
        UpdateUserRequest $request
    ): Response
    {
        $this->identityValidator->validate($id);

        $this->commandBus->dispatch(
            new UpdateUserCommand(
                $this->httpRequestService->actionClient->getId()->value,
                $id,
                $request->getName(),
                $request->getLastName(),
                $request->getCategory(),
                $request->getLocation(),
                $request->getAge(),
                $request->getActive()
            )
        );

        return new JsonResponse(
            null,
            Response::HTTP_OK
        );
    }

}