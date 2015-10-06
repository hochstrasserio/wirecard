<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage\Backend;

class RecurPaymentRequest extends AbstractBackendRequest
{
    protected $operation = 'recurPayment';
    protected $requiredParameters = ['amount', 'currency', 'orderDescription', 'sourceOrderNumber'];
    protected $fingerprintOrder = [
        'orderNumber', 'sourceOrderNumber', 'autoDeposit', 'orderDescription',
        'amount', 'currency', 'orderReference', 'customerStatement',
    ];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    /**
     * ID of the order
     *
     * @param string $orderNumber
     * @return RecurPaymentRequest
     */
    function setOrderNumber($orderNumber)
    {
        return $this->addParam('orderNumber', $orderNumber);
    }

    function setSourceOrderNumber($sourceOrderNumber)
    {
        return $this->addParam('sourceOrderNumber', $sourceOrderNumber);
    }

    function setAutoDeposit($autoDeposit)
    {
        return $this->addParam('autoDeposit', $autoDeposit ? 'Yes' : 'No');
    }

    function setOrderDescription($orderDescription)
    {
        return $this->addParam('orderDescription', $orderDescription);
    }

    function setOrderReference($orderReference)
    {
        return $this->addParam('orderReference', $orderReference);
    }

    function setCustomerStatement($customerStatement)
    {
        return $this->addParam('customerStatement', $customerStatement);
    }

    function setAmount($amount)
    {
        return $this->addParam('amount', $amount);
    }

    function setCurrency($currency)
    {
        return $this->addParam('currency', $currency);
    }

    function setTransactionIdentifier($transactionIdentifier)
    {
        return $this->addParam('transactionIdentifier', $transactionIdentifier);
    }

    function setMandateId($mandateId)
    {
        return $this->addParam('mandateId', $mandateId);
    }

    function setMandateSignatureDate(\DateTime $mandateSignatureDate)
    {
        return $this->addParam('mandateSignatureDate', $mandateSignatureDate->format('d.m.Y'));
    }

    function setCreditorId($creditorId)
    {
        return $this->addParam('creditorId', $creditorId);
    }

    function setDueDate(\DateTime $dueDate)
    {
        return $this->addParam('dueDate', $dueDate->format('d.m.Y'));
    }

    function setUseIbanBic($useIbanBic)
    {
        return $this->addParam('useIbanBic', $useIbanBic ? 'Yes' : 'No');
    }
}
