<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Dbp\Relay\ProxyBundle\Entity\ProxyData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProxyDataCollectionDataProvider extends AbstractController implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ProxyData::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return [];
    }
}
