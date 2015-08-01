<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

class InitDataStorageRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $client = $this->getClient();

        $response = $client->send(
            InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
        );

        $this->assertNotNull($response->toObject());

        $this->assertArrayHasKey('storageId', $response->toArray());
        $this->assertArrayHasKey('javascriptUrl', $response->toArray());
    }
}
