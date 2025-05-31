<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelService;
use App\Models\Hotel;
use App\Models\Service;

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
