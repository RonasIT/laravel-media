# Laravel Media plugin

## Introduction

This plugin adds the ability for users to work with media files.

## Installation

1. Install the package using the following command: `composer require ronasit/laravel-media`
2. Run `php artisan vendor:publish`
3. For Laravel <= 5.5 add `RonasIT\Media\MediaServiceProvider::class` to config `app.providers` list.
4. In `media.permitted_types` config, specify the allowed types of media files.
5. In `media.classes.user_model` config, specify the model class that will have a relationship with the media class.

## Contributing

Thank you for considering contributing to Laravel Media plugin! The contribution guide
can be found in the [Contributing guide](CONTRIBUTING.md).

## License

Laravel Media plugin is open-sourced software licensed under the [MIT license](LICENSE).
 
