# LaraLens


![CICD Github Actions](https://github.com/Hi-Folks/lara-lens/workflows/PHP%20Composer/badge.svg)
![GitHub last commit](https://img.shields.io/github/last-commit/hi-folks/lara-lens)
![GitHub Release Date](https://img.shields.io/github/release-date/hi-folks/lara-lens)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/hi-folks/lara-lens)



## What
LaraLens is a command to show you the up and running configuration of your  appliocation. It lists:
* Some configuration used by application
* The connection with Database
* The status of a table in the database
* The connection with the URL application via HTTP

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

New in 0.1.3, you can see Database diagnostic and you can define the table to check ,and the column used for the sorting:
```sh
php artisan laralens:diagnostic --table=migrations --column-sort=id
```
To take the last **created** user:
```
php artisan laralens:diagnostic --table=users --column-sort=created_at
```
To take the last **updated** user:
```
php artisan laralens:diagnostic --table=users --column-sort=updated_at
```



### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Submit ideas or feature requests or issues

* Take a look if your request is already there [https://github.com/Hi-Folks/lara-lens/issues](https://github.com/Hi-Folks/lara-lens/issues)
* If it is not present, you can create a new one [https://github.com/Hi-Folks/lara-lens/issues/new](https://github.com/Hi-Folks/lara-lens/issues/new)


## Credits

- [Roberto Butti](https://github.com/hi-folks)
- [All Contributors](../../contributors)
- [Laravel Package Boilerplate](https://laravelpackageboilerplate.com)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
