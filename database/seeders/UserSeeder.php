<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        // $superAdminRole = Role::where('name', 'super-admin')
        //     ->where('guard_name')
        //     ->first();
        // $superAdmin->assignRole($superAdminRole);
        $superAdmin->guard_name = 'admin';
        $superAdmin->assignRole('super-admin');

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
            ]
        );
        $customer->guard_name = 'web';
        $customer->assignRole('customer');

        $travel = User::firstOrCreate(
            ['email' => 'travel@example.com'],
            [
                'name' => 'Travel Company',
                'password' => Hash::make('password'),
            ]
        );
        $travel->guard_name = 'admin';
        $travel->assignRole('travel-company');

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Hotel Manager',
                'password' => Hash::make('password'),
            ]
        );
        $manager->guard_name = 'admin';
        $manager->assignRole('hotel-manager');

        $clerk = User::firstOrCreate(
            ['email' => 'clerk@example.com'],
            [
                'name' => 'Hotel Clerk',
                'password' => Hash::make('password'),
            ]
        );
        $clerk->guard_name = 'admin';
        $clerk->assignRole('hotel-clerk');
    }
}
