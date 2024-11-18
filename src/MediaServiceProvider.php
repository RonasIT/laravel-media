<?php

namespace RonasIT\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use RonasIT\Media\Contracts\Requests\BulkCreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\CreateMediaRequestContract;
use RonasIT\Media\Contracts\Requests\DeleteMediaRequestContract;
use RonasIT\Media\Contracts\Requests\SearchMediaRequestContract;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Media\Http\Requests\BulkCreateMediaRequest;
use RonasIT\Media\Http\Requests\CreateMediaRequest;
use RonasIT\Media\Http\Requests\DeleteMediaRequest;
use RonasIT\Media\Http\Requests\SearchMediaRequest;
use RonasIT\Media\Services\MediaService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::mixin(new MediaRouter());

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->mergeConfigFrom(__DIR__ . '/../config/media.php', 'media');

        $this->publishes([
            __DIR__ . '/../config/media.php' => config_path('media.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->app->bind(CreateMediaRequestContract::class, CreateMediaRequest::class);
        $this->app->bind(BulkCreateMediaRequestContract::class, BulkCreateMediaRequest::class);
        $this->app->bind(SearchMediaRequestContract::class, SearchMediaRequest::class);
        $this->app->bind(DeleteMediaRequestContract::class, DeleteMediaRequest::class);
        $this->app->bind(MediaServiceContract::class, MediaService::class);
    }
}