<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Amenity;

class PackageSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            ['name' => 'Beach & Island', 'slug' => 'beach-island', 'icon' => 'fas fa-umbrella-beach', 'color' => '#06B6D4'],
            ['name' => 'Adventure', 'slug' => 'adventure', 'icon' => 'fas fa-mountain', 'color' => '#10B981'],
            ['name' => 'Cultural', 'slug' => 'cultural', 'icon' => 'fas fa-landmark', 'color' => '#8B5CF6'],
            ['name' => 'City Break', 'slug' => 'city-break', 'icon' => 'fas fa-city', 'color' => '#F59E0B'],
            ['name' => 'Wildlife', 'slug' => 'wildlife', 'icon' => 'fas fa-paw', 'color' => '#EF4444'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create destinations
        $destinations = [
            ['name' => 'Maldives', 'slug' => 'maldives', 'country' => 'Maldives', 'country_code' => 'MV'],
            ['name' => 'Tokyo', 'slug' => 'tokyo', 'country' => 'Japan', 'country_code' => 'JP', 'city' => 'Tokyo'],
            ['name' => 'Paris', 'slug' => 'paris', 'country' => 'France', 'country_code' => 'FR', 'city' => 'Paris'],
            ['name' => 'Bali', 'slug' => 'bali', 'country' => 'Indonesia', 'country_code' => 'ID', 'city' => 'Bali'],
            ['name' => 'New York', 'slug' => 'new-york', 'country' => 'United States', 'country_code' => 'US', 'city' => 'New York'],
            ['name' => 'Swiss Alps', 'slug' => 'swiss-alps', 'country' => 'Switzerland', 'country_code' => 'CH'],
            ['name' => 'Kenya', 'slug' => 'kenya', 'country' => 'Kenya', 'country_code' => 'KE'],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }

        // Create amenities
        $amenities = [
            ['name' => 'Free WiFi', 'icon' => 'fas fa-wifi', 'category' => 'connectivity'],
            ['name' => 'Airport Transfer', 'icon' => 'fas fa-plane', 'category' => 'transport'],
            ['name' => 'Breakfast Included', 'icon' => 'fas fa-coffee', 'category' => 'meals'],
            ['name' => 'Swimming Pool', 'icon' => 'fas fa-swimming-pool', 'category' => 'recreation'],
            ['name' => 'Spa Services', 'icon' => 'fas fa-spa', 'category' => 'wellness'],
            ['name' => 'Tour Guide', 'icon' => 'fas fa-user-tie', 'category' => 'service'],
            ['name' => 'Air Conditioning', 'icon' => 'fas fa-snowflake', 'category' => 'comfort'],
            ['name' => 'Gym Access', 'icon' => 'fas fa-dumbbell', 'category' => 'fitness'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        // Create sample packages
        $packages = [
            [
                'title' => 'Tropical Paradise Retreat',
                'slug' => 'tropical-paradise-retreat',
                'short_description' => 'Escape to a tropical haven with pristine beaches and luxury accommodations.',
                'description' => 'Experience the ultimate tropical getaway in the Maldives with crystal-clear waters, white sandy beaches, and world-class resorts. This all-inclusive package includes overwater bungalows, spa treatments, and exciting water activities.',
                'category_id' => 1,
                'destination_id' => 1,
                'price' => 3299.00,
                'original_price' => 3999.00,
                'duration_days' => 7,
                'duration_nights' => 6,
                'min_participants' => 2,
                'max_participants' => 4,
                'difficulty_level' => 'easy',
                'featured_image' => 'packages/maldives-featured.jpg',
                'inclusions' => ['All meals', 'Accommodation', 'Airport transfers', 'Water sports', 'Spa treatment'],
                'exclusions' => ['International flights', 'Travel insurance', 'Personal expenses'],
                'highlights' => ['Overwater bungalow', 'Private beach access', 'Sunset cruise', 'Snorkeling'],
                'accommodation_type' => 'Luxury Resort',
                'meal_plan' => 'all-inclusive',
                'transportation' => 'Seaplane transfer',
                'is_featured' => true,
                'is_popular' => true,
                'status' => 'published',
                'tags' => ['luxury', 'beach', 'honeymoon', 'all-inclusive'],
            ],
            [
                'title' => 'Tokyo Cultural Adventure',
                'slug' => 'tokyo-cultural-adventure',
                'short_description' => 'Immerse yourself in Japanese culture with temples, cuisine, and modern attractions.',
                'description' => 'Discover the perfect blend of traditional and modern Japan in Tokyo. Visit ancient temples, experience authentic cuisine, explore bustling markets, and witness the latest technology in this incredible cultural journey.',
                'category_id' => 3,
                'destination_id' => 2,
                'price' => 2450.00,
                'duration_days' => 7,
                'duration_nights' => 6,
                'min_participants' => 1,
                'max_participants' => 12,
                'difficulty_level' => 'easy',
                'featured_image' => 'packages/tokyo-featured.jpg',
                'inclusions' => ['Hotel accommodation', 'Daily breakfast', 'Guided tours', 'JR Pass', 'Cultural experiences'],
                'exclusions' => ['International flights', 'Lunch and dinner', 'Personal shopping'],
                'highlights' => ['Senso-ji Temple', 'Tsukiji Fish Market', 'Mount Fuji day trip', 'Traditional tea ceremony'],
                'accommodation_type' => 'Boutique Hotel',
                'meal_plan' => 'breakfast',
                'transportation' => 'Public transport pass included',
                'is_featured' => true,
                'status' => 'published',
                'tags' => ['culture', 'temples', 'food', 'technology'],
            ],
            [
                'title' => 'Bali Beach Paradise',
                'slug' => 'bali-beach-paradise',
                'short_description' => 'Relax on beautiful beaches and explore the rich culture of Bali.',
                'description' => 'Experience the magic of Bali with its stunning beaches, ancient temples, lush rice terraces, and vibrant culture. This package combines relaxation with cultural exploration for the perfect tropical vacation.',
                'category_id' => 1,
                'destination_id' => 4,
                'price' => 1890.00,
                'duration_days' => 5,
                'duration_nights' => 4,
                'min_participants' => 2,
                'max_participants' => 8,
                'difficulty_level' => 'easy',
                'featured_image' => 'packages/bali-featured.jpg',
                'inclusions' => ['Beach resort accommodation', 'Daily breakfast', 'Temple tours', 'Cooking class', 'Airport transfers'],
                'exclusions' => ['International flights', 'Lunch and dinner', 'Spa treatments'],
                'highlights' => ['Tanah Lot Temple', 'Ubud Rice Terraces', 'Traditional cooking class', 'Beach relaxation'],
                'accommodation_type' => 'Beach Resort',
                'meal_plan' => 'breakfast',
                'transportation' => 'Private car with driver',
                'is_popular' => true,
                'status' => 'published',
                'tags' => ['beach', 'culture', 'temples', 'relaxation'],
            ],
        ];

        foreach ($packages as $packageData) {
            $package = Package::create($packageData);
            
            // Attach random amenities
        {
            $package = Package::create($packageData);
            
            // Attach random amenities
            $amenityIds = Amenity::pluck('id')->random(rand(3, 6));
            $package->amenities()->attach($amenityIds);
        }
    }
}
