<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Request\AbstractPaymentRequest;

class InitPaymentRequest extends AbstractPaymentRequest
{
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->requiredParameters = array_merge($this->requiredParameters, [
            'paymentType', 'confirmUrl',
            'consumerIpAddress', 'consumerUserAgent'
        ]);
    }
}
