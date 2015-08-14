<?php

namespace Hochstrasser\Wirecard\Test;

use Hochstrasser\Wirecard\Test\AbstractWirecardTest;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;
use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;

class InitPaymentRequestTest extends AbstractWirecardTest
{
    public function test()
    {
        $client = $this->getClient();

        $response = $client->send(
            InitPaymentRequest::with()
            ->addParam('paymentType', PaymentType::PayPal)
            ->addParam('amount', 12.01)
            ->addParam('currency', 'EUR')
            ->addParam('orderDescription', 'Some test order')
            ->addParam('successUrl', 'http://www.example.com')
            ->addParam('failureUrl', 'http://www.example.com')
            ->addParam('cancelUrl', 'http://www.example.com')
            ->addParam('serviceUrl', 'http://www.example.com')
            ->addParam('confirmUrl', 'http://www.example.com')
            ->addParam('consumerIpAddress', '127.0.0.1')
            ->addParam('consumerUserAgent', 'Mozilla')
        );

        $this->assertArrayHasKey('redirectUrl', $response->toArray());
    }

    public function testBasket()
    {
        $client = $this->getClient();

        $basket = new Basket();
        $basket->setAmount(11);
        $basket->setCurrency('EUR');
        $basket->addItem((new BasketItem)
            ->setArticleNumber('A001')
            ->setDescription('Product A1')
            ->setQuantity(1)
            ->setUnitPrice(10.00)
            ->setTax(1.00)
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
            ->setConsumerBirthDate(\DateTime::createFromFormat('Y-m-d', '1970-01-01'));

        $request = InitPaymentRequest::with()
            ->addParam('paymentType', PaymentType::PayPal)
            // Todo: set amount and currency automatically with setBasket()?
            ->addParam('amount', $basket->getAmount())
            ->addParam('currency', $basket->getCurrency())
            ->addParam('orderDescription', 'Some test order')
            ->addParam('successUrl', 'http://www.example.com')
            ->addParam('failureUrl', 'http://www.example.com')
            ->addParam('cancelUrl', 'http://www.example.com')
            ->addParam('serviceUrl', 'http://www.example.com')
            ->addParam('confirmUrl', 'http://www.example.com')
            ->addParam('consumerIpAddress', '127.0.0.1')
            ->addParam('consumerUserAgent', 'Mozilla')
            ;

        $request->setConsumerBillingInformation($billingInformation);
        $request->setConsumerShippingInformation($shippingInformation);
        $request->setBasket($basket);

        $response = $client->send($request);

        $this->assertArrayHasKey('redirectUrl', $response->toArray());
    }
}
