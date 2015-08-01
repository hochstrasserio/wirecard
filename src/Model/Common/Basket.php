<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

class Basket extends Model
{
    private $items = [];

    function setAmount($amount)
    {
        return $this->addParam('basketAmount', $amount);
    }

    function setCurrency($currency)
    {
        return $this->addParam('basketCurrency', $currency);
    }

    function addItem(BasketItem $item)
    {
        $basketItems = $this->getParam('basketItems') ?: 0;
        $basketItems++;

        $this->items[$basketItems] = $item;
        $this->addParam('basketItems', $basketItems);

        foreach ($item->toArray() as $param => $value) {
            $this->addParam('basketItem'.$basketItems.ucfirst($param), $value);
        }

        return $this;
    }
}
