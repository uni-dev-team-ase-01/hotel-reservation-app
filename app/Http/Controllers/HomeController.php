<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $cities = [
            ['name' => 'Colombo', 'img' => 'assets/images/category/hotel/nearby/01.jpg'],
            ['name' => 'Kandy', 'img' => 'assets/images/category/hotel/nearby/03.jpg'],
            ['name' => 'Galle', 'img' => 'assets/images/category/hotel/nearby/12.jpg'],
            ['name' => 'Negombo', 'img' => 'assets/images/category/hotel/nearby/04.jpg'],
            ['name' => 'Jaffna', 'img' => 'assets/images/category/hotel/nearby/05.jpg'],
            ['name' => 'Dabulla', 'img' => 'assets/images/category/hotel/nearby/06.jpg'],
            ['name' => 'Nuwara Eliya', 'img' => 'assets/images/category/hotel/nearby/07.jpg'],
            ['name' => 'Trincomalee', 'img' => 'assets/images/category/hotel/nearby/08.jpg'],
            ['name' => 'Batticaloa', 'img' => 'assets/images/category/hotel/nearby/09.jpg'],
            ['name' => 'Matara', 'img' => 'assets/images/category/hotel/nearby/10.jpg'],
            ['name' => 'Polonnaruwa', 'img' => 'assets/images/category/hotel/nearby/11.jpg'],
            ['name' => 'Dambulla', 'img' => 'assets/images/category/hotel/nearby/02.jpg'],
        ];

        return view('customer.home', compact('cities'));
    }
}
