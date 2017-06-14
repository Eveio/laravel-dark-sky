## Laravel DarkSky
[![Latest Stable Version](https://poser.pugx.org/naughtonium/laravel-dark-sky/v/stable)](https://packagist.org/packages/naughtonium/laravel-dark-sky)
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads](https://poser.pugx.org/naughtonium/laravel-dark-sky/downloads)](https://packagist.org/packages/naughtonium/laravel-dark-sky)

This provides a Laravel style wrapper for the DarkSky api. For more information regarding request and response formats, visit: https://darksky.net/dev/docs


### Install

Require this package with composer using the following command:

``` bash
$ composer require naughtonium/laravel-dark-sky
```


After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Naughtonium\LaravelDarkSky\LaravelDarkSkyServiceProvider::class,
```

### Configuration

Add the following line to the .env file:

```sh
DARKSKY_API_KEY=<your_darksky_api_key>
```

### Usage
For full details of response formats, visit: https://darksky.net/dev/docs/response

Pass in latitude and longitude coordinates for a basic response
``` php
DarkSky::location(lat, lon)->get()
```
#### Optional Parameters
For full details of optional parameters, visit: https://darksky.net/dev/docs/forecast

Specify which data blocks to exclude/include to reduce data transfer
```php
DarkSky::location(lat, lon)->excludes(['minutely','hourly', 'daily', 'alerts', 'flags'])->get()
DarkSky::location(lat, lon)->includes(['currently'])->get()

// Same output
```

Pass in timestamp to get forecast for that time.
Note: the timezone is relative to the given location

``` php
DarkSky::location(lat, lon)->atTime(timestamp)->get()
```
Specify a language
``` php
DarkSky::location(lat, lon)->language(lang)->get()
```
Specify units
``` php
DarkSky::location(lat, lon)->units(units)->get()
```
Extend the "hourly" response from 48 to 168 hours
Note: Does not work if used with an atTime() timestamp
Please see: https://darksky.net/dev/docs/time-machine
``` php
DarkSky::location(lat, lon)->extend()->get()
```
### Credits

- [Jack Naughton][link-author]
- [All Contributors][link-contributors]

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-author]: https://github.com/holiehandgrenade
[link-contributors]: ../../contributors
