<?php

namespace Garak\Money\TwigExtension;

use Money\Money;
use Twig\Extension\RuntimeExtensionInterface;

final class MoneyFormatRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly string $currency,
        private readonly string $decimal,
        private readonly string $thousands,
        private readonly bool $after = false,
        private readonly bool $space = false,
    ) {
    }

    public function formatNullable(?Money $money, ?string $decimal = null, ?string $thousands = null, ?bool $after = null, ?bool $space = null): ?string
    {
        return null === $money ? null : $this->format($money, $decimal, $thousands, $after, $space);
    }

    public function format(Money $money, ?string $decimal = null, ?string $thousands = null, ?bool $after = null, ?bool $space = null): string
    {
        $symbol = $this->getSymbol();
        $decimal ??= $this->decimal;
        $thousands ??= $this->thousands;
        $after ??= $this->after;
        $space ??= $this->space;
        if ($after) {
            return \number_format((int) $money->getAmount() / 100, 2, $decimal, $thousands).($space ? ' ' : '').$symbol;
        }
        if ($money->isNegative()) {
            $symbol = '-'.$symbol;
            $amount = (int) $money->absolute()->getAmount();
        } else {
            $amount = (int) $money->getAmount();
        }

        return $symbol.($space ? ' ' : '').\number_format($amount / 100, 2, $decimal, $thousands);
    }

    private function getSymbol(): string
    {
        return match ($this->currency) {
            'ALL' => 'L',
            'CHF' => 'S₣',
            'DKK', 'ISK', 'NOK', 'SEK' => 'kr.',
            'EUR' => '€',
            'HUF' => 'Ft.',
            'GBP' => '£',
            'PLN' => 'zł.',
            'TRY' => '₺',
            'USD' => '$',
            default => $this->currency.' ',
        };
    }
}
