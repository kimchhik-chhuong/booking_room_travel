<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelMetadata;
use App\Models\RoomType;
use App\Models\Province;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get provinces for relationships
        $phnomPenh = Province::where('name', 'Phnom Penh')->first();
        $siemReap = Province::where('name', 'Siem Reap')->first();
        $battambang = Province::where('name', 'Battambang')->first();

        // Sample hotels data
        $hotels = [
            [
                'name' => 'Royal Palace Hotel',
                'address' => 'Street 184, Sangkat Chey Chumneas, Khan Daun Penh, Phnom Penh',
                'latitude' => 11.5564,
                'longitude' => 104.9282,
                'star_rating' => 4.5,
                'description' => 'Luxury hotel in the heart of Phnom Penh, near the Royal Palace and Silver Pagoda. Offering world-class amenities and exceptional service.',
                'image_url' => 'https://example.com/images/royal-palace-hotel-main.jpg',
                'images' => [
                    'https://example.com/images/royal-palace-hotel-main.jpg',
                    'https://example.com/images/royal-palace-hotel-lobby.jpg',
                    'https://example.com/images/royal-palace-hotel-pool.jpg',
                    'https://example.com/images/royal-palace-hotel-restaurant.jpg'
                ],
                'amenities' => [
                    'Free WiFi',
                    'Swimming Pool',
                    'Restaurant',
                    'Fitness Center',
                    'Spa',
                    'Room Service',
                    'Parking',
                    'Airport Shuttle'
                ],
                'contact_phone' => '+855 23 981 888',
                'email' => 'info@royalpalacehotel.com',
                'website_url' => 'https://royalpalacehotel.com',
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'province_id' => $phnomPenh?->id,
                'status' => 'active',
                'room_types' => [
                    [
                        'name' => 'Standard Room',
                        'description' => 'Comfortable room with city view, perfect for business travelers',
                        'price' => 85.00,
                        'max_occupancy' => 2,
                        'available_rooms' => 20,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Mini Bar'],
                        'image_url' => 'https://example.com/images/standard-room.jpg'
                    ],
                    [
                        'name' => 'Deluxe Room',
                        'description' => 'Spacious room with premium amenities and river view',
                        'price' => 120.00,
                        'max_occupancy' => 3,
                        'available_rooms' => 15,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Mini Bar', 'Balcony', 'River View'],
                        'image_url' => 'https://example.com/images/deluxe-room.jpg'
                    ],
                    [
                        'name' => 'Royal Suite',
                        'description' => 'Luxurious suite with separate living area and panoramic city view',
                        'price' => 250.00,
                        'max_occupancy' => 4,
                        'available_rooms' => 5,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Mini Bar', 'Living Room', 'City View', 'Butler Service'],
                        'image_url' => 'https://example.com/images/royal-suite.jpg'
                    ]
                ]
            ],
            [
                'name' => 'Angkor Heritage Hotel',
                'address' => 'Sivatha Boulevard, Siem Reap',
                'latitude' => 13.3671,
                'longitude' => 103.8448,
                'star_rating' => 4.2,
                'description' => 'Traditional Khmer architecture hotel near Angkor Wat temples. Experience authentic Cambodian hospitality with modern comfort.',
                'image_url' => 'https://example.com/images/angkor-heritage-main.jpg',
                'images' => [
                    'https://example.com/images/angkor-heritage-main.jpg',
                    'https://example.com/images/angkor-heritage-courtyard.jpg',
                    'https://example.com/images/angkor-heritage-pool.jpg',
                    'https://example.com/images/angkor-heritage-temple-view.jpg'
                ],
                'amenities' => [
                    'Free WiFi',
                    'Swimming Pool',
                    'Restaurant',
                    'Spa',
                    'Tour Desk',
                    'Bicycle Rental',
                    'Parking',
                    'Temple Shuttle'
                ],
                'contact_phone' => '+855 63 760 124',
                'email' => 'reservations@angkorheritage.com',
                'website_url' => 'https://angkorheritage.com',
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'province_id' => $siemReap?->id,
                'status' => 'active',
                'room_types' => [
                    [
                        'name' => 'Garden View Room',
                        'description' => 'Peaceful room overlooking tropical gardens',
                        'price' => 75.00,
                        'max_occupancy' => 2,
                        'available_rooms' => 25,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Garden View'],
                        'image_url' => 'https://example.com/images/garden-view-room.jpg'
                    ],
                    [
                        'name' => 'Temple View Room',
                        'description' => 'Premium room with distant temple views',
                        'price' => 110.00,
                        'max_occupancy' => 3,
                        'available_rooms' => 18,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Temple View', 'Balcony'],
                        'image_url' => 'https://example.com/images/temple-view-room.jpg'
                    ],
                    [
                        'name' => 'Heritage Suite',
                        'description' => 'Luxurious suite with traditional Khmer decor',
                        'price' => 200.00,
                        'max_occupancy' => 4,
                        'available_rooms' => 8,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'Living Area', 'Traditional Decor', 'Butler Service'],
                        'image_url' => 'https://example.com/images/heritage-suite.jpg'
                    ]
                ]
            ],
            [
                'name' => 'Riverside Boutique Hotel',
                'address' => 'Street 1, Battambang City Center',
                'latitude' => 13.0957,
                'longitude' => 103.2027,
                'star_rating' => 3.8,
                'description' => 'Charming boutique hotel along the Sangker River. Perfect base for exploring Battambang\'s colonial architecture and countryside.',
                'image_url' => 'https://example.com/images/riverside-boutique-main.jpg',
                'images' => [
                    'https://example.com/images/riverside-boutique-main.jpg',
                    'https://example.com/images/riverside-boutique-terrace.jpg',
                    'https://example.com/images/riverside-boutique-restaurant.jpg'
                ],
                'amenities' => [
                    'Free WiFi',
                    'Restaurant',
                    'River Terrace',
                    'Bicycle Rental',
                    'Tour Arrangements',
                    'Parking'
                ],
                'contact_phone' => '+855 53 730 202',
                'email' => 'info@riversidebattambang.com',
                'website_url' => 'https://riversidebattambang.com',
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'province_id' => $battambang?->id,
                'status' => 'active',
                'room_types' => [
                    [
                        'name' => 'Standard Room',
                        'description' => 'Cozy room with modern amenities',
                        'price' => 45.00,
                        'max_occupancy' => 2,
                        'available_rooms' => 12,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV'],
                        'image_url' => 'https://example.com/images/boutique-standard.jpg'
                    ],
                    [
                        'name' => 'River View Room',
                        'description' => 'Room with beautiful river views and private balcony',
                        'price' => 65.00,
                        'max_occupancy' => 2,
                        'available_rooms' => 8,
                        'amenities' => ['Air Conditioning', 'Free WiFi', 'TV', 'River View', 'Balcony'],
                        'image_url' => 'https://example.com/images/boutique-river-view.jpg'
                    ]
                ]
            ]
        ];

        // Create hotels and their room types
        foreach ($hotels as $hotelData) {
            $roomTypesData = $hotelData['room_types'];
            unset($hotelData['room_types']);

            $hotel = HotelMetadata::create($hotelData);

            // Create room types for this hotel
            foreach ($roomTypesData as $roomTypeData) {
                $roomTypeData['hotel_metadata_id'] = $hotel->hotel_id;
                RoomType::create($roomTypeData);
            }
        }

        $this->command->info('Hotels and room types seeded successfully!');
    }
}
