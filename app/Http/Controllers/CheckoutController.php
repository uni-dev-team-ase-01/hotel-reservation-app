<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function process(Request $request)
    {
        $reservation = Reservation::find($request->reservation_id);

        if (! $reservation) {
            return 'Reservation not found.';
        }

        // 1. Calculate number of nights
        $checkIn = new \DateTime($reservation->check_in_date);
        $checkOut = new \DateTime; // Today
        $nights = $checkIn->diff($checkOut)->days;

        // 2. Calculate room rate
        $room = Room::find($reservation->room_id);
        $roomRate = $room->rate;
        $totalCharge = $nights * $roomRate;

        // 3. Add extra service charges
        $extraServices = 150; // change as needed
        $totalCharge += $extraServices;

        // 4. Late check-out logic
        $now = now();
        $checkoutTime = now()->setTime(12, 0); // 12 PM cutoff

        if ($now > $checkoutTime) {
            $totalCharge += $roomRate; // Extra night
        }

        // 5. Mark reservation and room as checked out/available
        $reservation->status = 'checked_out';
        $reservation->save();

        $room->status = 'available';
        $room->save();

        // 6. Return summary
        return "Check-out successful. Total Bill: $totalCharge";
    }
}
