<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use Illuminate\Database\Seeder;

class ReservationRoomSeeder extends Seeder
{
    public function run()
    {
        $reservation = Reservation::first();
        $rooms = Room::all();

        $data = [];

        foreach ($rooms as $room) {
            $data[] = [
                'reservation_id' => $reservation->id,
                'room_id' => $room->id,
            ];
        }

        foreach ($data as $item) {
            ReservationRoom::create($item);
        }
    }
}
