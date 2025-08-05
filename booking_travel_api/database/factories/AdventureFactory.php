<?php

namespace Database\Factories;

use App\Models\Adventure;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdventureFactory extends Factory
{
    use HasFactory;
    protected $model = Adventure::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'province_id' => Province::factory(),
        ];
    }
}
