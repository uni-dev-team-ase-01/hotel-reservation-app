<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\RoomRate; // Ensure RoomRate is imported
use App\Enum\UserRoleType;
use App\Enum\ReservationStatusType; // For checking status
use App\Enum\PaymentStatusEnum;     // For checking bill payment status
use Illuminate\Support\Facades\Session;
// use Stripe\PaymentIntent; // Mocking Stripe is complex, keeping basic test for now
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Hotel $hotel;
    private Room $room1;
    private Room $room2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => UserRoleType::CUSTOMER->value]);
        $this->hotel = Hotel::factory()->create();

        // Ensure rooms are distinct enough for tests
        $this->room1 = Room::factory()->create(['hotel_id' => $this->hotel->id, 'room_type' => 'Single Room', 'occupancy' => 1]);
        $this->room2 = Room::factory()->create(['hotel_id' => $this->hotel->id, 'room_type' => 'Double Room', 'occupancy' => 2]);

        RoomRate::factory()->create(['room_id' => $this->room1->id, 'rate_type' => 'daily', 'amount' => 100.00]);
        RoomRate::factory()->create(['room_id' => $this->room2->id, 'rate_type' => 'daily', 'amount' => 150.00]);
    }

    /** @test */
    public function start_booking_requires_authentication_and_redirects_to_payment_if_auth()
    {
        $bookingParams = [
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(), // 2 nights
            'adults' => 1, // For room1
            'children' => 0,
        ];
        // Test with a single room first for simplicity in price assertion
        $roomIds = $this->room1->id;
        $url = "/hotel/{$this->hotel->id}/rooms/{$roomIds}/book?" . http_build_query($bookingParams);

        // Test unauthenticated
        $responseUnauth = $this->get($url);
        $responseUnauth->assertRedirect(route('login'));

        // Test authenticated
        $responseAuth = $this->actingAs($this->user)->get($url);
        $responseAuth->assertRedirect(route('reservation.paymentForm'));
        $responseAuth->assertSessionHas('pending_booking');

        $pendingBooking = Session::get('pending_booking');
        $this->assertEquals($this->hotel->id, $pendingBooking['hotel_id']);
        $this->assertEquals([$this->room1->id], $pendingBooking['room_ids']); // Check as array
        $this->assertEquals(100.00 * 2, $pendingBooking['total_price']); // 100 (room1) * 2 nights
        $this->assertEquals(2, $pendingBooking['nights']);
    }

    /** @test */
    public function start_booking_validates_input()
    {
        // Missing check_in, check_out, adults
        $invalidBookingParams = [ 'children' => 0 ];
        $roomIds = $this->room1->id;
        $url = "/hotel/{$this->hotel->id}/rooms/{$roomIds}/book?" . http_build_query($invalidBookingParams);

        $response = $this->actingAs($this->user)->get($url);
        // The controller redirects back with errors to the previous page (likely hotel room page or home)
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['check_in', 'check_out', 'adults']);
    }

    /** @test */
    public function payment_form_displays_correctly_with_session_data()
    {
        $bookingData = [
            'hotel_id' => $this->hotel->id,
            'room_ids' => [$this->room1->id],
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(), // 1 night
            'adults' => 1,
            'children' => 0,
            'number_of_guests' => 1,
            'total_price' => 100.00,
            'nights' => 1,
            'selected_rooms_details' => [
                ['id' => $this->room1->id, 'room_type' => $this->room1->room_type, 'price_per_night' => 100.00]
            ]
        ];
        Session::put('pending_booking', $bookingData);

        $response = $this->actingAs($this->user)->get(route('reservation.paymentForm'));

        $response->assertStatus(200);
        $response->assertViewIs('reservation.payment');
        $response->assertViewHas('bookingData', $bookingData); // Controller passes 'bookingData'
        $response->assertViewHas('hotel');
        $response->assertViewHas('selectedRoomsDetails', $bookingData['selected_rooms_details']);
        $response->assertViewHas('totalAmount', 100.00);
        $response->assertViewHas('stripeKey');
    }

    /** @test */
    public function payment_form_redirects_if_no_session_data()
    {
        $response = $this->actingAs($this->user)->get(route('reservation.paymentForm'));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'No booking data found or session expired.');
    }

    /** @test */
    public function process_payment_creates_reservation_bill_and_payment_records()
    {
        $bookingData = [
            'hotel_id' => $this->hotel->id,
            'room_ids' => [$this->room1->id],
            'check_in' => now()->addDays(1)->toDateString(),
            'check_out' => now()->addDays(2)->toDateString(),
            'adults' => 1,
            'children' => 0,
            'number_of_guests' => 1,
            'total_price' => 100.00,
            'nights' => 1,
             'selected_rooms_details' => [
                ['id' => $this->room1->id, 'room_type' => $this->room1->room_type, 'price_per_night' => 100.00]
            ]
        ];
        Session::put('pending_booking', $bookingData);

        $paymentFormData = [
            'guest_name' => $this->user->name,
            'guest_email' => $this->user->email,
            'guest_phone' => '1234567890',
            'payment_method_id' => 'pm_card_visa' // A test payment method ID from Stripe
        ];

        $response = $this->actingAs($this->user)
                         ->post(route('reservation.processPayment'), $paymentFormData);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
            'status' => ReservationStatusType::CONFIRMED->value, // Should be confirmed after payment
        ]);

        $reservation = Reservation::first();
        $this->assertNotNull($reservation);

        $this->assertDatabaseHas('bills', [
            'reservation_id' => $reservation->id,
            'total_amount' => 100.00,
            'payment_status' => PaymentStatusEnum::PAID->value,
        ]);
        $this->assertDatabaseHas('payments', [
            'bill_id' => Bill::where('reservation_id', $reservation->id)->first()->id,
            'amount' => 100.00,
            'method' => 'card', // From controller default
            'status' => PaymentStatusEnum::COMPLETED->value, // Or PaymentStatusEnum::SUCCESS
        ]);

        $response->assertRedirect(route('reservation.success', ['reservation' => $reservation->id]));
        $response->assertSessionMissing('pending_booking');
    }

    /** @test */
    public function process_payment_validates_guest_details()
    {
        $bookingData = [ /* Contents as in previous test */ ];
        Session::put('pending_booking', $bookingData); // Ensure session has data

        $invalidPaymentFormData = ['guest_name' => '', 'guest_email' => 'not-an-email', 'guest_phone' => ''];

        $response = $this->actingAs($this->user)
                         ->post(route('reservation.processPayment'), $invalidPaymentFormData);

        $response->assertStatus(302); // Redirect back on validation failure
        $response->assertSessionHasErrors(['guest_name', 'guest_email', 'guest_phone']);
    }

    /** @test */
    public function reservation_success_page_is_accessible_and_shows_reservation()
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'hotel_id' => $this->hotel->id,
            'status' => ReservationStatusType::CONFIRMED->value
        ]);
        $reservation->rooms()->attach($this->room1->id);

        $response = $this->actingAs($this->user)->get(route('reservation.success', ['reservation' => $reservation->id]));

        $response->assertStatus(200);
        $response->assertViewIs('reservation.success');
        $response->assertViewHas('reservationData'); // Controller passes 'reservationData'
        $this->assertEquals($reservation->id, $response->viewData('reservationData')->id);
    }

    /** @test */
    public function create_stripe_intent_returns_client_secret_for_valid_amount()
    {
        $response = $this->actingAs($this->user)
                         ->postJson(route('stripe.intent'), ['amount' => 1000, 'currency' => 'usd']); // 10.00 USD

        $response->assertStatus(200);
        $response->assertJsonStructure(['clientSecret']);
        $this->assertNotNull($response->json('clientSecret'));
    }

    /** @test */
    public function create_stripe_intent_fails_for_invalid_amount()
    {
        $response = $this->actingAs($this->user)
                         ->postJson(route('stripe.intent'), ['amount' => -100]); // Invalid amount

        $response->assertStatus(400); // Or 422 if validation is used
        $response->assertJsonStructure(['error']);
    }
}
