<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DependencyInjection;

use Dbp\Relay\CoreBundle\Extension\ExtensionTrait;
use Dbp\Relay\ProxyBundle\Authorization\AuthorizationService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class DbpRelayProxyExtension extends ConfigurableExtension
{
    use ExtensionTrait;

    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $pathsToHide = [
            '/proxy/proxydata/{identifier}',
            '/proxy/proxydata',
        ];

        foreach ($pathsToHide as $path) {
            $this->addPathToHide($container, $path);
        }

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $definition = $container->getDefinition(AuthorizationService::class);
        $definition->addMethodCall('setConfig', [$mergedConfig]);
    }
}
