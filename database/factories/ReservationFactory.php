<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Hotel;
use App\Enum\ReservationStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str; // Added for Str::uuid typically, or for confirmation_number logic

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $checkInDate = $this->faker->dateTimeBetween('+1 day', '+2 weeks');
        // Ensure check_out_date is after check_in_date
        $checkOutDate = $this->faker->dateTimeBetween(
            (clone $checkInDate)->modify('+1 day'),
            (clone $checkInDate)->modify('+15 days')
        );

        $numAdults = $this->faker->numberBetween(1, 4);
        $numChildren = $this->faker->numberBetween(0, 3);

        return [
            'user_id' => User::factory(),
            'hotel_id' => Hotel::factory(),
            'check_in_date' => $checkInDate->format('Y-m-d H:i:s'),
            'check_out_date' => $checkOutDate->format('Y-m-d H:i:s'),
            'num_adults' => $numAdults,
            'num_children' => $numChildren,
            'number_of_guests' => $numAdults + $numChildren, // Derived field
            'notes' => $this->faker->optional(0.6)->paragraph(1), // Renamed from special_requests
            'total_amount' => $this->faker->randomFloat(2, 50, 2000), // Broader range
            'payment_method' => Arr::random(['credit_card', 'paypal', 'bank_transfer', 'on_arrival']),
            'payment_status' => Arr::random(['pending', 'paid', 'failed', 'refunded']),
            'status' => Arr::random(array_column(ReservationStatusType::cases(), 'value')),
            'confirmation_number' => strtoupper(Str::random(4) . '-' . $this->faker->numerify('######')), // New format
            'confirmed_at' => function (array $attributes) { // Confirm only if status is confirmed
                return $attributes['status'] === ReservationStatusType::CONFIRMED->value ? now() : null;
            },
            'cancelled_at' => function (array $attributes) { // Cancel only if status is cancelled
                return $attributes['status'] === ReservationStatusType::CANCELLED->value ? now() : null;
            },
            'cancellation_reason' => function (array $attributes) {
                 return $attributes['status'] === ReservationStatusType::CANCELLED->value ? $this->faker->sentence : null;
            },
            // Removed room_id and num_rooms from main definition for now
        ];
    }

    /**
     * Indicate that the reservation is confirmed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ReservationStatusType::CONFIRMED->value,
                'confirmed_at' => now(),
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ];
        });
    }

    /**
     * Indicate that the reservation is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ReservationStatusType::PENDING->value,
                'confirmed_at' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ];
        });
    }

    /**
     * Indicate that the reservation is cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ReservationStatusType::CANCELLED->value,
                'confirmed_at' => null,
                'cancelled_at' => now(),
                'cancellation_reason' => $this->faker->sentence,
            ];
        });
    }

    /**
     * Indicate that the reservation is for a specific number of guests.
     *
     * @param int $adults
     * @param int $children
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forGuests(int $adults, int $children = 0)
    {
        return $this->state(function (array $attributes) use ($adults, $children) {
            return [
                'num_adults' => $adults,
                'num_children' => $children,
                'number_of_guests' => $adults + $children,
            ];
        });
    }
}
