<?php

namespace RonasIT\Media\Tests;

use RonasIT\Media\Models\User;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaTest extends TestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected static User $admin;
    protected static User $user;
    protected static File $file;

    public function setUp(): void
    {
        parent::setUp();

        self::$admin ??= User::find(1);
        self::$user ??= User::find(2);
        self::$file ??= UploadedFile::fake()->image('file.png', 600, 600);
    }

    public function testCreate(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$admin)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $this->assertDatabaseHas('media', [
            'id' => 6,
            'name' => 'file.png',
            'owner_id' => self::$admin->id,
            'is_public' => false,
            'link' => '/storage/file.png',
        ]);
    }

    public function testCreatePublic(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$user)->json('post', '/media', [
            'file' => self::$file,
            'is_public' => true,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('media', [
            'id' => 6,
            'name' => 'file.png',
            'owner_id' => self::$user->id,
            'is_public' => true,
            'link' => '/storage/file.png',
        ]);
    }

    public function testCreateCheckUrls(): void
    {
        $this->mockGenerateFilename();

        $this->actingAs(self::$admin)->json('post', '/media', ['file' => self::$file]);

        $this->assertEquals(1, Media::where('link', 'like', '/%')->count());
    }

    public function testCreateCheckResponse(): void
    {
        $this->mockGenerateFilename();

        $response = $this->actingAs(self::$admin)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $this->assertDatabaseHas('media', [
            'id' => 6,
            'link' => '/storage/file.png',
        ]);

        Storage::disk('local')->assertExists($this->getFilePathFromUrl('file.png'));

        $this->clearUploadedFilesFolder();
    }

    public function testCreateNoAuth(): void
    {
        $response = $this->json('post', '/media', ['file' => self::$file]);

        $response->assertUnauthorized();
    }

    public function testBulkCreate(): void
    {
        $this->mockGenerateFilename('file1.png', 'file2.png');

        $response = $this->actingAs(self::$admin)->json('post', '/media/bulk', [
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

        $this->assertDatabaseHas('media', [
            'id' => 6,
            'name' => 'file1.png',
            'owner_id' => self::$admin->id,
            'meta' => "[\"test1\"]",
            'is_public' => false,
        ]);

        $this->assertDatabaseHas('media', [
            'id' => 7,
            'name' => 'file2.png',
            'owner_id' => self::$admin->id,
            'meta' => "[\"test2\"]",
            'is_public' => false,
        ]);
    }

    public function testDelete(): void
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/media/1');

        $response->assertNoContent();

        $this->assertSoftDeleted('media', [
            'id' => 1,
        ]);
    }

    public function testDeleteNotExists(): void
    {
        $response = $this->actingAs(self::$admin)->json('delete', '/media/0');

        $response->assertNotFound();
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs(self::$user)->json('delete', '/media/1');

        $response->assertForbidden();

        $this->assertDatabaseHas('media', [
            'id' => 1,
        ]);
    }

    public function testDeleteNoAuth(): void
    {
        $response = $this->json('delete', '/media/1');

        $response->assertUnauthorized();

        $this->assertDatabaseHas('media', [
            'id' => 1,
        ]);
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
                'result' => 'get_by_query_as_admin.json',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'result' => 'get_complex_as_admin.json',
            ],
        ];
    }

    public function getUserSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query_as_user.json',
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3,
                ],
                'result' => 'get_complex_as_user.json',
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
        $response = $this->actingAs(self::$admin)->json('get', '/media', $filter);

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
        $response = $this->actingAs(self::$user)->json('get', '/media', $filter);

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

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

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
        self::$file = UploadedFile::fake()->image($filter['fileName'], 600, 600);

        $response = $this->actingAs(self::$user)->json('post', '/media', ['file' => self::$file]);

        $response->assertCreated();

        $this->assertDatabaseHas('media', ['id' => 6]);
    }
}
