<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

class InitDataStorageRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $client = $this->getClient();
        $request = InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
            ->setContext($this->getContext());

        $response = $request->createResponse($client->send($request->createHttpRequest()));

        $this->assertNotNull($response->toObject());

        $this->assertArrayHasKey('storageId', $response->toArray());
        $this->assertArrayHasKey('javascriptUrl', $response->toArray());
    }
}
