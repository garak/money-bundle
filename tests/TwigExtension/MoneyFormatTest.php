<?php

namespace Garak\Money\Tests\DoctrineType;

use Garak\Money\TwigExtension\MoneyFormat;
use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

final class MoneyFormatTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new MoneyFormat('EUR', '', '');
        self::assertCount(1, $ext->getFilters());
        self::assertInstanceOf(TwigFilter::class, $ext->getFilters()[0]);
    }

    #[DataProvider('moneyProvider')]
    public function testGetFormat(string $currency, Money $input, string $output): void
    {
        $ext = new MoneyFormat($currency, ',', '.');
        self::assertEquals($output, $ext->format($input));
    }

    /**
     * @return array<string, array<int, string|Money>>
     */
    public static function moneyProvider(): array
    {
        return [
            'EUR' => ['EUR', Money::EUR(1000_55), '€1.000,55'],
            'USD' => ['USD', Money::EUR(1000_55), '$1.000,55'],
        ];
    }
}
