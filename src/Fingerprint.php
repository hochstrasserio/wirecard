<?php

namespace Hochstrasser\Wirecard;

class Fingerprint
{
    private $parameters;
    private $context;
    private $fingerprintOrder = [];

    static function compute(Request\ParameterBag $parameters, Context $context, $fingerprintOrder)
    {
        $self = static::fromParameters($parameters)
            ->setContext($context)
            ->setFingerprintOrder($fingerprintOrder);

        $parameters->set('requestFingerprint', (string) $self);
    }

    static function fromParameters(Request\ParameterBag $parameters)
    {
        $self = new static;
        $self->parameters = $parameters;

        return $self;
    }

    function setContext(Context $context)
    {
        $this->context = $context;
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
            $raw .= $this->parameters->get($parameter);
        }

        return hash('sha512', $raw.$this->context->getSecret());
    }
}
