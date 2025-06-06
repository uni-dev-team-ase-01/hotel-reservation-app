<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('customer.hotels.index', compact('hotels'));
    }
    public function getHotels(Request $request)
    {
        $q = Hotel::query()->where('active', true);

        // AJAX filter params
        if ($request->filled('location')) {
            $q->where('address', 'like', '%' . $request->location . '%');
        }
        if ($request->filled('hotel_type')) {
            $types = $request->input('hotel_type', []);
            if (is_array($types) && count($types) && !in_array('All', $types)) {
                $q->whereIn('type', $types);
            }
        }
        if ($request->filled('star_rating')) {
            $stars = $request->input('star_rating', []);
            if (is_array($stars) && count($stars)) {
                $q->whereIn(\DB::raw('FLOOR(star_rating)'), $stars);
            }
        }

        // Pagination
        $pageSize = (int) $request->input('per_page', 5);
        $hotels = $q->orderBy('id', 'desc')->get();

        // Format hotels for JS
        $hotels = $hotels->map(function ($h) {
            return [
                'id' => $h->id,
                'name' => $h->name,
                'address' => $h->address,
                'star_rating' => $h->star_rating,
                'images' => $h->images ? [trim($h->images)] : ['/assets/images/category/hotel/4by3/04.jpg'],
                'price' => $h->price ?? '',
                'original_price' => $h->original_price ?? null,
                'discount' => $h->discount ?? null,
                'website' => $h->website ?? '#',
                'description' => $h->description ?? '',
                'type' => $h->type ?? '',
                // Optionally add amenities etc...
            ];
        })->values();

        return response()->json(['data' => $hotels]);
    }
    /**
     * Search hotels by location, check-in/out date, guests, rooms.
     * Endpoint: GET /api/hotels/search
     * Query params:
     *   - location (string, optional)
     *   - check_in (date, optional, format: Y-m-d)
     *   - check_out (date, optional, format: Y-m-d)
     *   - guests (int, optional)
     *   - rooms (int, optional)
     */
    public function search(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string',
            'check_in' => 'required|date|before:check_out',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'nullable|integer|min:1',
        ]);

        $location = $request->location;
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $adults = intval($request->input('adults', 1));
        $children = intval($request->input('children', 0));
        $totalGuests = $adults + $children;
        $numRooms = intval($request->input('rooms', 1));

        // Step 1: Filter hotels by location if provided
        $hotelsQuery = Hotel::query();
        if ($location) {
            $hotelsQuery->where('address', 'like', "%$location%");
        }
        $hotels = $hotelsQuery->with('rooms')->get();

        $result = [];
        foreach ($hotels as $hotel) {
            // Step 2: Find available rooms for this hotel
            $eligibleRooms = $hotel->rooms()
                ->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut) {
                    $q->where(function ($query) use ($checkIn, $checkOut) {
                        $query->where('check_in_date', '<', $checkOut)
                            ->where('check_out_date', '>', $checkIn)
                            ->whereIn('status', ['confirmed', 'checked_in']);
                    });
                })
                // Optionally, filter by occupancy if needed
                ->where('occupancy', '>=', $totalGuests)
                ->get();

            // Only include hotels with enough available rooms
            if ($eligibleRooms->count() >= $numRooms) {
                $result[] = [
                    'hotel' => $hotel,
                    'rooms' => $eligibleRooms->take($numRooms)->values(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function selectOptions()
    {
        $hotels = Hotel::select('id', 'name', 'address')->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $hotels
        ]);
    }

}
