<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;

abstract class AbstractWirecardTest extends \PHPUnit_Framework_TestCase
{
    protected function getContext()
    {
        return new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de', 'qmore');
    }

    protected function getClient()
    {
        $context = $this->getContext();
        $client = new Client($context, Adapter::defaultAdapter());

        return $client;
    }
}
