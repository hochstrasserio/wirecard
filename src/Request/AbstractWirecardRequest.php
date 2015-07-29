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

    function getParameterBag()
    {
        return $this->params;
    }
}
