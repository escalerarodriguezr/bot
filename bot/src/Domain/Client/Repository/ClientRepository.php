<?php

namespace Bot\Domain\Client\Repository;

use Bot\Domain\Client\Model\Client;
use Bot\Domain\Shared\Model\Value\Uuid;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface ClientRepository
{
    public function save(Client|PasswordAuthenticatedUserInterface $client): void;
    public function findByEmail(string $email): ?Client;
    public function findById(Uuid $uuid): Client;

}