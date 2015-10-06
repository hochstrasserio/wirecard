<?php

namespace Hochstrasser\Wirecard\Response;

use Hochstrasser\Wirecard\Model\Common\BackendResponseStatus;

class WirecardBackendResponse extends WirecardResponse
{
    function getStatus()
    {
        return new BackendResponseStatus($this->parameters['status']);
    }
}
