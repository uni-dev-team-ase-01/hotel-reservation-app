<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use App\Enum\ReservationStatusType; // For creating reservations with specific statuses
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_rooms_page_is_accessible_and_passes_hotel_data()
    {
        $hotel = Hotel::factory()->has(Room::factory()->count(3))->create();

        $response = $this->get("/hotel/{$hotel->id}/rooms");

        $response->assertStatus(200);
        $response->assertViewIs('customer.hotels.rooms.index');
        $response->assertViewHas('hotel');
        $this->assertEquals($hotel->id, $response->viewData('hotel')->id);
        // Check for a key element rendered by Vue/JS part of the page, e.g., the root app div for room listing
        $response->assertSee('<div id="room-listing-app"', false);
    }

    /** @test */
    public function available_rooms_api_requires_check_in_and_check_out_dates()
    {
        $hotel = Hotel::factory()->create();
        $response = $this->getJson("/hotel/{$hotel->id}/available-rooms");

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['check_in', 'check_out']);
    }

    /** @test */
    public function available_rooms_api_returns_correctly_filtered_rooms()
    {
        $hotel = Hotel::factory()->create();
        $roomType1 = Room::factory()->create(['hotel_id' => $hotel->id, 'occupancy' => 2, 'room_type' => 'Single', 'status' => 'Available']);
        $roomType2 = Room::factory()->create(['hotel_id' => $hotel->id, 'occupancy' => 4, 'room_type' => 'Double', 'status' => 'Available']);
        $unavailableRoom = Room::factory()->create(['hotel_id' => $hotel->id, 'occupancy' => 2, 'room_type' => 'SingleBlocked', 'status' => 'Available']);

        $checkIn = now()->addDays(2)->toDateString();
        $checkOut = now()->addDays(5)->toDateString();

        // Make one room unavailable by creating a conflicting reservation
        // Ensure the reservation is for the specific room and covers the query dates.
        Reservation::factory()->create([
            'hotel_id' => $hotel->id,
            'check_in_date' => $checkIn, // Conflicting check-in
            'check_out_date' => $checkOut, // Conflicting check-out
            'status' => ReservationStatusType::CONFIRMED->value // Ensure it's a blocking status
        ])->rooms()->attach($unavailableRoom->id);


        $queryParams = [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => 2,
            'children' => 0,
        ];

        $response = $this->getJson("/hotel/{$hotel->id}/available-rooms?" . http_build_query($queryParams));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        // $response->assertJsonCount(2, 'data'); // RoomFactory might create additional rooms for Hotel::factory() if not careful
                                               // So, check for presence/absence instead of exact count if hotel factory creates rooms by default.
                                               // Or, ensure hotel factory doesn't create rooms when not needed for this test.
                                               // For now, we assume Hotel::factory() does not create rooms unless specified with ->has()

        $returnedRoomIds = collect($response->json('data'))->pluck('id')->all();
        $this->assertContains($roomType1->id, $returnedRoomIds, "RoomType1 (ID: {$roomType1->id}) not found in available rooms.");
        $this->assertContains($roomType2->id, $returnedRoomIds, "RoomType2 (ID: {$roomType2->id}) not found in available rooms.");
        $this->assertNotContains($unavailableRoom->id, $returnedRoomIds, "UnavailableRoom (ID: {$unavailableRoom->id}) was unexpectedly found in available rooms.");

        // Check occupancy filtering
        $queryParamsHighOccupancy = array_merge($queryParams, ['adults' => 3]); // Request for 3 adults
        $responseHighOccupancy = $this->getJson("/hotel/{$hotel->id}/available-rooms?" . http_build_query($queryParamsHighOccupancy));

        $responseHighOccupancy->assertStatus(200);
        $responseHighOccupancy->assertJsonPath('success', true);
        $returnedRoomIdsHighOccupancy = collect($responseHighOccupancy->json('data'))->pluck('id')->all();

        $this->assertNotContains($roomType1->id, $returnedRoomIdsHighOccupancy, "RoomType1 should not be available for 3 adults.");
        $this->assertContains($roomType2->id, $returnedRoomIdsHighOccupancy, "RoomType2 should be available for 3 adults.");
        if(!empty($responseHighOccupancy->json('data'))){
            $this->assertEquals($roomType2->id, $responseHighOccupancy->json('data.0.id'));
            $this->assertCount(1, $responseHighOccupancy->json('data')); // Only roomType2 (occupancy 4) should be available
        } else {
            $this->fail("Expected roomType2 to be available for high occupancy search but got empty data set.");
        }
    }

    /** @test */
    public function available_rooms_api_returns_empty_if_no_rooms_match_criteria()
    {
        $hotel = Hotel::factory()->create();
        Room::factory()->create(['hotel_id' => $hotel->id, 'occupancy' => 2, 'status' => 'Available']);

        $checkIn = now()->addDays(2)->toDateString();
        $checkOut = now()->addDays(5)->toDateString();

        $queryParams = [
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'adults' => 10, // No room will match this occupancy
        ];

        $response = $this->getJson("/hotel/{$hotel->id}/available-rooms?" . http_build_query($queryParams));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(0, 'data');
    }
}
