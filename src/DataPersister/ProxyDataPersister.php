<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Dbp\Relay\ProxyBundle\Entity\ProxyData;
use Dbp\Relay\ProxyBundle\Event\ProxyDataEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

        $proxyDataEvent = new ProxyDataEvent($data);
        $this->eventDispatcher->dispatch($proxyDataEvent, ProxyDataEvent::NAME.$data->getNamespace());
        $data->setIdentifier($data->getNamespace().'::'.$data->getFunctionName());

        return $data;
    }

    public function remove($data, array $context = []): void
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    }
}
