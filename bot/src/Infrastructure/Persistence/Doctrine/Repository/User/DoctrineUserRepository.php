<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Repository\User;

use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Exception\UserNotFoundException;
use Bot\Domain\User\Model\User;
use Bot\Domain\User\Repository\SearchUserFilters;
use Bot\Domain\User\Repository\UserRepository;
use Bot\Infrastructure\Persistence\Doctrine\Repository\DoctrineBaseRepository;

class DoctrineUserRepository extends DoctrineBaseRepository implements UserRepository
{
    const ALIAS = 'u';

    protected static function entityClass(): string
    {
        return User::class;
    }

    protected function getAlias(): string
    {
        return self::ALIAS;
    }


    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    public function delete(User $user): void
    {
        $this->removeEntity($user);
    }


    public function findByIdAndClientId(Uuid $id, Uuid $clientId): User
    {
        $criteria = [
            'id' => $id->value,
            'client' => $clientId->value
        ];

        $user = $this->objectRepository->findOneBy($criteria);
        if($user === null){
            throw UserNotFoundException::fromId($id);
        }

        return $user;
    }


    public function search(Uuid $clientId, SearchUserFilters $filters): array
    {
        $queryBuilder = $this->objectRepository->createQueryBuilder('u');

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(self::ALIAS.'.client', ':client'))
            ->setParameter(
               ':client',
                $clientId->value
            );

        $queryBuilder = $this->buildSearchQuery($queryBuilder,$filters);

        $queryBuilder->orderBy(self::ALIAS.'.createdAt','ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}