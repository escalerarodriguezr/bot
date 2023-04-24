<?php
declare(strict_types=1);

namespace Bot\Domain\User\Repository;


use Bot\Domain\Client\Model\Client;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\User;
use Doctrine\Common\Collections\Collection;

interface UserRepository
{
    public function save(User $user): void;

    public function findByIdAndClientId(Uuid $id,Uuid $clientId): User;

    public function delete(User $user): void;
    public function search(Uuid $clientId, SearchUserFilters $filters): array;

}