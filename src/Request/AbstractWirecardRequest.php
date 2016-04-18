<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Response\WirecardResponse;
use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Exception\RequiredParameterMissingException;

abstract class AbstractWirecardRequest
    implements WirecardRequestInterface, \Serializable
{
    private $parameters;
    private $context;

    protected $requiredParameters = [];
    protected $fingerprintOrder = [];
    protected $endpoint = '';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\DefaultModel';

    /**
     * {@inheritDoc}
     */
    function __construct(Context $context = null)
    {
        if (null !== $context) {
            $this->setContext($context);
        }
    }

    /**
     * {@inheritDoc}
     */
    function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Converts the request to a PSR-7 RequestInterface
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    function createHttpRequest()
    {
        $headers = [
            'User-Agent' => $this->getContext()->getUserAgent(),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $body = $this->buildQuery($this->getRequestParameters());

        return $this->getContext()->getMessageFactory()->createRequest(
            'POST',
            $this->getEndpoint(),
            $headers,
            $body
        );

        $httpRequest = new Request(
            'POST',
            $this->getEndpoint(),
            $headers,
            $body
        );

        return $httpRequest;
    }

    /**
     * Converts the PSR-7 ResponseInterface to a Wirecard Response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return WirecardResponse
     */
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

    /**
     * {@inheritDoc}
     */
    function serialize()
    {
        return serialize([
            'context' => $this->context,
            'parameters' => $this->parameters
        ]);
    }

    /**
     * {@inheritDoc}
     */
    function unserialize($data)
    {
        $data = unserialize($data);

        $this->context = $data['context'];
        $this->parameters = $data['parameters'];
    }

    /**
     * Build a query string from an array of key value pairs.
     *
     * This function can use the return value of parseQuery() to build a query
     * string. This function does not modify the provided keys when an array is
     * encountered (like http_build_query would).
     *
     * @param array     $params   Query string parameters.
     * @param int|false $encoding Set to false to not encode, PHP_QUERY_RFC3986
     *                            to encode using RFC3986, or PHP_QUERY_RFC1738
     *                            to encode using RFC1738.
     * @return string
     */
    protected function buildQuery(array $params, $encoding = PHP_QUERY_RFC3986)
    {
        if (!$params) {
            return '';
        }
        if ($encoding === false) {
            $encoder = function ($str) { return $str; };
        } elseif ($encoding == PHP_QUERY_RFC3986) {
            $encoder = 'rawurlencode';
        } elseif ($encoding == PHP_QUERY_RFC1738) {
            $encoder = 'urlencode';
        } else {
            throw new \InvalidArgumentException('Invalid type');
        }
        $qs = '';
        foreach ($params as $k => $v) {
            $k = $encoder($k);
            if (!is_array($v)) {
                $qs .= $k;
                if ($v !== null) {
                    $qs .= '=' . $encoder($v);
                }
                $qs .= '&';
            } else {
                foreach ($v as $vv) {
                    $qs .= $k;
                    if ($vv !== null) {
                        $qs .= '=' . $encoder($vv);
                    }
                    $qs .= '&';
                }
            }
        }
        return $qs ? (string) substr($qs, 0, -1) : '';
    }
}
