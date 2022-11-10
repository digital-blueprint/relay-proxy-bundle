<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DependencyInjection;

use Dbp\Relay\CoreBundle\Authorization\UserAuthorizationChecker;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const AUTHORIZATON_NODE = 'authorization';
    public const CALL_FUNCTION_RIGHT = 'CALL_FUNCTION';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dbp_relay_proxy');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode(self::AUTHORIZATON_NODE)
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode(UserAuthorizationChecker::RIGHTS_CONFIG_ATTRIBUTE)
                                ->children()
                                    ->scalarNode(self::CALL_FUNCTION_RIGHT)
                                    ->info('The (boolean) expression checking whether the current user may call the requested function. Available parameters: user, subject (of type ProxyData)')
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
