cookie-helper
=============

Simple class for working with cookie

## Installation

This package is available via Composer:

```json
{
    "require": {
        "dmitrymomot/cookie-helper": "1.*"
    }
}
```

## Example of usage

```php
$cookie = new \Helper\Cookie;
$cookie->set('cookie_name', 'cookie test value');

// returns for the first time - 'default value', after page reload - 'cookie test value'
echo $cookie->get('cookie_name', 'default value');

// delete cookie
$cookie->delete('cookie_name');
```

## License

The MIT License (MIT). Please see [License File](https://github.com/dmitrymomot/cookie-helper/blob/master/LICENSE) for more information.
