<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function showRooms(Request $request, $hotelId)
    {
        // dd($request->all());
        $hotel = Hotel::with(['rooms.rates'])->findOrFail($hotelId);

        return view('customer.hotels.rooms.index', compact('hotel'));
    }

    /**
     * API: Get available rooms for a hotel given check-in and check-out dates.
     *
     * Example endpoint: GET /hotel/{hotel}/available-rooms?check_in=YYYY-MM-DD&check_out=YYYY-MM-DD?rooms=?
     */
    public function availableRooms(Request $request, $hotelId)
    {
        $request->validate([
            'check_in' => 'required|date|before:check_out',
            'check_out' => 'required|date|after:check_in',
        ]);

        $hotel = Hotel::findOrFail($hotelId);
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $adults = (int) $request->input('adults', 1);
        $children = (int) $request->input('children', 0);
        $totalGuests = $adults + $children;

        // Get rooms that are NOT reserved for the selected dates, eager load daily rate
        $rooms = $hotel->rooms()
            ->with([
                'roomRates' => function ($q) {
                    $q->where('rate_type', 'daily');
                },
            ])
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn)
                        ->whereIn('status', ['confirmed', 'checked_in']); // Ensure only active reservations block rooms
                });
            })
            ->where('occupancy', '>=', $totalGuests) // Filter by occupancy
            ->get()
            ->map(function ($room) {
                $dailyRate = optional($room->roomRates->first())->amount;

                // Handle images: JSON string, comma string, or array, always return array
                $images = [];
                if (is_array($room->images)) {
                    $images = $room->images;
                } elseif (is_string($room->images) && $room->images) {
                    $decoded = json_decode($room->images, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $images = $decoded;
                    } else {
                        $images = array_map('trim', explode(',', $room->images));
                    }
                }

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_type' => $room->room_type,
                    'occupancy' => $room->occupancy,
                    'location' => $room->location,
                    'images' => $images,
                    'image' => $images[0] ?? null,
                    'daily_rate' => $dailyRate,
                ];
            })
            ->values(); // Reset keys for JSON output

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }
}
