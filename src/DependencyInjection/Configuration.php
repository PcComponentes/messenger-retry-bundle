<?php
declare(strict_types=1);

namespace PcComponentes\MessengerRetryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('messenger_retry');
        $treeBuilder
            ->getRootNode()
            ->arrayPrototype()
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('max_retries')->defaultValue(5)->end()
                    ->integerNode('delay_milliseconds')->defaultValue(10000)->end()
                    ->floatNode('multiplier')->min(1.0)->defaultValue(6.0)->end()
                    ->integerNode('max_delay_milliseconds')->defaultValue(1800000)->end()
                    ->arrayNode('msg_types')
                        ->arrayPrototype()
                            ->scalarPrototype()->end()
                            ->enumPrototype()->values(['auto', 'always_retry', 'never_retry'])->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
