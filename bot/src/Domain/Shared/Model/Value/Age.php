<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model\Value;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Age
{

    public function __construct(
        public readonly int $value
    )
    {
        try {
            Assertion::min($value, 0);
            Assertion::max($this->value,150);
        } catch(AssertionFailedException $e) {
            throw new \DomainException(sprintf('"%s" invalid Age value'), $this->value);
        }

    }

}