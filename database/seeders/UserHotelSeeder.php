<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserHotel;
use App\Models\User;
use App\Models\Hotel;

class UserHotelSeeder extends Seeder
{
    public function run()
    {
        // UserHotel::truncate();

        $users = User::all();
        $hotel = Hotel::first();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'user_id' => $user->id,
                'hotel_id' => $hotel->id,
            ];
        }

        foreach ($data as $item) {
            UserHotel::create($item);
        }
    }
}
