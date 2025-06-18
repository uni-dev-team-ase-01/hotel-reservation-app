<?php

namespace Tests\Unit\Jobs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Jobs\CancelNoPaymentMethodReservationsJob;
use App\Enum\ReservationStatusType; // Changed from ReservationStatus to ReservationStatusType to match previous usage
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CancelNoPaymentMethodReservationsJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cancels_old_pending_reservations_without_payment_method()
    {
        // --- Setup ---
        $hotel = Hotel::factory()->create();
        $user = User::factory()->create();

        // Reservation 1: Should be cancelled
        // - pending, old, and assume job checks for something like 'payment_details_provided' => false
        // or 'payment_guarantee_type' => 'none'
        $oldPendingReservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::PENDING->value,
            'created_at' => Carbon::now()->subDays(2),
            // 'payment_guaranteed_by' => null, // Example field job might check
            // 'has_payment_method_on_file' => false, // Another example
            // For this test, the primary filter will be age and PENDING status.
            // The job's actual implementation would determine how "no payment method" is identified.
        ]);

        // Reservation 2: Should NOT be cancelled - confirmed status
        $confirmedReservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::CONFIRMED->value,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Reservation 3: Should NOT be cancelled - pending but recent
        $recentPendingReservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::PENDING->value,
            'created_at' => Carbon::now()->subHours(1), // Too recent for a typical cleanup job
        ]);

        // Reservation 4: Should NOT be cancelled - already cancelled
        $alreadyCancelledReservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::CANCELLED->value,
            'created_at' => Carbon::now()->subDays(2),
        ]);


        // --- Dispatch the job ---
        CancelNoPaymentMethodReservationsJob::dispatch();

        // --- Assertions ---
        $this->assertDatabaseHas('reservations', [
            'id' => $oldPendingReservation->id,
            'status' => ReservationStatusType::CANCELLED->value,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $confirmedReservation->id,
            'status' => ReservationStatusType::CONFIRMED->value,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $recentPendingReservation->id,
            'status' => ReservationStatusType::PENDING->value,
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $alreadyCancelledReservation->id,
            'status' => ReservationStatusType::CANCELLED->value, // Should remain cancelled
        ]);
    }

    /** @test */
    public function it_does_not_cancel_reservations_if_job_logic_is_very_specific_on_time_and_none_match()
    {
        // This test assumes the job has a very specific time window, e.g., "older than X hours but not older than Y hours"
        // or "created exactly at 7 PM yesterday". For simplicity, we'll test if no reservations match a basic "older than" criteria.

        $hotel = Hotel::factory()->create();
        $user = User::factory()->create();

        // All reservations are recent
        Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::PENDING->value,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);
         Reservation::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'status' => ReservationStatusType::PENDING->value,
            'created_at' => Carbon::now()->subHours(2),
        ]);

        CancelNoPaymentMethodReservationsJob::dispatch();

        // Assert that all reservations remain pending (or whatever their initial status was if not PENDING)
        $this->assertDatabaseMissing('reservations', [
            'status' => ReservationStatusType::CANCELLED->value,
        ]);
        $this->assertEquals(2, Reservation::where('status', ReservationStatusType::PENDING->value)->count());
    }
}
