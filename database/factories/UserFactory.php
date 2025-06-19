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
            // 'role' => UserRoleType::CUSTOMER->value, // Default role -- Removed: Roles are assigned via spatie/laravel-permission
            'phone' => fake()->unique()->phoneNumber(), // Changed from phone_number to phone
            // 'address' => fake()->address(), // Temporarily removed to check if it's causing DB errors
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

    // Removed role-specific state methods as roles are handled by spatie/laravel-permission
    // and assigned in tests/seeders directly e.g. $user->assignRole('customer');
}
