<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {

        $services = [
            ['name' => 'WiFi'],
            ['name' => 'Breakfast'],
            ['name' => 'Parking'],
            ['name' => 'Swimming Pool'],
            ['name' => 'Gym'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
