<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;

class InitPaymentRequestTest extends AbstractWirecardTest
{
    public function test()
    {
        $client = $this->getClient();

        $response = $client->send(
            InitPaymentRequest::with()
            ->addParam('paymentType', PaymentType::PayPal)
            ->addParam('amount', 12.01)
            ->addParam('currency', 'EUR')
            ->addParam('orderDescription', 'Some test order')
            ->addParam('successUrl', 'http://www.example.com')
            ->addParam('failureUrl', 'http://www.example.com')
            ->addParam('cancelUrl', 'http://www.example.com')
            ->addParam('serviceUrl', 'http://www.example.com')
            ->addParam('confirmUrl', 'http://www.example.com')
            ->addParam('consumerIpAddress', '127.0.0.1')
            ->addParam('consumerUserAgent', 'Mozilla')
        );

        $this->assertArrayHasKey('redirectUrl', $response->toArray());
    }
}
