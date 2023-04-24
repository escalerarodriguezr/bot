<?php
declare(strict_types=1);

namespace Bot\Domain\Shared\Bus\Query;

interface QueryBus
{
    public function handle(Query $query): mixed;

}