<?php

namespace Bot\Infrastructure\Persistence\Doctrine\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Bot\Infrastructure\Persistence\Doctrine\Dbal\Types\Traits\TimezoneTrait;

class UTCDateTimeImmutableType extends DateTimeImmutableType
{
    use TimezoneTrait;

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform
    ): ?string {
        if (null === $value) {
            return null;
        }

        if ($value instanceof \DateTime) {
            $value = \DateTimeImmutable::createFromMutable(
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
    ): ?\DateTimeImmutable {
        if (null === $value) {
            return null;
        }

        $dateTimeImmutable = $value;
        if (false === ($value instanceof \DateTimeImmutable)) {
            $dateTimeImmutable = \DateTimeImmutable::createFromFormat(
                $platform->getDateTimeFormatString(),
                $value,
                self::getUtcDateTimeZone()
            );
        }

        if (false === $dateTimeImmutable) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $dateTimeImmutable;
    }

    public function requiresSQLCommentHint(
        AbstractPlatform $platform
    ): bool {
        return true;
    }

}