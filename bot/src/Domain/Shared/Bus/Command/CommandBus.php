<?php

namespace Bot\Domain\Shared\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;

}