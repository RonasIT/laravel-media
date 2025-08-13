<?php

namespace RonasIT\Media\Tests;

use Illuminate\Support\Facades\Config;

class MediaDisabledApiTest extends TestCase
{
    protected function beforeEnvironmentSetUpHook(): void
    {
        Config::set('media.api_enable', false);
    }

    public function testGetWhenAutoRoutesDisabled(): void
    {
        $response = $this->json('get', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testPostWhenAutoRoutesDisabled(): void
    {
        $response = $this->json('post', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testPostBulkWhenAutoRoutesDisabled(): void
    {
        $response = $this->json('post', '/media/bulk');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/bulk could not be found.']);
    }

    public function testDeleteWhenAutoRoutesDisabled(): void
    {   
        $response = $this->json('delete', '/media/4');
        
        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media/4 could not be found.']);
    }
}