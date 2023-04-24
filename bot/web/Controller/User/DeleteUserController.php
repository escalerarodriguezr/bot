<?php
declare(strict_types=1);

namespace App\Controller\User;

use Bot\Application\User\DeleteUser\DeleteUserCommand;
use Bot\Domain\Shared\Bus\Command\CommandBus;
use Bot\Domain\Shared\Model\IdentityValidator;
use Bot\Infrastructure\Ui\Http\Service\HttpRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserController
{


    public function __construct(
        private readonly HttpRequestService $httpRequestService,
        private readonly IdentityValidator $identityValidator,
        private readonly CommandBus $commandBus
    )
    {
    }

    public function __invoke(string $id): Response
    {
        $this->identityValidator->validate($id);

        $this->commandBus->dispatch(
            new DeleteUserCommand(
                $this->httpRequestService->actionClient->getId()->value,
                $id
            )
        );

        return new JsonResponse(
            null,
            Response::HTTP_OK
        );
    }


}