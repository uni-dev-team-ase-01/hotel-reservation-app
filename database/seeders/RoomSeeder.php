<?php

namespace Database\Seeders;

use App\Enum\RoomType;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $hotels = Hotel::all();

        if ($hotels->isEmpty()) {
            return;
        }

        // Decide which hotel gets only 4 rooms (e.g., the first one by ID)
        $firstHotel = $hotels->first();

        // Image list from public path (you can adjust these as needed)
        $imagePaths = [
            '01.jpg', '02.jpg', '03.jpg', '04.jpg',
            '05.jpg', '06.jpg', '07.jpg', '08.jpg',
            '09.jpg', '10.jpg', '11.jpg', '12.jpg',
        ];

        foreach ($hotels as $hotel) {
            $roomCount = ($hotel->id === $firstHotel->id) ? 4 : 9;
            $roomTypes = [RoomType::SINGLE->value, RoomType::DOUBLE->value, RoomType::FAMILY->value];

            for ($i = 1; $i <= $roomCount; $i++) {
                $imageIndex = ($i - 1) % count($imagePaths); // loop through images
                Room::create([
                    'hotel_id' => $hotel->id,
                    'room_number' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    'room_type' => $roomTypes[($i - 1) % count($roomTypes)],
                    'occupancy' => match ($roomTypes[($i - 1) % count($roomTypes)]) {
                        RoomType::SINGLE->value => 1,
                        RoomType::DOUBLE->value => 2,
                        RoomType::FAMILY->value => 4,
                        default => 1,
                    },
                    'location' => ($i <= 4) ? '1st Floor' : (($i <= 7) ? '2nd Floor' : '3rd Floor'),
                    'images' => json_encode([
                        'assets/images/category/hotel/4by3/' . $imagePaths[$imageIndex],
                    ]),
                ]);
            }
        }
    }
}
