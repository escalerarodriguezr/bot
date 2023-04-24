<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Bot\Infrastructure\Persistence\Doctrine\Dbal\Types\Traits\TimezoneTrait;

class UTCDateImmutableType extends DateTimeImmutableType
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

        if (!$value instanceof \DateTimeImmutable) {
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
    ): ?\DateTimeImmutable {
        if (null === $value) {
            return null;
        }

        $dateTimeImmutable = $value;
        if (false === ($value instanceof \DateTimeImmutable)) {
            $dateTimeImmutable = \DateTimeImmutable::createFromFormat(
                $platform->getDateFormatString(),
                $value,
                self::getUtcDateTimeZone()
            );
        }

        if (false === $dateTimeImmutable) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $dateTimeImmutable = $dateTimeImmutable->setTime(
            0, 0, 0
        );

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