<?php

namespace Hochstrasser\Wirecard\Model\Seamless\Frontend;

use Hochstrasser\Wirecard\Model\Model;

class PaymentInformation extends Model
{
    function getPaymentType()
    {
        return $this->getParam('paymentType');
    }

    function getAnonymousPan()
    {
        return $this->getParam('anonymousPan');
    }

    function getMaskedPan()
    {
        return $this->getParam('maskedPan');
    }

    function getFinancialInstitution()
    {
        return $this->getParam('financialInstitution');
    }

    function getBrand()
    {
        return $this->getParam('brand');
    }

    function getCardholdername()
    {
        return $this->getParam('cardholdername');
    }

    function getExpiry()
    {
        return $this->getParam('expiry');
    }

    function getAccountOwner()
    {
        return $this->getParam('accountOwner');
    }

    function getBankName()
    {
        return $this->getParam('bankName');
    }

    function getBankCountry()
    {
        return $this->getParam('bankCountry');
    }

    function getBankAccount()
    {
        return $this->getParam('bankAccount');
    }

    function getBankBic()
    {
        return $this->getParam('bankBic');
    }

    function getBankAccountIban()
    {
        return $this->getParam('bankAccountIban');
    }

    function getPayerPayboxNumber()
    {
        return $this->getParam('payerPayboxNumber');
    }
}
