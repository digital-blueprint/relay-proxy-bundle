<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Dbp\Relay\CoreBundle\Helpers\Tools;
use Dbp\Relay\ProxyBundle\Entity\ProxyData;
use Dbp\Relay\ProxyBundle\Event\ProxyDataEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProxyDataPersister extends AbstractController implements ContextAwareDataPersisterInterface
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
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
        $this->denyAccessUnlessGranted('ROLE_SCOPE_API-PROXY');

        if (Tools::isNullOrEmpty($data->getNamespace())) {
            throw new BadRequestException('parameter namespace must not be null nor empty');
        } elseif (Tools::isNullOrEmpty($data->getFunctionName())) {
            throw new BadRequestException('parameter functionName must not be null nor empty');
        } else {
            $proxyDataEvent = new ProxyDataEvent($data);
            $this->eventDispatcher->dispatch($proxyDataEvent, ProxyDataEvent::NAME.$data->getNamespace());

            if ($proxyDataEvent->wasHandled() === false) {
                throw new BadRequestException(sprintf('unknown namespace "%s"', $data->getNamespace()));
            }
        }

        $data->setIdentifier($data->getNamespace().'::'.$data->getFunctionName());

        return $data;
    }

    public function remove($data, array $context = []): void
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SCOPE_API-PROXY');
    }
}
