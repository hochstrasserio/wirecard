# Wirecard

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

An unofficial, but well made library for making payments with the [Wirecard](http://wirecard.at).

For general information about making payments with Wirecard in your application, see the [Wirecard Integration Wiki][Integration Wiki].

You can get an account for the Integration Wiki for free by signing up on the Wiki’s signup page.

[Integration Wiki]: http://integration.wirecard.at

## Install

Via Composer in your project directory:

``` bash
$ composer require hochstrasserio/wirecard
```

## Usage

### Initialization

First, configure a Wirecard *Context* with your customer ID and customer secret, which you got from Wirecard:

``` php
<?php

use Hochstrasser\Wirecard\Context;

// Constructor takes Customer ID, Customer Secret, Language code, and Shop ID:
$context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de', 'qmore');
```

The *Context* has some more options that you can pass to the constructor:

1. `customerId`: Your customer ID, which you got from Wirecard or the [Integration Wiki][]
2. `secret`: Your customer secret, which you got from Wirecard or the [Integration Wiki][]. Do not share this with anyone
3. `language`: Language of Wirecard’s UIs and error messages
4. `shopId` (Optional): Set this if one set of credentials is used for many shops (if Wirecard tells you to)
5. `javascriptScriptVersion` (Optional): Enable [PCI DSS SAQ A compliance features](https://integration.wirecard.at/doku.php/wcs:pci3_fallback:start)
6. `userAgent` (Optional): Library user agent used for HTTP requests, you can optionally set this to your site’s name

Next we’ll create the *Client*. The *Client* sends your requests to the Wirecard API, using the context:

``` php
<?php

use Hochstrasser\Wirecard\Client;
use Hochstrasser\Wirecard\Adapter;

$client = new Client($context, Adapter::defaultAdapter());
```

Now with a client initialized, we can start to make some payments.

### Wirecard Seamless Checkout (WCS)

With Wirecard Seamless Checkout, your customer uses your own custom page to select the payment methods you enabled for your site. How this UI looks and works is completely up to you.

There are a couple of requests that you have to make:

* `InitDataStorageRequest`: Usually you want not to deal with PCI compliance yourself and want Wirecard to take care of it. This request initializes a data storage, which is on Wirecard’s servers and securely stores the customer’s credit card data for you. The only way to store data is via an JavaScript library. The `InitDataStorageRequest` returns the URL for including the library after a succcessful call. This is only necessary if you are taking credit card payments and has to be made before requesting the payment.
* Optionally, `ReadDataStorageRequest`: You can use this request to display the credit card data, which the customer entered, with all sensitive information removed. You can use this to give your customer a last look at the entered payment data before making the payment.
* `InitPaymentRequest`: Makes the payment and returns an URL. Redirect the customer to the URL to complete the requested payment. If this was successful, your site gets a POST request by Wirecard at the Confirm URL, which contains the payment details, like the transaction number. Finally the customer is then redirected back to your site.

#### InitDataStorageRequest

Response Model: [DataStorageInitResult](src/Model/Seamless/Frontend/DataStorageInitResult.php)

Sets up the data storage and returns a URL to the JavaScript library and the storage ID. It’s only necessary to make this request when your customer intends to pay by credit card. The storage ID can be used to read masked data out of the data storage, and has to be passed to the `InitPaymentRequest` later on.

To create the request use the `withOrderIdentAndReturnUrl` named constructor. The "order ident" should be a unique value for the checkout and the "return URL" passed as second argument is used for legacy browsers.

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

Response Model: [DataStorageReadResult](src/Model/Seamless/Frontend/DataStorageReadResult.php)

Reads the data storage and returns an array of masked [customer data](src/Model/Seamless/Frontend/PaymentInformation).

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

Response Model: [DataStorageReadResult](src/Model/Seamless/Frontend/InitPaymentResult.php)

Requests the payment and returns a redirect URL. Redirect your customer to this URL to finish the payment.

##### Required payment parameters

* `paymentType` ([PaymentType](src/Model/Common/PaymentType.php)): The payment type, which was selected by your customer. You can use `PaymentType::isValid()` to validate the identifier of the requested payment type.
* `orderDescription`: Description of your order, can be e.g. structured data about the order.
* `successUrl`: If the payment was successful, then the customer gets redirected by Wirecard to this URL.
* `failureUrl`: If there was some kind of failure during confirmation of the checkout, then the customer gets redirected by Wirecard to this URL.
* `cancelUrl`: If the payment was cancelled during confirmation (e.g. at the PayPal screen), then the customer gets redirected by Wirecard to this URL.
* `serviceUrl`: A page which lists your terms of service and contact information, usually your "Contact us" or "Imprint" page.
* `confirmUrl`: An endpoint in your site, which receives detailed information about the payment via a POST request by Wirecard after the customer successfully confirmed the payment. Usually you send the order confirmation email at this endpoint.
* `consumerUserAgent` and `consumerIpAddress`: For optional prevention of duplicate payments.

These parameters can be either set with the `addParam` method on the request object, or with their respective `set*` method, e.g. `setSuccessUrl`.

There are a lot more optional parameters for payment requests, to e.g. make recurring payments, set the statement which is displayed on the credit card bill, and more. Look at the [Documentation about Request Parameters](https://integration.wirecard.at/doku.php/request_parameters) for more information.

##### Adding a Basket

The payment request uses a basket to show the customer’s products at the checkout screen of e.g. PayPal. You can also use this to easily set the amount and currency of your order. In most cases you should be able to infer the basket from your site’s own basket model.

``` php
<?php

use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;

// Create the basket, this is optional for most payment methods, but probably
// can be automatically created from your shop’s cart model

$basket = new Basket();
$basket->setAmount(17.00);
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
```

##### Adding Shipping and Billing Information

Some payment types require shipping and billing information.

``` php
<?php

use Hochstrasser\Wirecard\Model\Common\ShippingInformation;
use Hochstrasser\Wirecard\Model\Common\BillingInformation;

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
```

If the customer’s billing information matches the shipping information, then the `BillingInformation` class provides a named constructor for convenience. Otherwise the `BillingInformation` class features the same methods as `ShippingInformation`.

```php
<?php

$billingInformation = BillingInformation::fromShippingInformation($shippingInformation);
```

A valid billing information requires two additional parameters: The customer’s email and their birth date:

``` php
<?php

$billingInformation->setConsumerEmail("max@mustermann.com");
$billingInformation->setConsumerBirthDate(new \DateTime("Sept 1 1970"));
```

Finally you can add the shipping and billing information to the request with the `setConsumerShippingInformation` and `setConsumerBillingInformation` methods.

##### Requesting the payment

With our basket object in hand, we can now easily initialize the payment request:

``` php
<?php

use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitPaymentRequest;

if (!PaymentType::isValid($_POST['paymentType'])) {
    // Show error and return
}

$request = InitPaymentRequest::withBasket($basket)
    // Set the payment type selected by the user in the frontend
    ->setPaymentType($_POST['paymentType'])
    ->setOrderDescription('Some test order')
    ->setSuccessUrl('http://example.com')
    ->setFailureUrl('http://example.com')
    ->setCancelUrl('http://example.com')
    ->setServiceUrl('http://example.com')
    // Your callback controller for handling the result of the payment, you will
    // receive a POST request at this URL
    ->setConfirmUrl('http://example.com')
    ->setConsumerUserAgent($_SERVER['HTTP_USER_AGENT'])
    ->setConsumerIpAddress($_SERVER['REMOTE_IP'])
    ;

// Set the data storage ID if the data storage was initialized
if (isset($_SESSION['dataStorageId'])) {
    $request->setStorageId($_SESSION['dataStorageId']);
}

$result = $client->send($request);

if ($response->hasErrors()) {
    // Show errors in the UI
}

// Redirect customers so they can confirm the payment with 3-D secure, PayPal,
// or something similar
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
