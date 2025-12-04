<?php

namespace Garak\Money\Tests\DependencyInjection;

use Garak\Money\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

final class ConfigurationTest extends TestCase
{
    public function testGetTree(): void
    {
        $configuration = new Configuration();
        self::assertEquals('garak_money', $configuration->getConfigTreeBuilder()->buildTree()->getName());
    }
}
