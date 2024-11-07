<?php

use App\Models\User;

return [
    'permitted_types' => [
        'jpg',
        'jpeg',
        'bmp',
        'png',
    ],

    'classes' => [
        'user_model' => User::class,
    ],

    'preview' => [
        'width' => 250,
        'height' => 250,
    ],

    'standard_routes_loaded' => false,
];
