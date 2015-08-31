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
}
