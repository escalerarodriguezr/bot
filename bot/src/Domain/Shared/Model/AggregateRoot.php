<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model;

abstract class AggregateRoot
{

    public function __construct(
        protected \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        protected \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),


    )
    {

    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }


    protected function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

}