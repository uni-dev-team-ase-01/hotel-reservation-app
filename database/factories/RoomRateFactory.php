<?php

namespace Database\Factories;

use App\Models\RoomRate;
use App\Models\Room;
use App\Enum\RateTypeEnum; // Assuming an Enum for Rate Types if you have one
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class RoomRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rateTypes = ['daily', 'weekly', 'monthly', 'weekend', 'weekday', 'seasonal', 'promotional'];

        $startDate = $this->faker->optional(0.5)->dateTimeBetween('-1 month', '+1 month'); // 50% chance of having a start date
        $endDate = null;
        if ($startDate) {
            $endDate = $this->faker->optional(0.8)->dateTimeBetween(
                (clone $startDate)->modify('+1 day'),
                (clone $startDate)->modify('+3 months')
            ); // 80% chance of having an end date if start date exists
        }

        return [
            'room_id' => Room::factory(),
            'rate_type' => class_exists(RateTypeEnum::class) ? Arr::random(array_column(RateTypeEnum::cases(), 'value')) : Arr::random($rateTypes),
            'amount' => $this->faker->randomFloat(2, 40, 600), // Price for this rate type
            'description' => $this->faker->optional()->sentence,
            'start_date' => $startDate ? $startDate->format('Y-m-d') : null,
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'min_nights' => $this->faker->optional(0.2)->numberBetween(1, 3), // 20% chance of min_nights
            'max_nights' => $this->faker->optional(0.1)->numberBetween(7, 30), // 10% chance of max_nights
            'conditions' => $this->faker->optional(0.3)->paragraph(1), // 30% chance of having conditions
            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }

    /**
     * Indicate a daily rate.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function daily()
    {
        return $this->state(function (array $attributes) {
            return [
                'rate_type' => class_exists(RateTypeEnum::class) && defined(RateTypeEnum::class . '::DAILY') ? RateTypeEnum::DAILY->value : 'daily',
                'start_date' => null, // Daily rates usually don't have specific start/end dates unless they are promotional daily rates
                'end_date' => null,
            ];
        });
    }

    /**
     * Indicate a seasonal rate with specific dates.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function seasonal()
    {
        $startDate = $this->faker->dateTimeBetween('+1 month', '+3 months');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 2) . ' months');

        return $this->state(function (array $attributes) use ($startDate, $endDate) {
            return [
                'rate_type' => class_exists(RateTypeEnum::class) && defined(RateTypeEnum::class . '::SEASONAL') ? RateTypeEnum::SEASONAL->value : 'seasonal',
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'amount' => $this->faker->randomFloat(2, 100, 800), // Seasonal rates might be higher
            ];
        });
    }
}
