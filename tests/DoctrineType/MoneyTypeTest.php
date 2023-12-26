<?php

namespace Garak\Money\Tests\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Garak\Money\DoctrineType\MoneyType;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTypeTest extends TestCase
{
    public function testConvertToPhpValue(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $type = new MoneyType();
        self::assertNull($type->convertToPHPValue(null, $platform));
        self::assertNull($type->convertToPHPValue('', $platform));
        self::assertInstanceOf(Money::class, $type->convertToPHPValue(Money::EUR(10), $platform));
        $money = $type->convertToPHPValue(10, $platform);
        self::assertInstanceOf(Money::class, $money);
        self::assertEquals('EUR', $money->getCurrency()->getCode());
    }

    public function testConvertToInvalidPhpValue(): void
    {
        $this->expectException(ConversionException::class);
        $platform = $this->createMock(AbstractPlatform::class);
        $type = new MoneyType();
        $type->convertToPHPValue('invalid', $platform);
    }

    public function testConvertToPhpValueWithCurrency(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $type = new MoneyType();
        $type->setCurrency('GBP');
        $money = $type->convertToPHPValue(10, $platform);
        self::assertEquals('GBP', $money?->getCurrency()->getCode());
    }

    public function testConvertToDatabaseValue(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        $type = new MoneyType();
        self::assertNull($type->convertToDatabaseValue(null, $platform));
        self::assertEquals(200, $type->convertToDatabaseValue(Money::EUR(200), $platform));
    }

    public function testConvertToInvalidDatabaseValue(): void
    {
        $this->expectException(ConversionException::class);
        $platform = $this->createMock(AbstractPlatform::class);
        $type = new MoneyType();
        $type->convertToDatabaseValue('invalid', $platform);
    }

    public function testGetName(): void
    {
        self::assertEquals('money', (new MoneyType())->getName());
    }

    public function testRequiresSqlCommentHint(): void
    {
        $platform = $this->createMock(AbstractPlatform::class);
        self::assertTrue((new MoneyType())->requiresSQLCommentHint($platform));
    }
}
