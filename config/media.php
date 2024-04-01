<?php

use RonasIT\Media\Models\User;

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
