<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;

class AbstractPaymentRequest extends AbstractWirecardRequest
{
    /**
     * Transaction identifier for a single, non-recurring transaction
     */
    const TRANSACTION_SINGLE = 'SINGLE';

    /**
     * Transaction identifier for the first transaction in a series of recurring
     * transactions
     */
    const TRANSACTION_INITIAL = 'INITIAL';

    protected $requiredParameters = [
        'language', 'amount', 'currency', 'orderDescription',
        'successUrl', 'requestFingerprintOrder', 'cancelUrl', 'failureUrl', 'serviceUrl'
    ];

    protected $endpoint = 'https://checkout.wirecard.com/seamless/frontend/init';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult';

    static function with()
    {
        return new static();
    }

    static function withBasket(Basket $basket)
    {
        return static::with()
            ->setBasket($basket)
            ->setAmount($basket->getAmount())
            ->setCurrency($basket->getCurrency());
    }

    function setPaymentType($value)
    {
        return $this->addParam('paymentType', $value);
    }

    function setAmount($value)
    {
        return $this->addParam('amount', $value);
    }

    function setCurrency($value)
    {
        return $this->addParam('currency', $value);
    }

    function setOrderDescription($value)
    {
        return $this->addParam('orderDescription', $value);
    }

    function setSuccessUrl($value)
    {
        return $this->addParam('successUrl', $value);
    }

    function setCancelUrl($value)
    {
        return $this->addParam('cancelUrl', $value);
    }

    function setFailureUrl($value)
    {
        return $this->addParam('failureUrl', $value);
    }

    function setServiceUrl($value)
    {
        return $this->addParam('serviceUrl', $value);
    }

    function setConfirmUrl($value)
    {
        return $this->addParam('confirmUrl', $value);
    }

    function setConsumerIpAddress($value)
    {
        return $this->addParam('consumerIpAddress', $value);
    }

    function setConsumerUserAgent($value)
    {
        return $this->addParam('consumerUserAgent', $value);
    }

    function setPendingUrl($value)
    {
        return $this->addParam('pendingUrl', $value);
    }

    function setNoScriptInfoUrl($value)
    {
        return $this->addParam('noScriptInfoUrl', $value);
    }

    function setOrderNumber($value)
    {
        return $this->addParam('orderNumber', $value);
    }

    function setWindowName($value)
    {
        return $this->addParam('windowName', $value);
    }

    function setDuplicateRequestCheck($value)
    {
        return $this->addParam('duplicateRequestCheck', $value ? 'yes' : 'no');
    }

    function setCustomerStatement($value)
    {
        return $this->addParam('customerStatement', $value);
    }

    function setOrderReference($value)
    {
        return $this->addParam('orderReference', $value);
    }

    function setTransactionIdentifier($value)
    {
        return $this->addParam('transactionIdentifier', $value);
    }

    function setFinancialInstitution($value)
    {
        return $this->addParam('financialInstitution', $value);
    }

    function setOrderIdent($value)
    {
        return $this->addParam('orderIdent', $value);
    }

    function setStorageId($value)
    {
        return $this->addParam('storageId', $value);
    }

    function setAutoDeposit($value)
    {
        return $this->addParam('autoDeposit', $value ? 'yes' : 'no');
    }

    function setConfirmMail($value)
    {
        return $this->addParam('confirmMail', $value);
    }

    function setRiskSuppress($value)
    {
        return $this->addParam('riskSuppress', $value ? 1 : 0);
    }

    function setRiskConfigAlias($value)
    {
        return $this->addParam('riskConfigAlias', $value);
    }

    function setConsumerShippingInformation(ShippingInformation $info)
    {
        foreach ($info->toArray() as $param => $value) {
            $this->addParam($param, $value);
        }

        return $this;
    }

    function setConsumerBillingInformation(BillingInformation $info)
    {
        foreach ($info->toArray() as $param => $value) {
            $this->addParam($param, $value);
        }

        return $this;
    }

    /**
     * Adds the basket to the payment, required for some payment methods
     *
     * @param Basket $basket
     * @return InitPaymentRequest
     */
    function setBasket(Basket $basket)
    {
        foreach ($basket->toArray() as $param => $value) {
            $this->addParam($param, $value);
        }

        return $this;
    }

    protected function getRawParameters()
    {
        $params = parent::getRawParameters();

        if (empty($params['language'])) {
            $params['language'] = $this->getContext()->getLanguage();
        }

        return $params;
    }

    function getRequestParameters()
    {
        $params = $this->getRawParameters();

        $requestFingerprintOrder = array_merge(array_keys($params), ['requestFingerprintOrder', 'secret']);
        $params['requestFingerprintOrder'] = join(',', $requestFingerprintOrder);

        $params['requestFingerprint'] = Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder($requestFingerprintOrder);

        $this->assertParametersAreValid($params, array_merge(['customerId', 'requestFingerprint'], $this->requiredParameters));

        return $params;
    }
}
