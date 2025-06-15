<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\User; // Added for explicit User factory call
use App\Enum\HotelType; // Assuming this Enum exists for hotel types
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr; // Added for Arr::random

class HotelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hotel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Example hotel types, if Enum is not available or for variety
        $hotelTypes = ['Hotel', 'Resort', 'Apartment', 'Villa', 'Boutique Hotel', 'Hostel'];

        return [
            'name' => $this->faker->company . ' ' . Arr::random(['Hotel', 'Resort', 'Inn', 'Suites']),
            'description' => $this->faker->paragraphs(3, true), // More extensive description
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'star_rating' => $this->faker->numberBetween(1, 5),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'email_address' => $this->faker->unique()->companyEmail,
            'website_url' => $this->faker->optional()->url,
            'check_in_time' => Arr::random(['14:00', '15:00', '16:00']),
            'check_out_time' => Arr::random(['10:00', '11:00', '12:00']),
            'amenities' => json_encode($this->faker->randomElements(
                ['Wi-Fi', 'Pool', 'Gym', 'Parking', 'Restaurant', 'Spa', 'Room Service', 'Pet Friendly', 'Airport Shuttle'],
                $this->faker->numberBetween(3, 7)
            )),
            'policies' => $this->faker->text(300),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'active' => $this->faker->boolean(95), // 95% chance of being active
            'user_id' => User::factory()->hotelManager(), // Assign a hotel manager by default
            'images' => json_encode([ // Example of a more diverse set of placeholder images
                'placeholders/hotel_image_1.jpg',
                'placeholders/hotel_image_2.jpg',
                'placeholders/hotel_image_3.jpg',
                'placeholders/hotel_lobby.jpg',
                'placeholders/hotel_room_view.jpg',
            ]),
            'price_per_night' => $this->faker->randomFloat(2, 50, 750), // Adjusted price range
            'currency_code' => 'USD',
            'availability_status' => Arr::random(['available', 'limited_availability', 'sold_out']),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name'] . '-' . Str::random(4)); // Add random suffix for uniqueness
            },
            'owner_name' => $this->faker->name,
            'contact_person_name' => $this->faker->name,
            'contact_person_email' => $this->faker->safeEmail,
            'contact_person_phone' => $this->faker->phoneNumber,
            'additional_information' => $this->faker->optional()->text(150),
            'approved_at' => now(),
            // 'type' field using a fallback if Enum is not present
            'type' => class_exists(HotelType::class) ? Arr::random(array_column(HotelType::cases(), 'value')) : Arr::random($hotelTypes),
            'view_count' => $this->faker->numberBetween(0, 10000),
            'booking_count' => $this->faker->numberBetween(0, 5000),
            'min_stay_duration' => $this->faker->optional(0.3, 1)->numberBetween(1,3), // 30% chance for min stay, 1-3 days
            'max_stay_duration' => $this->faker->optional(0.2, 30)->numberBetween(7,30), // 20% chance for max stay
            'cancellation_policy_days' => $this->faker->optional(0.7, 0)->numberBetween(0, 7), // 70% chance for cancellation policy days
        ];
    }

    /**
     * Indicate that the hotel is inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }

    /**
     * Indicate that the hotel has a specific star rating.
     *
     * @param int $stars
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withStars(int $stars)
    {
        return $this->state(function (array $attributes) use ($stars) {
            return [
                'star_rating' => max(1, min(5, $stars)), // Ensure stars are between 1 and 5
            ];
        });
    }
}
