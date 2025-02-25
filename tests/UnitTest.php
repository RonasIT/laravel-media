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
    protected static ModelTestState $mediaTestState;

    public function setUp(): void
    {
        parent::setUp();

        self::$file ??= UploadedFile::fake()->image('file.png', 600, 600);
        self::$mediaTestState ??= new ModelTestState(Media::class);

        Storage::fake();

        MediaRouter::$isBlockedBaseRoutes = false;
    }

    public function testCreateMediaWithSetPreviewDrivers()
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [],
            previewDrivers: PreviewDriverEnum::File,
        );

        $this->assertEqualsFixture('create_media_with_set_preview_drivers.json', $media->toArray());
    }

    public function testCreateMediaWithDefaultPreviewDrivers()
    {
        $this->mockGenerateFilename();

        $media = app(MediaServiceContract::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: []
        );

        $this->assertEqualsFixture('create_media_with_set_preview_drivers.json', $media->toArray());
    }

    public function testCreateBulkMediaWithSetPreviewDrivers()
    {
        $this->mockGenerateFilename();

        $mediaArray = [
            'media' => [
                'file' => self::$file,
            ],
        ];

        $media = app(MediaServiceContract::class)->bulkCreate(
            data: $mediaArray,
            previewDrivers: PreviewDriverEnum::File
        );

        $this->assertEqualsFixture('bulk_create_media_with_set_preview_drivers.json', $media['media']->toArray());
    }

    public function testCreateBulkMediaWithDefaultPreviewDrivers()
    {
        $this->mockGenerateFilename();

        $mediaArray = [
            'media' => [
                'file' => self::$file,
            ],
        ];

        $media = app(MediaServiceContract::class)->bulkCreate(
            data: $mediaArray,
            previewDrivers: PreviewDriverEnum::File
        );

        $this->assertEqualsFixture('bulk_create_media_with_set_preview_drivers.json', $media['media']->toArray());
    }
}