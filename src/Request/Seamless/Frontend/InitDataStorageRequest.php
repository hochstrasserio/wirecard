<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7 as psr;

class InitDataStorageRequest extends AbstractWirecardRequest
{
    /**
     * Unique reference to the order of your consumer
     *
     * @param string $orderIdent
     * @return InitDataStorageRequest
     */
    function setOrderIdent($orderIdent)
    {
        return $this->addParam('orderIdent', $orderIdent);
    }

    /**
     * Return URL for outdated browsers
     *
     * @param string $returnUrl
     * @return InitDataStorageRequest
     */
    function setReturnUrl($returnUrl)
    {
        return $this->addParam('returnUrl', $returnUrl);
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

        if ($this->getContext()->getJavascriptScriptVersion()) {
            $params->put('javascriptScriptVersion', $this->getContext()->getJavascriptScriptVersion());
        }

        $params->set('requestFingerprint', Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder(['customerId', 'shopId', 'orderIdent', 'returnUrl', 'language', 'javascriptScriptVersion'])
        );

        $params->validate(['customerId', 'language', 'orderIdent', 'returnUrl', 'requestFingerprint']);

        $body = psr\build_query($params->all());

        $httpRequest = new Request(
            'POST',
            'https://checkout.wirecard.com/seamless/dataStorage/init',
            $headers,
            $body
        );

        return $httpRequest;
    }

    function createResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        return WirecardResponse::fromHttpResponse($response, 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult');
    }
}
