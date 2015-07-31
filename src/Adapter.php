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

            $stream = fopen((string) $request->getUri(), 'r', false, $streamContext);
            $responseBody = stream_get_contents($stream);
            fclose($stream);
            $headers = [];

            $statusLine = $http_response_header[0];

            preg_match('{^HTTP/([0-9\.]+) (\d+) (.+)$}', $statusLine, $matches);

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
}
