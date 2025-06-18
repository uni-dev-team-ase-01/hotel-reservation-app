<?php

namespace Database\Factories;

use App\Enum\UserRoleType; // Added
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRoleType::CUSTOMER->value, // Default role
            'phone_number' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'stripe_customer_id' => 'cus_' . Str::random(14), // Example Stripe ID
            'has_stripe_payment_method' => fake()->boolean(75), // 75% chance of true
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a customer.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleType::CUSTOMER->value,
        ]);
    }

    /**
     * Indicate that the user is a hotel manager.
     */
    public function hotelManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleType::HOTEL_MANAGER->value,
        ]);
    }

    /**
     * Indicate that the user is hotel staff.
     */
    public function hotelStaff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleType::HOTEL_STAFF->value,
        ]);
    }

    /**
     * Indicate that the user is a travel agent.
     */
    public function travelAgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleType::TRAVEL_AGENT->value,
        ]);
    }

    /**
     * Indicate that the user is a system administrator.
     */
    public function systemAdministrator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRoleType::SYSTEM_ADMINISTRATOR->value,
        ]);
    }
}
