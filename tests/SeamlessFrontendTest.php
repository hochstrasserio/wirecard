<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Service\SeamlessFrontend;

class SeamlessFrontendTest extends \PHPUnit_Framework_TestCase
{
    private function getContext()
    {
        return new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de', 'qmore');
    }

    private function getClient()
    {
        $client = new Client($this->getContext(), Adapter::defaultAdapter());

        return $client;
    }

    public function test()
    {
        $client = $this->getClient();
        $service = new SeamlessFrontend($client, $this->getContext());

        $response = $service->initDataStorage(['orderIdent' => 1234, 'returnUrl' => 'http://example.com']);
        $this->assertFalse($response->hasErrors());
    }
}
