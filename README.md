# ⚠️ IMPORTANT

Starting with September 30, 2020 Wirecard the methods used by this SDK are no longer supported by Wirecard.

This repository is therefore archived and is no longer maintained. You're free to fork this code under the terms of the license.

# Wirecard

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

An unofficial SDK for making payments with [Wirecard](http://wirecard.at).

For general information about making payments with Wirecard in your application, visit the [Wirecard Integration Wiki][Integration Wiki].

Get an account for the Integration Wiki, by signing up on the Wiki’s signup page for free.

[Integration Wiki]: https://guides.wirecard.at/

SDK Coverage:

- [x] Wirecard Checkout Page
- [x] Wirecard Seamless Checkout
- [x] Seamless Backend operations
- [x] Checkout Page Backend operations

## Install

Via Composer in your project directory:

``` bash
$ composer require hochstrasserio/wirecard
```

## Usage

### Setup

This library does not ship with functionality to send HTTP requests over the wire. You need to get a library to do this for you. Any PSR-7 compliant library will work, like [Guzzle v6+][guzzle6] or the [Ivory HTTP Adapters library][ivory].

    $ composer require 'guzzlehttp/guzzle:^6.0.0'

[guzzle6]: http://guzzle.readthedocs.org/en/latest/
[ivory]: http://github.com/egeloen/ivory-http-adapter

Then, configure a Wirecard *Context* with your Customer ID, Customer secret, and optionally your Shop ID. You should have received both by Wirecard. Demo or Test credentials are also available in the [Integration Wiki][].

``` php
<?php

use Hochstrasser\Wirecard\Context;

// Constructor takes Customer ID, Customer Secret, Language code, and Shop ID:
$context = new Context([
    'customer_id' => 'D200001',
    'secret' => 'B8AKTPWBRMNBV455FG6M2DANE99WU2',
    'language' => 'de',
    'shop_id' => 'qmore',
    'hashing_method' => Context::HASHING_HMAC,
]);
```

The *Context* has some more options that you can pass to the constructor:

1. `customer_id`: Your customer ID, which you got from Wirecard or the [Integration Wiki][]
2. `secret`: Your customer secret, which you got from Wirecard or the [Integration Wiki][]. Do not share this with anyone
3. `language`: Language of Wirecard’s UIs and error messages
4. `shop_id` (Optional): Set this if one set of credentials is used for many shops (if Wirecard tells you to)
5. `javascript_script_version` (Optional): Enable [PCI DSS SAQ A compliance features](https://guides.wirecard.at/wcs:pci3_fallback)
6. `user_agent` (Optional): Library user agent used for HTTP requests, you can optionally set this to your site’s name
7. `hashing_method` (Optional): The used method for computing the fingerprint hash. If omitted, the old method `Context::HASHING_SHA` is used for compatibility reasons. New Wirecard customers should use `Context::HASHING_HMAC`, as it is the current Wirecard default. The demo customer still uses `Context::HASHING_SHA`, though.

### SDK Patterns

#### Requests

All requests are simple classes which implement the [WirecardRequestInterface](src/Request/WirecardRequestInterface.php) and can be constructed directly. Most classes have a specific named constructor, which starts with `with*`, e.g. `withBasket`, or `withOrderIdentAndReturnUrl`. These should be preferred over the simple constructor.

#### Sending Requests

Requests are converted to PSR-7 compatible requests with the
`createHttpRequest` method. The context has to be set before. Then you can send the PSR-7 compatible request message with your HTTP client. The `createResponse` method converts any PSR-7 compatible response object to WirecardResponseInterface after you sent the request.

```php
<?php

$client = new \GuzzleHttp\Client;

$request = InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
    ->setContext($context);

$httpResponse = $client->send($request->createHttpRequest());
$response = $request->createResponse($httpResponse);
```

It may seem a bit cumbersome, but it has the big benefit of freeing you of any imposed dependency on a particular version of a HTTP client library, like Guzzle, or Buzz. You may use the client that suits you best, or offers you the best performance, which will always be better, faster, and have more features than anything this library could ever provide. Plus there’s little risk of dependencies of the SDK conflicting with your application’s.

#### Using the WirecardHelper

There’s a small utility included, the `WirecardHelper` which saves you a couple of lines every time you make a request. Initialize with the context and send the request with a configured client.

You pass it two things: the Wirecard context and a function to send a `Psr\Http\Message\RequestInterface` over the wire which returns a `Psr\Http\Message\ResponseInterface`.

```php
<?php

use Hochstrasser\Wirecard\Helper\WirecardHelper;

// Guzzle 6
$guzzle = new Guzzle\Client;

$helper = new WirecardHelper($context, function ($request) use ($guzzle) {
    return $guzzle->send($request);
});

// Sets the context, converts the request and makes the http response to a
// WirecardResponseInterface
$response = $helper->send(InitDataStorageRequest::withOrderIdentAndReturnUrl(
    '1234', 'http://example.com'
));

$dataStorage = $response->toObject();
```

Note, that the helper sends requests only synchronously. If you want async requests you have to use your HTTP library directly.

#### Required Parameters

Requests know about their required parameters. If a known required parameter is missing, then a [RequiredParameterMissingException](src/Exception/RequiredParameterMissingException.php) is thrown.

#### Responses

All responses implement the [WirecardResponseInterface](src/Response/WirecardResponseInterface.php).

##### Getting Results

Results are retrieved by using the `toObject` or `toArray` methods. The `toObject` method is usually used. This method returns a model class, which has methods to conveniently retrieve returned values. This methods are defined in the model class, so your IDE should be able to suggest them to you.

The `toArray` method returns the raw response parameters returned by the request. This is useful for debugging and checking with the official documentation.

```php
<?php

$a = $response->toArray();
var_dump($a['redirectUrl']);

$b = $response->toObject();
var_dump($b->getRedirectUrl());
```

##### Errors

Every response has the `hasErrors` method, which checks for any returned errors. All returned errors can be retrieved as array by using `getErrors` on the response object.

```php
<?php

if ($response->hasErrors()) {
    foreach ($response->getErrors() as $error) {
        echo $error, "<br>";
    }
}
```

### Wirecard Checkout Page

With Wirecard Checkout Page you don’t handle the UI for selecting the payment type or storing the credit card data. The InitCheckoutPageRequest is also an exception in another way: it can’t be sent with the client, you have to use a HTML form, which has to be submitted by the customers themselves.

The InitCheckoutPageRequest takes the same parameters and is initialized the same way as the InitPaymentRequest from Seamless Checkout.

The only differences are:

* `confirmUrl` is not required
* `paymentType` does not have to be set in the request object, can be set in the form

[Example:](example/checkout-page/index.php)

```php
<?php

use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Model\Common\PaymentType;
use Hochstrasser\Wirecard\Request\CheckoutPage\InitCheckoutPageRequest;

$context = new Context(['customer_id' => 'D200001', 'secret' =>  'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'language' => 'de']);
$request = InitCheckoutPageRequest::with()
    ->setPaymentType(PaymentType::Select)
    ->setContext($context)
    ->setAmount('33.00')
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
```

Another major difference of Checkout Page is, that the `successUrl` receives all payment response parameters as a POST request, just like the `confirmUrl`. The `confirmUrl` can additionally be set to receive the parameters independent of user actions, for you to record for reference.

You can find out more about this here: [Receiving the Results of the Payment Process in your Online Shop](https://guides.wirecard.at/wcp:integration#receiving_the_payment_process_result)

### Wirecard Seamless Checkout

With Wirecard Seamless Checkout, your customer uses your own custom page to select the payment methods which you did enable for your site. How this UI looks and works is completely up to you.

There are a couple of requests that you have to make:

1. `InitDataStorageRequest`: Usually you want not to deal with PCI compliance yourself and want Wirecard to take care of it. This request initializes a data storage, which is on Wirecard’s servers and securely stores the customer’s credit card data for you. The only way to store data is via an JavaScript library. The `InitDataStorageRequest` returns the URL for including the library after a succcessful call. This is only necessary if you are taking credit card payments and has to be made before requesting the payment.
2. Optionally, `ReadDataStorageRequest`: You can use this request to display the credit card data, which the customer entered, with all sensitive information removed. You can use this to give your customer a last look at the entered payment data before making the payment.
3. `InitPaymentRequest`: Makes the payment and returns an URL. Redirect the customer to the URL to complete the requested payment. If this was successful, your site gets a POST request by Wirecard at the Confirm URL, which contains the payment details, like the transaction number. Finally the customer is then redirected back to your site.

#### InitDataStorageRequest

Response Model: [DataStorageInitResult](src/Model/Seamless/Frontend/DataStorageInitResult.php)

Sets up the data storage and returns a URL to the JavaScript library and the storage ID. It’s only necessary to make this request when your customer intends to pay by credit card. The storage ID can be used to read masked data out of the data storage, and has to be passed to the `InitPaymentRequest` later on.

To create the request use the `withOrderIdentAndReturnUrl` named constructor. The "order ident" should be a unique value for the checkout and the "return URL" passed as second argument is used for legacy browsers.

``` php
<?php

use Hochstrasser\Wirecard\Request\Seamless\Frontend\InitDataStorageRequest;

$request = InitDataStorageRequest::withOrderIdentAndReturnUrl('1234', 'http://example.com')
    ->setContext($context);

$response = $request->createResponse($client->send($request->createHttpRequest()));

// Store the storage ID for later usage with the payment request
$_SESSION['wirecardDataStorageId'] = $response->toObject()->getStorageId();

var_dump($response->hasErrors());
var_dump($response->toObject()->getStorageId());
var_dump($response->toObject()->getJavascriptUrl());
```

#### ReadDataStorageRequest

Response Model: [DataStorageReadResult](src/Model/Seamless/Frontend/DataStorageReadResult.php)

Reads the data storage and returns an array of masked [customer data](src/Model/Seamless/Frontend/PaymentInformation.php).

``` php
<?php

use Hochstrasser\Wirecard\Request\Seamless\Frontend\ReadDataStorageRequest;

$request = ReadDataStorageRequest::withStorageId($_SESSION['wirecardDataStorageId'])
    ->setContext($context);

$response = $request->createResponse($client->send($request->createHttpRequest()));

var_dump($response->hasErrors());
var_dump($response->toObject()->getStorageId());

foreach ($response->toObject()->getPaymentInformation() as $paymentInformation) {
    var_dump($paymentInformation->getMaskedPan());
}
```

#### Making a payment with InitPaymentRequest

Response Model: [InitPaymentResult](src/Model/Seamless/Frontend/InitPaymentResult.php)

Requests the payment and returns a redirect URL. Redirect your customer to this URL to finish the payment.

##### Required payment parameters

* `amount`: Payment amount in the currency’s base units, e.g. `12.00`. Set from the basket when `withBasket` is used. Set as string to avoid rounding errors, which can happen with floats.
* `currency`: ISO Currency Code, e.g. `EUR`. Set from the basket when `withBasket` is used.
* `paymentType` ([PaymentType](src/Model/Common/PaymentType.php)): The payment type, which was selected by your customer. You can use `PaymentType::isValid()` to validate the identifier of the requested payment type.
* `orderDescription`: Description of your order, can be e.g. structured data about the order.
* `successUrl`: If the payment was successful, then the customer gets redirected by Wirecard to this URL.
* `failureUrl`: If there was some kind of failure during confirmation of the checkout, then the customer gets redirected by Wirecard to this URL.
* `cancelUrl`: If the payment was cancelled during confirmation (e.g. at the PayPal screen), then the customer gets redirected by Wirecard to this URL.
* `serviceUrl`: A page which lists your terms of service and contact information, usually your "Contact us" or "Imprint" page.
* `confirmUrl`: An endpoint in your site, which receives detailed information about the payment via a POST request by Wirecard after the customer successfully confirmed the payment. Usually you send the order confirmation email at this endpoint.
* `consumerUserAgent` and `consumerIpAddress`: For optional prevention of duplicate payments.

These parameters can be either set with the `addParam` method on the request object, or with their respective `set*` method, e.g. `setSuccessUrl`.

There are a lot more optional parameters for payment requests, to e.g. make recurring payments, set the statement which is displayed on the credit card bill, and more. Look at the [Documentation about Request Parameters](https://guides.wirecard.at/request_parameters) for more information.

##### Adding a Basket

The payment request uses a [Basket](src/Model/Common/Basket.php) filled with [Basket Items](src/Model/Common/BasketItem.php) to show the customer’s products at the checkout screen of e.g. PayPal. You can also use this to easily set the amount and currency of your order. In most cases you should be able to infer the basket from your site’s own basket model.

**Note:** If a basket is set on the payment request, Wirecard validates on the server if all prices of the basket items and the total amount add up correctly. Otherwise, Wirecard returns an error.

``` php
<?php

use Hochstrasser\Wirecard\Model\Common\Basket;
use Hochstrasser\Wirecard\Model\Common\BasketItem;

// Create the basket, this is optional for most payment methods, but probably
// can be automatically created from your shop’s cart model

$basket = new Basket();
$basket->setAmount('17.00');
$basket->setCurrency('EUR');
$basket->addItem((new BasketItem)
    ->setArticleNumber('A001')
    ->setDescription('Product A1')
    ->setQuantity(1)
    ->setUnitPrice('10.00')
    ->setTax('1.00')
);
$basket->addItem((new BasketItem)
    ->setArticleNumber('SHIPPING')
    ->setDescription('Shipping')
    ->setQuantity(1)
    ->setUnitPrice('5.00')
    ->setTax('1.00')
);
```

##### Adding Shipping and Billing Information

[ShippingInformation]: src/Model/Common/ShippingInformation.php
[BillingInformation]: src/Model/Common/BillingInformation.php

Some payment types require shipping and billing information. The SDK provides [ShippingInformation][] and [BillingInformation][] classes to help you with that.

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

If the customer’s billing information matches the shipping information, then the [BillingInformation][] class provides a named constructor for convenience. Otherwise the [BillingInformation][] class features the same methods as [ShippingInformation][].

```php
<?php

$billingInformation = BillingInformation::fromShippingInformation($shippingInformation);
```

A valid billing information requires two additional parameters: The customer’s email and their birth date (as `\DateTime` object):

``` php
<?php

$billingInformation->setConsumerEmail("max@mustermann.com");
$billingInformation->setConsumerBirthDate(new \DateTime("Sept 1 1970"));
```

Finally you can add the shipping and billing information to the request with the `setConsumerShippingInformation` and `setConsumerBillingInformation` methods.

##### Requesting the payment

With our basket object in hand, we can now initialize the payment request:

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
    ->setConsumerShippingInformation($shippingInformation)
    ->setConsumerBillingInformation($billingInformation)
    ->setConsumerUserAgent($_SERVER['HTTP_USER_AGENT'])
    ->setConsumerIpAddress($_SERVER['REMOTE_IP'])
    ->setContext($context)
    ;

// Set the data storage ID if the data storage was initialized
if (isset($_SESSION['wirecardDataStorageId'])) {
    $request->setStorageId($_SESSION['wirecardDataStorageId']);
}

$response = $request->createResponse($client->send($request->createHttpRequest()));
```

Using the response model, you now can redirect customers to Wirecard’s payment confirmation flow. This flow will then connect your customers to their selected payment provider, e.g. PayPal or 3-D secure, where they’ll confirm the payment.

```php
<?php

if ($response->hasErrors()) {
    // Show errors in the UI
}

// Redirect if no errors happened
header('Location: '.$response->toObject()->getRedirectUrl());
```

##### Handling response parameters

Afterwards, Wirecard will send a POST request to the URL passed as confirmUrl with the response parameters of the payment request. These parameters contain the order number, and more. Store these parameters for reference.

You can use the provided Fingerprint class to verify the supplied response fingerprint:

``` php
<?php

use Hochstrasser\Wirecard\Fingerprint;

$responseParameters = $_POST;
$fingerprint = Fingerprint::fromResponseParameters($responseParameters, $context);

if (!hash_equals($responseParameters['responseFingerprint'], (string) $fingerprint)) {
    // Fingerprint is not valid, abort
    exit;
}

// Fingerprint is valid. Go on and store the response parameters
```

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
