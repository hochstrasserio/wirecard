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

        $response = $client->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        ));
        $this->assertNotNull($response->toObject());

        $storageId = $response->toObject()->getStorageId();

        $response = $client
            ->send(ReadDataStorageRequest::withStorageId($storageId));

        $this->assertNotNull($response->toObject());

        $this->assertEmpty($response->getErrors());
        $this->assertNotEmpty($response->toObject()->getStorageId());
        $this->assertCount(0, $response->toObject()->getPaymentInformation());
    }
}
