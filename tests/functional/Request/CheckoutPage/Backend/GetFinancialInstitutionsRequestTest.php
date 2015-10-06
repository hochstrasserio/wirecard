<?php

namespace Hochstrasser\Wirecard\Test\Request\CheckoutPage\Backend;

use Hochstrasser\Wirecard\Request\Seamless\Backend\GetFinancialInstitutionsRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Test\AbstractWirecardTest;

class GetFinancialInstitutionsRequestTest extends AbstractWirecardTest
{
    function test()
    {
        $request = new GetFinancialInstitutionsRequest;
        $request->setPaymentType(PaymentType::TrustPay);
        $request->setContext($this->getContext());

        $response = $request->createResponse($this->getClient()->send($request->createHttpRequest()));

        var_dump($response->toArray());
    }
}
