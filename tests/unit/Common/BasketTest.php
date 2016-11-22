<?php

namespace Hochstrasser\Wirecard\Test\Model\Common;

use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;

class BasketTest extends \PHPUnit_Framework_Testcase
{
    function test()
    {
        $basket = new Basket;
        $basket->setAmount(110);
        $basket->setCurrency('EUR');
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A001')
            ->setName('Product A1')
            ->setDescription('Desc for Product A1')
            ->setQuantity(1)
            ->setUnitGrossAmount(55)
            ->setUnitNetAmount(50)
            ->setUnitTaxAmount(5)
            ->setUnitTaxRate(10)
        );
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A002')
            ->setName('Product A2')
            ->setDescription('Desc for Product A2')
            ->setQuantity(2)
            ->setUnitGrossAmount(25)
            ->setUnitNetAmount(20)
            ->setUnitTaxAmount(5)
            ->setUnitTaxRate(25)
        );
        $basket->addItem((new BasketItem)
            ->setArticleNumber('S001')
            ->setName('Shipping')
            ->setQuantity(1)
            ->setUnitGrossAmount(5)
            ->setUnitNetAmount(5)
            ->setUnitTaxAmount(0)
            ->setUnitTaxRate(0)
        );

        $this->assertEquals(
            [
                'amount' => 110,
                'currency' => 'EUR',
                'basketItems' => 3,
                'basketItem1ArticleNumber' => 'A001',
                'basketItem1Name' => 'Product A1',
                'basketItem1Description' => 'Desc for Product A1',
                'basketItem1Quantity' => 1,
                'basketItem1UnitGrossAmount' => 55,
                'basketItem1UnitNetAmount' => 50,
                'basketItem1UnitTaxAmount' => 5,
                'basketItem1UnitTaxRate' => 10,
                'basketItem2ArticleNumber' => 'A002',
                'basketItem2Name' => 'Product A2',
                'basketItem2Description' => 'Desc for Product A2',
                'basketItem2Quantity' => 2,
                'basketItem2UnitGrossAmount' => 25,
                'basketItem2UnitNetAmount' => 20,
                'basketItem2UnitTaxAmount' => 5,
                'basketItem2UnitTaxRate' => 25,
                'basketItem3ArticleNumber' => 'S001',
                'basketItem3Name' => 'Shipping',
                'basketItem3Quantity' => 1,
                'basketItem3UnitGrossAmount' => 5,
                'basketItem3UnitNetAmount' => 5,
                'basketItem3UnitTaxAmount' => 0,
                'basketItem3UnitTaxRate' => 0,
            ],
            $basket->toArray()
        );

        $this->assertCount(3, $basket->getItems());
    }
}
