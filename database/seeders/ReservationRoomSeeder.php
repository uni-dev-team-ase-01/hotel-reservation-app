<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationRoom;
use App\Models\Reservation;
use App\Models\Room;

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
