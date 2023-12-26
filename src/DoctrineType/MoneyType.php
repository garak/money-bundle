<?php

namespace Garak\Money\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use Money\Currency;
use Money\Money;

final class MoneyType extends IntegerType
{
    public const NAME = 'money';

    private string $currency = 'EUR';

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Money
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($value instanceof Money) {
            return $value;
        }

        try {
            $currency = new Currency($this->currency);

            return new Money($value, $currency);
        } catch (\InvalidArgumentException $exception) {
            throw ConversionException::conversionFailed($value, self::NAME, $exception);
        }
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof Money) {
            return (int) $value->getAmount();
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
