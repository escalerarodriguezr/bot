<?php
declare(strict_types=1);

namespace App\Controller\User;

use Bot\Application\User\SearchUser\SearchUserQuery;
use Bot\Domain\Shared\Bus\Query\QueryBus;
use Bot\Domain\User\Repository\SearchUserFilters;
use Bot\Infrastructure\Ui\Http\Service\HttpRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchUserController
{


    public function __construct(
        private readonly HttpRequestService $httpRequestService,
        private readonly QueryBus $queryBus
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $filters = new SearchUserFilters();
        $filters->createFilters($request->query->all());

        $response = $this->queryBus->handle(
            new SearchUserQuery(
                $this->httpRequestService->actionClient->getId()->value,
                $filters
            )
        );

        return new JsonResponse(
            $response,
            Response::HTTP_OK
        );
    }

}