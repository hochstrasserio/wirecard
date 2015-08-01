<?php

namespace Hochstrasser\Wirecard\Model;

/**
 * Base class for all models
 *
 * @author Christoph Hochstrasser <christoph@hochstrasser.io>
 */
abstract class Model implements \Serializable
{
    private $parameters;

    /**
     * Constructor
     *
     * @param array $parameters
     */
    function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Initializes the model from parameters
     *
     * @param array $parameters
     * @return Model
     */
    static function fromParameters(array $parameters)
    {
        return new static($parameters);
    }

    /**
     * Returns a parameter
     *
     * @param string $param
     * @return mixed
     */
    function getParam($param)
    {
        if (array_key_exists($param, $this->parameters)) {
            return $this->parameters[$param];
        }
    }

    /**
     * Modifies a parameter on the model
     *
     * @param string $param
     * @param mixed $value
     * @return Model
     */
    function addParam($param, $value)
    {
        $this->parameters[$param] = $value;
        return $this;
    }

    /**
     * @return array
     */
    function toArray()
    {
        return $this->parameters;
    }

    /**
     * Implement Serializable
     */
    function serialize()
    {
        return serialize($this->parameters);
    }

    /**
     * Implement Serializable
     */
    function unserialize($data)
    {
        $this->parameters = unserialize($data);
    }
}
