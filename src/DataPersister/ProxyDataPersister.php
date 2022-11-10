<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Dbp\Relay\CoreBundle\Helpers\Tools;
use Dbp\Relay\CoreBundle\ProxyApi\ProxyDataEvent;
use Dbp\Relay\ProxyBundle\Authorization\AuthorizationService;
use Dbp\Relay\ProxyBundle\Entity\ProxyData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProxyDataPersister extends AbstractController implements ContextAwareDataPersisterInterface
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

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ProxyData;
    }

    /**
     * @param mixed $data
     */
    public function persist($data, array $context = []): ProxyData
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($data instanceof ProxyData) {
            if (Tools::isNullOrEmpty($data->getNamespace())) {
                throw new BadRequestException('parameter namespace must not be null nor empty');
            } elseif (Tools::isNullOrEmpty($data->getFunctionName())) {
                throw new BadRequestException('parameter functionName must not be null nor empty');
            } else {
                $this->authorizationService->denyAccessUnlessIsGranted('CALL_FUNCTION', $data);

                $proxyDataEvent = new ProxyDataEvent($data);
                $this->eventDispatcher->dispatch($proxyDataEvent, ProxyDataEvent::NAME.'.'.$data->getNamespace());

                if ($proxyDataEvent->wasAcknowledged() === false) {
                    throw new BadRequestException(sprintf('unknown namespace "%s"', $data->getNamespace()));
                }
            }

            $data->setIdentifier($data->getNamespace().'::'.$data->getFunctionName());
        }

        return $data;
    }

    public function remove($data, array $context = []): void
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SCOPE_API-PROXY');
    }
}
