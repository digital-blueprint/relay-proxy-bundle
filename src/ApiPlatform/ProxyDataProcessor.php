<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\ApiPlatform;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use Dbp\Relay\CoreBundle\Helpers\Tools;
use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataEvent;
use Dbp\Relay\ProxyBundle\Authorization\AuthorizationService;
use Dbp\Relay\ProxyBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * @psalm-suppress MissingTemplateParam
 */
class ProxyDataProcessor extends AbstractController implements ProcessorInterface
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var AuthorizationService */
    private $authorizationService;

    public function __construct(EventDispatcherInterface $eventDispatcher, AuthorizationService $authorizationService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->authorizationService = $authorizationService;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($operation instanceof Post) {
            if ($data instanceof ProxyData) {
                if (Tools::isNullOrEmpty($data->getNamespace())) {
                    throw new BadRequestException('parameter namespace must not be null nor empty');
                } elseif (Tools::isNullOrEmpty($data->getFunctionName())) {
                    throw new BadRequestException('parameter functionName must not be null nor empty');
                } else {
                    $this->authorizationService->denyAccessUnlessIsGrantedResourcePermission(Configuration::MAY_POST_PROXYDATA, $data);

                    $proxyDataEvent = new ProxyDataEvent($data);
                    $this->eventDispatcher->dispatch($proxyDataEvent, ProxyDataEvent::class.'.'.$data->getNamespace());

                    if ($proxyDataEvent->wasAcknowledged() === false) {
                        throw new BadRequestException(sprintf('unknown namespace "%s"', $data->getNamespace()));
                    }
                }

                $data->setIdentifier($data->getNamespace().'::'.$data->getFunctionName());
            }

            return $data;
        } elseif ($operation instanceof Delete) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $this->authorizationService->denyAccessUnlessIsGrantedResourcePermission(Configuration::MAY_POST_PROXYDATA, $data);

            return null;
        }

        return null;
    }
}
