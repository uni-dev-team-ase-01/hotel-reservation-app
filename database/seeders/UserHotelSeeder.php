<?php

namespace Database\Seeders;

use App\Enum\UserRoleType;
use App\Models\Hotel;
use App\Models\User;
use App\Models\UserHotel;
use Illuminate\Database\Seeder;

class UserHotelSeeder extends Seeder
{
    public function run()
    {
        // UserHotel::truncate();

        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', [UserRoleType::HOTEL_CLERK->value, UserRoleType::HOTEL_MANAGER->value]);
        })->get();
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
