<?php

namespace Hochstrasser\Wirecard\Exception;

class RequiredParameterMissingException extends \Exception
{
    private $param;

    /**
     * Named constructor
     *
     * @param string $param
     * @return RequiredParameterMissingException
     */
    static function withParameter($param)
    {
        $self = new static(sprintf('Required parameter "%s" missing', $param));
        $self->param = $param;

        return $self;
    }

    /**
     * Returns the parameter name
     *
     * @return string
     */
    function getParam()
    {
        return $this->param;
    }
}
