<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class PendingBookingAuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('customer', 'web');
        Role::findOrCreate('admin', 'web');
    }

    public function test_user_with_pending_booking_is_redirected_to_payment_on_login()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        session()->put('pending_booking', ['room_id' => 1, 'days' => 2]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password', // Default password for factory users
        ]);

        $response->assertRedirect(route('reservation.paymentForm'));
        $this->assertTrue(session()->has('pending_booking'));
    }

    public function test_customer_without_pending_booking_is_redirected_to_hotels_listing_on_login()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        session()->forget('pending_booking');

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('filament.customer.pages.dashboard'));
    }

    public function test_non_customer_is_redirected_to_login_on_attempted_login()
    {
        $user = User::factory()->create();
        $user->assignRole('admin'); // Non-customer role

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_with_pending_booking_is_redirected_to_payment_on_register()
    {
        session()->put('pending_booking', ['room_id' => 1, 'days' => 2]);

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('reservation.paymentForm'));
        $this->assertTrue(session()->has('pending_booking'));
        $this->assertAuthenticated();
    }

    public function test_user_without_pending_booking_is_redirected_to_hotels_listing_on_register()
    {
        session()->forget('pending_booking');

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('filament.customer.pages.dashboard'));
        $this->assertAuthenticated();
    }
}
