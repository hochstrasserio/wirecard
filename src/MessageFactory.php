<?php

namespace Hochstrasser\Wirecard;

/**
 * Interface to abstract request creation
 */
interface MessageFactory
{
    /**
     * Creates a HTTP request object
     *
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string $body
     */
    function createRequest($method, $url, $headers, $body);
}
