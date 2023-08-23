<?php

namespace Garak\Money\TwigExtension;

use Money\Money;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class MoneyFormat extends AbstractExtension
{
    public function __construct(
        private readonly string $currency,
        private readonly string $decimal,
        private readonly string $thousands,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFilters(): array
    {
        return [new TwigFilter('money', [$this, 'format'])];
    }

    public function format(Money $money, string $decimal = null, string $thousands = null): string
    {
        $symbol = $this->getSymbol();
        $decimal ??= $this->decimal;
        $thousands ??= $this->thousands;

        return $symbol.\number_format((int) $money->getAmount() / 100, 2, $decimal, $thousands);
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
