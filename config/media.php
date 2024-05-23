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
];
