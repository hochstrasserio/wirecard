<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage;

use Hochstrasser\Wirecard\Request\AbstractPaymentRequest;

class InitCheckoutPageRequest extends AbstractPaymentRequest
{
    protected $endpoint = 'https://checkout.wirecard.com/page/init.php';
}
