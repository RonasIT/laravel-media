<?php

namespace RonasIT\Media\Tests;

use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase as BaseTest;
use RonasIT\Media\MediaServiceProvider;
use RonasIT\Support\Traits\FixturesTrait;

class TestCase extends BaseTest
{
    use FixturesTrait;

    protected bool $globalExportMode = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTestDump();

        if (config('database.default') === 'pgsql') {
            $this->prepareSequences($this->getTables());
        }

        putenv('FAIL_EXPORT_JSON=false');
    }

    public function getFixturePath(string $fixtureName): string
    {
        $class = get_class($this);
        $explodedClass = explode('\\', $class);
        $className = Arr::last($explodedClass);

        $path = base_path("tests/fixtures/{$className}/{$fixtureName}");

        return str_replace('vendor/orchestra/testbench-core/laravel/', '', $path);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', env('DB_DEFAULT'));
        $app['config']->set('database.connections.pgsql', [
            'driver' => env('DB_DRIVER'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            MediaServiceProvider::class
        ];
    }
}
