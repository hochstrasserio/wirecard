<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

class DepositReversalRequest extends AbstractBackendRequest
{
    protected $operation = 'depositReversal';
    protected $requiredParameters = ['orderNumber', 'paymentNumber'];
    protected $fingerprintOrder = ['orderNumber', 'paymentNumber'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    static function withOrderNumberAndPaymentNumber($orderNumber, $paymentNumber)
    {
        return (new static())
            ->setOrderNumber($orderNumber)
            ->setPaymentNumber($paymentNumber)
            ;
    }

    /**
     * ID of the order
     *
     * @param string $orderNumber
     * @return DepositReversalRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }

    function setPaymentNumber($paymentNumber)
    {
        return $this->addParam('paymentNumber', $paymentNumber);
    }
}
