<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage\Backend;

class GetOrderDetailsRequest extends AbstractBackendRequest
{
    protected $operation = 'getOrderDetails';
    protected $requiredParameters = ['orderNumber'];
    protected $fingerprintOrder = ['orderNumber'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    static function withOrderNumber($orderNumber)
    {
        return (new static())
            ->setOrderNumber($orderNumber)
            ;
    }

    /**
     * ID of the order
     *
     * @param string $orderIdent
     * @return InitDataStorageRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }
}
