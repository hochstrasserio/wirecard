<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

class RefundReversalRequest extends AbstractBackendRequest
{
    protected $operation = 'refundReversal';
    protected $requiredParameters = ['orderNumber', 'creditNumber'];
    protected $fingerprintOrder = ['orderNumber', 'creditNumber'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    static function withOrderNumberAndCreditNumber($orderNumber, $creditNumber)
    {
        return (new static())
            ->setOrderNumber($orderNumber)
            ->setCreditNumber($creditNumber)
        ;
    }

    /**
     * ID of the order
     *
     * @param string $orderNumber
     * @return RefundReversalRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }

    /**
     * ID of the credit note created by the RefundRequest
     *
     * @param string $creditNumber
     * @return RefundReversalRequest
     */
    function setCreditNumber($creditNumber)
    {
        return $this->addParam('creditNumber', $creditNumber);
    }
}
