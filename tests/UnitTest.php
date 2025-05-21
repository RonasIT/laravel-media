<?php

namespace RonasIT\Media\Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\Contracts\Services\MediaServiceContract;
use RonasIT\Media\Enums\PreviewDriverEnum;
use RonasIT\Media\MediaRouter;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\FilesUploadTrait;

class UnitTest extends TestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected static File $file;
    protected static File $secondFile;
    protected static ModelTestState $mediaTestState;

    public function setUp(): void
    {
        parent::setUp();

        self::$file ??= UploadedFile::fake()->image('file.png', 600, 600);
        self::$secondFile ??= UploadedFile::fake()->image('file.png', 600, 600);

        self::$mediaTestState ??= new ModelTestState(Media::class);

        Storage::fake();

        MediaRouter::$isBlockedBaseRoutes = false;
    }

    public function testCreateMediaWithSetPreviewDrivers(): void
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [],
            file: PreviewDriverEnum::File,
            hash: PreviewDriverEnum::Hash
        );

        Storage::assertExists('preview_file.png');

        $this->assertEqualsFixture('create_media_with_set_preview_drivers', $media->toArray());

        self::$mediaTestState->assertChangesEqualsFixture('create_media_with_set_preview_drivers');
    }

    public function testCreateMediaWithDefaultPreviewDrivers(): void
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [],
        );

        $this->assertEqualsFixture('create_media_with_default_preview_drivers', $media->toArray());
    }

    public function testCreateWithSetBlurhashDriver(): void
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [],
            previewDrivers: PreviewDriverEnum::Hash,
        );

        $this->assertEqualsFixture('create_media_with_set_blurhash_driver', $media->toArray());

        $this->assertTrue(Storage::missing('tmp_file.png'));
    }

    public function testCreateWithSetFileDriver(): void
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [],
            previewDrivers: PreviewDriverEnum::File,
        );

        $this->assertEqualsFixture('create_media_with_set_file_driver', $media->toArray());
    }

    public function testCreateBulkMediaWithSetPreviewDrivers()
    {
        $this->mockGenerateFilename(
            [
                'argument' => 'file.png',
                'result' => 'file1.png',
            ],
            [
                'argument' => 'file.png',
                'result' => 'file2.png',
            ],
        );

        $mediaArray = [
            [
                'file' => self::$file,
            ],
            [
                'file' => self::$file,
            ]
        ];

        $media = app(MediaServiceContract::class)->bulkCreate(
            data: $mediaArray,
            file: PreviewDriverEnum::File,
            hash: PreviewDriverEnum::Hash,
        );

        $result = array_map(fn ($item) => $item->toArray(), $media);
        dd($result);

        Storage::assertExists(['preview_file1.png', 'preview_file2.png']);

        $this->assertEqualsFixture('bulk_create_media_with_set_preview_drivers', $result);

        self::$mediaTestState->assertChangesEqualsFixture('create_bulk_media_with_set_preview_drivers');
    }

    public function testCreateBulkMediaWithDefaultPreviewDrivers(): void
    {
        $this->mockGenerateFilename(
            [
                'argument' => 'file.png',
                'result' => 'file1.png',
            ],
            [
                'argument' => 'file.png',
                'result' => 'file2.png',
            ],
        );

        $mediaArray = [
            [
                'file' => self::$file,
            ],
            [
                'file' => self::$file,
            ]
        ];

        $media = app(MediaServiceContract::class)->bulkCreate(
            data: $mediaArray,
        );

        $result = array_map(fn ($item) => $item->toArray(), $media);

        Storage::assertExists(['preview_file1.png', 'preview_file2.png']);

        $this->assertEqualsFixture('bulk_create_media_with_default_preview_drivers', $result);

        self::$mediaTestState->assertChangesEqualsFixture('create_bulk_media_with_default_preview_drivers');
    }

    public function testCreateMediaWithSetOwnerId()
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: ['owner_id' => 1],
        );

        $this->assertEqualsFixture('create_media_with_set_owner_id', $media->toArray());
    }

    public function testCreateMediaWithNullOwnerId()
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
        );

        $this->assertEqualsFixture('create_media_with_null_owner_id', $media->toArray());
    }
}