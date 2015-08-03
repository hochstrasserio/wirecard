<?php

namespace Hochstrasser\Wirecard\Exception;

class RequiredParameterMissingException extends \Exception
{
    static function withParameter($param)
    {
        return new static(sprintf('Required parameter "%s" missing', $param));
    }
}
