<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7;

class InitPaymentRequest extends AbstractFrontendRequest
{
    protected $requiredParameters = [
        'language', 'paymentType', 'amount', 'currency', 'orderDescription',
        'successUrl', 'cancelUrl', 'failureUrl', 'serviceUrl', 'requestFingerprintOrder',
        'confirmUrl', 'consumerIpAddress', 'consumerUserAgent',
    ];

    protected $endpoint = 'https://checkout.wirecard.com/seamless/frontend/init';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\InitPaymentResult';

    static function with()
    {
        return new static();
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
        foreach ($info as $param => $value) {
            $this->addParam($param, $value);
        }

        return $this;
    }

    function setConsumerBillingInformation(BillingInformation $info)
    {
        foreach ($info as $param => $value) {
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

    function createHttpRequest()
    {
        $headers = [
            'User-Agent' => $this->getContext()->getUserAgent(),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $params = $this->getParameterBag();
        $params->put('customerId', $this->getContext()->getCustomerId());

        if ($this->getContext()->getShopId()) {
            $params->put('shopId', $this->getContext()->getShopId());
        }

        $params->put('language', $this->getContext()->getLanguage());

        $requestFingerprintOrder = array_merge(array_keys($params->all()), ['requestFingerprintOrder', 'secret']);
        $params->set('requestFingerprintOrder', join(',', $requestFingerprintOrder));

        $params->set('requestFingerprint', Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder($requestFingerprintOrder)
        );

        $params->validate(array_merge(['customerId', 'requestFingerprint'], $this->requiredParameters));

        $body = Psr7\build_query($params->all());

        $httpRequest = new Request(
            'POST',
            $this->endpoint,
            $headers,
            $body
        );

        return $httpRequest;
    }
}
