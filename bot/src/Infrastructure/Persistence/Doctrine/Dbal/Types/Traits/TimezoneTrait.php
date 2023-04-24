<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Dbal\Types\Traits;

trait TimezoneTrait
{
    protected static ?\DateTimeZone $utcDateTimeZone = null;

    protected static function getUtcDateTimeZone(): \DateTimeZone
    {
        if (null === self::$utcDateTimeZone) {
            self::$utcDateTimeZone = new \DateTimeZone(
                'UTC'
            );
        }
        return self::$utcDateTimeZone;
    }

}