<?php

namespace Garak\Money\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('garak_money');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
              ->scalarNode('currency')->defaultValue('EUR')->end()
              ?->scalarNode('decimal')->defaultValue(',')->end()
              ?->scalarNode('thousands')->defaultValue('.')->end()
              ?->booleanNode('after')->defaultValue(false)->end()
            ?->end()
        ;

        return $treeBuilder;
    }
}
