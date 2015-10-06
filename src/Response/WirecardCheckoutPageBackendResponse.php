<?php

namespace Hochstrasser\Wirecard\Response;

class WirecardCheckoutPageBackendResponse extends WirecardBackendResponse
{
    function hasErrors()
    {
        return isset($this->parameters['errorCode']);
    }

    function getErrors()
    {
        if ($this->hasErrors()) {
            return [
                [
                    'errorCode' => $this->parameters['errorCode'],
                    'message' => $this->parameters['message'],
                    'paySysMessage' => $this->parameters['paySysMessage'],
                ]
            ];
        }
    }
}
