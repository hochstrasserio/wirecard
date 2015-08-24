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
            if (isset($this->parameters[$parameter])) {
                $raw .= $this->parameters[$parameter];
            }
        }

        return hash('sha512', $raw.$this->context->getSecret());
    }
}
