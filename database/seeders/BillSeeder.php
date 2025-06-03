<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    public function run()
    {
        $reservations = Reservation::all();

        $data = [];

        foreach ($reservations as $reservation) {
            $data[] = [
                'reservation_id' => $reservation->id,
                'room_charges' => 500.00,
                'extra_charges' => 50.00,
                'discount' => 25.00,
                'taxes' => 55.00,
                'total_amount' => 580.00,
                'payment_status' => 'pending',
            ];
        }

        foreach ($data as $bill) {
            Bill::create($bill);
        }
    }
}
