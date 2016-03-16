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
    private $parameters;
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

    /**
     * {@inheritDoc}
     */
    function setContext(Context $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    function addParam($param, $value)
    {
        $this->parameters[$param] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    function getParam($param)
    {
        if (!array_key_exists($param, $this->parameters)) {
            return;
        }

        return $this->parameters[$param];
    }

    /**
     * Returns the request parameters without calculated fingerprint
     *
     * Override this method in your subclasses to modify parameters before they are
     * passed to the PSR-7 request message.
     */
    protected function getRawParameters()
    {
        $params = $this->parameters;
        $context = $this->getContext();

        if (empty($params['customerId'])) {
            $params['customerId'] = $context->getCustomerId();
        }

        if ($context->getShopId() and empty($params['shopId'])) {
            $params['shopId'] = $context->getShopId();
        }

        return $params;
    }

    /**
     * {@inheritDoc}
     */
    function getRequestParameters()
    {
        $params = $this->getRawParameters();

        $params['requestFingerprint'] = Fingerprint::fromParameters($params)
            ->setContext($this->getContext())
            ->setFingerprintOrder(array_merge(['customerId', 'shopId'], $this->fingerprintOrder, ['secret']));

        $this->assertParametersAreValid($params, array_merge(['customerId', 'requestFingerprint'], $this->requiredParameters));

        return $params;
    }

    /**
     * Validates that all required parameters are set, otherwise throws an exception
     *
     * @param array $parameters
     * @param array $requiredParameters List of required parameters
     * @throws RequiredParameterMissingException
     */
    protected function assertParametersAreValid(array $parameters, array $requiredParameters)
    {
        foreach ($requiredParameters as $parameter) {
            if (empty($parameters[$parameter])) {
                throw RequiredParameterMissingException::withParameter($parameter);
            }
        }
    }

    function serialize()
    {
        return serialize([
            'context' => $this->context,
            'parameters' => $this->parameters
        ]);
    }

    function unserialize($data)
    {
        $data = unserialize($data);

        $this->context = $data['context'];
        $this->parameters = $data['parameters'];
    }
}
