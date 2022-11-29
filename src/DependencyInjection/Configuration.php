<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const AUTHORIZATON_NODE = 'authorization';
    public const MAY_POST_PROXYDATA = 'MAY_POST_PROXYDATA';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dbp_relay_proxy');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode(self::AUTHORIZATON_NODE)
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('rights')
                                ->children()
                                    ->scalarNode(self::MAY_POST_PROXYDATA)
                                    ->info('The (boolean) expression checking whether the current user may post the given proxy data. Available parameters: user, subject (of type ProxyData)')
                                    ->example('user.get("CALL_PROXY_FUNCTIONS") === true || subject.getNamespace() === "public"')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
