<?php

require __DIR__.'/../../vendor/autoload.php';

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;
use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;
use Hochstrasser\Wirecard\Request\CheckoutPage\InitCheckoutPageRequest;

$context = new Context([
    'customer_id' => 'D200411',
    'secret' => 'CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ',
    'language' => 'de',
    'shop_id' => 'qmore'
]);

$basket = new Basket();
$basket->setAmount('18.00');
$basket->setCurrency('EUR');
$basket->addItem((new BasketItem)
    ->setArticleNumber('A001')
    ->setDescription('Product A1')
    ->setQuantity(1)
    ->setUnitPrice('10.00')
    ->setTax('2.00')
);
$basket->addItem((new BasketItem)
    ->setArticleNumber('SHIPPING')
    ->setDescription('Shipping')
    ->setQuantity(1)
    ->setUnitPrice('5.00')
    ->setTax('1.00')
);

$shipping = (new ShippingInformation)
    ->setFirstname('Christoph')
    ->setLastname('Hochstrasser')
    ->setAddress1('Markt 1')
    ->setZipCode('1234')
    ->setCity('Musterstadt')
    ->setState('Niederösterreich')
    ->setCountry('AT');

$billing = BillingInformation::fromShippingInformation($shipping)
    ->setConsumerEmail('me@christophh.net')
    ->setConsumerBirthdate(new \DateTime('01.01.1970'))
    ;

$request = InitCheckoutPageRequest::withBasket($basket)
    ->setPaymentType(PaymentType::Select)
    ->setConsumerShippingInformation($shipping)
    ->setConsumerBillingInformation($billing)
    ->setContext($context)
    ->setOrderDescription("12345")
    ->setSuccessUrl("http://localhost:8001/success.php")
    ->setFailureUrl("http://localhost")
    ->setCancelUrl("http://localhost")
    ->setServiceUrl("http://localhost")
    ;
?>

<form action="<?= $request->getEndpoint() ?>" method="POST">
    <?php foreach ($request->getRequestParameters() as $param => $value): ?>
    <input type="hidden" name="<?= $param ?>" value="<?= $value ?>"/>
    <?php endforeach ?>

    <input type="submit" value="Buy"/>
</form>
