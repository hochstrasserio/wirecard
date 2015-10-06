<?php

namespace Hochstrasser\Wirecard\Test\Request\CheckoutPage\Backend;

use Hochstrasser\Wirecard\Request\Seamless\Backend\GetOrderDetailsRequest;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class GetOrderDetailsRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $request = new GetOrderDetailsRequest;
        $request->setOrderNumber('2726917');
        $request->setContext($this->getContext());

        $response = $request->createResponse($this->getClient()->send($request->createHttpRequest()));
        var_dump($response->toArray());
        $this->assertNotEmpty($response->toArray());
        $this->assertFalse($response->hasErrors());
    }
}
