<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model\Value;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Uuid
{
    public function __construct(
        public readonly string $value
    )
    {
        try {
            Assertion::uuid($this->value);
        } catch(AssertionFailedException $e) {
            throw new \DomainException(sprintf('"%s" is an invalid uuid', $value));
        }
    }

}