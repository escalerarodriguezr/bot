<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\IdentityGenerator;

use Bot\Domain\Shared\Model\IdentityGenerator;
use Symfony\Component\Uid\Uuid;

final class UuidIdentityGenerator implements IdentityGenerator
{
    public function generateId(): string
    {
        return Uuid::v4()->toRfc4122();
    }

}