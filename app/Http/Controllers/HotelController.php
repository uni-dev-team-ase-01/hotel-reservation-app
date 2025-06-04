<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        return view('customer.hotels.index');

    }
    public function getHotels()
    {
        $hotels = Hotel::paginate(10);
    //  dd(response()->json($hotels));
        return response()->json($hotels);
    }
}
