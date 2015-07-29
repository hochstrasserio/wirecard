<?php

namespace Hochstrasser\Wirecard\Request;

use Hochstrasser\Wirecard\Context;

interface WirecardRequestInterface
{
    function setContext(Context $context);

    /**
     * @return Context
     */
    function getContext();

    /**
     * @return Psr\Http\Message\RequestInterface
     */
    function createHttpRequest();

    /**
     * @return WirecardResponseInterface
     */
    function createResponse(\Psr\Http\Message\ResponseInterface $response);
}
