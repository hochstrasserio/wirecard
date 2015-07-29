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
            $metadata = stream_get_meta_data($stream);
            fclose($stream);

            $response = new Response(200, [], $responseBody);

            return $response;
        };
    }
}
