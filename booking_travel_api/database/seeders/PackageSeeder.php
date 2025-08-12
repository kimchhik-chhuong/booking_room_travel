<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Phnom Penh',
                'location' => 'Phnom Penh, Cambodia',
                'duration' => '7 Days',
                'price' => 250,
                'rating' => 4.9,
                'bookings' => 156,
                'status' => 'Active',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png',
                'category' => 'Cultural'
            ],
            [
                'title' => 'Battambang',
                'location' => 'Bali, Indonesia',
                'duration' => '5 Days',
                'price' => 1890,
                'rating' => 4.8,
                'bookings' => 203,
                'status' => 'Active',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png',
                'category' => 'Beach'
            ],
            [
                'title' => 'Kompot',
                'location' => 'Europe',
                'duration' => '14 Days',
                'price' => 4200,
                'rating' => 4.7,
                'bookings' => 89,
                'status' => 'Active',
                'image' => 'https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-GP7z1nSgCIj2SDO3HWgyYcov2Fgfii.png',
                'category' => 'Cultural'
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
