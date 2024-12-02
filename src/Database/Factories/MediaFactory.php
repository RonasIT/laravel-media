<?php

namespace RonasIT\Media\Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use RonasIT\Media\Models\Media;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        $faker = app(Faker::class);

        $name = $faker->unique()->lexify('???????????????'). 'jpg';

        return [
            'name' => $name,
            'link' => Storage::url($name),
        ];
    }
}