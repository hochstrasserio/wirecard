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
     * Set the item's name
     *
     * @param string $name
     * @return BasketItem
     */
    function setName($name)
    {
        return $this->addParam('name', $name);
    }

    /**
     * @return string
     */
    function getName()
    {
        return $this->getParam('name');
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
     * Set item tax amount as string, formatted as float, e.g. "2.50"
     *
     * @param string $amount
     * @return BasketItem
     */
    function setUnitTaxAmount($amount)
    {
        return $this->addParam('unitTaxAmount', $amount);
    }

    /**
     * @return string
     */
    function getUnitTaxAmount()
    {
        return $this->getParam('unitTaxAmount');
    }

    /**
     * Set item tax rate as string, formatted as float, e.g. "25"
     *
     * @param string $rate
     * @return BasketItem
     */
    function setUnitTaxRate($rate)
    {
        return $this->addParam('unitTaxRate', $rate);
    }

    /**
     * @return string
     */
    function getUnitTaxRate()
    {
        return $this->getParam('unitTaxRate');
    }

    /**
     * Set item gross amount as string, formatted as float, e.g. "12.50"
     *
     * @param string $amount
     * @return BasketItem
     */
    function setUnitGrossAmount($amount)
    {
        return $this->addParam('unitGrossAmount', $amount);
    }

    /**
     * @return string
     */
    function getUnitGrossAmount()
    {
        return $this->getParam('unitGrossAmount');
    }

    /**
     * Set item net amount as string, formatted as float, e.g. "10.00"
     *
     * @param string $amount
     * @return BasketItem
     */
    function setUnitNetAmount($amount)
    {
        return $this->addParam('unitNetAmount', $amount);
    }

    /**
     * @return string
     */
    function getUnitNetAmount()
    {
        return $this->getParam('unitNetAmount');
    }
}
