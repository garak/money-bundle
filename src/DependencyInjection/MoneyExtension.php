<?php

namespace Garak\Money\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

final class MoneyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();

        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        $container->setParameter('garak.money.currency', $config['currency']);
        $container->setParameter('garak.money.decimal', $config['decimal']);
        $container->setParameter('garak.money.thousands', $config['thousands']);
        $container->setParameter('garak.money.after', $config['after']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');
    }
}
