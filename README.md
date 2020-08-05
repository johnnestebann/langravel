# Langravel

Multi Localization for Laravel. The Easy Way

## Overview

If you are building a multilingual website and you need URL management then this is the package for you. It integrates into existing Laravel functionality to support translated URLs and custom locales.

**It supports route caching out of the box.**

This package simplified the whole setup and integration process about localization.

Whatever `mcamara/laravel-localization` package can do, Langravel can do too, but better.

### Features

- [x] Simple installation
- [x] Easy configuration
- [x] Custom locales
- [x] Hide default locale
- [x] Translated routes
- [x] Language selector
- [x] Route caching
- [x] Native Laravel helper functions (`route` and `url`)
- [x] Support for non localized routes
- [x] Slug translation

## Installation

From the command line:

```
composer require johnnestebann/langravel
```

Then, add the `Langravel` trait to your `RouteServiceProvider` class:

```
use Johnnestebann\Langravel\Langravel;

class RouteServiceProvider extends ServiceProvider
{
    use Langravel;
}
```

> If you don't intend to use translated URLs (by default it is set to false) then publish the route file with the command bellow. Otherwise be sure to publish the config file, set `useTranslatedUrls` to `true` and create appropriate route files `{locale}.web.php`.

Publish the localized routes file `langravel.web.php` to your `/routes` directory:

```
php artisan vendor:publish --provider="Johnnestebann\Langravel\ServiceProvider" --tag=route
```

Now, add `$this->mapLocalizedWebRoutes();` to the `map` method in the `RouteServiceProvider`.

**Installation complete!**

---

Put your localized routes in `/routes/langravel.web.php` file. If you are using translated URLs create a route file for each locale `{locale}.web.php` (eg. `en.web.php`). Your non localized routes can remain in the `/routes/web.php` file. To access non localized routes use `URL::getNonLocalizedRoute` or `URL::getNonLocalizedUrl`. See the helpers chapter bellow to find out more.

**That's it!** View the configuration chapter bellow to configure your preferences.

### Configuration

Publish the config file with:

```
php artisan vendor:publish --provider="Johnnestebann\Langravel\ServiceProvider" --tag=config
```

You will find it under `config/langravel.php`.

#### `supportedLocales` [array]

Locale names (codes) can be whatever you want.

_example._ en-GB, hr-HR, en-US, english, croatian, german, de, fr, ...

```
'supportedLocales' => ['hr', 'en'],
```

#### `defaultLocale` [string]

The default application locale must be from one of the locales defined in `supportedLocales`.

```
'defaultLocale' => 'en',
```

#### `hideDefaultLocale` [boolean]

If you want to hide the default locale in your URL set this to true. (The default is `true`.)

_example._ If your default locale is set to `en` then requests to URLs starting with `/en` will be redirected to `/`.

```
'hideDefaultLocale' => true,
```

#### `useTranslatedUrls` [boolean]

This enables you to use localized routes. (The default is `false`.)

If you are using translated URLs for each locale then set this to `true`.

_example._ `/en/about-us` on `en` locale will be `/hr/o-nama` on `hr` locale.

```
'useTranslatedUrls' => true,
```

Once this option is set to  `true` you have to create a routes file for each locale with the prefix of the locale.

`routes/en.web.php`:

```
<?php

Route::get('/', 'SampleController@home')->name('home');
Route::get('contact', 'SampleController@contact')->name('contact');
Route::get('about', 'SampleController@about')->name('about');
```

`routes/hr.web.php`:

```
<?php

Route::get('/', 'SampleController@home')->name('home');
Route::get('kontakt', 'SampleController@contact')->name('contact');
Route::get('o-nama', 'SampleController@about')->name('about');
```

**If you have this enabled, be sure to use route names otherwise it will not work correctly.**

##### Resource routes

If you are using `Route::resource` and have set `useTranslatedUrls` to `true` you will have to set the names for the resource manually or stop using `resource` mapper and manually map resource routes.

*Syntax for the `show` method, but you can apply the same to others.*

`en.web.php`

```
Route::resource('news', 'NewsController')->only(['show']);
```

`hr.web.php`

```
Route::resource('novosti', 'NewsController')->only(['show'])->names([
    'show' => 'news.show'
]);
```

## Helpers

**The default Laravel helper functions `route` and `url` have been changed to support URL localization. So you can use those as you normally would.** This enables you to easily swap the `mcamara/laravel-localization` package with this one. Also, you can install this package, configure your supported locales and your route and URL links will work without the need to change anything.

> The bellow helper methods are for edge cases where you want to retrieve the URL for specific locale or just get the current URL in specific locale.


#### `URL::getNonLocalizedRoute($name, $parameters = [], $absolute = true)`

It will return the URL for the given route name to the route located in `/routes/web.php` file.

**Don't use the same route names for routes in `web.php` and `langravel.web.php`.**


#### `URL::getNonLocalizedUrl($path, $extra = [], $secure = null)`

It will return the URL for the given path as is.


#### `URL::getLocalizedRoute($locale, $name = null, $parameters = [], $absolute = true)`

There are two ways of using this method:

1. Specify just the `$locale` - it will return the current route in the specified locale
2. Specify the `$locale` and the route `$name` - it will return the URL to the given route name for given locale

**If you are using translated routes be sure to use this method if needed.**


#### `URL::getLocalizedUrl($locale, $path = null, $extra = [], $secure = null)`

There are two ways of using this method:

1. Specify just the `$locale` - it will return the current URL in the specified locale
2. Specify the `$locale` and the `$path` - it will return the URL to the given path for given locale


#### `URL::overrideParameters($locale, array|string $parameters)`

This method will set the parameters for given locale, so that when method `getLocalizedRoute`
is called it will use these parameters instead of the already present parameters.

Useful for translating slugs. Remember to include all parameters.


## Language switcher

Use this blade template snippet to enable users to change the language:

```
<ul>
    @foreach(config('langravel.supportedLocales') as $locale)
        <li>
            <a rel="alternate" hreflang="{{ $locale }}" href="{{ URL::getLocalizedRoute($locale) }}">
                {{ $locale }}
            </a>
        </li>
    @endforeach
</ul>
```

_You can modify the template however you want._

## Translating URL slugs/parameters

Add this code to your view file inside `@php {your code here} @endphp` block or inside a controller method where you want to translate slugs:

```
foreach (config('langravel.supportedLocales') as  $locale) {
    URL::overrideParameters($locale, $model->{'slug_'.$locale});
}
```

Replace `$model->{'slug_'.$locale}` with whatever logic you use to get the translated slug for the model from database.

This method will override the route parameters for given locale, so that the language switcher will return correct URLs with translated slugs.

## Contributing

Thank you for considering contributing to Langravel!.

## License

Langravel is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
