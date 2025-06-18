<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\TravelCompany;
use App\Enum\UserRoleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_have_many_reservations()
    {
        // Forward declaration for factories to be created in subsequent steps
        if (!class_exists(\Database\Factories\HotelFactory::class)) {
            // If HotelFactory doesn't exist, we might need a way to create a hotel
            // For now, assume Hotel::factory() will work if HotelFactory is created by worker.
        }

        $user = User::factory()->create();
        // Assuming ReservationFactory will be created by the worker as part of this task.
        Reservation::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Reservation::class, $user->reservations->first());
        $this->assertCount(3, $user->reservations);
    }

    /** @test */
    public function a_user_can_be_associated_with_many_hotels_via_user_hotels()
    {
        $user = User::factory()->create();
        $hotel1 = Hotel::factory()->create(); // HotelFactory needs to exist
        $hotel2 = Hotel::factory()->create();

        $user->hotels()->attach([$hotel1->id, $hotel2->id]);

        $this->assertInstanceOf(Hotel::class, $user->hotels->first());
        $this->assertCount(2, $user->hotels);
    }

    /** @test */
    public function a_user_can_have_one_travel_company()
    {
        $user = User::factory()->create();
        // TravelCompanyFactory needs to exist
        $travelCompany = TravelCompany::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(TravelCompany::class, $user->travelCompany);
        $this->assertEquals($travelCompany->id, $user->travelCompany->id);
    }

    /** @test */
    public function user_password_is_hashed()
    {
        $user = User::factory()->create(['password' => 'password123']);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotEquals('password123', $user->password);
    }

    /** @test */
    public function user_has_fillable_attributes()
    {
        $fillable = [
            'name', 'email', 'password', 'role', 'phone_number',
            'address', 'stripe_customer_id', 'has_stripe_payment_method'
        ];
        $user = new User();
        $this->assertEquals($fillable, $user->getFillable());
    }

    /** @test */
    public function user_casts_attributes()
    {
        $user = User::factory()->create(['has_stripe_payment_method' => true]); // Relies on UserFactory setting this correctly
        $this->assertIsBool($user->has_stripe_payment_method);

        $expectedCasts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_stripe_payment_method' => 'boolean',
        ];
        $userModel = new User();
        // Compare only the keys present in $expectedCasts to avoid issues with other default casts (like id)
        $actualCasts = array_intersect_key($userModel->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);
    }

    /** @test */
    public function user_has_role_attribute_and_can_be_assigned_a_role()
    {
        $customerUser = User::factory()->create(['role' => UserRoleType::CUSTOMER->value]);
        $this->assertEquals(UserRoleType::CUSTOMER->value, $customerUser->role);

        $adminUser = User::factory()->create(['role' => UserRoleType::SYSTEM_ADMINISTRATOR->value]);
        $this->assertEquals(UserRoleType::SYSTEM_ADMINISTRATOR->value, $adminUser->role);
    }
}
