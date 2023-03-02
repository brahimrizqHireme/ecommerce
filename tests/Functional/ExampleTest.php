<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\FunctionalTestCase;

/**
 * Class ExampleTest
 *
 * @package App\Tests\Functional
 */
class ExampleTest extends FunctionalTestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/en');
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
