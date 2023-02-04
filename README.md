# LaraLens


![CI/CD Github Actions](https://img.shields.io/github/actions/workflow/status/hi-folks/lara-lens/php-code-quality.yml?style=for-the-badge)
![GitHub last commit](https://img.shields.io/github/last-commit/hi-folks/lara-lens?style=for-the-badge)
![GitHub Release Date](https://img.shields.io/github/release-date/hi-folks/lara-lens?style=for-the-badge)
![Packagist PHP Version](https://img.shields.io/packagist/v/hi-folks/lara-lens?style=for-the-badge)


![LaraLens](https://raw.githubusercontent.com/Hi-Folks/lara-lens/develop/LaraLens-Laravel-Artisan.png)

## What
**LaraLens** is a _Laravel_ artisan command to show you the current configuration of your
application. It is useful to show in your terminal the status of:
* some useful configuration variable;
* the database connection;
* the tables in the database;
* the connection via HTTP request;
* the server requirements (PHP version, PHP modules required and installed, Laravel version etc.).

![LaraLens - diagnostic package for Laravel](https://dev-to-uploads.s3.amazonaws.com/uploads/articles/ryagpu0qqwgy476x5w5b.gif)


## Why
When I have a new Laravel Application deployed on the target server, usually I perform a list of commands in order to check the configuration, the connection to database, inspect some tables, the response of the web server.
I tried to show more information in just one command.
This is useful also when the installation of your Laravel application is on premises, and someone else takes care about the configuration. So, in this scenario usually, as developer, your first question is: "how is configured the application?".

## Installation

You can install the package via composer:

```shell script
composer require hi-folks/lara-lens
```

The Packagist page is:
https://packagist.org/packages/hi-folks/lara-lens

## Usage

```shell script
php artisan laralens:diagnostic
```

### Usage: control database connection
You can see Database Connection information, and you can choose the table to check, and the column used for the "order by" (default created_at):
```shell script
php artisan laralens:diagnostic --table=migrations --column-sort=id
```
To take the last **created** user:
```shell script
php artisan laralens:diagnostic --table=users --column-sort=created_at
```
To take the last **updated** user:
```shell script
php artisan laralens:diagnostic --table=users --column-sort=updated_at
```

### Usage: control the output
You can control the output via the _show_ option. You can define:

* config
* connection
* database
* runtime
* migration
* php-ext
* php-ini
* all

The default for _--show_ option (if you avoid specifying _--show_) is to display: config, connection, database, runtime, migration.


```shell script
php artisan laralens:diagnostic --show=config --show=connection --show=database --show=runtime --show=migration
```

If you want to see only database information:

```shell script
php artisan laralens:diagnostic --show=database
```

If you want to see a verbose output (with also PHP extensions and PHP INI values):

```shell script
php artisan laralens:diagnostic --show=all
```
or better:
```shell script
php artisan laralens:diagnostic --all
```


If you want to see only PHP extensions:
```shell script
php artisan laralens:diagnostic --show=php-ext
```

If you want to see only PHP INI values:
```shell script
php artisan laralens:diagnostic --show=php-ini
```

### Usage: skip database connection and database diagnostics
If your Laravel application doesn't use the database, you can skip the database inspection with --skip-database option.

```shell script
php artisan laralens:diagnostic --skip-database
```

### Usage: show some oprating system information
You can show some operating system information like:
- PHP script owner's UID
- Current User
- Operating System
- Hostname
- Release name
- Machine Name
- Version info

using _"--show os"_ option or _"--show all"_ option
```shell
php artisan laralens:diagnostic  --show os
```

### Usage: change the style of output table
You can choose one of these styles via *--style=* option:

* default
* borderless
* compact
* symfony-style-guide
* box
* box-double

For example:
```sh
php artisan laralens:diagnostic --style=borderless
```

### Usage: change the width of the output table
To use 120 characters (wide terminal), you can use --large option
```sh
php artisan laralens:diagnostic --large
```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Usage as Web Page

LaraLens provides information with the command line via terminal as shown above.
You have also the opportunity to see the information via your web browser.
You can enable web view via the configuration.

Publish default configuration for LaraLens in your Laravel Application:
```shell script
php artisan vendor:publish --provider="HiFolks\LaraLens\LaraLensServiceProvider" --tag="config"
```

After that,you will have a new configuration file in your config directory. The file is: config/lara-lens.php

Add `LARALENS_WEB_ENABLED=on` option to your .env file. You may also override the default parameters for `LARALENS_PREFIX` and `LARALENS_MIDDLEWARE`
```
# Wether Web Report should be enabled or not
LARALENS_WEB_ENABLED=on
# Path prefix in order to acess the Web Report via browser
LARALENS_PREFIX="laralens"
# Which middleware should be used when acessing the Web Report, separete more with ;
LARALENS_MIDDLEWARE="web;auth.basic"
```

For example, with the configuration above you would have enabled the web view (_web-enabled_ parameter) under _/laralens_test/_ path and with the `web` and `auth.basic` middleware

```php
return [
    'prefix' => env('LARALENS_PREFIX', 'laralens'), // URL prefix (default=laralens)
    'middleware' => explode(';', env('LARALENS_MIDDLEWARE', 'web')), // middleware (default=web) more separate with ;
    'web-enabled' => env('LARALENS_WEB_ENABLED', 'off') // Activate web view (default=off)
];
```

### Web view configuration hint
LaraLens shows some internal configuration of your Laravel application, so I suggest you to disable it in a production environment.
To disable LaraLens web view, make sure to remove LARALENS_WEB_ENABLED config from .env file or set it to _off_
```
LARALENS_WEB_ENABLED=off
```




## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Submit ideas or feature requests or issues

* Take a look if your request is already there [https://github.com/Hi-Folks/lara-lens/issues](https://github.com/Hi-Folks/lara-lens/issues)
* If it is not present, you can create a new one [https://github.com/Hi-Folks/lara-lens/issues/new](https://github.com/Hi-Folks/lara-lens/issues/new)


## Credits

- [Roberto Butti](https://github.com/hi-folks)
- [All Contributors](https://github.com/Hi-Folks/lara-lens/graphs/contributors)
- [Laravel Package Boilerplate](https://laravelpackageboilerplate.com)

## Who talks about LaraLens
- [Laravel News](https://laravel-news.com/inspect-application-configuration-with-laralens)
- [Medium Article](https://levelup.gitconnected.com/laralens-a-laravel-command-for-inspecting-configuration-2bbb4e714cf7)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
