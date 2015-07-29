<?php

namespace Hochstrasser\Wirecard\Response;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7;

class WirecardResponse implements WirecardResponseInterface
{
    private $parameters = [];
    private $objectClass;

    static function fromHttpResponse(ResponseInterface $response, $objectClass)
    {
        $responseParameters = Psr7\parse_query((string) $response->getBody());

        $wirecardResponse = new static;
        $wirecardResponse->objectClass = $objectClass;

        static::parseResponseParameters($wirecardResponse, $responseParameters);

        return $wirecardResponse;
    }

    private static function parseResponseParameters(WirecardResponse $wirecardResponse, array $parameters)
    {
        foreach ($parameters as $param => $value) {
            if (strpos($param, '.', 0) !== false) {
                $params = &$wirecardResponse->parameters;
                $parts = explode('.', $param);

                while (count($parts) > 1) {
                    $key = array_shift($parts);
                    if (empty($params[$key])) {
                        $params[$key] = [];
                    }
                    $params = &$params[$key];
                }

                $key = reset($parts);
                $params[$key] = $value;
            } else {
                $wirecardResponse->parameters[$param] = $value;
            }
        }
    }

    function toObject()
    {
        if ($this->hasErrors()) {
            return;
        }

        if (null === $this->objectClass) {
            return;
        }

        $class = $this->objectClass;
        return new $class($this->parameters);
    }

    function toArray()
    {
        return $this->parameters;
    }

    function hasErrors()
    {
        return isset($this->parameters['errors']) && (int) $this->parameters['errors'] > 0;
    }

    function getErrors()
    {
        if (!$this->hasErrors()) {
            return [];
        }

        return $this->parameters['error'];
    }
}
