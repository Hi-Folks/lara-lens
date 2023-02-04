# Changelog

## 0.4.0 - 2023-02-04
- Support for Laravel 10
- Add requirements check for Laravel 10
- GitHub Actions Workflows: cleaning and fine-tuning
- Using Pint for Check styleguide (PSR12)
- Using PestPHP for testing
- Removing Makefile and using composer scripts to launching code quality tools
- migrating tests from PHPUnit to PestPHP, thanks to @dansysanalyst
- Fix skip-database option, now with works with all "show" options. Before the fix it worked only with "show all" option. 

## 0.3.1- 2022-07-17
- Fine tuning dependencies for PHP 8.1, and Symfony / Doctrine packages
- Drop supprt for PHP7.4, for older PHP and Laravel, use Laralens v0.2.x


## 0.3.0 - 2022-07-17
- Add Termwind package
- Review console output, thanks also to @exodusanto
- Support for PHP 8.0 and greater

## 0.2.7 - 2022-01-23
- Add Support for Laravel 9
- Add tests for PHP8.1
- Drop support for PHP7.3
- Some fine tuning for Static Code analysis

## 0.2.6 - 2021-10-03
Hacktoberfest!!!
We would like to say thank you to @tweichart and @martijnengler for the contributions.
### Add
- Add --all option for showing all information (verbose output). Thanks to @tweichart
- Add --large option for using 120 chars width. Thanks to @martijnengler

## 0.2.5 - 2021-06-18
### Add
- Add some Operating System information:
    - PHP script owner's UID
    - Current User
    - Operating System
    - Hostname
    - Release name
    - Machine Name
    - Version info

## 0.2.4 - 2021-04-18
### Add
- In runtime information, added upload_max_filesize and post_max_size from php configuration ( thanks to @yogendra-revanna )

## 0.2.3 - 2021-04-10
### Add
- Check DEBUG and ENV configuration for production (if production avoid having debug on);
- Check Storage Links and show the user a waring if some directory links are missing

### Change
- improve tests and checks script for Workflows

## 0.2.2 - 2020-11-05
### Add
- Add configuration parameters (config/lara-lens.php) for managing webview: LARALENS_PREFIX, LARALENS_MIDDLEWARE, LARALENS_WEB_ENABLED, thanks to @yogendra-revanna, @JexPY, @Zuruckt;
- Initial support for PHP8-rc;



## 0.2.1 - 2020-09-22
### Add
- Show available PHP extension via --show=php-ext option  (thanks to https://github.com/yogendra-revanna);
- Show PHP ini configuration via --show=php-ini (thanks to https://github.com/yogendra-revanna).

### Change
- Managing default show options. Before this change the default was to show all options. Now we have a lot of option to show (also the long list of PHP extension and PHP ini configuration), so by default LaraLens shows: configuration, runtime, HTTP connection, database, migrations.

## 0.2.0 - 2020-09-20
### Add
- You can watch your LaraLens report with your browser (not just with your terminal);
- Makefile to manage development tasks;
- Add a _timeout_ when checking HTTP connection;
- CI/CD: Add Caching vendors in GitHub actions pipeline.

### Change
- DOCS: Update README, add some docs about skip database connection and database.

## 0.1.20 - 2020-09-11
### Fix
Thanks to [phpstan](https://github.com/phpstan/phpstan) :
- using $line instead of $row
- initialize correctly $show
- re throw exception for HTTP connection


## 0.1.19 - 2020-09-11
### Add
- Add --skip-database in order to execute all checks except database and migration status (it is useful for example if the application it doesn't need the database);

### Change
- Code more PSR2 compliant (phpcs);
- Fix LaraHttpResponse::status(). The method returns the http status code. Close #19


## 0.1.18 - 2020-09-05
### Add
- Support for Laravel 8

## 0.1.17 - 2020-08-30
### Add
- Adding support for Laravel 6
- Check server requirements for  PHP modules needed by Laravel;
- Check server requirements for PHP Version (based on the Laravel version);
- Adding PORT display for Database connection;
- List PHP modules installed, needed by Laravel;

### Change
- Updating test cases

## 0.1.16 - 2020-08-19
### Add
- Adding php linter in CI/CD. We use php 7.2 linter for incoming support to Laravel 6.
### Fix
- Fix syntax in DatabaseLens.php. Close #17 ;

## 0.1.15 - 2020-08-09

### Add
- Add --url-path for using a specific path during HTTP connection. For example --url-path=test (for checking "test" path)
### Change
- Refactor logic with traits for: database connection, configuration, runtime parameters, filesystem, http connections (issue #11);
- Improve the HTTP Connection check (configuration url, url generated)


## 0.1.14 - 2020-08-04

- Refactoring "check" functionality
- add warning messages
- show report check
- add alert green
- managing multiple type of message (warning / error / hint)
- try catch for db connection, for checking files, for scanning directories
- check PDO for database connection


## 0.1.13 - 2020-07-31

### Add
- Add hints for database tables (table exists, column exists)
### Change
- Fix typo in banner.  ( close #6 )
- updated tests


## 0.1.12 - 2020-07-25

### Add
- When a check is not working properly, now it is displayed some hints;
- Add hints for database connection;
- Add hints for HTTP connections.

## 0.1.11 - 2020-07-12

### Add

### Change
- Improved list of languages;
- Update readme

## 0.1.10 - 2020-06-25

### Add
New function for check files.
- check .env file
- check language storage
- list languages available

## 0.1.9 - 2020-06-18

### Add

New runtime config about some paths:

* "langPath" =>"Path to the language files",
* "publicPath" =>" Path to the public / web directory",
* "storagePath" => "Storage directory",
* "resourcePath" =>"Resources directory",
* "getCachedServicesPath" => "Path to the cached services.php",
* "getCachedPackagesPath" => "Path to the cached packages.php",
* "getCachedConfigPath" => "Path to the configuration cache",
* "getCachedRoutesPath" => "Path to the routes cache",
* "getCachedEventsPath" => "Path to the events cache file",
* "getNamespace" => "Application namespace"

## 0.1.8 - 2020-06-18

### Add

:nail_care: Style output table via --style option. You can choose one of these styles (box-double is the default used by LaraLens):
* default
* borderless
* compact
* symfony-style-guide
* box
* box-double

:eyeglasses: Runtime config:
* environmentPath
* environmentFile
* environmentFilePath

### Change

* Refactor for App::"function"()
* update tests
* update readme
* update help (-h)

## 0.1.7 - 2020-06-16

### Add

Database connection:
* connection name
* getQueryGrammar
* getDriverName
* getDatabaseName
* getTablePrefix
* database server version

Runtime configuration:
* Laravel version
* PHP version

## 0.1.6 - 2020-06-01

### Add

* Add show option --show (all|config|runtime|connection|database|migration)

## 0.1.5 - 2020-05-29

### Improve

* managing a better output
* extracting long message from tables
* settings width for columns tables


## 0.1.4 - 2020-05-28

### Fix

* Catch HTTP connection exception

## 0.1.3 - 2020-05-27

### Add

* detect DB connection type;
* get tables for mysql;
* get tables for sqlite;
* count and retrieve last row from a table. Table and column name are as input parameters;
* test database diagnostics;
* update readme for documentation.


## 0.1.2 - 2020-05-22

### Add

* Add new argument as input (it is optional):
    - overview: you can see configuration, http connection, db connection etc.;
    - allconfigs: you can see the verbose configuration from Laravel application. Try to use 'php artisan laralens:diagnostic allconfigs' in your laravel application. You will see the dump of all configuration parameters in json format.

## 0.1.1 - 2020-05-22

### Add

* Add runtime config:
    * App::getLocale()
    * App::environment())
    * Generated url via asset() and url() helpers
* Invoke migrate:status

## 0.1.0 - 2020-05-21

* :tada: initial release
* Add laralens:diagnostic artisan command (Laravel)
* Check config parameter like app.url, app.locale, app.url and database.*
* Check the http connection with "app.url" defined in base configuration
* Check the connection with DB and counts the row for a specific table (users by default)
