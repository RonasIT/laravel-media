<?php

namespace RonasIT\Media\Tests;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use RonasIT\Media\Jobs\DeleteMediaJob;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\MockTrait;

class CleanupCommandTest extends TestCase
{
    use MockTrait;

    protected static ModelTestState $mediaTestState;

    public function setUp(): void
    {
        parent::setUp();

        self::$mediaTestState ??= new ModelTestState(Media::class);
    }

    public static function getCleanupCommandData(): array
    {
        return [
            [
                'option' => '',
                'jobsNumber' => '5',
                'queueStateFixture' => 'cleanup_default',
            ],
            [
                'option' => '--delete-all',
                'jobsNumber' => '9',
                'queueStateFixture' => 'cleanup_delete_all',
            ],
            [
                'option' => '--public',
                'jobsNumber' => '4',
                'queueStateFixture' => 'cleanup_public',
            ],
        ];
    }

    #[DataProvider('getCleanupCommandData')]
    public function testCleanupCommand(string $option, string $jobsNumber, string $queueStateFixture): void
    {
        Queue::fake();

        $this
            ->artisan("media:cleanup {$option}")
            ->expectsOutput("Successfully dispatched {$jobsNumber} job(s) for deletion.")
            ->assertExitCode(0);
// TODO: use laravel-helpers "assertQueueEqualsFixture"
        $this->assertQueueEqualsFixture($queueStateFixture);
    }

    public function testDeleteMediaJob(): void
    {
        $files = [
            'preview_Category Photo photo',
            'Category Photo photo',
        ];

        foreach ($files as $path) {
            Storage::put($path, 'content');
        }

        DeleteMediaJob::dispatchSync(7);

        self::$mediaTestState->assertChangesEqualsFixture('delete_media_job_state');

        Storage::assertMissing($files);
    }
}
