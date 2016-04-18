<?php

namespace Hochstrasser\Wirecard\Helper;

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\WirecardRequestInterface;
use Hochstrasser\Wirecard\Response\WirecardResponseInterface;

class WirecardHelper
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var callable
     */
    private $client;

    /**
     * Setup the helper with a function which can turn a request into a response
     *
     * Example:
     *
     * $guzzle = new \Guzzle\Client;
     * $helper = new WirecardHelper($context, [$guzzle, 'send']);
     *
     * @param Context $context
     * @param callable $client
     */
    function __construct(Context $context, callable $client)
    {
        $this->context = $context;
        $this->client =  $client;
    }

    /**
     * Synchronously sends the request with the configured client
     *
     * @return WirecardResponseInterface
     */
    function send(WirecardRequestInterface $wirecardRequest)
    {
        $wirecardRequest->setContext($this->getContext());

        $httpRequest = $wirecardRequest->createHttpRequest();
        $httpResponse = call_user_func($this->client, $httpRequest);

        return $wirecardRequest->createResponse($httpResponse);
    }

    /**
     * @return Context
     */
    private function getContext()
    {
        return $this->context;
    }
}
