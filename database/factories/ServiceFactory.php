<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $exampleServices = [
            'Wi-Fi Access', 'Daily Housekeeping', 'Room Service', 'Airport Shuttle',
            'Breakfast Buffet', 'Laundry Service', 'Concierge', 'Gym Access',
            'Swimming Pool Access', 'Spa Services', 'Parking', 'Business Center Access',
            'Pet Friendly Accommodations', 'Childcare Services', 'Bike Rentals'
        ];

        $serviceName = Arr::random($exampleServices) . ' ' . $this->faker->unique()->word; // Add a unique word to ensure variety

        return [
            'name' => $serviceName,
            'description' => $this->faker->optional()->sentence(10),
            'price' => $this->faker->optional(0.7, 0)->randomFloat(2, 5, 100), // 70% chance of having a price, otherwise free (0 or null)
            'type' => Arr::random(['general', 'room_specific', 'hotel_wide', 'add_on']), // Example types of services
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'duration_minutes' => $this->faker->optional(0.3)->randomElement([30, 60, 90, 120, 180, 240]), // Optional duration for timed services
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'services', true),
        ];
    }

    /**
     * Indicate that the service is free.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function free()
    {
        return $this->state(function (array $attributes) {
            return [
                'price' => 0.00,
            ];
        });
    }

    /**
     * Indicate that the service is inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
