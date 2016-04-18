<?php

namespace Hochstrasser\Wirecard;

use Hochstrasser\Wirecard\Context;

class Fingerprint
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var array
     */
    private $fingerprintOrder = [];

    /**
     * Create a fingerprint from parameters array
     *
     * @return Fingerprint
     */
    static function fromParameters(array $parameters)
    {
        $self = new static;
        $self->parameters = $parameters;

        return $self;
    }

    /**
     * Create a fingerprint based on received response paramaters
     *
     * @param array $parameters
     * @param Context $context
     * @return Fingerprint
     */
    static function fromResponseParameters(array $parameters, Context $context = null)
    {
        if (empty($parameters['responseFingerprintOrder'])) {
            throw new \UnexpectedValueException('The responseFingerprintOrder parameter is missing');
        }

        $self = static::fromParameters($parameters);
        $self->setFingerprintOrder(explode(',', $parameters['responseFingerprintOrder']));

        if (null !== $context) {
            $self->setContext($context);
        }

        return $self;
    }

    /**
     * Sets the context for fingerprint calculation
     *
     * @param Context $context
     * @return Fingerprint
     */
    function setContext(Context $context)
    {
        $this->context = $context;

        if (empty($this->parameters['secret'])) {
            $this->parameters['secret'] = $context->getSecret();
        }

        return $this;
    }

    /**
     * Sets the parameter order for fingerprint calculation
     *
     * @param array $fingerprintOrder List of parameter names
     * @return Fingerprint
     */
    function setFingerprintOrder(array $fingerprintOrder)
    {
        $this->fingerprintOrder = $fingerprintOrder;
        return $this;
    }

    /**
     * @return string
     */
    function __toString()
    {
        $raw = '';

        foreach ($this->fingerprintOrder as $parameter) {
            if (isset($this->parameters[$parameter])) {
                $raw .= $this->parameters[$parameter];
            }
        }

        return hash('sha512', $raw);
    }
}
