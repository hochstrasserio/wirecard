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

    /**
     * @return static
     */
    static function with()
    {
        return new static();
    }

    /**
     * @param Basket $basket
     * @return static
     */
    static function withBasket(Basket $basket)
    {
        return static::with()
            ->setBasket($basket)
            ->setAmount($basket->getAmount())
            ->setCurrency($basket->getCurrency());
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setPaymentType($value)
    {
        return $this->addParam('paymentType', $value);
    }

    /**
     * @param string $value
     * @return AbstractPaymentRequest
     */
    function setAmount($value)
    {
        return $this->addParam('amount', $value);
    }

    /**
     * @param string $value
     * @return AbstractPaymentRequest
     */
    function setCurrency($value)
    {
        return $this->addParam('currency', $value);
    }

    /**
     * @param string $value
     * @return AbstractPaymentRequest
     */
    function setOrderDescription($value)
    {
        return $this->addParam('orderDescription', $value);
    }

    /**
     * @param string $value
     * @return AbstractPaymentRequest
     */
    function setSuccessUrl($value)
    {
        return $this->addParam('successUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setCancelUrl($value)
    {
        return $this->addParam('cancelUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setFailureUrl($value)
    {
        return $this->addParam('failureUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setServiceUrl($value)
    {
        return $this->addParam('serviceUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setConfirmUrl($value)
    {
        return $this->addParam('confirmUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setConsumerIpAddress($value)
    {
        return $this->addParam('consumerIpAddress', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setConsumerUserAgent($value)
    {
        return $this->addParam('consumerUserAgent', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setPendingUrl($value)
    {
        return $this->addParam('pendingUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setImageUrl($value)
    {
        return $this->addParam('imageUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setNoScriptInfoUrl($value)
    {
        return $this->addParam('noScriptInfoUrl', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setOrderNumber($value)
    {
        return $this->addParam('orderNumber', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setWindowName($value)
    {
        return $this->addParam('windowName', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setDuplicateRequestCheck($value)
    {
        return $this->addParam('duplicateRequestCheck', $value ? 'yes' : 'no');
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setCustomerStatement($value)
    {
        return $this->addParam('customerStatement', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setOrderReference($value)
    {
        return $this->addParam('orderReference', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setTransactionIdentifier($value)
    {
        return $this->addParam('transactionIdentifier', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setFinancialInstitution($value)
    {
        return $this->addParam('financialInstitution', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setOrderIdent($value)
    {
        return $this->addParam('orderIdent', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setStorageId($value)
    {
        return $this->addParam('storageId', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setAutoDeposit($value)
    {
        return $this->addParam('autoDeposit', $value ? 'yes' : 'no');
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setConfirmMail($value)
    {
        return $this->addParam('confirmMail', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setRiskSuppress($value)
    {
        return $this->addParam('riskSuppress', $value ? 'yes' : 'no');
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setRiskConfigAlias($value)
    {
        return $this->addParam('riskConfigAlias', $value);
    }

    /**
     * @return AbstractPaymentRequest
     */
    function setConsumerShippingInformation(ShippingInformation $info)
    {
        foreach ($info->toArray() as $param => $value) {
            $this->addParam($param, $value);
        }

        return $this;
    }

    /**
     * @return AbstractPaymentRequest
     */
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
