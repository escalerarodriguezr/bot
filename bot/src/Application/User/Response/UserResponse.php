<?php
declare(strict_types=1);

namespace Bot\Application\User\Response;

use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\User;
use DateTimeInterface;

class UserResponse
{
    const ID = 'id';
    const CLIENT_ID = 'clientId';
    const NAME = 'name';
    const LAST_NAME = 'lastName';
    const CATEGORY = 'category';
    const AGE = 'age';
    const LOCATION = 'location';
    const ACTIVE = 'active';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function __construct(
        public readonly string $id,
        public readonly string $clientId,
        public readonly string $name,
        public readonly string $lastName,
        public readonly string $category,
        public readonly int $age,
        public readonly string $location,
        public readonly bool $active,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt

    )
    {
    }

    public static function createFromModel(User $model, Uuid $clientId=null): self
    {
        if($clientId != null){
            $clientIdResponse= $clientId->value;
        }else{
            $clientIdResponse = $model->getClient()->getId()->value;
        }

        return new self(
            $model->getId()->value,
            $clientIdResponse,
            $model->getName()->value,
            $model->getLastName()->value,
            $model->getCategory()->value,
            $model->getAge()->value,
            $model->getLocation()->value,
            $model->isActive(),
            $model->getCreatedAt(),
            $model->getUpdatedAt()
        );
    }


    public function toArray(): array
    {
        return [
            self::ID => $this->id,
            self::CLIENT_ID => $this->clientId,
            self::NAME => $this->name,
            self::LAST_NAME => $this->lastName,
            self::CATEGORY => $this->category,
            self::AGE => $this->age,
            self::LOCATION => $this->location,
            self::ACTIVE => $this->active,
            self::CREATED_AT => $this->createdAt->format(DateTimeInterface::RFC3339),
            self::UPDATED_AT => $this->updatedAt->format(DateTimeInterface::RFC3339),
        ];
    }

}