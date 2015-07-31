<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use Hochstrasser\Wirecard\Fingerprint;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7 as psr;

abstract class AbstractFrontendRequest extends AbstractWirecardRequest
{
    protected $requiredParameters = [];
    protected $fingerprintOrder = [];
    protected $endpoint = '';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\DefaultModel';

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
            ->setFingerprintOrder(array_merge(['customerId', 'shopId'], $this->fingerprintOrder))
        );

        $params->validate(array_merge(['customerId', 'requestFingerprint'], $this->requiredParameters));

        $body = psr\build_query($params->all());

        $httpRequest = new Request(
            'POST',
            $this->endpoint,
            $headers,
            $body
        );

        return $httpRequest;
    }

    function createResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        return WirecardResponse::fromHttpResponse(
            $response,
            $this->resultClass
        );
    }
}
