<?php

namespace RonasIT\Media;

use Illuminate\Support\ServiceProvider;

class ChangeMediaConstraintServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/additional');
    }
}
