<?php

namespace Garak\Money\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Money\Currency;
use Money\Money;

final class MoneyType extends Type
{
    /** @var non-empty-string */
    private string $currency = 'EUR';

    /**
     * @param non-empty-string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
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
            $msg = \sprintf('Could not convert database value "%s" to Doctrine Type money', $value);
            throw new ConversionException($msg, previous: $exception);
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

        $msg = \sprintf('Could not convert money value value "%s" to database value', $value);
        throw new ConversionException($msg);
    }

    public function getName(): string
    {
        return 'money';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
