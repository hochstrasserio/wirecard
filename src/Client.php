<?php

namespace Hochstrasser\Wirecard;

use Hochstrasser\Wirecard\Request\WirecardRequestInterface;

/**
 * Runs the API Requests
 *
 * @author Christoph Hochstrasser <christoph@hochstrasser.io>
 */
class Client
{
    private $context;
    private $adapter;

    /**
     * Constructor
     *
     * @param Context $context API Context with configuration settings
     * @param callable $adapter HTTP Client adapter, transforms a request to a response
     */
    function __construct(Context $context, callable $adapter)
    {
        $this->context = $context;
        $this->adapter = $adapter;
    }

    /**
     * Executes the API request and returns the parsed response
     *
     * @param WirecardRequestInterface $request
     * @return WirecardResponseInterface
     */
    function execute(WirecardRequestInterface $request)
    {
        $adapter = $this->adapter;

        if ($request->getContext() === null) {
            $request->setContext($this->context);
        }

        $httpResponse = $adapter($request->createHttpRequest());

        return $request->createResponse($httpResponse);
    }
}
