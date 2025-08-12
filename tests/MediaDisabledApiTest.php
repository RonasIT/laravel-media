<?php

namespace RonasIT\Media\Tests;

class MediaDisabledApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        self::$apiEnable = false;

        $this->refreshApplication();
    }

    public function testGetWhenAutoRoutesDisabled(): void
    {
        $response = $this->json('get', '/media');

        $response->assertNotFound();

        $response->assertJson(['message' => 'The route media could not be found.']);
    }

    public function testPostWhenAutoRoutesDisabled(): void
    {
        $response = $this->json('post', '/media/bulk', []);

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