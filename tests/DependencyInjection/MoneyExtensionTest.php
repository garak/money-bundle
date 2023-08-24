<?php

namespace Garak\Money\Tests\DoctrineType;

use Garak\Money\DependencyInjection\MoneyExtension;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MoneyExtensionTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testLoad(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $ext = new MoneyExtension();
        $ext->load([], $container);
    }
}
