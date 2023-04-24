<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Dbal\Types;

use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Bot\Infrastructure\Persistence\Doctrine\Dbal\Types\Traits\TimezoneTrait;

class UTCDateType extends DateType
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

        if (!$value instanceof \DateTime) {
            return null;
        }

        return $value->setTimezone(
            self::getUtcDateTimeZone()
        )->setTime(
            0, 0, 0
        )->format(
            $platform->getDateFormatString()
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
                $platform->getDateFormatString(),
                $value,
                self::getUtcDateTimeZone()
            );
        }

        if (false === $dateTime) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $dateTime = $dateTime->setTime(
            0, 0, 0
        );

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