<?php

namespace RonasIT\Media;

use Bepsvpt\Blurhash\BlurHash;
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
    protected static $blurHash;

    public function boot(): void
    {
        Route::mixin(new MediaRouter());

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

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
    }

    public function register(): void
    {
        $this->app->bind(CreateMediaRequestContract::class, CreateMediaRequest::class);
        $this->app->bind(BulkCreateMediaRequestContract::class, BulkCreateMediaRequest::class);
        $this->app->bind(SearchMediaRequestContract::class, SearchMediaRequest::class);
        $this->app->bind(DeleteMediaRequestContract::class, DeleteMediaRequest::class);
        $this->app->bind(MediaServiceContract::class, MediaService::class);
    }

    public static function blurHash(): BlurHash
    {
        if (!self::$blurHash) {
            $config = config('blurhash');

            self::$blurHash = new BlurHash(
                $config['driver'],
                $config['components-x'],
                $config['components-y'],
                $config['resized-max-size']
            );
        }

        return self::$blurHash;
    }
}
