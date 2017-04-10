<?php

namespace Hochstrasser\Wirecard\Test\Request\CheckoutPage;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\CheckoutPage\InitCheckoutPageRequest;

class InitCheckoutPageRequestTest extends AbstractWirecardTest
{
    /**
     * @expectedException \BadMethodCallException
     */
    function testCannotBeSentByClient()
    {
        $this->getClient()->send(InitCheckoutPageRequest::with()
            ->setAmount(10.00)
            ->setCurrency('EUR')
            ->setSuccessUrl('http://localhost')
            ->setCancelUrl('http://localhost')
            ->setFailureUrl('http://localhost')
            ->setServiceUrl('http://localhost')->createHttpRequest());
    }

    function testSetEndpoint()
    {
        $checkoutRequest = InitCheckoutPageRequest::with()
            ->setAmount(10.00)
            ->setCurrency('EUR')
            ->setSuccessUrl('http://localhost')
            ->setCancelUrl('http://localhost')
            ->setFailureUrl('http://localhost')
            ->setServiceUrl('http://localhost');

        $newEndpoint = "http://sandbox.wirecard.com";
        $this->assertFalse($newEndpoint == $checkoutRequest->getEndpoint());

        $checkoutRequest->setEndpoint($newEndpoint);
        $this->assertTrue($newEndpoint == $checkoutRequest->getEndpoint());
    }

    function testDuplicateRequestCheckSet()
    {
        $checkoutRequest = InitCheckoutPageRequest::with()
            ->setAmount(10.00)
            ->setCurrency('EUR')
            ->setSuccessUrl('http://localhost')
            ->setCancelUrl('http://localhost')
            ->setFailureUrl('http://localhost')
            ->setDuplicateRequestCheck(true);

        $this->assertTrue('yes' == $checkoutRequest->getParam('duplicateRequestCheck'));

        $checkoutRequestWithoutDupes = InitCheckoutPageRequest::with()
            ->setAmount(10.00)
            ->setCurrency('EUR')
            ->setSuccessUrl('http://localhost')
            ->setCancelUrl('http://localhost')
            ->setFailureUrl('http://localhost')
            ->setDuplicateRequestCheck(false);
        $this->assertTrue(null == $checkoutRequestWithoutDupes->getParam('duplicateRequestCheck'));
    }
}
