<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TravelCompany;

class TravelCompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            ['company_name' => 'Travel Express', 'email' => 'contact@travelexpress.com', 'phone' => '555-1234'],
            ['company_name' => 'Global Tours', 'email' => 'info@globaltours.com', 'phone' => '555-5678'],
        ];

        foreach ($companies as $company) {
            TravelCompany::create($company);
        }
    }
}
