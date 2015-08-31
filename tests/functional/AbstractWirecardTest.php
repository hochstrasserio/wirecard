<?php

namespace Hochstrasser\Wirecard\Test;

use GuzzleHttp\Client;
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;

abstract class AbstractWirecardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Hochstrasser\Wirecard\Context
     */
    protected function getContext()
    {
        return new Context([
            'customer_id' => $_SERVER['CUSTOMER_ID'],
            'secret' => $_SERVER['CUSTOMER_SECRET'],
            'language' => 'de',
            'shop_id' => $_SERVER['SHOP_ID']
        ]);
    }

    /**
     * @return GuzzleHttp\Client
     */
    protected function getClient()
    {
        $client = new Client();

        return $client;
    }
}
