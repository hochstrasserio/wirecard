<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage\Backend;

class GetFinancialInstitutionsRequest extends AbstractBackendRequest
{
    protected $operation = 'getFinancialInstitutions';
    protected $requiredParameters = ['paymentType'];
    protected $fingerprintOrder = ['paymentType', 'transactionType', 'bankCountry'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    function setPaymentType($paymentType)
    {
        return $this->addParam('paymentType', $paymentType);
    }

    function setTransactionType($transactionType)
    {
        return $this->addParam('transactionType', $transactionType);
    }

    function setBankCountry($bankCountry)
    {
        return $this->addParam('bankCountry', $bankCountry);
    }
}
