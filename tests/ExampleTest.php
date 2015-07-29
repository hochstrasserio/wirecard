<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

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

        $request = (new InitDataStorageRequest)
            ->setReturnUrl('http://www.example.com')
            ->setOrderIdent(1234)
            ;

        $response = $client->execute($request);

        $this->assertFalse($response->hasErrors());
        $params = $response->toArray();

        $this->assertArrayHasKey('storageId', $params);
        $this->assertArrayHasKey('javascriptUrl', $params);

        $model = $response->toObject();

        $this->assertNotEmpty($model->getStorageId());
        $this->assertNotEmpty($model->getJavascriptUrl());
    }

    public function testWrongSecret()
    {
        $context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2a', 'de', 'qmore');
        $client = new Client($context, Adapter::defaultAdapter());

        $request = (new InitDataStorageRequest)
            ->setReturnUrl('http://www.example.com')
            ->setOrderIdent(1234)
            ;

        $response = $client->execute($request);

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getErrors());
    }
}
