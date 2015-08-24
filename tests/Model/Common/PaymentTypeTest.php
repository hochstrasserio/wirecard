<?php

namespace Hochstrasser\Wirecard\Test\Model\Common;

use Hochstrasser\Wirecard\Model\Common\PaymentType;

class PaymentTypeTest extends \PHPUnit_Framework_Testcase
{
    function testReturnsFalseWhenInvalid()
    {
        $this->assertFalse(PaymentType::isValid('foo'));
    }

    function testReturnsTrueWhenValid()
    {
        $this->assertTrue(PaymentType::isValid(PaymentType::CreditCard));
    }
}
