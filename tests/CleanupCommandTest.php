<?php

namespace RonasIT\Media\Tests;

use Exception;
use Illuminate\Support\Facades\Log;
use Mockery;
use RonasIT\Media\Commands\CleanupCommand;
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
        $this
            ->artisan('media:cleanup')
            ->expectsOutput('Deleted 3 record(s).')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_default');
    }

    public function testCleanupCommandDeleteAll(): void
    {
        $this
            ->artisan('media:cleanup --delete-all')
            ->expectsOutput('Deleted 7 record(s).')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_all');
    }

    public function testCleanupCommandIsPublicTrue(): void
    {
        $this
            ->artisan('media:cleanup --public')
            ->expectsOutput('Deleted 4 record(s).')
            ->assertExitCode(0);

        self::$mediaTestState->assertChangesEqualsFixture('delete_records_public');
    }

    public function testCleanupCommandFailed(): void
    {
        Log::shouldReceive('error')->with('DB failure');

        $command = Mockery::mock(CleanupCommand::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $command
            ->shouldReceive('deleteRecords')
            ->once()
            ->andThrow(Exception::class, 'DB failure');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to delete records.');

        $command->handle();

        self::$mediaTestState->assertNotChanged();
    }
}
