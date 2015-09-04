<?php

namespace Hochstrasser\Wirecard\Test\Request\Seamless\Backend;

use Hochstrasser\Wirecard\Request\Seamless\Backend\DepositRequest;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class DepositRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $request = new DepositRequest;
        $request->setOrderNumber('2726917');
        $request->setAmount('10.00');
        $request->setCurrency('EUR');
        $request->setContext($this->getContext());

        $response = $request->createResponse($this->getClient()->send($request->createHttpRequest()));

        $this->assertNotEmpty($response->toArray());
        $this->assertFalse($response->hasErrors());
    }
}
