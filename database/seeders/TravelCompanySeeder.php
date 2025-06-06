<?php

namespace Database\Seeders;

use App\Models\TravelCompany;
use Illuminate\Database\Seeder;

class TravelCompanySeeder extends Seeder
{
    public function run()
    {
        $companies = [
            [
                'company_name' => 'Travel Express',
                'email' => 'contact@travelexpress.com',
                'phone' => '555-1234',
                'message' => ''
            ],
            [
                'company_name' => 'Global Tours',
                'email' => 'info@globaltours.com',
                'phone' => '555-5678',
                'message' => ''
            ],
        ];


        foreach ($companies as $company) {
            TravelCompany::create($company);
        }
    }
}
