<?php

namespace Hochstrasser\Wirecard\Service;

/**
 * @method initDataStorage
 * @method readDataStorage
 * @method initPayment
 */
class SeamlessFrontend
{
    private $client;
    private $context;

    function __construct(Client $client, Context $context)
    {
        $this->client = $client;
        $this->context = $context;
    }

    function __call($method, $arguments)
    {
        $requestClass = 'Hochstrasser\Wirecard\Request\Seamless'.ucfirst($method).'Request';

        if (!class_exists($requestClass)) {
            throw new \BadMethodCallException(sprintf('Call to undefined method "%s"', $method));
        }

        $request = new $requestClass();

        $params = isset($arguments[0]) ? $arguments[0] : [];

        foreach ($params as $param => $value) {
            $request->addParam($param, $value);
        }

        return $this->client->execute($request);
    }
}
