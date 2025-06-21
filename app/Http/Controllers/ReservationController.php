<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use Session;
use Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class ReservationController extends Controller
{
    public function startBooking(Request $request, $hotelId, $rooms)
    {
        $request->validate([
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $maxDate = \Carbon\Carbon::today()->addDays(300)->format('Y-m-d');
                    if ($value > $maxDate) {
                        $fail('Check-in date cannot be more than 300 days from today.');
                    }
                }
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in',
                function ($attribute, $value, $fail) use ($request) {
                    $maxDate = \Carbon\Carbon::today()->addDays(300)->format('Y-m-d');
                    if ($value > $maxDate) {
                        $fail('Check-out date cannot be more than 300 days from today.');
                    }
                }
            ],
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
    public function ajaxConfirm(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'payment_status' => 'required|in:pending,paid',
        ]);

        try {
            DB::beginTransaction();

            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'hotel_id' => $request->hotel_id,
                'check_in_date' => $request->check_in,
                'check_out_date' => $request->check_out,
                'number_of_guests' => $request->adults + $request->children,
                'status' => 'pending',
                'confirmation_number' => strtoupper(Str::random(10)),
            ]);

            foreach ($request->room_ids as $roomId) {
                $reservation->rooms()->attach($roomId);
            }

            // $bill = Bill::create([
            //     'reservation_id' => $reservation->id,
            //     'room_charges' => $request->room_charges,
            //     'extra_charges' => $request->extra_charges,
            //     'discount' => $request->discount,
            //     'taxes' => $request->taxes,
            //     'total_amount' => $request->total_amount,
            //     'payment_status' => $request->payment_status,
            // ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('home')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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
        $room_charges = $total;
        $extra_charges = 0;
        $discount = 0;
        $taxes = 0;
        $total_amount = $total;
        $stripeKey = config('services.stripe.public');

        return view('reservation.payment', [
            'booking' => $bookingData,
            'hotel' => $hotel,
            'rooms' => $rooms,
            'roomsCount' => $rooms->count(),
            'total' => $total,
            'stripeKey' => $stripeKey,
            'room_charges' => $room_charges,
            'extra_charges' => $extra_charges,
            'discount' => $discount,
            'taxes' => $taxes,
            'total_amount' => $total_amount,
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
        return redirect('/customer/reservations');
    }
}