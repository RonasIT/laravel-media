<?php

namespace RonasIT\Media\database\seeders;

use Illuminate\Database\Seeder;
use RonasIT\Media\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        Media::factory(10)->create();
    }
}
