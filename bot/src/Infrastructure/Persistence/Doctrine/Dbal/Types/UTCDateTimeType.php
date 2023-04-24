<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Bot\Infrastructure\Persistence\Doctrine\Dbal\Types\Traits\TimezoneTrait;

class UTCDateTimeType extends DateTimeType
{
    use TimezoneTrait;

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform
    ): ?string {
        if (null === $value) {
            return null;
        }

        if ($value instanceof \DateTimeImmutable) {
            $value = \DateTime::createFromImmutable(
                $value
            );
        }

        if (false === $value) {
            return null;
        }

        return $value->setTimezone(
            self::getUtcDateTimeZone()
        )->format(
            $platform->getDateTimeFormatString()
        );
    }

    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform
    ): ?\DateTime {
        if (null === $value) {
            return null;
        }

        $dateTime = $value;
        if (false === ($value instanceof \DateTimeImmutable)) {
            $dateTime = \DateTime::createFromFormat(
                $platform->getDateTimeFormatString(),
                $value,
                self::getUtcDateTimeZone()
            );
        }

        if (false === $dateTime) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $dateTime;
    }

    public function requiresSQLCommentHint(
        AbstractPlatform $platform
    ): bool {
        return true;
    }

}