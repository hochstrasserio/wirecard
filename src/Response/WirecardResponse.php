<?php

namespace Hochstrasser\Wirecard\Response;

use Psr\Http\Message\ResponseInterface;

class WirecardResponse implements WirecardResponseInterface
{
    const DEFAULT_MODEL = 'Hochstrasser\Wirecard\Model\DefaultModel';

    protected $parameters = [];
    protected $resultClass;

    static function fromHttpResponse(ResponseInterface $response, $resultClass = self::DEFAULT_MODEL)
    {
        $responseParameters = static::parseQuery((string) $response->getBody());

        $wirecardResponse = new static;
        $wirecardResponse->resultClass = $resultClass;

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

    /**
     * Parse a query string into an associative array.
     *
     * If multiple values are found for the same key, the value of that key
     * value pair will become an array. This function does not parse nested
     * PHP style arrays into an associative array (e.g., foo[a]=1&foo[b]=2 will
     * be parsed into ['foo[a]' => '1', 'foo[b]' => '2']).
     *
     * @param string      $str         Query string to parse
     * @param bool|string $urlEncoding How the query string is encoded
     *
     * @return array
     */
    private static function parseQuery($str, $urlEncoding = true)
    {
        $result = [];
        if ($str === '') {
            return $result;
        }
        if ($urlEncoding === true) {
            $decoder = function ($value) {
                return rawurldecode(str_replace('+', ' ', $value));
            };
        } elseif ($urlEncoding == PHP_QUERY_RFC3986) {
            $decoder = 'rawurldecode';
        } elseif ($urlEncoding == PHP_QUERY_RFC1738) {
            $decoder = 'urldecode';
        } else {
            $decoder = function ($str) { return $str; };
        }
        foreach (explode('&', $str) as $kvp) {
            $parts = explode('=', $kvp, 2);
            $key = $decoder($parts[0]);
            $value = isset($parts[1]) ? $decoder($parts[1]) : null;
            if (!isset($result[$key])) {
                $result[$key] = $value;
            } else {
                if (!is_array($result[$key])) {
                    $result[$key] = [$result[$key]];
                }
                $result[$key][] = $value;
            }
        }
        return $result;
    }

    function toObject()
    {
        if ($this->hasErrors()) {
            return;
        }

        if (null === $this->resultClass) {
            return;
        }

        $class = $this->resultClass;
        return $class::fromParameters($this->parameters);
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
