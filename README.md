# Wirecard

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Install

Via Composer

``` bash
$ composer require hochstrasserio/wirecard
```

## Usage

### Initialization

``` php
use Hochstrasser\Wirecard\Adapter;
use Hochstrasser\Wirecard\Context;
use Hochstrasser\Wirecard\Client;

$context = new Context('Your customer ID', 'Your secret', 'de');
$client = new Client($context, Adapter::defaultAdapter());
```

### InitDataStorageRequest

``` php
use Hochstrasser\Wirecard\Request\Seamless\InitDataStorageRequest;

$response = $client->execute(
        (new InitDataStorageRequest)
        ->setOrderIdent('1234')
        ->setReturnUrl('http://www.example.com')
);

var_dump($response->hasErrors());
var_dump($response->toObject()->getStorageId());
var_dump($response->toObject()->getJavascriptUrl());
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
