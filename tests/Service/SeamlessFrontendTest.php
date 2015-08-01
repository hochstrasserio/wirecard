<?php

namespace Hochstrasser\Wirecard\Test\Service;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Service\SeamlessFrontend;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class SeamlessFrontendTest extends AbstractWirecardTest
{
    public function test()
    {
        $client = $this->getClient();
        $service = new SeamlessFrontend($client, $this->getContext());

        $response = $service->initDataStorage(['orderIdent' => 1234, 'returnUrl' => 'http://example.com']);
        $this->assertFalse($response->hasErrors());
    }
}
