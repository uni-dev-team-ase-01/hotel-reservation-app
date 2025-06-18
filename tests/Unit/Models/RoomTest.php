<?php

namespace Tests\Unit\Models;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomRate;
use App\Models\Reservation; // For reservation_rooms relationship
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_room_belongs_to_a_hotel()
    {
        $hotel = Hotel::factory()->create();
        $room = Room::factory()->create(['hotel_id' => $hotel->id]);

        $this->assertInstanceOf(Hotel::class, $room->hotel);
        $this->assertEquals($hotel->id, $room->hotel->id);
    }

    /** @test */
    public function a_room_has_many_room_rates()
    {
        $room = Room::factory()->create();
        // RoomRateFactory will be created in a subsequent step/subtask
        RoomRate::factory()->count(2)->create(['room_id' => $room->id]);

        $this->assertInstanceOf(RoomRate::class, $room->roomRates->first());
        $this->assertCount(2, $room->roomRates);
    }

    /** @test */
    public function a_room_belongs_to_many_reservations_through_reservation_rooms()
    {
        $room = Room::factory()->create();
        $reservation1 = Reservation::factory()->create(); // ReservationFactory exists
        $reservation2 = Reservation::factory()->create();

        // Assuming 'reservations' is the name of the BelongsToMany relationship in Room model
        // And it uses a pivot table like 'reservation_room'
        $room->reservations()->attach([
            $reservation1->id => ['quantity' => 1, 'price_at_booking' => 100.00], // Example pivot data
            $reservation2->id => ['quantity' => 1, 'price_at_booking' => 120.00]  // Example pivot data
        ]);


        $this->assertInstanceOf(Reservation::class, $room->reservations->first());
        $this->assertCount(2, $room->reservations);

        // Optionally, check pivot data if your setup supports it easily
        // $this->assertEquals(100.00, $room->reservations->first()->pivot->price_at_booking);
    }

    /** @test */
    public function room_has_fillable_attributes()
    {
        $fillable = [
            'hotel_id', 'room_number', 'room_type', 'description',
            'occupancy', 'status', 'images', 'location', // 'location' was seen in RoomController
            // Added based on RoomFactory enhancements
            'beds_configuration', 'area_sqm', 'amenities', 'notes', 'is_available'
        ];
        $room = new Room();
        $this->assertEqualsCanonicalizing($fillable, $room->getFillable());
    }

    /** @test */
    public function room_casts_attributes()
    {
        $room = Room::factory()->make(); // Use make() as we are not testing DB persistence here

        $expectedCasts = [
            'images' => 'array',
            'amenities' => 'array',
            'beds_configuration' => 'array',
            'is_available' => 'boolean',
            // 'id' => 'int' // Default, usually not needed to assert
        ];
        // Get actual casts from the model instance
        $actualModelCasts = $room->getCasts();

        // Filter actual casts to only include keys present in expectedCasts for precise comparison
        $filteredActualCasts = array_intersect_key($actualModelCasts, $expectedCasts);
        $this->assertEquals($expectedCasts, $filteredActualCasts);

        // Test actual casting behavior for 'images'
        $jsonDataImages = ['room_img1.jpg', 'room_img2.png'];
        $roomWithImages = Room::factory()->create(['images' => json_encode($jsonDataImages)]);
        $this->assertIsArray($roomWithImages->images);
        $this->assertEquals($jsonDataImages, $roomWithImages->images);

        // Test 'is_available' casting
        $roomAvailable = Room::factory()->create(['is_available' => 1, 'status' => 'Available']);
        $this->assertIsBool($roomAvailable->is_available);
        $this->assertTrue($roomAvailable->is_available);

        $roomUnavailable = Room::factory()->create(['is_available' => 0, 'status' => 'Occupied']);
        $this->assertIsBool($roomUnavailable->is_available);
        $this->assertFalse($roomUnavailable->is_available);
    }
}
