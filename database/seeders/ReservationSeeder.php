<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;

class ReservationSeeder extends Seeder
{
    public function run()
    {

        $users = User::all();

        $reservations = [];

        foreach ($users as $index => $user) {
            $reservations[] = [
                'user_id' => $user->id,
                'check_in_date' => now()->addDays($index + 1)->toDateString(),
                'check_out_date' => now()->addDays($index + 3)->toDateString(),
                'status' => 'confirmed',
                'number_of_guests' => 2,
                'confirmation_number' => 'CONF' . rand(100000, 999999),
                'auto_cancelled' => false,
                'no_show_billed' => false,
            ];
        }

        foreach ($reservations as $reservation) {
            Reservation::create($reservation);
        }
    }
}
