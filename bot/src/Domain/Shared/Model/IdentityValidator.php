<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model;

interface IdentityValidator
{
    public function validate(string $id): void;

}