<?php

namespace Garak\Money\Tests\TwigExtension;

use Garak\Money\TwigExtension\MoneyFormat;
use Garak\Money\TwigExtension\MoneyFormatRuntime;
use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

final class MoneyFormatTest extends TestCase
{
    public function testGetFilters(): void
    {
        $ext = new MoneyFormat();
        self::assertCount(2, $ext->getFilters());
        self::assertInstanceOf(TwigFilter::class, $ext->getFilters()[0]);
    }

    #[DataProvider('moneyProvider')]
    public function testGetFormat(string $currency, ?Money $input, ?string $output, bool $after = false, bool $space = false): void
    {
        $ext = new MoneyFormatRuntime($currency, ',', '.', $after, $space);
        self::assertEquals($output, $ext->formatNullable($input));
    }

    /**
     * @return array<string, array<int, string|Money|bool|null>>
     */
    public static function moneyProvider(): array
    {
        return [
            'EUR' => ['EUR', Money::EUR(1000_55), '€1.000,55'],
            'USD' => ['USD', Money::EUR(1000_55), '$1.000,55'],
            'negative' => ['EUR', Money::EUR(-1000), '-€10,00'],
            'symbol after' => ['EUR', Money::EUR(1000), '10,00€', true],
            'symbol after + space' => ['EUR', Money::EUR(1000), '10,00 €', true, true],
            'negative and symbol after' => ['EUR', Money::EUR(-1000), '-10,00€', true],
            'null' => ['EUR', null, null],
        ];
    }
}
