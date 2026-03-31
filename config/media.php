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
    */
    'previews' => [
        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        | A list of preview generation drivers will be used by the default
        | the create and bulk create operations in case the previews argument was skipped.
        |
        | Supported any set of values from the PreviewDriverEnum:
        |    PreviewDriverEnum::File - image file with the Media entity, store reference in preview_id field
        |    PreviewDriverEnum::Hash - BlurHash string representation, store in blur_hash field
        */
        'drivers' => [
            PreviewDriverEnum::File,
        ],
        /*
        |--------------------------------------------------------------------------
        | File driver settings
        |--------------------------------------------------------------------------
        */
        'file_driver' => [
            /*
            |--------------------------------------------------------------------------
            | Preview file resolution, pixels
            |--------------------------------------------------------------------------
            */
            'size' => [
                'width' => 250,
                'height' => 250,
            ],
        ],
    ],
];
