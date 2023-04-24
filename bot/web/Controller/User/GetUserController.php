<?php
declare(strict_types=1);

namespace App\Controller\User;

use Bot\Application\User\GetUserById\GetUserByIdQuery;
use Bot\Application\User\Response\UserResponse;
use Bot\Domain\Shared\Bus\Query\QueryBus;
use Bot\Domain\Shared\Model\IdentityValidator;
use Bot\Infrastructure\Ui\Http\Service\HttpRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetUserController
{

    public function __construct(
        private readonly HttpRequestService $httpRequestService,
        private readonly IdentityValidator $identityValidator,
        private readonly QueryBus $queryBus
    )
    {
    }

    public function __invoke(string $id): Response
    {
        $this->identityValidator->validate($id);

        /**
         * @var $response UserResponse
         */
        $response = $this->queryBus->handle(
            new GetUserByIdQuery(
                $this->httpRequestService->actionClient->getId()->value,
                $id
            )
        );

        return new JsonResponse(
            $response->toArray(),
            Response::HTTP_OK
        );
    }


}