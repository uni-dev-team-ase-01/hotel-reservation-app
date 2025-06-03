<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelService;
use App\Models\Service;
use Illuminate\Database\Seeder;

class HotelServicesSeeder extends Seeder
{
    public function run()
    {
        $hotel = Hotel::first();
        $services = Service::all();

        $data = [];

        foreach ($services as $service) {
            $data[] = [
                'hotel_id' => $hotel->id,
                'services_id' => $service->id,
                'charge' => rand(5, 50),
            ];
        }

        foreach ($data as $item) {
            HotelService::create($item);
        }
    }
}
