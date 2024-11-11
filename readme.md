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

If you need basic media routes,they will be available after installing the package, but they can also be declared elsewhere:

```php
#routes/api.php

<?php

use Illuminate\Support\Facades\Route;

Route::media();
```

## Customizing

You can enable of disable features:

```php
#routes/api.php

<?php

use Illuminate\Support\Facades\Route;

Route::media([
    'create' => true,
    'delete' => true,
    'bulk_create' => false,
    'search' => true,
]);
```

## Integration with [LaravelSwagger](https://github.com/RonasIT/laravel-swagger)

This package includes OpenAPI documentation file. To include it to your project's documentation, you need to register it in the `auto-doc.additional_paths` config:

`vendor/ronasit/laravel-media/documentation.json`

## Contributing

Thank you for considering contributing to Laravel Media plugin! The contribution guide can be found in the [Contributing guide](CONTRIBUTING.md).

## License

Laravel Media plugin is open-sourced software licensed under the [MIT license](LICENSE).
 
