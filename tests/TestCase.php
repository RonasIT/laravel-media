<?php

namespace RonasIT\Media\Tests;

use Carbon\Carbon;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTest;
use RonasIT\Media\MediaServiceProvider;
use RonasIT\Support\Traits\FixturesTrait;
use RonasIT\Media\Tests\Models\User;

class TestCase extends BaseTest
{
    use FixturesTrait {
        getFixturePath as traitGetFixturePath;
    }

    protected bool $globalExportMode = false;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('media.classes.user_model', User::class);
        Config::set('media.on_delete_constraint', 'cascade');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTestDump();

        if (config('database.default') === 'pgsql') {
            $this->prepareSequences();
        }

        Carbon::setTestNow(Carbon::create(2024));
    }

    public function getFixturePath(string $fixtureName): string
    {
        $path = $this->traitGetFixturePath($fixtureName);

        return str_replace('vendor/orchestra/testbench-core/laravel/', '', $path);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $this->beforeEnvironmentSetUpHook();

        Dotenv::createImmutable(__DIR__ . '/..', '.env.testing')->load();

        $this->setupDb($app);
    }
    
    protected function beforeEnvironmentSetUpHook(): void
    {
    }

    protected function getPackageProviders($app): array
    {
        return [
            MediaServiceProvider::class,
        ];
    }

    protected function setupDb($app): void
    {
        $app['config']->set('database.default', env('DB_DEFAULT', 'pgsql'));
        $app['config']->set('database.connections.pgsql', [
            'driver' => env('DB_DRIVER', 'pgsql'),
            'host' => env('DB_HOST', 'pgsql'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', 'secret'),
        ]);
    }
}
