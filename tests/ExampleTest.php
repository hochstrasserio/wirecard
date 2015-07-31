<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    private function getClient()
    {
        $context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de', 'qmore');
        $client = new Client($context, Adapter::defaultAdapter());

        return $client;
    }

    public function test()
    {
        $client = $this->getClient();

        $response = $client->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        ));

        $params = $response->toArray();

        $this->assertEmpty($response->getErrors());
        $this->assertArrayHasKey('storageId', $params);
        $this->assertArrayHasKey('javascriptUrl', $params);

        $model = $response->toObject();
        $this->assertNotNull($model);

        $this->assertNotEmpty($model->getStorageId());
        $this->assertNotEmpty($model->getJavascriptUrl());
    }

    public function testWrongSecret()
    {
        $context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2a', 'de', 'qmore');
        $client = new Client($context, Adapter::defaultAdapter());

        $response = $client->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        ));

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getErrors());
    }

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
