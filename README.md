# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soiposervices/httpauth.svg?style=flat-square)](https://packagist.org/packages/soiposervices/httpauth)
[![Total Downloads](https://img.shields.io/packagist/dt/soiposervices/httpauth.svg?style=flat-square)](https://packagist.org/packages/soiposervices/httpauth)
![GitHub Actions](https://github.com/soiposervices/httpauth/actions/workflows/main.yml/badge.svg)

This package provides a simple http auth middleware. It can be enable for specific environments and it allows white listed ip to skip the auth check.

## Installation

You can install the package via composer:

```bash
composer require soiposervices/httpauth
```

## Usage

Make sure 

To use this package simply add the HttpAuth middleware to the routes your want to enable the authentication.

You can set the admin and password, using the env variables *HTTP_AUTH_USER* and *HTTP_AUTH_PASS* 

 
To whitelist some ip addresses set a comma delimited string in the env variable *HTTP_AUTH_WHITELIST*


To enable for specific enviroments set the env vairable *HTTP_AUTH_ENABLED_ENV* eg: "prod,stage"


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email luigi@soiposervices.com instead of using the issue tracker.

## Credits

-   [Luigi Laezza](https://github.com/soiposervices)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.