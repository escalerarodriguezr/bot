<?php
declare(strict_types=1);

namespace Bot\Domain\Client\Exception;


use Bot\Domain\Shared\Model\Value\Email;

class ClientAlreadyExistsException extends \DomainException
{
    public static function withEmail(Email $email): self
    {
        throw new self(sprintf('Client with email %s already exists', $email->value));
    }

}