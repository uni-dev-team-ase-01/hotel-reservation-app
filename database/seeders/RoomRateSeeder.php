<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomRate;
use App\Models\Room;

class RoomRateSeeder extends Seeder
{
    public function run()
    {

        $room = Room::first();

        $rates = [
            ['room_id' => $room->id, 'rate_type' => 'daily', 'amount' => 100.00],
            ['room_id' => $room->id, 'rate_type' => 'weekly', 'amount' => 600.00],
        ];

        foreach ($rates as $rate) {
            RoomRate::create($rate);
        }
    }
}
