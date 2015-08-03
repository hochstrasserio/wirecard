<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

class BillingInformation extends Address
{
    protected $addressParameterPrefix = 'consumerBilling';

    static function fromShippingInformation(ShippingInformation $shipping)
    {
        $billing = new static();
        $billing->setFirstname($shipping->getFirstname());
        $billing->setLastname($shipping->getLastname());
        $billing->setAddress1($shipping->getAddress1());
        $billing->setAddress2($shipping->getAddress2());
        $billing->setCity($shipping->getCity());
        $billing->setState($shipping->getState());
        $billing->setCountry($shipping->getCountry());
        $billing->setZipCode($shipping->getZipCode());
        $billing->setPhone($shipping->getPhone());
        $billing->setFax($shipping->getFax());

        return $billing;
    }

    function setConsumerBirthDate(\DateTime $value)
    {
        return $this->addParam('consumerBirthDate', $value->format('Y-m-d'));
    }

    function getConsumerBirthDate()
    {
        return \DateTime::createFromFormat('Y-m-d', $this->getParam('consumerBirthDate'));
    }

    function setConsumerEmail($value)
    {
        return $this->addParam('consumerEmail', $value);
    }

    function getConsumerEmail()
    {
        return $this->getParam('consumerEmail');
    }
}
