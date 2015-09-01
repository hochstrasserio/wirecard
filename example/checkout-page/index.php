<?php

require __DIR__.'/../../vendor/autoload.php';

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;
use Hochstrasser\Wirecard\Request\CheckoutPage\InitCheckoutPageRequest;

$context = new Context([
    'customer_id' => 'D200001',
    'secret' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2',
    'language' => 'de',
    'shop_id' => 'qmore'
]);

$basket = new Basket();
$basket->setAmount(18);
$basket->setCurrency('EUR');
$basket->addItem((new BasketItem)
    ->setArticleNumber('A001')
    ->setDescription('Product A1')
    ->setQuantity(1)
    ->setUnitPrice(10.00)
    ->setTax(2.00)
);
$basket->addItem((new BasketItem)
    ->setArticleNumber('SHIPPING')
    ->setDescription('Shipping')
    ->setQuantity(1)
    ->setUnitPrice(5.00)
    ->setTax(1.00)
);

$request = InitCheckoutPageRequest::withBasket($basket)
    ->setPaymentType(PaymentType::Select)
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
