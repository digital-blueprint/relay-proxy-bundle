<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\DataProvider;

use Dbp\Relay\CoreBundle\Exception\ApiError;
use Dbp\Relay\CoreBundle\Rest\AbstractDataProvider;
use Symfony\Component\HttpFoundation\Response;

class ProxyDataProvider extends AbstractDataProvider
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function isUserGrantedOperationAccess(int $operation): bool
    {
        return $this->isAuthenticated();
    }

    protected function getItemById($id, array $filters = [], array $options = []): object
    {
        throw ApiError::withDetails(Response::HTTP_NOT_IMPLEMENTED, 'GET operations not implemented for this resource');
    }

    protected function getPage(int $currentPageNumber, int $maxNumItemsPerPage, array $filters = [], array $options = []): array
    {
        return [];
    }
}
