<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Service;

use Bot\Domain\Client\Exception\ClientNotFoundException;
use Bot\Domain\Client\Model\Client;
use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Infrastructure\Security\Listener\JWTAuthenticatedListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HttpRequestService
{
    const ID = 'uuid';
    public readonly Uuid $actionClientId;
    public readonly Client $actionClient;




    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ClientRepository $clientRepository
    )
    {
        $this->actionClientId = new Uuid(
            $this->requestStack->getCurrentRequest()->get(
                JWTAuthenticatedListener::ACTION_CLIENT_ID
            )
        );
        $this->setActionClient();
    }

    private function setActionClient() : void
    {
        try{
            $this->actionClient = $this->clientRepository->findById($this->actionClientId);
        }catch (ClientNotFoundException $exception){
            throw new AccessDeniedException();
        }
    }
}