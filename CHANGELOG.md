# Changelog

## 0.1.2 - 2020-05-22

### Add

* Add new argument as input (it is optional):
    - overview: you can see configuration, http connection, db connection etc
    - allconfigs: you can see verbose configuration from Laravel application. Try to use 'php artisan laralens:diagnostic allconfigs' in your laravel application. You will see the dump of all configuration parameters in json format

## 0.1.1 - 2020-05-22

### Add

* Add runtime config:
    * App::getLocale()
    * App::environment())
    * Generated url via asset() and url() helpers
* Invoke migrate:status

## 0.1.0 - 2020-05-21

* :tada: initial release
* Add laralens:diagnostic artisan command (Laraval)
* Check config parameter like app.url, app.locale, app.url and database.*
* Check the http connection with app.url defined in base configuration
* Check the connection with DB and counts the row for a specific table (users by default)