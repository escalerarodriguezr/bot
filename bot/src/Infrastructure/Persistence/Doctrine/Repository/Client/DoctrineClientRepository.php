<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Repository\Client;

use Bot\Domain\Client\Exception\ClientAlreadyExistsException;
use Bot\Domain\Client\Exception\ClientNotFoundException;
use Bot\Domain\Client\Model\Client;
use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Infrastructure\Persistence\Doctrine\Repository\DoctrineBaseRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class DoctrineClientRepository extends DoctrineBaseRepository implements ClientRepository
{
    protected static function entityClass(): string
    {
        return Client::class;
    }

    protected function getAlias(): string
    {
        return 'c';
    }


    public function save(Client|PasswordAuthenticatedUserInterface $client): void
    {
        try {
            $this->saveEntity($client);
        }catch (UniqueConstraintViolationException $exception){
            ClientAlreadyExistsException::withEmail($client->getEmail());
        }
    }

    public function findById(Uuid $id): Client
    {
        if (null === $client = $this->objectRepository->findOneBy(['id' => $id->value])) {
            throw ClientNotFoundException::fromId($id);
        }

        return $client;
    }

    public function findByEmail(string $email): ?Client
    {
        if (null === $client = $this->objectRepository->findOneBy(['email' => $email])) {
            throw ClientNotFoundException::fromEmail($email);
        }

        return $client;
    }


}