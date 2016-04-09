<?php

namespace Hochstrasser\Wirecard\Test\Helper;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Helper\WirecardHelper;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

use Psr\Http\Message\RequestInterface;

class WirecardHelperTest extends AbstractWirecardTest
{
    function test()
    {
        $guzzle = $this->getClient();

        $helper = new WirecardHelper($this->getContext(), function (RequestInterface $request) use ($guzzle) {
            return $guzzle->send($request);
        });

        $response = $helper->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
            1234, 'http://www.example.com'
        ));

        $this->assertFalse($response->hasErrors());
    }
}
