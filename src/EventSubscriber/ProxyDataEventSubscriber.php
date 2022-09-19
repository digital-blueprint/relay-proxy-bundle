<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\EventSubscriber;

use Dbp\Relay\ProxyBundle\Event\ProxyDataEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class ProxyDataEventSubscriber implements EventSubscriberInterface
{
    protected const NAMESPACE = '';

    public static function getSubscribedEvents(): array
    {
        return [
            ProxyDataEvent::NAME.static::NAMESPACE => 'onProxyDataEvent',
        ];
    }

    /**
     * @throws BadRequestException
     */
    public function onProxyDataEvent(ProxyDataEvent $event): void
    {
        $event->setHandled();
        $proxyData = $event->getProxyData();
        $functionName = $proxyData->getFunctionName();
        $arguments = $proxyData->getArguments();
        $returnValue = null;

        if ($this->isFunctionDefined($functionName) === false) {
            throw new BadRequestException(sprintf('unknown function "%s" under namespace "%s"', $functionName, static::NAMESPACE));
        } elseif ($this->areAllRequiredArgumentsDefined($arguments) === false) {
            throw new BadRequestException(sprintf('incomplete argument list for function "%s" under namespace "%s"', $functionName, static::NAMESPACE));
        }

        try {
            $returnValue = $this->callFunction($functionName, $arguments);
        } catch (Exception $exception) {
            $proxyData->setErrorsFromException($exception);
        }

        $proxyData->setData($returnValue);
    }

    abstract protected function isFunctionDefined(string $functionName): bool;

    abstract protected function areAllRequiredArgumentsDefined(array $arguments): bool;

    abstract protected function callFunction(string $functionName, array $arguments);
}
