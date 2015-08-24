<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Exception\RequiredParameterMissingException;

class ParameterBag
{
    private $parameters;

    function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    function set($param, $value)
    {
        $this->parameters[$param] = $value;
    }

    function put($param, $value)
    {
        if ($this->has($param)) {
            return;
        }

        $this->set($param, $value);
    }

    function get($param)
    {
        if ($this->has($param)) {
            return $this->parameters[$param];
        }
    }

    function has($param)
    {
        return array_key_exists($param, $this->parameters);
    }

    function all()
    {
        return $this->parameters;
    }
}
