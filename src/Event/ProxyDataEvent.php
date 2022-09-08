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

    public function __construct(ProxyData $proxyData)
    {
        $this->proxyData = $proxyData;
    }

    public function getProxyData(): ProxyData
    {
        return $this->proxyData;
    }
}
