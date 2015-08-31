<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;

class ReadDataStorageRequestTest extends AbstractWirecardTest
{
    public function testReadRequest()
    {
        $client = $this->getClient();
        $request = InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        );
        $request->setContext($this->getContext());

        $response = $request->createResponse($client->send($request->createHttpRequest()));
        $this->assertNotNull($response->toObject());

        $storageId = $response->toObject()->getStorageId();

        $request = ReadDataStorageRequest::withStorageId($storageId)
            ->setContext($this->getContext());

        $response = $request->createResponse($client->send($request->createHttpRequest()));

        $this->assertNotNull($response->toObject());

        $this->assertEmpty($response->getErrors());
        $this->assertNotEmpty($response->toObject()->getStorageId());
        $this->assertCount(0, $response->toObject()->getPaymentInformation());
    }
}
