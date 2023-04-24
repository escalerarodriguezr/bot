<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model\Value;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{

    public function __construct(
        public readonly string $value
    )
    {
        if (!filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException('Invalid email');
        }
//        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//
//        if (!preg_match($regex, $this->value)) {
//            throw new \DomainException('Invalid email');
//        }
    }

}