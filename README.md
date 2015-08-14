# Wirecard

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

An unofficial, but well made library for making payments with the [Wirecard](http://wirecard.at).

## Install

Via Composer

``` bash
$ composer require hochstrasserio/wirecard
```

## Usage

### Initialization

``` php
<?php

use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Client;

$context = new Context('Your customer ID', 'Your secret', 'de');
$client = new Client($context, Adapter::defaultAdapter());
```

### Seamless Frontend

#### InitDataStorageRequest

``` php
<?php

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

$response = $client->send(
    InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
);

$storageId = $response->toObject()->getStorageId();

var_dump($response->hasErrors());
var_dump($storageId);
var_dump($response->toObject()->getJavascriptUrl());
```

#### ReadDataStorageRequest

``` php
<?php

use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;

$response = $client->send(
    ReadDataStorageRequest::withStorageId($storageId)
);

var_dump($response->hasErrors());
var_dump($response->toObject()->getStorageId());

foreach ($response->toObject()->getPaymentInformation() as $paymentInformation) {
    var_dump($paymentInformation->getMaskedPan());
}
```

#### Making a payment with InitPaymentRequest

``` php
<?php

use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;

// Create the basket, this is optional for most payment methods, but probably
// can be automatically created from your shop’s cart model

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
$basket->addItem((new BasketItem)
    ->setArticleNumber('SHIPPING')
    ->setDescription('Shipping')
    ->setQuantity(1)
    ->setUnitPrice(5.00)
    ->setTax(1.00)
);

$request = InitPaymentRequest::withBasket($basket)
    // Set the payment type selected by the user in the frontend
    ->setPaymentType(PaymentType::PayPal)
    ->setOrderDescription('Some test order')
    ->setSuccessUrl('http://example.com')
    ->setFailureUrl('http://example.com')
    ->setCancelUrl('http://example.com')
    ->setServiceUrl('http://example.com')
    // Your callback controller for handling the result of the payment
    ->setConfirmUrl('http://example.com')
    ->setConsumerUserAgent($_SERVER['HTTP_USER_AGENT'])
    ->setConsumerIpAddress($_SERVER['REMOTE_IP'])
    ;

$result = $client->send($request);

if ($response->hasErrors()) {
    // Show errors in the UI
}

header('Location: '.$response->toObject()->getRedirectUrl());
```

### Usage with a PSR-7 enabled client

If you already use a HTTP client which uses the PSR-7 standard for request and response messages, then you can avoid the provided client completely.

It's a bit more work, but it frees you completely of unwanted, or even conflicting, dependencies.

Every request is capable of converting itself to a standard PSR-7 request message and converting any PSR-7 compliant response to an API response.

``` php
<?php

use GuzzleHttp\Client;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

$context = new Context('Your customer ID', 'Your secret', 'de');
$client = new Client;

$request = InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
    ->setContext($context);

$response = $request->createResponse($client->send($request->createHttpRequest()));

var_dump($response->toObject()->getStorageId());
```

### Implementing your own Adapter

An adapter is any simple function, which takes a PSR-7 compliant request message and converts it to a PSR-7 response.

The signature would look like this:

```
function(Psr\Http\Message\RequestInterface $request): Psr\Http\Message\ResponseInterface
```

For some adapters to popular libraries, have a look at the [src/Adapter directory](src/Adapter).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email christoph@hochstrasser.io instead of using the issue tracker.

## Credits

- [Christoph Hochstrasser][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hochstrasserio/wirecard.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/hochstrasserio/wirecard/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hochstrasserio/wirecard.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/hochstrasserio/wirecard
[link-travis]: https://travis-ci.org/hochstrasserio/wirecard
[link-downloads]: https://packagist.org/packages/hochstrasserio/wirecard
[link-author]: https://github.com/CHH
[link-contributors]: ../../contributors
