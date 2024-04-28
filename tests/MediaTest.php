<?php

namespace RonasIT\Media\Tests;

use Carbon\Carbon;
use RonasIT\Media\Models\User;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaTest extends TestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected static User $userOne;
    protected static User $userTwo;
    protected static File $file;
    protected static ModelTestState $mediaTestState;

    public function setUp(): void
    {
        parent::setUp();

        self::$userOne ??= User::find(1);
        self::$userTwo ??= User::find(2);
        self::$file ??= UploadedFile::fake()->image('file.png', 600, 600);
        self::$mediaTestState ??= new ModelTestState(Media::class);
    }

    public function testCreate(): void
    {
        $this->mockGenerateFilename();

        Carbon::setTestNow(Carbon::create(2024));

        $mediaTestState = new ModelTestState(Media::class);

        $response = $this->actingAs(self::$userOne)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $mediaTestState->assertChangesEqualsFixture('create_changes.json');

        Carbon::setTestNow();
    }

    public function testCreatePublic(): void
    {
        $this->mockGenerateFilename();

        Carbon::setTestNow(Carbon::create(2024));

        $mediaTestState = new ModelTestState(Media::class);

        $response = $this->actingAs(self::$userTwo)->json('post', '/media', [
            'file' => self::$file,
            'is_public' => true,
        ]);

        $response->assertCreated();

        $mediaTestState->assertChangesEqualsFixture('create_public_changes.json');

        Carbon::setTestNow();
    }

    public function testCreateCheckUrls(): void
    {
        $this->mockGenerateFilename();

        $this->actingAs(self::$userOne)->json('post', '/media', ['file' => self::$file]);

        $this->assertEquals(1, Media::where('link', 'like', '/%')->count());
    }

    public function testCreateCheckFile(): void
    {
        $this->mockGenerateFilename();

        Carbon::setTestNow(Carbon::create(2024));

        $mediaTestState = new ModelTestState(Media::class);

        $response = $this->actingAs(self::$userOne)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $mediaTestState->assertChangesEqualsFixture('create_check_file_changes.json');

        Storage::disk('local')->assertExists($this->getFilePathFromUrl('file.png'));

        Carbon::setTestNow();

        $this->clearUploadedFilesFolder();
    }

    public function testCreateNoAuth(): void
    {
        $response = $this->json('post', '/media', ['file' => self::$file]);

        $response->assertUnauthorized();
    }

    public function testBulkCreate(): void
    {
        Carbon::setTestNow(Carbon::create(2024));

        $mediaTestState = new ModelTestState(Media::class);

        $this->mockGenerateFilename('file1.png', 'file2.png');

        $response = $this->actingAs(self::$userOne)->json('post', '/media/bulk', [
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

        $mediaTestState->assertChangesEqualsFixture('bulk_create_changes.json');

        Carbon::setTestNow();
    }

    public function testDelete(): void
    {
        $response = $this->actingAs(self::$userOne)->json('delete', '/media/1');

        $response->assertNoContent();

        $this->assertSoftDeleted('media', [
            'id' => 1,
        ]);
    }

    public function testDeleteNotExists(): void
    {
        $response = $this->actingAs(self::$userOne)->json('delete', '/media/0');

        $response->assertNotFound();
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs(self::$userTwo)->json('delete', '/media/1');

        $response->assertForbidden();

        self::$mediaTestState->assertNotChanged();
    }

    public function testDeleteNoAuth(): void
    {
        $response = $this->json('delete', '/media/1');

        $response->assertUnauthorized();

        self::$mediaTestState->assertNotChanged();
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['all' => true],
                'result' => 'get_by_all.json',
            ],
        ];
    }

    public function getAdminSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query_as_user_one.json',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'result' => 'get_complex_as_user_one.json',
            ],
        ];
    }

    public function getUserSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query_as_user_two.json',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'result' => 'get_complex_as_user_two.json',
            ],
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch(array $filter, string $fixture): void
    {
        $response = $this->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    /**
     * @dataProvider  getAdminSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearchByAdmin($filter, $fixture)
    {
        $response = $this->actingAs(self::$userOne)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    /**
     * @dataProvider  getUserSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearchByUser(array $filter, string $fixture): void
    {
        $response = $this->actingAs(self::$userTwo)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function getBadFiles(): array
    {
        return [
            [
                'filter' => ['fileName' => 'notAVirus.exe'],
            ],
            [
                'filter' => ['fileName' => 'notAVirus.psd'],
            ],
        ];
    }

    /**
     * @dataProvider  getBadFiles
     *
     * @param array $filter
     */
    public function testUploadingBadFiles(array $filter): void
    {
        self::$file = UploadedFile::fake()->create($filter['fileName'], 1024);

        $response = $this->actingAs(self::$userTwo)->json('post', '/media', ['file' => self::$file]);

        $response->assertUnprocessable();

        $response->assertJson([
            'errors' => [
                'file' => ['The file field must be a file of type: jpg, jpeg, bmp, png.'],
            ],
        ]);
    }

    public function getGoodFiles(): array
    {
        return [
            [
                'filter' => ['fileName' => 'image.jpg'],
            ],
            [
                'filter' => ['fileName' => 'image.png'],
            ],
            [
                'filter' => ['fileName' => 'image.bmp'],
            ],
        ];
    }

    /**
     * @dataProvider  getGoodFiles
     *
     * @param array $filter
     */
    public function testUploadingGoodFiles(array $filter): void
    {
        $this->mockGenerateFilename();

        Carbon::setTestNow(Carbon::create(2024));

        $mediaTestState = new ModelTestState(Media::class);

        self::$file = UploadedFile::fake()->image($filter['fileName'], 600, 600);

        $response = $this->actingAs(self::$userTwo)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $mediaTestState->assertChangesEqualsFixture('uploading_good_files_changes.json');

        Carbon::setTestNow();
    }
}
