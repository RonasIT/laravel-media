<?php

namespace RonasIT\Media\Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Models\User;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaStaticTest extends TestCase
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

        Route::media([]);
    }

    public function testCreate(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('create_changes.json');
    }

    public function testCreatePublic(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', [
            'file' => self::$file,
            'is_public' => true,
        ]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('create_public_changes.json');
    }

    public function testCreateCheckFile(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        Storage::disk('local')->assertExists($this->getFilePathFromUrl('file.png'));

        $this->clearUploadedFilesFolder();
    }

    public function testBulkCreate(): void
    {
        $this->mockGenerateFilename('file1.png', 'file2.png');

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

        self::$mediaTestState->assertChangesEqualsFixture('bulk_create_changes.json');
    }

    public function testDelete(): void
    {
        $filePath = 'preview_Private photo';
        Storage::put($filePath, 'content');

        $response = $this->actingAs(self::$user)->json('delete', '/media/4');

        $response->assertNoContent();

        self::$mediaTestState->assertChangesEqualsFixture('delete_changes.json');

        Storage::assertMissing($filePath);
    }

    public function testDeleteNotExists(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/0');

        $response->assertNotFound();
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/1');

        $response->assertForbidden();

        self::$mediaTestState->assertNotChanged();
    }

    public static function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['all' => true],
                'fixture' => 'get_by_all.json',
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
                'fixture' => 'get_by_query.json',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'fixture' => 'get_complex.json',
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
                'file' => ['The file field must be a file of type: jpg, jpeg, bmp, png.'],
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
        $this->mockGenerateFilename();

        self::$file = UploadedFile::fake()->image($fileName, 600, 600);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        self::$mediaTestState->assertChangesEqualsFixture('uploading_good_files_changes.json');
    }
}
