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

    /**
     * Unique reference to the data storage of a consumer
     *
     * @return string
     */
    function getStorageId()
    {
        return $this->getParam('storageId');
    }

    /**
     * Array of PaymentInformation objects, representing the sanitized data which was
     * stored in the data storage by the user
     *
     * @return array
     */
    function getPaymentInformation()
    {
        return $this->getParam('paymentInformation');
    }
}
