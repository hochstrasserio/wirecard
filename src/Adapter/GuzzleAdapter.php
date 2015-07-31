<?php

namespace Hochstrasser\Wirecard\Adapter;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client;

class GuzzleAdapter
{
    private $client;

    function __construct(Client $client)
    {
        $this->client = $client;
    }

    function __invoke(RequestInterface $request)
    {
        return $this->client->send($request);
    }
}
