<?php

namespace Hochstrasser\Wirecard\Model;

abstract class Model
{
    protected $parameters;

    function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    function getParam($param)
    {
        if (array_key_exists($param, $this->parameters)) {
            return $this->parameters[$param];
        }
    }
}
