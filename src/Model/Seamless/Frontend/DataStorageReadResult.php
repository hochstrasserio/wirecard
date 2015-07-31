<?php

namespace Hochstrasser\Wirecard\Model\Seamless\Frontend;

use Hochstrasser\Wirecard\Model\Model;

class DataStorageReadResult extends Model
{
    function __construct(array $parameters)
    {
        parent::__construct($parameters);

        $this->addParam('paymentInformation', array_map(
            function ($parameters) {
                return new PaymentInformation($parameters);
            },
            $this->getParam('paymentInformation') ?: []
        ));
    }

    function getStorageId()
    {
        return $this->getParam('storageId');
    }

    function getPaymentInformation()
    {
        return $this->getParam('paymentInformation');
    }
}
