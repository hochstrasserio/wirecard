<?php

namespace Hochstrasser\Wirecard\Helper;

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\WirecardRequestInterface;

class WirecardHelper
{
    private $context;
    private $client;

    /**
     * Setup the helper with a function which can turn a request into a response
     *
     * Example:
     *
     * $guzzle = new \Guzzle\Client;
     * $helper = new WirecardHelper($context, function ($request) use ($guzzle) {
     *      return $guzzle->send($request);
     * });
     */
    function __construct(Context $context, callable $client)
    {
        $this->context = $context;
        $this->client =  $client;
    }

    /**
     * Synchronously sends the request with the configured client
     */
    function send(WirecardRequestInterface $wirecardRequest)
    {
        $client = $this->client;

        $wirecardRequest->setContext($this->context);

        $httpRequest = $wirecardRequest->createHttpRequest();
        $httpResponse = call_user_func($client, $httpRequest);

        return $wirecardRequest->createResponse($httpResponse);
    }
}
