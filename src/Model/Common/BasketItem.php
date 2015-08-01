<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

class BasketItem extends Model
{
    function setArticleNumber($articleNumber)
    {
        return $this->addParam('articleNumber', $articleNumber);
    }

    function getArticleNumber()
    {
        return $this->getParam('articleNumber');
    }

    function setDescription($description)
    {
        return $this->addParam('description', $description);
    }

    function getDescription()
    {
        return $this->getParam('description');
    }

    function setQuantity($quantity)
    {
        return $this->addParam('quantity', $quantity);
    }

    function getQuantity()
    {
        return $this->getParam('quantity');
    }

    function setTax($amount)
    {
        return $this->addParam('tax', $amount);
    }

    function getTax()
    {
        return $this->getParam('tax');
    }

    function setUnitPrice($amount)
    {
        return $this->addParam('unitPrice', $amount);
    }

    function getUnitPrice()
    {
        return $this->getParam('unitPrice');
    }
}
