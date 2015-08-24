<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage;

use Hochstrasser\Wirecard\Request\AbstractPaymentRequest;

class InitCheckoutPageRequest extends AbstractPaymentRequest
{
    protected $endpoint = 'https://checkout.wirecard.com/page/init.php';

    function createHttpRequest()
    {
        throw new \BadMethodCallException("Request can't be sent. Use a form instead to send this request");
    }
}
