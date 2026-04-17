<?php

namespace RonasIT\Media;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use RonasIT\Media\Commands\CleanupCommand;
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

class MediaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'media');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->mergeConfigFrom(__DIR__ . '/../config/media.php', 'media');

        $this->publishes([
            __DIR__ . '/../config/media.php' => config_path('media.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/media'),
        ], 'lang');

        $this->mergeConfigFrom(__DIR__ . '/../config/blurhash.php', 'blurhash');

        Route::mixin(new MediaRouter());

        if (config('media.api_enable')) {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        }

        $this->commands([
            CleanupCommand::class,
        ]);
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
