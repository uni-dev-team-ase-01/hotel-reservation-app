<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    public function run()
    {
        $hotels = [
            [
                'name' => 'Grand Hotel',
                'description' => 'Luxurious hotel in the city center.',
                'address' => 'Nuwara Eliya, Central Province, Sri Lanka',
                'star_rating' => 5,
                'images' => json_encode(['/assets/images/category/hotel/resort/01.jpg']),
                'website' => 'https://grandhotel.com',
                'active' => true,
            ],
            [
                'name' => 'Cozy Inn',
                'description' => 'Comfortable and affordable.',
                'address' => 'Kurunegala, North Western Province, Sri Lanka',
                'star_rating' => 3,
                'images' => json_encode(['/assets/images/category/hotel/resort/02.jpg']),
                'website' => 'https://cozyinn.com',
                'active' => true,
            ],
            [
                'name' => 'Jetwing Lighthouse',
                'description' => 'Elegant beachfront hotel in Galle.',
                'address' => 'Dadella, Galle, Southern Province, Sri Lanka',
                'star_rating' => 5,
                'images' => json_encode(['/assets/images/category/hotel/resort/03.jpg']),
                'website' => 'https://www.jetwinghotels.com/jetwinglighthouse/',
                'active' => true,
            ],
            [
                'name' => 'Cinnamon Grand',
                'description' => 'Luxury hotel in the heart of Colombo.',
                'address' => '77 Galle Road, Colombo 03, Sri Lanka',
                'star_rating' => 5,
                'images' => json_encode(['/assets/images/category/hotel/resort/04.jpg']),
                'website' => 'https://www.cinnamonhotels.com/cinnamongrandcolombo',
                'active' => true,
            ],
            [
                'name' => 'Amaya Lake',
                'description' => 'Tranquil lakeside resort in Dambulla.',
                'address' => 'Kap Ela, Kandalama, Dambulla, Sri Lanka',
                'star_rating' => 4,
                'images' => json_encode(['/assets/images/category/hotel/resort/05.jpg']),
                'website' => 'https://www.amayaresorts.com/amayalake/',
                'active' => true,
            ],
            [
                'name' => 'Anantara Tangalle',
                'description' => 'Secluded luxury resort on the southern coast.',
                'address' => 'Goyambokka Estate, Tangalle 82200, Sri Lanka',
                'star_rating' => 5,
                'images' => json_encode(['/assets/images/category/hotel/resort/06.jpg']),
                'website' => 'https://www.anantara.com/en/peace-haven-tangalle',
                'active' => true,
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}
