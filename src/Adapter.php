<?php

namespace Hochstrasser\Wirecard;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;

abstract class Adapter
{
    static function defaultAdapter()
    {
        return function (RequestInterface $request) {
            $headers = [];

            foreach ($request->getHeaders() as $header => $values) {
                $headers[] = $header.': '.implode(',', $values);
            }

            $streamContext = stream_context_create([
                'http' => [
                    'method' => $request->getMethod(),
                    'header' => $headers,
                    'content' => Psr7\copy_to_string($request->getBody()),
                    'protocol_version' => $request->getProtocolVersion()
                ]
            ]);

            $responseBody = file_get_contents((string) $request->getUri(), false, $streamContext);
            $headers = [];

            $statusLines = array_reverse(preg_grep('{^HTTP/([0-9\.]+) (\d+) (.+)$}', $http_response_header));

            // TODO: Get offset of last header, then parse only headers which succeed
            // that offset to get the headers of the last response after following redirects

            preg_match('{^HTTP/([0-9\.]+) (\d+) (.+)$}', current($statusLines), $matches);

            $version = $matches[1];
            $status = $matches[2];
            $reason = $matches[3];

            foreach (array_slice($http_response_header, 1) as $headerLine) {
                list($header, $value) = explode(':', $headerLine);
                $headers[$header] = explode(';', $value);
            }

            $response = new Response($status, $headers, $responseBody, $version, $reason);

            return $response;
        };
    }

    static function guzzleAdapter(\GuzzleHttp\Client $client)
    {
        return new Adapter\GuzzleAdapter($client);
    }

    static function ivoryHttpAdapter(\Ivory\HttpAdapter\HttpAdapterInterface $adapter)
    {
        return new Adapter\IvoryHttpAdapter($adapter);
    }
}
