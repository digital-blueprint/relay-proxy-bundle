<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\ApiPlatform;

use Dbp\Relay\CoreBundle\Helpers\Tools;
use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataEvent;
use Dbp\Relay\CoreBundle\Rest\AbstractDataProcessor;
use Dbp\Relay\ProxyBundle\Authorization\AuthorizationService;
use Dbp\Relay\ProxyBundle\DependencyInjection\Configuration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProxyDataProcessor extends AbstractDataProcessor
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly AuthorizationService $authorizationService)
    {
    }

    protected function addItem(mixed $data, array $filters): mixed
    {
        assert($data instanceof ProxyData);

        if (Tools::isNullOrEmpty($data->getNamespace())) {
            throw new BadRequestException('parameter namespace must not be null nor empty');
        } elseif (Tools::isNullOrEmpty($data->getFunctionName())) {
            throw new BadRequestException('parameter functionName must not be null nor empty');
        }
        $this->authorizationService->denyAccessUnlessIsGrantedResourcePermission(Configuration::MAY_POST_PROXYDATA, $data);

        $proxyDataEvent = new ProxyDataEvent($data);
        $this->eventDispatcher->dispatch($proxyDataEvent, ProxyDataEvent::class.'.'.$data->getNamespace());

        if ($proxyDataEvent->wasAcknowledged() === false) {
            throw new BadRequestException(sprintf('unknown namespace "%s"', $data->getNamespace()));
        }

        $data->setIdentifier($data->getNamespace().'::'.$data->getFunctionName());

        return $data;
    }
}
