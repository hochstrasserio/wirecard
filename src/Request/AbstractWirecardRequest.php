<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Request\ParameterBag;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Exception\RequiredParameterMissingException;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7 as psr;

abstract class AbstractWirecardRequest
    implements WirecardRequestInterface, \Serializable
{
    private $params;
    private $context;

    protected $requiredParameters = [];
    protected $fingerprintOrder = [];
    protected $endpoint = '';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\DefaultModel';

    function __construct(Context $context = null)
    {
        if (null !== $context) {
            $this->setContext($context);
        }

        $this->params = new ParameterBag;
    }

    function getEndpoint()
    {
        return $this->endpoint;
    }

    function createHttpRequest()
    {
        $headers = [
            'User-Agent' => $this->getContext()->getUserAgent(),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $body = psr\build_query($this->getRequestParameters());

        $httpRequest = new Request(
            'POST',
            $this->getEndpoint(),
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

    function setContext(Context $context)
    {
        $this->context = $context;
        return $this;
    }

    function getContext()
    {
        return $this->context;
    }

    function addParam($param, $value)
    {
        $this->getParameterBag()->set($param, $value);
        return $this;
    }

    function getParam($param)
    {
        return $this->getParameterBag()->get($param);
    }

    function getParameterBag()
    {
        return $this->params;
    }

    function getRawParameters()
    {
        $params = $this->getParameterBag()->all();
        $context = $this->getContext();

        if (empty($params['customerId'])) {
            $params['customerId'] = $context->getCustomerId();
        }

        if ($context->getShopId() and empty($params['shopId'])) {
            $params['shopId'] = $context->getShopId();
        }

        return $params;
    }

    function getRequestParameters()
    {
        $params = $this->getRawParameters();

        $params['requestFingerprint'] = Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder(array_merge(['customerId', 'shopId'], $this->fingerprintOrder, ['secret']));

        $this->assertParametersAreValid($params, array_merge(['customerId', 'requestFingerprint'], $this->requiredParameters));

        return $params;
    }

    protected function assertParametersAreValid(array $params, array $requiredParameters)
    {
        foreach ($requiredParameters as $parameter) {
            if (empty($params[$parameter])) {
                throw RequiredParameterMissingException::withParameter($parameter);
            }
        }
    }

    function serialize()
    {
        return serialize([
            'context' => $this->context,
            'params' => $this->params->all()
        ]);
    }

    function unserialize($data)
    {
        $data = unserialize($data);

        $this->context = $data['context'];
        $this->params = new ParameterBag($data['params']);
    }
}
