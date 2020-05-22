# Changelog

All notable changes to `lara-lens` will be documented in this file

## 0.1.1 - WIP

### Add
* Add runtime config:
    * App::getLocale()
    * App::environment())
    * Generated url via asset() and url() helpers


## 0.1.0 - 2020-05-21

* :tada: initial release
* Add laralens:diagnostic artisan command (Laraval)
* Check config parameter like app.url, app.locale, app.url and database.*
* Check the http connection with app.url defined in base configuration
* Check the connection with DB and counts the row for a specific table (users by default)