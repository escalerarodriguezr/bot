<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Security\Core\Client;

use Bot\Domain\Client\Exception\ClientNotFoundException;
use Bot\Domain\Client\Model\Client;
use Bot\Domain\Client\Repository\ClientRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;


class ClientProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly ClientRepository $clientRepository
    )
    {

    }

    public function loadUserByIdentifier(
        string $identifier
    ): UserInterface
    {
        try {
            return $this->clientRepository->findByEmail($identifier);
        } catch (NotFoundHttpException $e) {
            throw new ClientNotFoundException(\sprintf('Client with email %s not found', $identifier));
        }
    }

    public function refreshUser(
        UserInterface $client
    ): UserInterface
    {
        if (!$client instanceof Client) {
            throw new UnsupportedUserException(\sprintf('Instances of %s are not supported', \get_class($client)));
        }

        return $this->loadUserByIdentifier($client->getUserIdentifier());
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $client,
        string $newHashedPassword
    ): void
    {
        $client->setPassword($newHashedPassword);

        $this->clientRepository->save($client);
    }

    public function supportsClass(
        string $class
    ): bool
    {
        return Client::class === $class || is_subclass_of($class, Client::class);
    }


}