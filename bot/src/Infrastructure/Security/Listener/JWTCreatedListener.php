<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Security\Listener;

use Bot\Domain\Client\Model\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JWTCreatedListener
{
    const CLIENT_ID = 'clientId';
    const CLIENT_EMAIL = 'clientEmail';


    public function onJWTCreated(JWTCreatedEvent $event): void
    {

        /**
         * @var Client $client
         */
        $client = $event->getUser();
        $payload = $event->getData();
        unset($payload['roles']);
        $payload[self::CLIENT_ID] = $client->getId()->value;
        $payload[self::CLIENT_EMAIL] = $client->getEmail()->value;
        $event->setData($payload);


    }

}