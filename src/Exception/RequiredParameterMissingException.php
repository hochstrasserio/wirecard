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
        $this->param = $param;

        return new static(sprintf('Required parameter "%s" missing', $param));
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
