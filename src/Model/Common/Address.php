<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

abstract class Address extends Model
{
    protected $addressParameterPrefix = '';

    function setFirstname($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Firstname', $value);
    }

    function getFirstname()
    {
        return $this->getParam($this->addressParameterPrefix.'Firstname');
    }

    function setLastname($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Lastname', $value);
    }

    function getLastname()
    {
        return $this->getParam($this->addressParameterPrefix.'Lastname');
    }

    function setAddress1($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Address1', $value);
    }

    function getAddress1()
    {
        return $this->getParam($this->addressParameterPrefix.'Address1');
    }

    function setAddress2($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Address2', $value);
    }

    function getAddress2()
    {
        return $this->getParam($this->addressParameterPrefix.'Address2');
    }

    function setCity($value)
    {
        return $this->addParam($this->addressParameterPrefix.'City', $value);
    }

    function getCity()
    {
        return $this->getParam($this->addressParameterPrefix.'City');
    }

    function setState($value)
    {
        return $this->addParam($this->addressParameterPrefix.'State', $value);
    }

    function getState()
    {
        return $this->getParam($this->addressParameterPrefix.'State');
    }

    function setCountry($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Country', $value);
    }

    function getCountry()
    {
        return $this->getParam($this->addressParameterPrefix.'Country');
    }

    function setZipCode($value)
    {
        return $this->addParam($this->addressParameterPrefix.'ZipCode', $value);
    }

    function getZipCode()
    {
        return $this->getParam($this->addressParameterPrefix.'ZipCode');
    }

    function setPhone($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Phone', $value);
    }

    function getPhone()
    {
        return $this->getParam($this->addressParameterPrefix.'Phone');
    }

    function setFax($value)
    {
        return $this->addParam($this->addressParameterPrefix.'Fax', $value);
    }
    
    function getFax()
    {
        return $this->getParam($this->addressParameterPrefix.'Fax');
    }
}
