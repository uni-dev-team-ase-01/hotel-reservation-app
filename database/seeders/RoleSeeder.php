<?php

namespace Database\Seeders;

use App\Enum\UserRoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            UserRoleType::SUPER_ADMIN->value,
            UserRoleType::CUSTOMER->value,
            UserRoleType::TRAVEL_COMPANY->value,
            UserRoleType::HOTEL_MANAGER->value,
            UserRoleType::HOTEL_CLERK->value,
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => $role === UserRoleType::CUSTOMER->value ? 'web' : 'admin']);
        }
    }
}
