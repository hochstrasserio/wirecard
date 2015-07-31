<?php

namespace Hochstrasser\Wirecard\Adapter;

use Psr\Http\Message\RequestInterface;
use Ivory\HttpAdapter\HttpAdapterInterface;

class IvoryHttpAdapter
{
    private $adapter;

    function __construct(HttpAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    function __invoke(RequestInterface $request)
    {
        return $this->adapter->sendRequest($request);
    }
}
