<?php

namespace Hochstrasser\Wirecard;

use GuzzleHttp\Psr7\Request;

/**
 * Message factory which creates Guzzle PSR7 Request objects
 */
class GuzzlePsrMessageFactory implements MessageFactory
{
    /**
     * {@inheritDoc}
     */
    function createRequest($method, $url, $headers, $body)
    {
        return new Request(
            $method,
            $url,
            $headers,
            $body
        );
    }
}
