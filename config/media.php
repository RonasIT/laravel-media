<?php

use App\Models\User;
use RonasIT\Media\Enums\PreviewDriverEnum;

return [
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
  | Preview drivers
  |--------------------------------------------------------------------------
  |
  | Make preview (file,blurhash)
  */

    'drivers' => [
        PreviewDriverEnum::File,
    ],
];
