<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\Event;

use Dbp\Relay\ProxyBundle\Entity\ProxyData;
use Symfony\Contracts\EventDispatcher\Event;

class ProxyDataEvent extends Event
{
    public const NAME = 'dbp.relay.proxy_bundle.proxy_data';

    /** @var ProxyData */
    private $proxyData;

    /** @var bool */
    private $wasHandled;

    public function __construct(ProxyData $proxyData)
    {
        $this->proxyData = $proxyData;
        $this->wasHandled = false;
    }

    public function getProxyData(): ProxyData
    {
        return $this->proxyData;
    }

    /**
     * Indicate, that the event was handled, e.g. there was an event subscriber for the requested proxy data namespace.
     */
    public function setHandled(): void
    {
        $this->wasHandled = true;
    }

    /**
     * True, if the event was handled, e.g. there was an event subscriber for the requested proxy data namespace, false otherwise.
     */
    public function wasHandled(): bool
    {
        return $this->wasHandled;
    }
}
