<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('super-admin');

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
            ]
        );
        $customer->assignRole('customer');

        $travel = User::firstOrCreate(
            ['email' => 'travel@example.com'],
            [
                'name' => 'Travel Company',
                'password' => Hash::make('password'),
            ]
        );
        $travel->assignRole('travel-company');

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Hotel Manager',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole('hotel-manager');

        $clerk = User::firstOrCreate(
            ['email' => 'clerk@example.com'],
            [
                'name' => 'Hotel Clerk',
                'password' => Hash::make('password'),
            ]
        );
        $clerk->assignRole('hotel-clerk');
    }
}
