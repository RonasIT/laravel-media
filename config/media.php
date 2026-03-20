<?php

use App\Models\User;
use RonasIT\Media\Enums\PreviewDriverEnum;

return [
    /*
    |--------------------------------------------------------------------------
    | Automatic API routes registration
    |--------------------------------------------------------------------------
    |
    | Enabling automatically registration of the
    | [default API routes](https://github.com/RonasIT/laravel-media/blob/master/src/Enums/MediaRouteActionEnum.php)
    */
    'api_enable' => true,

    /*
    |--------------------------------------------------------------------------
    | Permitted file types
    |--------------------------------------------------------------------------
    |
    | The list of permitted file types
    */
    'permitted_types' => [
        'jpg',
        'jpeg',
        'bmp',
        'png',
        'heic',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max File Size
    |--------------------------------------------------------------------------
    |
    | Max file size in kilobytes
    */
    'max_file_size' => 5120,

    'classes' => [
        /*
        |--------------------------------------------------------------------------
        | User model
        |--------------------------------------------------------------------------
        |
        | Authenticatable User model which will be used to create owner relation with Media
        */
        'user_model' => User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Previews
    |--------------------------------------------------------------------------
    |
    | Drivers: A list of preview generation drivers to use. Supported options are:
    |    PreviewDriverEnum::File - generates a preview as an actual image file
    |    PreviewDriverEnum::Hash - generates a BlurHash string representation for placeholder previews
    |
    | Size: File preview width and height in pixels
    */
    'previews' => [
        'drivers' => [
            PreviewDriverEnum::File,
        ],
        'size' => [
            'width' => 250,
            'height' => 250,
        ],
    ],
];
