<?php

namespace Database\Factories;

use App\Models\TravelCompany;
use App\Models\User;
use App\Enum\UserRoleType; // Ensure UserRoleType is imported for clarity if used directly in state
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // For any string manipulations if needed

class TravelCompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TravelCompany::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isApproved = $this->faker->boolean(80); // 80% chance of being approved

        return [
            'user_id' => User::factory()->state(['role' => UserRoleType::TRAVEL_AGENT->value]), // Explicitly set role
            'name' => $this->faker->company . ' Voyages', // Slight variation in naming
            'contact_person_name' => $this->faker->name(), // Changed from contact_person to match test expectation
            'email' => $this->faker->unique()->companyEmail(), // Changed from email_address to match test expectation
            'phone_number' => $this->faker->unique()->phoneNumber(),
            'address' => $this->faker->address(),
            'website_url' => $this->faker->optional()->url,
            'description' => $this->faker->optional()->paragraph(2),
            'registration_number' => $this->faker->unique()->bothify('TC-REG-#######??'),
            'license_number' => $this->faker->optional(0.7)->bothify('TCLIC-#####??'), // 70% chance of having license
            'is_approved' => $isApproved,
            'approved_at' => $isApproved ? now() : null,
            'logo_path' => $this->faker->optional(0.6)->imageUrl(250, 250, 'travel', true, 'logo'), // 60% chance for a logo
            'commission_rate' => $this->faker->randomFloat(2, 5, 15), // Added commission_rate as it's common
            'discount_rate' => $this->faker->randomFloat(2, 0, 25), // Added discount_rate
            'services_offered' => json_encode($this->faker->randomElements(['Flights', 'Hotels', 'Tours', 'Car Rentals', 'Cruises', 'Travel Insurance'], $this->faker->numberBetween(2,5))), // Added
            'operational_hours' => 'Mon-Fri, 9 AM - 6 PM', // Added
            'slug' => function (array $attributes) { // Added slug
                return Str::slug($attributes['name']);
            }
        ];
    }

    /**
     * Indicate that the travel company is approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => true,
                'approved_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the travel company is not yet approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notApproved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => false,
                'approved_at' => null,
            ];
        });
    }
}
