<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Request\ParameterBag;
use Hochstrasser\Wirecard\Context;

abstract class AbstractWirecardRequest implements WirecardRequestInterface
{
    private $params;
    private $context;

    function __construct(Context $context = null)
    {
        if (null !== $context) {
            $this->setContext($context);
        }

        $this->params = new ParameterBag;
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

    protected function getParameterBag()
    {
        return $this->params;
    }

    protected function getFingerprint(array $params)
    {
        $raw = '';
        $parameters = array_merge($this->fingerprintParameters, ['customerId']);

        foreach ($parameters as $parameter) {
            $raw .= isset($params[$parameter]) ? $params[$parameter] : '';
        }

        return hash('sha512', $raw.$this->getContext()->getSecret());
    }
}
