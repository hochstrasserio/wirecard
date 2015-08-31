<?php

namespace Hochstrasser\Wirecard\Test\Request;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class RequestTest extends AbstractWirecardTest
{
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
