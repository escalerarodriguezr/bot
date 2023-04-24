<?php

namespace Bot\Domain\User\Exception;

use Bot\Domain\Client\Model\Client;
use Bot\Domain\Shared\Model\Value\Uuid;

class UserNotFoundException extends \DomainException
{
    public static function fromId(Uuid $id): self
    {
        throw new self(\sprintf('User with UUID %s not found', $id->value));
    }

}