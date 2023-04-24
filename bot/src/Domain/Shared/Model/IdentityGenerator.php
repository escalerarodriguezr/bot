<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Model;

interface IdentityGenerator
{
    public function generateId(): string;

}