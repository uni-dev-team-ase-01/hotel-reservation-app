<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillService;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BillServiceSeeder extends Seeder
{
    public function run()
    {
        $bill = Bill::first();
        $services = Service::all();

        $data = [];

        foreach ($services as $service) {
            $data[] = [
                'bill_id' => $bill->id,
                'service_id' => $service->id,
                'charge' => rand(10, 50),
            ];
        }

        foreach ($data as $item) {
            BillService::create($item);
        }
    }
}
