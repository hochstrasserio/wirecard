<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;

class ExampleTest extends AbstractWirecardTest
{
    public function testHasErrorWhenWrongSecretIsUsed()
    {
        $context = new Context(['customer_id' => 'D200001', 'secret' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2a', 'language' => 'de', 'shop_id' => 'qmore']);
        $client = new Client($context, Adapter::defaultAdapter());

        $response = $client->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        ));

        $this->assertTrue($response->hasErrors());
        $this->assertCount(1, $response->getErrors());
    }

    function testRequestIsSerializable()
    {
        $request = InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234,
            'http://www.example.com'
        );
        $request->setContext($this->getContext());

        $data = serialize($request);

        $this->assertNotEmpty($data);
    }
}
