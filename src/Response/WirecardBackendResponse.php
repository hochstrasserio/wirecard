<?php

namespace Hochstrasser\Wirecard\Response;

use Hochstrasser\Wirecard\Model\Common\BackendResponseStatus;

class WirecardBackendResponse extends WirecardResponse
{
    /**
     * @return string
     */
    function getStatus()
    {
        return new BackendResponseStatus($this->parameters['status']);
    }
}
