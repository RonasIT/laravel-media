<?php

use App\Models\User;

return [
    /*
    |--------------------------------------------------------------------------
    | Permitted file types
    |--------------------------------------------------------------------------
    |
    | The list of permitted file types which will be validated
    */

    'permitted_types' => [
        'jpg',
        'jpeg',
        'bmp',
        'png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    |
    | Authenticatable User model which will be used to create owner relation with Media
    */

    'classes' => [
        'user_model' => User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    |
    | File preview width and height in pixels which will be used to create preview
    */
    'preview' => [
        'width' => 250,
        'height' => 250,
    ],

    /*
   |--------------------------------------------------------------------------
   | Max File Size
   |--------------------------------------------------------------------------
   |
   | Max File size in kilobytes which will be validated
   */
    'max_file_size' => 5120,
];
