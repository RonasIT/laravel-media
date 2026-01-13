<?php

namespace RonasIT\Media\Tests;

use Illuminate\Support\Facades\Config;

class MediaDefaultAPIRoutesDisabledTest extends TestCase
{
    protected function beforeEnvironmentSetUpHook(): void
    {
        Config::set('media.api_enable', false);
    }

    public function testSearch(): void
    {
        $response = $this->json('get', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testSingleUpload(): void
    {
        $response = $this->json('post', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testBulkUpload(): void
    {
        $response = $this->json('post', '/media/bulk');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/bulk could not be found.']);
    }

    public function testDelete(): void
    {
        $response = $this->json('delete', '/media/4');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/4 could not be found.']);
    }
}
