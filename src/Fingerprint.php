<?php

namespace Hochstrasser\Wirecard;

class Fingerprint
{
    private $parameters;
    private $context;
    private $fingerprintOrder = [];

    static function fromParameters(array $parameters)
    {
        $self = new static;
        $self->parameters = $parameters;

        return $self;
    }

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

    function setContext(Context $context)
    {
        $this->context = $context;

        if (empty($this->parameters['secret'])) {
            $this->parameters['secret'] = $context->getSecret();
        }

        return $this;
    }

    function setFingerprintOrder(array $fingerprintOrder)
    {
        $this->fingerprintOrder = $fingerprintOrder;
        return $this;
    }

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
