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
            ],
            [
                'option' => '--delete-all',
                'jobsNumber' => '9',
            ],
            [
                'option' => '--public',
                'jobsNumber' => '4',
            ],
        ];
    }

    #[DataProvider('getCleanupCommandData')]
    public function testCleanupCommand(string $option, string $jobsNumber): void
    {
        Queue::fake();

        $this
            ->artisan("media:cleanup {$option}")
            ->expectsOutput("Successfully dispatched {$jobsNumber} job(s) for deletion.")
            ->assertExitCode(0);

        Queue::assertPushed(DeleteMediaJob::class);
    }

    public function testDeleteMediaJob(): void
    {
        $files = [
            'preview_Category Photo photo',
            'Category Photo photo',
            'Main photo without preview',
        ];

        foreach ($files as $path) {
            Storage::put($path, 'content');
        }

        $this->artisan('media:cleanup')
            ->expectsOutput('Successfully dispatched 5 job(s) for deletion.')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_default', 1);

        foreach ($files as $path) {
            Storage::assertMissing($path);
        }
    }
}
