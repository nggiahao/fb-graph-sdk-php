# Facebook Graph SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nggiahao/facebook-graph.svg?style=flat-square)](https://packagist.org/packages/nggiahao/facebook-graph)
[![Build Status](https://img.shields.io/travis/nggiahao/facebook-graph/master.svg?style=flat-square)](https://travis-ci.org/nggiahao/facebook-graph)
[![Quality Score](https://img.shields.io/scrutinizer/g/nggiahao/facebook-graph.svg?style=flat-square)](https://scrutinizer-ci.com/g/nggiahao/facebook-graph)
[![Total Downloads](https://img.shields.io/packagist/dt/nggiahao/facebook-graph.svg?style=flat-square)](https://packagist.org/packages/nggiahao/facebook-graph)


## Installation

You can install the package via composer:

```bash
composer require nggiahao/facebook-graph
```

## Usage

``` php
use Nggiahao\Facebook\Facebook;
use Nggiahao\Facebook\Model;

class UsageExample
{
    public function run()
    {
        $accessToken = 'xxx';

        $graph = new Facebook();
        $graph->setAccessToken($accessToken);

        $user = $graph->createRequest("GET", "/me")
                      ->setReturnType(Model\User::class)
                      ->execute();

        echo "Hello, I am $user->getName() ";
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Security

If you discover any security related issues, please email giahao9899@gmail.com instead of using the issue tracker.

## Credits

- [Nguyen Gia Hao](https://github.com/nggiahao)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.