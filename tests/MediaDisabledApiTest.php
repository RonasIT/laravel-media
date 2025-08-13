<?php

namespace RonasIT\Media\Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\MediaRouter;
use RonasIT\Media\Models\Media;
use RonasIT\Media\Tests\Models\User;
use RonasIT\Media\Tests\Support\MediaTestTrait;
use RonasIT\Media\Tests\Support\ModelTestState;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaDisabledApiTest extends TestCase
{
    protected function beforeEnvironmentSetUpHook($app): void
    {
        Config::set('media.api_enable', false);
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetWhenAutoRoutesDisabled(): void
    {
        Config::set('media.api_enable', false);
        
        $response = $this->json('get', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testPostWhenAutoRoutesDisabled(): void
    {
        Config::set('media.api_enable', false);
        
        $response = $this->json('post', '/media/bulk');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/bulk could not be found.']);
    }

    public function testDeleteWhenAutoRoutesDisabled(): void
    {
        Config::set('media.api_enable', false);
        
        $response = $this->json('delete', '/media/4');
        
        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/4 could not be found.']);
    }
}