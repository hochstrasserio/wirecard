<?php

namespace Hochstrasser\Wirecard\Model;

abstract class Model
{
    private $parameters;

    function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    static function fromParameters(array $parameters)
    {
        return new static($parameters);
    }

    function getParam($param)
    {
        if (array_key_exists($param, $this->parameters)) {
            return $this->parameters[$param];
        }
    }

    function addParam($param, $value)
    {
        $this->parameters[$param] = $value;
        return $this;
    }
}
