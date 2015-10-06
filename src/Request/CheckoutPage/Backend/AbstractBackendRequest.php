<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage\Backend;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Hochstrasser\Wirecard\Response\WirecardCheckoutPageBackendResponse;
use Hochstrasser\Wirecard\Fingerprint;

abstract class AbstractBackendRequest extends AbstractWirecardRequest
{
    protected $operation;

    function getEndpoint()
    {
        return 'https://checkout.wirecard.com/page/toolkit.php';
    }

    function createResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        return WirecardCheckoutPageBackendResponse::fromHttpResponse(
            $response,
            $this->resultClass
        );
    }

    protected function getRawParameters()
    {
        $params = parent::getRawParameters();

        if (empty($params['language'])) {
            $params['language'] = $this->getContext()->getLanguage();
        }

        if (empty($params['toolkitPassword'])) {
            $params['toolkitPassword'] = $this->getContext()->getBackendPassword();
        }

        if (empty($params['command'])) {
            $params['command'] = $this->operation;
        }

        return $params;
    }

    function getRequestParameters()
    {
        $params = $this->getRawParameters();

        $params['requestFingerprint'] = Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder(array_merge(['customerId', 'shopId', 'password', 'secret', 'language'], $this->fingerprintOrder));

        $this->assertParametersAreValid($params, array_merge(['customerId', 'requestFingerprint', 'password', 'language'], $this->requiredParameters));

        return $params;
    }
}
