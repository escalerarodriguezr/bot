<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model\Value;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Location
{

    public function __construct(
        public readonly string $value
    )
    {
        try {
            Assertion::maxLength($value, 100);
        } catch(AssertionFailedException $e) {
            throw new \DomainException(sprintf('"%s" must be %d characters maximum', $value, 100));
        }

    }

}