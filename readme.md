# Laravel Media plugin

[![Coverage Status](https://coveralls.io/repos/github/RonasIT/laravel-media/badge.svg?branch=master)](https://coveralls.io/github/RonasIT/laravel-media?branch=master)

## Introduction

This plugin adds the ability for users to work with media files.

## Installation

1. Install the package using the following command:

```sh
composer require ronasit/laravel-media
```

2. Publish the package configuration:

``` sh
php artisan vendor:publish --provider=RonasIT\\Media\\MediaServiceProvider
```

3. For Laravel <= 5.5 add `RonasIT\Media\MediaServiceProvider::class` to config `app.providers` list.
4. Set your project's User model to the `media.classes.user_model` config.

## Usage

All media routes, will be automatically registered with the package installation.

You can manually register package routes in any place in your app routes using `Route::media()` helper:

```php
#routes/api.php

<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['my_auth']], function () {
    Route::media();
});
```

In this case automatically registered package routes will fails with the `404` code error.

## Disable media routes

If you want to disable the automatically registered routes, you can set the `auto_routes_enabled` option to false in the `config/media.php` file:

```php
#config/media.php

return [
    ...

    'auto_routes_enabled' => false,
];
```

## Customizing

You can register only necessary routes using MediaRouteActionEnum:

```php
#routes/api.php

<?php

use Illuminate\Support\Facades\Route;
use RonasIT\Media\Enums\MediaRouteActionEnum;

Route::media(MediaRouteActionEnum::SingleUpload, MediaRouteActionEnum::Delete);
```

## Integration with [LaravelSwagger](https://github.com/RonasIT/laravel-swagger)

This package includes OpenAPI documentation file. To include it to your project's documentation, you need to register it in the `auto-doc.additional_paths` config:

`vendor/ronasit/laravel-media/documentation.json`

## Contributing

Thank you for considering contributing to Laravel Media plugin! The contribution guide can be found in the [Contributing guide](CONTRIBUTING.md).

## License

Laravel Media plugin is open-sourced software licensed under the [MIT license](LICENSE).
 
