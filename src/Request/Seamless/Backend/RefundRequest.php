<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

class RefundRequest extends AbstractBackendRequest
{
    protected $operation = 'refund';
    protected $requiredParameters = ['orderNumber', 'amount', 'currency'];
    protected $fingerprintOrder = ['orderNumber', 'amount', 'currency'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    static function withOrderNumberAmountAndCurrency($orderNumber, $amount, $currency)
    {
        return (new static())
            ->setOrderNumber($orderNumber)
            ->setAmount($amount)
            ->setCurrency($currency)
            ;
    }

    /**
     * ID of the order
     *
     * @param string $orderNumber
     * @return RefundRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }

    function setAmount($amount)
    {
        return $this->addParam('amount', $amount);
    }

    function setCurrency($currency)
    {
        return $this->addParam('currency', $currency);
    }
}
