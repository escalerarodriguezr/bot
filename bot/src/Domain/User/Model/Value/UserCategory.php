<?php

namespace Bot\Domain\User\Model\Value;

use Assert\Assertion;
use Assert\AssertionFailedException;

class UserCategory
{
    const CATEGORY_X = 'X';
    const CATEGORY_Y = 'Y';
    const CATEGORY_Z = 'Z';


    const VALID_CATEGORIES = [
        self::CATEGORY_X,
        self::CATEGORY_Y,
        self::CATEGORY_Z
    ];

    public function __construct(
        public readonly string $value
    )
    {

        try {
            Assertion::choice($value, self::VALID_CATEGORIES);
        } catch(AssertionFailedException $e) {
            throw new \DomainException(sprintf('"%s" is an invalid category', $value));
        }

    }

}