<?php

namespace Hochstrasser\Wirecard\Test\Model\Common;

use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;

class BasketTest extends \PHPUnit_Framework_Testcase
{
    function test()
    {
        $basket = new Basket;
        $basket->setAmount(58);
        $basket->setCurrency('EUR');
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A001')
            ->setDescription('Product A1')
            ->setQuantity(1)
            ->setUnitPrice(10)
            ->setTax(1)
        );
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A002')
            ->setDescription('Product A2')
            ->setQuantity(2)
            ->setUnitPrice(20)
            ->setTax(2)
        );
        $basket->addItem((new BasketItem)
            ->setArticleNumber('S001')
            ->setDescription('Shipping')
            ->setQuantity(1)
            ->setUnitPrice(5)
            ->setTax(0)
        );

        $this->assertEquals(
            [
                'basketAmount' => 58,
                'basketCurrency' => 'EUR',
                'basketItems' => 3,
                'basketItem1ArticleNumber' => 'A001',
                'basketItem1Description' => 'Product A1',
                'basketItem1Quantity' => 1,
                'basketItem1UnitPrice' => 10,
                'basketItem1Tax' => 1,
                'basketItem2ArticleNumber' => 'A002',
                'basketItem2Description' => 'Product A2',
                'basketItem2Quantity' => 2,
                'basketItem2UnitPrice' => 20,
                'basketItem2Tax' => 2,
                'basketItem3ArticleNumber' => 'S001',
                'basketItem3Description' => 'Shipping',
                'basketItem3Quantity' => 1,
                'basketItem3UnitPrice' => 5,
                'basketItem3Tax' => 0,
            ],
            $basket->toArray()
        );
    }
}
