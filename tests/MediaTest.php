<?php

namespace RonasIT\Media\Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Services\MediaService;
use RonasIT\Media\Tests\Models\User;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaTest extends TestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected static User $user;
    protected static File $file;
    protected static ModelTestState $mediaTestState;

    public function setUp(): void
    {
        parent::setUp();

        self::$user ??= User::find(2);
        self::$file ??= UploadedFile::fake()->image('file.png', 600, 600);
        self::$mediaTestState ??= new ModelTestState(Media::class);

        Storage::fake();
    }

    public function testCreateFromStream(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('create');

        $this->assertEqualsFixture('create_response', $response->json());
    }

    public function testCreatePublic(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', [
            'file' => self::$file,
            'is_public' => true,
        ]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('create_public');
    }

    public function testCreateCheckFile(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        Storage::disk('local')->assertExists($this->getFilePathFromUrl('file.png'));

        $this->clearUploadedFilesFolder();
    }

    public function testCreateNoAuth(): void
    {
        $response = $this->json('post', '/media', ['file' => self::$file]);

        $response->assertUnauthorized();
    }

    public function testCreateRawFileContent(): void
    {
        $this->mockGenerateFilename();

        app(MediaService::class)->create(
            content: file_get_contents(self::$file->getPathname()),
            fileName: self::$file->getClientOriginalName(),
            data: [
                'is_public' => true,
                'owner_id' => 2,
            ],
        );

        self::$mediaTestState->assertChangesEqualsFixture('create_public');
    }

    public function testBulkCreate(): void
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

        $response = $this->actingAs(self::$user)->json('post', '/media/bulk', [
            'media' => [
                [
                    'file' => self::$file,
                    'meta' => ['test1'],
                ],
                [
                    'file' => self::$file,
                    'meta' => ['test2'],
                ],
            ],
        ]);

        $response->assertOk();

        self::$mediaTestState->assertChangesEqualsFixture('bulk_create');

        $this->assertEqualsFixture('bulk_create_response', $response->json());
    }

    public function testBulkCreateRawFileContent(): void
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

        app(MediaService::class)->bulkCreate([
            [
                'file' => self::$file,
                'meta' => ['test1'],
                'owner_id' => 2,
            ],
            [
                'file' => self::$file,
                'meta' => ['test2'],
                'owner_id' => 2,
            ],
        ]);

        self::$mediaTestState->assertChangesEqualsFixture('bulk_create');
    }

    public function testDelete(): void
    {
        $filePath = 'Private photo';
        $previewFilePath = "preview_{$filePath}";
        Storage::put($filePath, 'content');
        Storage::put($previewFilePath, 'content');

        $response = $this->actingAs(self::$user)->json('delete', '/media/9');

        $response->assertNoContent();

        self::$mediaTestState->assertChangesEqualsFixture('delete');

        Storage::assertMissing($filePath);
        Storage::assertMissing($previewFilePath);
    }

    public function testDeleteNotExists(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/0');

        $response->assertNotFound();

        $response->assertJson(['message' => 'Media does not exist']);
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/6');

        $response->assertForbidden();

        self::$mediaTestState->assertNotChanged();
    }

    public function testDeletePreview(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/3');

        $response->assertBadRequest();

        $response->assertJson(['message' => 'Media is preview']);

        self::$mediaTestState->assertNotChanged();
    }

    public function testDeleteNoAuth(): void
    {
        $response = $this->json('delete', '/media/1');

        $response->assertUnauthorized();

        self::$mediaTestState->assertNotChanged();
    }

    public static function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['all' => true],
                'fixture' => 'get_by_all',
            ],
        ];
    }

    #[DataProvider('getSearchFilters')]
    public function testSearch(array $filter, string $fixture): void
    {
        $response = $this->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public static function getUserSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'fixture' => 'get_by_query',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'fixture' => 'get_complex',
            ],
        ];
    }

    #[DataProvider('getUserSearchFilters')]
    public function testSearchWithAuth(array $filter, string $fixture): void
    {
        $response = $this->actingAs(self::$user)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public static function getBadFiles(): array
    {
        return [
            [
                'fileName' => 'notAVirus.exe',
            ],
            [
                'fileName' => 'notAVirus.psd',
            ],
        ];
    }

    #[DataProvider('getBadFiles')]
    public function testUploadingBadFiles(string $fileName): void
    {
        self::$file = UploadedFile::fake()->create($fileName, 1024);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertUnprocessable();

        $response->assertJson([
            'errors' => [
                'file' => ['The file field must be a file of type: jpg, jpeg, bmp, png, heic.'],
            ],
        ]);
    }

    public static function getGoodFiles(): array
    {
        return [
            [
                'fileName' => 'image.jpg',
            ],
            [
                'fileName' => 'image.png',
            ],
            [
                'fileName' => 'image.bmp',
            ],
        ];
    }

    #[DataProvider('getGoodFiles')]
    public function testUploadingGoodFiles(string $fileName): void
    {
        $this->mockGenerateFilename(
            [
                'argument' => $fileName,
                'result' => 'file.png',
            ],
        );

        self::$file = UploadedFile::fake()->image($fileName, 600, 600);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('uploading_good_files');
    }

    public function testCreateNotImageMedia(): void
    {
        $this->mockGenerateFilename([
            'argument' => 'document.pdf',
            'result' => 'document.pdf',
        ]);

        Config::push('media.permitted_types', 'pdf');

        self::$file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('uploading_not_image_file');

        $this->assertEqualsFixture('uploading_not_image_file', $response->json());
    }

    public function testCreateBaseAutomaticallyRegistered(): void
    {
        Config::set('media.api_enable', false);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertNotFound();

        $response->assertJson(['message' => 'Not found.']);

        self::$mediaTestState->assertNotChanged();
    }

    public function testSearchStaticDefined(): void
    {
        Config::set('media.api_enable', false);

        $response = $this->actingAs(self::$user)->json('get', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'Not found.']);
    }

    public function testDeleteWhenStaticDefined(): void
    {
        Config::set('media.api_enable', false);

        $filePath = 'preview_Private photo';
        Storage::put($filePath, 'content');

        $response = $this->actingAs(self::$user)->json('delete', '/media/4');

        $response->assertNotFound();

        $response->assertJson(['message' => 'Not found.']);

        Storage::assertExists($filePath);

        self::$mediaTestState->assertNotChanged();
    }

    public function testCreateBulkWhenStaticDefined(): void
    {
        Config::set('media.api_enable', false);

        $response = $this->actingAs(self::$user)->json('post', '/media/bulk', ['file' => self::$file]);

        $response->assertNotFound();

        $response->assertJson(['message' => 'Not found.']);

        self::$mediaTestState->assertNotChanged();
    }
}