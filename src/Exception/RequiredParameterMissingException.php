<?php

namespace Hochstrasser\Wirecard\Exception;

class RequiredParameterMissingException extends \Exception
{
    static function create($param)
    {
        return new static(sprintf('Required parameter "%s" missing', $param));
    }
}
