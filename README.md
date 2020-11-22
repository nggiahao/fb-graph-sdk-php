# Facebook Graph SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nggiahao/facebook-graph.svg?style=flat-square)](https://packagist.org/packages/nggiahao/facebook-graph)
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

        $user = $graph->request("GET", "/me")
                      ->returnEmtry(Model\User::class) //or return Collection

        echo "Hello, I am $user->name ";
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
