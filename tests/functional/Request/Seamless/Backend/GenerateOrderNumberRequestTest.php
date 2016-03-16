<?php

namespace Hochstrasser\Wirecard\Test\Request\Seamless\Backend;

use Hochstrasser\Wirecard\Request\Seamless\Backend\GenerateOrderNumberRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class GenerateOrderNumberRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $request = new GenerateOrderNumberRequest;
        $request->setContext($this->getContext());

        $response = $request->createResponse($this->getClient()->send($request->createHttpRequest()));

        $this->markTestIncomplete();
    }
}
