<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            // HotelSeeder::class,
            // ServiceSeeder::class,
            // HotelServicesSeeder::class,
            // RoomSeeder::class,
            // RoomRateSeeder::class,
            // TravelCompanySeeder::class,
            // UserHotelSeeder::class,
            // ReservationSeeder::class,
            // ReservationRoomSeeder::class,
            // BillSeeder::class,
            // BillServiceSeeder::class,
        ]);
    }
}
