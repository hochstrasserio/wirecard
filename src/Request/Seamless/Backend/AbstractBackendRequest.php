<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Backend;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;
use Hochstrasser\Wirecard\Fingerprint;

abstract class AbstractBackendRequest extends AbstractWirecardRequest
{
    protected $operation;

    function getEndpoint()
    {
        return 'https://checkout.wirecard.com/seamless/backend/'.$this->operation;
    }

    protected function getRawParameters()
    {
        $params = parent::getRawParameters();

        if (empty($params['language'])) {
            $params['language'] = $this->getContext()->getLanguage();
        }

        if (empty($params['password'])) {
            $params['password'] = $this->getContext()->getBackendPassword();
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
