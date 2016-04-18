<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

class BasketItem extends Model
{
    /**
     * Set the item's article number, typically an SKU
     *
     * @param string $articleNumber
     * @return BasketItem
     */
    function setArticleNumber($articleNumber)
    {
        return $this->addParam('articleNumber', $articleNumber);
    }

    /**
     * @return string
     */
    function getArticleNumber()
    {
        return $this->getParam('articleNumber');
    }

    /**
     * Set the item description, typically a product name
     *
     * @param string $description
     * @return BasketItem
     */
    function setDescription($description)
    {
        return $this->addParam('description', $description);
    }

    /**
     * @return string
     */
    function getDescription()
    {
        return $this->getParam('description');
    }

    /**
     * Set item quantity
     *
     * @param int $quantity
     * @return BasketItem
     */
    function setQuantity($quantity)
    {
        return $this->addParam('quantity', $quantity);
    }

    /**
     * @return int
     */
    function getQuantity()
    {
        return $this->getParam('quantity');
    }

    /**
     * Set item tax amount as string, formatted as float, e.g. "12.50"
     *
     * @param string $amount
     * @return BasketItem
     */
    function setTax($amount)
    {
        return $this->addParam('tax', $amount);
    }

    /**
     * @return string
     */
    function getTax()
    {
        return $this->getParam('tax');
    }

    /**
     * Set item cost as string, formatted as float, e.g. "12.50"
     *
     * @param string $amount
     * @return BasketItem
     */
    function setUnitPrice($amount)
    {
        return $this->addParam('unitPrice', $amount);
    }

    /**
     * @return string
     */
    function getUnitPrice()
    {
        return $this->getParam('unitPrice');
    }
}
