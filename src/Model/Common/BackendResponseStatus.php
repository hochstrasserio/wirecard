<?php

namespace Hochstrasser\Wirecard\Model\Common;

class BackendResponseStatus
{
    const OPERATION_SUCCESSFUL = 0;
    const ERROR_REQUEST_PARAMETER_VERIFICATION = 1;
    const OPERATION_EXECUTION_DENIED = 2;
    const ERROR_WITHIN_EXTERNAL_FINANCIAL_INSTITUTION = 3;
    const INTERNAL_ERROR = 4;

    private $status;

    function __construct($status)
    {
        $this->status = $status;
    }

    function getStatus()
    {
        return $status;
    }
}
