<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

use Hochstrasser\Wirecard\Model\Common\FundTransferType;

class TransferFundRequest extends AbstractBackendRequest
{
    protected $operation = 'transferFund';
    protected $requiredParameters = ['amount', 'currency', 'fundTransferType', 'orderDescription'];
    protected $fingerprintOrder = ['orderNumber', 'creditNumber', 'orderDescription',
        'amount', 'currency', 'orderReference', 'customerStatement', 'fundTransferType'];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    function getRequestParameters()
    {
        $params = $this->getRawParameters();

        $fingerprintOrder = array_merge(
            ['customerId', 'shopId', 'password', 'secret', 'language'],
            $this->fingerprintOrder
        );

        $requiredParameters = array_merge(
            ['customerId', 'requestFingerprint', 'password', 'language'],
            $this->requiredParameters
        );

        switch ($this->getParam('fundTransferType')) {
        case FundTransferType::EXISTINGORDER:
            $fingerprintOrder[] = 'sourceOrderNumber';
            $requiredParameters[] = 'sourceOrderNumber';
            break;
        case FundTransferType::MONETA:
            $fingerprintOrder[] = 'consumerWalletId';
            $requiredParameters[] = 'consumerWalletId';
            break;
        case FundTransferType::SEPA_CT:
            $fingerprintOrder[] = 'bankAccountOwner';
            $fingerprintOrder[] = 'bankBic';
            $fingerprintOrder[] = 'bankAccountIban';
            $requiredParameters[] = 'bankAccountOwner';
            $requiredParameters[] = 'bankBic';
            $requiredParameters[] = 'bankAccountIban';
            break;
        case FundTransferType::SKRILLWALLET:
            $fingerprintOrder[] = 'consumerEmail';
            $requiredParameters[] = 'consumerEmail';
            $requiredParameters[] = 'customerStatement';
            break;
        }

        $params['requestFingerprint'] = Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder($fingerprintOrder);
        $this->assertParametersAreValid($params, $requiredParameters);

        return $params;
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

    function setCreditNumber($creditNumber)
    {
        return $this->addParam('creditNumber', $creditNumber);
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

    function setFundTransferType($fundTransferType)
    {
        return $this->addParam('fundTransferType', $fundTransferType);
    }

    function setConsumerEmail($consumerEmail)
    {
        return $this->addParam('consumerEmail', $consumerEmail);
    }

    function setBankBic($bankBic)
    {
        return $this->addParam('bankBic', $bankBic);
    }

    function setBankAccountOwner($bankAccountOwner)
    {
        return $this->addParam('bankAccountOwner', $bankAccountOwner);
    }

    function setBankAccountIban($bankAccountIban)
    {
        return $this->addParam('bankAccountIban', $bankAccountIban);
    }

    function setConsumerWalletId($consumerWalletId)
    {
        return $this->addParam('consumerWalletId', $consumerWalletId);
    }

    function setSourceOrderNumber($sourceOrderNumber)
    {
        return $this->addParam('sourceOrderNumber', $sourceOrderNumber);
    }
}
