<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Context;

/**
 * Interface for all API requests
 */
interface WirecardRequestInterface
{
    /**
     * Returns the URL of the API endpoint
     *
     * @return string
     */
    function getEndpoint();

    /**
     * Returns the URL of the API endpoint
     *
     * @return void
     */
    function setEndpoint();

    /**
     * Set the context, which contains global API settings
     *
     * @param Context $context
     * @return WirecardRequestInterface
     */
    function setContext(Context $context);

    /**
     * @return Context
     */
    function getContext();

    /**
     * Returns all request parameters which are going to be sent
     *
     * Can be used to set parameters as hidden form inputs.
     *
     * @return array
     */
    function getRequestParameters();

    /**
     * Converts the API request object to a PSR-7 compatible request
     *
     * @return Psr\Http\Message\RequestInterface
     */
    function createHttpRequest();

    /**
     * Converts the PSR-7 compatible response to an API response object
     *
     * @return WirecardResponseInterface
     */
    function createResponse(\Psr\Http\Message\ResponseInterface $response);
}
