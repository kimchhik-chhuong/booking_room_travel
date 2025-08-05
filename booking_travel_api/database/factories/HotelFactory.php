<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\Adventure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;
    use HasFactory;
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'image' => $this->faker->imageUrl(640, 480, 'hotel', true),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'day' => $this->faker->numberBetween(1, 14),
            'description' => $this->faker->paragraph(),
            'adventure_id' => Adventure::factory(),
        ];
    }
}
