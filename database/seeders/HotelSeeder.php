<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run()
    {
        $hotels = [
            [
                'name' => 'Grand Hotel',
                'description' => 'Luxurious hotel in the city center.',
                'address' => 'Nuwara Eliya, Central Province, Sri Lanka',
                'district' => 'Nuwara Eliya',
                'star_rating' => 5,
                'images' => '/assets/images/category/hotel/resort/01.jpg',
                'website' => 'https://grandhotel.com',
                'type' => 'Luxury',
                'active' => true,
            ],
            [
                'name' => 'Cozy Inn',
                'description' => 'Comfortable and affordable.',
                'address' => 'Kurunegala, North Western Province, Sri Lanka',
                'district' => 'Kurunegala',
                'star_rating' => 3,
                'images' => '/assets/images/category/hotel/resort/02.jpg',
                'website' => 'https://cozyinn.com',
                'type' => 'Budget',
                'active' => true,
            ],
            [
                'name' => 'Jetwing Lighthouse',
                'description' => 'Elegant beachfront hotel in Galle.',
                'address' => 'Dadella, Galle, Southern Province, Sri Lanka',
                'district' => 'Galle',
                'star_rating' => 5,
                'images' => '/assets/images/category/hotel/resort/03.jpg',
                'website' => 'https://www.jetwinghotels.com/jetwinglighthouse/',
                'type' => 'Resort',
                'active' => true,
            ],
            [
                'name' => 'Cinnamon Grand',
                'description' => 'Luxury hotel in the heart of Colombo.',
                'address' => '77 Galle Road, Colombo 03, Sri Lanka',
                'district' => 'Colombo',
                'star_rating' => 5,
                'images' => '/assets/images/category/hotel/resort/04.jpg',
                'website' => 'https://www.cinnamonhotels.com/cinnamongrandcolombo',
                'type' => 'Luxury',
                'active' => true,
            ],
            [
                'name' => 'Amaya Lake',
                'description' => 'Tranquil lakeside resort in Dambulla.',
                'address' => 'Kap Ela, Kandalama, Dambulla, Sri Lanka',
                'district' => 'Dambulla',
                'star_rating' => 4,
                'images' => '/assets/images/category/hotel/resort/05.jpg',
                'website' => 'https://www.amayaresorts.com/amayalake/',
                'type' => 'Resort',
                'active' => true,
            ],
            [
                'name' => 'Anantara Tangalle',
                'description' => 'Secluded luxury resort on the southern coast.',
                'address' => 'Goyambokka Estate, Tangalle 82200, Sri Lanka',
                'district' => 'Tangalle',
                'star_rating' => 5,
                'images' => '/assets/images/category/hotel/resort/06.jpg',
                'website' => 'https://www.anantara.com/en/peace-haven-tangalle',
                'type' => 'Resort',
                'active' => true,
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}
