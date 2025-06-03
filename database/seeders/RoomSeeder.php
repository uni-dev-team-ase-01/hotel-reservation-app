<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {

        $hotel = Hotel::first();

        $rooms = [
            [
                'hotel_id' => $hotel->id,
                'room_number' => '101',
                'room_type' => 'Deluxe',
                'occupancy' => 2,
                'location' => '1st Floor',
                'images' => null,
            ],
            [
                'hotel_id' => $hotel->id,
                'room_number' => '102',
                'room_type' => 'Standard',
                'occupancy' => 1,
                'location' => '1st Floor',
                'images' => null,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
