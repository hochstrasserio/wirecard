<?php

require __DIR__.'/../../vendor/autoload.php';

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Request\CheckoutPage\InitCheckoutPageRequest;

$context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de');
$request = InitCheckoutPageRequest::with()
    ->setPaymentType(PaymentType::Select)
    ->setContext($context)
    ->setAmount(33.00)
    ->setCurrency('EUR')
    ->setOrderDescription("12345")
    ->setSuccessUrl("http://localhost:8001/")
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
