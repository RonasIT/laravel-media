<?php

namespace RonasIT\Media\Tests;

use Illuminate\Support\Facades\Storage;
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

    public function testCleanupCommandDefault(): void
    {
        $filePath = 'preview_Category Photo photo';
        Storage::put($filePath, 'content');

        $this
            ->artisan('media:cleanup')
            ->expectsOutput('Successfully dispatched 3 job(s) for deletion.')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_default');

        Storage::assertMissing($filePath);
    }

    public function testCleanupCommandDeleteAll(): void
    {
        $filePath = 'Product main photo';
        Storage::put($filePath, 'content');

        $this
            ->artisan('media:cleanup --delete-all')
            ->expectsOutput('Successfully dispatched 7 job(s) for deletion.')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_all');
    }

    public function testCleanupCommandIsPublicTrue(): void
    {
        $filePath = 'Photo';
        Storage::put($filePath, 'content');

        $this
            ->artisan('media:cleanup --public')
            ->expectsOutput('Successfully dispatched 4 job(s) for deletion.')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_public');
    }
}
