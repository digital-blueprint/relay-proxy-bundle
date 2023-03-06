<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DependencyInjection;

use Dbp\Relay\CoreBundle\Authorization\AuthorizationConfigDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const MAY_POST_PROXYDATA = 'MAY_POST_PROXYDATA';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $authorizationConfigDefinition = AuthorizationConfigDefinition::create()
            ->addRole(self::MAY_POST_PROXYDATA, 'false',
                'The (boolean) expression checking whether the current user may post the given proxy data. Available parameters: user, subject (of type ProxyData)');

        $treeBuilder = new TreeBuilder('dbp_relay_proxy');
        $treeBuilder->getRootNode()
            ->append($authorizationConfigDefinition->getNodeDefinition());

        return $treeBuilder;
    }
}
