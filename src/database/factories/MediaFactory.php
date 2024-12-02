<?php

namespace RonasIT\Media\database\factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class MediaFactory extends Factory
{
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