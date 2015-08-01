<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Model\Common\Basket;
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
