# LaraLens

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hi-folks/lara-lens.svg?style=flat-square)](https://packagist.org/packages/hi-folks/lara-lens)
![PHP Composer](https://github.com/Hi-Folks/lara-lens/workflows/PHP%20Composer/badge.svg)

[![Total Downloads](https://img.shields.io/packagist/dt/hi-folks/lara-lens.svg?style=flat-square)](https://packagist.org/packages/hi-folks/lara-lens)

## Why
When I have a new Laravel Application deployed on the target server, usually I perform a list of commands in order to check the configuration, the connetion to database, inspect some tables, the response of the web server.
I tried to list these commands in 1 command.
This is useful also when the installation of your Laravel application is on premises and some one else take care about configuration. So in this scenario usually, as developer your first question is: "how is configured the application?".

## Installation

You can install the package via composer:

```bash
composer require hi-folks/lara-lens
```

## Usage

```bash
php artisan laralens:diagnostic
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email roberto.butti@gmail.com instead of using the issue tracker.

## Credits

- [Roberto Butti](https://github.com/hi-folks)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).