<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon; // Added for Carbon instance check

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_reservation_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $reservation->user);
        $this->assertEquals($user->id, $reservation->user->id);
    }

    /** @test */
    public function a_reservation_belongs_to_a_hotel()
    {
        $hotel = Hotel::factory()->create();
        $reservation = Reservation::factory()->create(['hotel_id' => $hotel->id]);

        $this->assertInstanceOf(Hotel::class, $reservation->hotel);
        $this->assertEquals($hotel->id, $reservation->hotel->id);
    }

    /** @test */
    public function a_reservation_belongs_to_many_rooms_through_reservation_rooms()
    {
        $reservation = Reservation::factory()->create();
        // Ensure rooms are created for the same hotel as the reservation for consistency
        $room1 = Room::factory()->create(['hotel_id' => $reservation->hotel_id]);
        $room2 = Room::factory()->create(['hotel_id' => $reservation->hotel_id]);

        // Attach rooms with example pivot data
        $reservation->rooms()->attach([
            $room1->id => ['number_of_guests_for_room' => 2, 'notes' => 'Near window if possible'],
            $room2->id => ['number_of_guests_for_room' => 1, 'notes' => 'Quiet room requested']
        ]);

        $this->assertInstanceOf(Room::class, $reservation->rooms->first());
        $this->assertCount(2, $reservation->rooms);

        // Example of checking pivot data if the 'pivot' attribute is available and configured
        // This assumes your Room model's reservations relationship correctly defines pivot access.
        if ($reservation->rooms->first()->pivot) {
             $this->assertEquals(2, $reservation->rooms->first()->pivot->number_of_guests_for_room);
        }
    }

    /** @test */
    public function a_reservation_has_one_bill()
    {
        $reservation = Reservation::factory()->create();
        // BillFactory will be created in this subtask
        $bill = Bill::factory()->create(['reservation_id' => $reservation->id]);

        $this->assertInstanceOf(Bill::class, $reservation->bill);
        $this->assertEquals($bill->id, $reservation->bill->id);
    }

    /** @test */
    public function reservation_has_fillable_attributes()
    {
        $fillable = [
            'user_id', 'hotel_id', 'check_in_date', 'check_out_date',
            'num_adults', 'num_children', // Added from factory
            'number_of_guests', 'status', 'confirmation_number', 'notes',
            'total_amount', 'payment_method', 'payment_status', // Added from factory
            'confirmed_at', 'cancelled_at', 'cancellation_reason' // Added from factory
        ];
        $reservation = new Reservation();
        $this->assertEqualsCanonicalizing($fillable, $reservation->getFillable());
    }

    /** @test */
    public function reservation_casts_attributes()
    {
        $reservation = Reservation::factory()->make(); // Using make for cast checking

        $expectedCasts = [
            'check_in_date' => 'datetime', // Adjusted to datetime as per factory
            'check_out_date' => 'datetime',// Adjusted to datetime as per factory
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'total_amount' => 'float', // Assuming it might be decimal or float
            // 'id' => 'int' // Default
        ];
        $actualCasts = $reservation->getCasts(); // Get all casts from model

        // Check that all expected casts are present and correct in the actual casts
        foreach ($expectedCasts as $key => $type) {
            $this->assertArrayHasKey($key, $actualCasts, "Cast for '$key' not found.");
            $this->assertEquals($type, $actualCasts[$key], "Cast type for '$key' does not match.");
        }

        // Test actual casting behavior for dates
        $checkInString = '2024-12-25 14:00:00';
        $reservationWithDates = Reservation::factory()->create(['check_in_date' => $checkInString]);
        $this->assertInstanceOf(Carbon::class, $reservationWithDates->check_in_date);
        // Compare full datetime string if that's how it's stored/cast
        $this->assertEquals($checkInString, $reservationWithDates->check_in_date->format('Y-m-d H:i:s'));
    }
}
