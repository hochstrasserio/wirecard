<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

class ApproveReversalRequest extends AbstractBackendRequest
{
    protected $operation = 'approveReversal';
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
     * @param string $orderNumber
     * @return ApproveReversalRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }
}
