<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class Test extends ApiTestCase
{
    public function testBasics()
    {
        $this->assertNotNull(self::createClient());
    }
}
