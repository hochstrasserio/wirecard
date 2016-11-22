<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;
use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;
use DateTime;

class InitPaymentRequestTest extends AbstractWirecardTest
{
    public function test()
    {
        $request = InitPaymentRequest::with()
            ->addParam('paymentType', PaymentType::PayPal)
            ->addParam('amount', '12.01')
            ->addParam('currency', 'EUR')
            ->addParam('orderDescription', 'Some test order')
            ->addParam('successUrl', 'http://www.example.com')
            ->addParam('failureUrl', 'http://www.example.com')
            ->addParam('cancelUrl', 'http://www.example.com')
            ->addParam('serviceUrl', 'http://www.example.com')
            ->addParam('confirmUrl', 'http://www.example.com')
            ->addParam('consumerIpAddress', '127.0.0.1')
            ->addParam('consumerUserAgent', 'Mozilla')
            ->setContext($this->getContext());

        $response = $request->createResponse($this->getClient()->send($request->createHttpRequest()));

        $this->assertArrayHasKey('redirectUrl', $response->toArray());
    }

    public function testBasket()
    {
        $client = $this->getClient();

        $basket = new Basket();
        $basket->setAmount('18.00');
        $basket->setCurrency('EUR');
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A001')
            ->setName('Product A1')
            ->setQuantity(1)
            ->setUnitGrossAmount('11.00')
            ->setUnitNetAmount('10.00')
            ->setUnitTaxAmount('1.00')
            ->setUnitTaxRate('10')
        );
        $basket->addItem((new BasketItem)
            ->setArticleNumber('SHIPPING')
            ->setName('Shipping')
            ->setQuantity(1)
            ->setUnitGrossAmount('5.00')
            ->setUnitNetAmount('5.00')
            ->setUnitTaxAmount('0')
            ->setUnitTaxRate('0')
        );

        $shippingInformation = (new ShippingInformation)
            ->setFirstname('Max')
            ->setLastname('Mustermann')
            ->setAddress1('Musterstraße')
            ->setAddress2('2')
            ->setZipCode('1234')
            ->setState('Lower Austria')
            ->setCity('Musterstadt')
            ->setCountry('AT')
            ->setPhone('+431231231234')
            ->setFax('+431231231234');

        $billingInformation = BillingInformation::fromShippingInformation($shippingInformation)
            ->setConsumerEmail('test@test.com')
            ->setConsumerBirthDate(DateTime::createFromFormat('Y-m-d', '1970-01-01'));
 
        /** @var InitPaymentRequest */
        $request = InitPaymentRequest::withBasket($basket)
            ->setPaymentType(PaymentType::PayPal)
            ->setOrderDescription("Some test order")
            ->setSuccessUrl('http://www.example.com')
            ->setFailureUrl('http://www.example.com')
            ->setCancelUrl('http://www.example.com')
            ->setServiceUrl('http://www.example.com')
            ->setConfirmUrl('http://www.example.com')
            ->setConsumerIpAddress('127.0.0.1')
            ->setConsumerUserAgent('Mozilla')
        ;

        $request->setConsumerBillingInformation($billingInformation);
        $request->setConsumerShippingInformation($shippingInformation);

        $request->setContext($this->getContext());

        $response = $request->createResponse($client->send($request->createHttpRequest()));

        //var_dump($response->toObject()->getRedirectUrl());

        $this->assertArrayHasKey('redirectUrl', $response->toArray());
    }
}
