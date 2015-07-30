<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7 as psr;

class ReadDataStorageRequest extends AbstractWirecardRequest
{
    static function withStorageId($storageId)
    {
        $request = new static();
        $request->setStorageId($storageId);

        return $request;
    }

    function setStorageId($storageId)
    {
        return $this->addParam('storageId', $storageId);
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

        $params->set('requestFingerprint', Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder(['customerId', 'shopId', 'storageId'])
        );

        $params->validate(['customerId', 'storageId', 'requestFingerprint']);

        $body = psr\build_query($params->all());

        $httpRequest = new Request(
            'POST',
            'https://checkout.wirecard.com/seamless/dataStorage/read',
            $headers,
            $body
        );

        return $httpRequest;
    }

    function createResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        return WirecardResponse::fromHttpResponse($response, 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageReadResult');
    }
}
