<?php
declare(strict_types=1);

namespace Bot\Domain\Client\Exception;

use Bot\Domain\Shared\Model\Value\Email;
use Bot\Domain\Shared\Model\Value\Uuid;

class ClientNotFoundException extends \DomainException
{
    public static function fromId(Uuid $id): self
    {
        throw new self(\sprintf('Client with UUID %s not found', $id->value));
    }

    public static function fromEmail(string $email): self
    {
        throw new self(\sprintf('Client with email %s not found', $email));
    }

}