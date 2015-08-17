<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;

abstract class AbstractWirecardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Hochstrasser\Wirecard\Context
     */
    protected function getContext()
    {
        return new Context($_SERVER['CUSTOMER_ID'], $_SERVER['CUSTOMER_SECRET'], 'de', $_SERVER['SHOP_ID']);
    }

    /**
     * @return Hochstrasser\Wirecard\Client
     */
    protected function getClient()
    {
        $context = $this->getContext();
        $client = new Client($context, Adapter::defaultAdapter());

        return $client;
    }
}
