<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ReservationController extends Controller
{
    public function startBooking(Request $request, $hotelId, $rooms)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
        ]);

        $roomIds = explode(',', $rooms);
        foreach ($roomIds as $id) {
            if (!ctype_digit($id)) {
                return redirect()->back()->with('error', 'Invalid room ID provided.');
            }
        }

        $hotel = Hotel::findOrFail($hotelId);
        $roomsCollection = Room::whereIn('id', $roomIds)
            ->where('hotel_id', $hotel->id)
            ->get();

        if ($roomsCollection->count() !== count($roomIds)) {
            return redirect()->back()->with('error', 'One or more selected rooms are invalid for this hotel.');
        }

        $checkIn = \Carbon\Carbon::parse($request->input('check_in'));
        $checkOut = \Carbon\Carbon::parse($request->input('check_out'));
        $nights = $checkIn->diffInDays($checkOut);

        $total = 0;
        foreach ($roomsCollection as $room) {
            $rate = \DB::table('room_rates')
                ->where('room_id', $room->id)
                ->where('rate_type', 'daily')
                ->value('amount');
            $total += ($rate ?? 0) * $nights;
        }

        $bookingData = [
            'hotel_id' => $hotel->id,
            'room_ids' => $roomsCollection->pluck('id')->toArray(),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'adults' => $request->input('adults', 1),
            'children' => $request->input('children', 0),
            'total_price' => $total,
            'nights' => $nights,
        ];

        Session::forget('pending_booking');
        Session::put('pending_booking', $bookingData);

        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $sripeKey = config('services.stripe.public');
        return redirect()->route('reservation.paymentForm');
    }

    // public function paymentForm(Request $request)
    // {
    //     $bookingData = $request->session()->get('pending_booking');
    //     if (!$bookingData) {
    //         return redirect()->route('home')->with('error', 'No booking data found.');
    //     }

    //     $user = Auth::user();

    //     if (method_exists($user, 'hasRole') && !$user->hasRole('customer')) {
    //         abort(403, 'Only customers can make reservations.');
    //     }

    //     $hotel = Hotel::find($bookingData['hotel_id']);
    //     $rooms = Room::whereIn('id', $bookingData['room_ids'])
    //         ->where('hotel_id', $hotel->id)
    //         ->get();

    //     if ($rooms->count() !== count($bookingData['room_ids'])) {
    //         return redirect()->route('home')->with('error', 'One or more selected rooms are invalid.');
    //     }

    //     $total = $bookingData['total_price'];

    //     return view('reservation.payment', [
    //         'booking' => $bookingData,
    //         'hotel' => $hotel,
    //         'rooms' => $rooms,
    //         'roomsCount' => $rooms->count(),
    //         'total' => $total,
    //         'stripeKey' => config('services.stripe.key'),
    //     ]);
    // }

    
    public function paymentForm(Request $request)
{
    $bookingData = $request->session()->get('pending_booking');
    if (!$bookingData) {
        return redirect()->route('home')->with('error', 'No booking data found.');
    }

    $hotel = Hotel::find($bookingData['hotel_id']);
    $rooms = Room::whereIn('id', $bookingData['room_ids'])
        ->where('hotel_id', $hotel->id)
        ->get();

    $total = $bookingData['total_price'];

    $stripeKey = config('services.stripe.public'); 

    return view('reservation.payment', [
        'booking' => $bookingData,
        'hotel' => $hotel,
        'rooms' => $rooms,
        'roomsCount' => $rooms->count(),
        'total' => $total,
        'stripeKey' => $stripeKey,   
    ]);
}
    
    public function createStripeIntent(Request $request)
    {
        $amount = $request->input('amount');
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }
public function processPayment(Request $request)
{
    $booking = $request->session()->get('pending_booking');
    if (!$booking) {
        return redirect()->route('home')->with('error', 'No booking data.');
    }

    $data = $request->validate([
        'guest_name' => 'required|string|max:255',
        'guest_email' => 'required|email',
        'guest_last_name' => 'nullable|string|max:255',
        'guest_mobile' => 'nullable|string|max:30',
        'guest_title' => 'nullable|string|max:10',
    ]);

    // $guest_full_name = trim($data['guest_name'].' '.($data['guest_last_name'] ?? '')); // consider

    $number_of_guests = ($booking['adults'] ?? 0) + ($booking['children'] ?? 0);


    $reservation = \App\Models\Reservation::create([
        'user_id' => Auth::id(),
        'hotel_id' => $booking['hotel_id'],
        'check_in_date' => $booking['check_in'],
        'check_out_date' => $booking['check_out'],
        'status' => 'pending',
        'number_of_guests' => $number_of_guests,
        'confirmation_number' => strtoupper(uniqid('CONF')),

    ]);

    $reservation->rooms()->attach($booking['room_ids']);

    $bill = Bill::create([
        'reservation_id' => $reservation->id,
        'room_charges' => $booking['total_price'],
        'extra_charges' => 0,
        'discount' => 0,
        'taxes' => 0,
        'total_amount' => $booking['total_price'],
        'payment_status' => 'paid',
    ]);
    Payment::create([
        'bill_id' => $bill->id,
        'method' => 'credit_card',
        'amount' => $bill->total_amount,
        'paid_at' => now(),
    ]);

    $request->session()->forget('pending_booking');

    return redirect()->route('reservation.success', ['reservation' => $reservation->id]);
}
    public function success($reservationId)
    {
        $reservation = Reservation::with('hotel', 'rooms')->findOrFail($reservationId);

        return view('reservation.success', ['reservation' => $reservation]);
    }
}