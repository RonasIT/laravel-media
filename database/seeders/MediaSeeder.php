<?php

namespace Database\Seeders;

use RonasIT\Media\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        Media::factory(10)->create();
    }
}
