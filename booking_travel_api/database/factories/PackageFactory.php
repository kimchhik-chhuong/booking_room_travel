<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition()
    {
        $title = $this->faker->sentence(3);
        $days = $this->faker->numberBetween(3, 14);
        
        return [
            'title' => $title,
            'slug' => \Str::slug($title),
            'short_description' => $this->faker->text(200),
            'description' => $this->faker->paragraphs(5, true),
            'category_id' => Category::factory(),
            'destination_id' => Destination::factory(),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'original_price' => $this->faker->optional(0.3)->randomFloat(2, 600, 6000),
            'duration_days' => $days,
            'duration_nights' => $days - 1,
            'min_participants' => $this->faker->numberBetween(1, 2),
            'max_participants' => $this->faker->numberBetween(4, 20),
            'difficulty_level' => $this->faker->randomElement(['easy', 'moderate', 'challenging']),
            'featured_image' => 'packages/sample-' . $this->faker->numberBetween(1, 10) . '.jpg',
            'gallery' => $this->faker->optional(0.7)->randomElements([
                'packages/gallery-1.jpg',
                'packages/gallery-2.jpg',
                'packages/gallery-3.jpg',
                'packages/gallery-4.jpg',
            ], $this->faker->numberBetween(2, 4)),
            'inclusions' => $this->faker->randomElements([
                'Accommodation',
                'Meals',
                'Transportation',
                'Tour guide',
                'Entrance fees',
                'Airport transfers',
            ], $this->faker->numberBetween(3, 6)),
            'exclusions' => $this->faker->randomElements([
                'International flights',
                'Travel insurance',
                'Personal expenses',
                'Tips',
                'Visa fees',
            ], $this->faker->numberBetween(2, 4)),
            'highlights' => $this->faker->randomElements([
                'Scenic views',
                'Cultural experiences',
                'Adventure activities',
                'Local cuisine',
                'Historical sites',
                'Wildlife viewing',
            ], $this->faker->numberBetween(3, 5)),
            'accommodation_type' => $this->faker->randomElement([
                'Hotel', 'Resort', 'Lodge', 'Guesthouse', 'Camping'
            ]),
            'meal_plan' => $this->faker->randomElement([
                'breakfast', 'half-board', 'full-board', 'all-inclusive'
            ]),
            'transportation' => $this->faker->randomElement([
                'Private car', 'Bus', 'Train', 'Boat', 'Walking'
            ]),
            'is_featured' => $this->faker->boolean(20),
            'is_popular' => $this->faker->boolean(30),
            'is_active' => $this->faker->boolean(90),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'available_from' => $this->faker->optional(0.5)->dateTimeBetween('now', '+1 month'),
            'available_until' => $this->faker->optional(0.3)->dateTimeBetween('+2 months', '+1 year'),
            'advance_booking_days' => $this->faker->numberBetween(1, 30),
            'cancellation_policy' => $this->faker->optional(0.8)->paragraph(),
            'tags' => $this->faker->optional(0.7)->randomElements([
                'adventure', 'luxury', 'budget', 'family', 'solo', 'group',
                'cultural', 'nature', 'beach', 'mountain', 'city'
            ], $this->faker->numberBetween(2, 5)),
            'rating' => $this->faker->randomFloat(2, 3.5, 5.0),
            'total_reviews' => $this->faker->numberBetween(0, 500),
            'total_bookings' => $this->faker->numberBetween(0, 200),
        ];
    }

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
                'status' => 'published',
                'is_active' => true,
            ];
        });
    }

    public function popular()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_popular' => true,
                'total_bookings' => $this->faker->numberBetween(100, 500),
                'rating' => $this->faker->randomFloat(2, 4.0, 5.0),
            ];
        });
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'is_active' => true,
            ];
        });
    }
}
