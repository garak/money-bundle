<?php

namespace Garak\Money;

use Doctrine\DBAL\Types\Type;
use Garak\Money\DoctrineType\MoneyType;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MoneyBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function boot(): void
    {
        /** @var string $currency */
        $currency = $this->container?->getParameter('garak.money.currency');
        Type::addType('money', MoneyType::class);
        /** @var MoneyType $moneyType */
        $moneyType = Type::getType('money');
        $moneyType->setCurrency($currency);
    }
}
