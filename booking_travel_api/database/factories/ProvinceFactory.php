<?php

namespace Database\Factories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition()
    {
        return [
            'name' => $this->faker->state(),
            'image' => $this->faker->imageUrl(640, 480, 'nature', true), // generates a fake image URL
        ];
    }
}
