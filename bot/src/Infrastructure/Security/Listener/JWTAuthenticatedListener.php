<?php

namespace Bot\Infrastructure\Security\Listener;

use Bot\Domain\Shared\Model\Value\Uuid;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTAuthenticatedListener
{
    const ACTION_CLIENT_ID = 'actionClientId';

    public function __construct(
        private readonly RequestStack $requestStack,

    )
    {
    }

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {
        $clientId = new Uuid($event->getPayload()[JWTCreatedListener::CLIENT_ID]);
        $this->addRequestParams($clientId);
    }

    private function addRequestParams(Uuid $actionClientId): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $request->attributes->set(self::ACTION_CLIENT_ID, $actionClientId->value);

    }

}