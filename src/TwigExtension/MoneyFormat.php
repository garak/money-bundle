<?php

namespace Garak\Money\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class MoneyFormat extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('money', [MoneyFormatRuntime::class, 'format']),
            new TwigFilter('nullable_money', [MoneyFormatRuntime::class, 'formatNullable']),
        ];
    }
}
