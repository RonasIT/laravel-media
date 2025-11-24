<?php

namespace RonasIT\Media;

use Illuminate\Support\ServiceProvider;

class ChangeMediaConstraintServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../database/additional' => database_path('migrations'),
        ], 'change-on-delete-constraint');
    }
}
