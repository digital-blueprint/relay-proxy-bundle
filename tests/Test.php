<?php

declare(strict_types=1);

namespace Dbp\Relay\ProxyBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class Test extends KernelTestCase
{
    public function testBasics()
    {
        $this->assertNotNull($this->getContainer());
    }
}
