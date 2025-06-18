<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Reservation;
use App\Enum\PaymentStatusEnum; // Assuming an Enum for Payment Statuses
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str; // For uuid or other identifiers

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paymentStatuses = ['Pending', 'Paid', 'Failed', 'Refunded', 'Partially Paid'];

        $roomCharges = $this->faker->randomFloat(2, 50, 1500);
        $extraCharges = $this->faker->optional(0.7)->randomFloat(2, 10, 300); // e.g., mini-bar, spa
        $discount = $this->faker->optional(0.3)->randomFloat(2, 5, $roomCharges * 0.1); // Discount up to 10% of room charges
        $taxes = $this->faker->randomFloat(2, $roomCharges * 0.05, $roomCharges * 0.20); // Taxes between 5% and 20% of room charges

        $subTotal = $roomCharges + ($extraCharges ?? 0);
        $totalAmount = $subTotal - ($discount ?? 0) + $taxes;

        return [
            'reservation_id' => Reservation::factory(),
            'bill_number' => 'BILL-' . strtoupper(Str::random(8)), // Example bill number
            'issued_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d'),
            'room_charges' => $roomCharges,
            'service_charges' => $this->faker->optional(0.5)->randomFloat(2, 20, 200), // e.g. room service specific charges
            'extra_charges' => $extraCharges,
            'discount_amount' => $discount,
            'tax_amount' => $taxes,
            'total_amount' => round($totalAmount, 2), // Ensure it's rounded to 2 decimal places
            'payment_status' => class_exists(PaymentStatusEnum::class) ? Arr::random(array_column(PaymentStatusEnum::cases(), 'value')) : Arr::random($paymentStatuses),
            'payment_method' => $this->faker->optional()->randomElement(['credit_card', 'bank_transfer', 'cash', 'paypal']),
            'payment_date' => function (array $attributes) {
                // Payment date only if status is 'Paid' or 'Partially Paid'
                $paidStatuses = [PaymentStatusEnum::PAID->value ?? 'Paid', PaymentStatusEnum::PARTIALLY_PAID->value ?? 'Partially Paid'];
                return in_array($attributes['payment_status'], $paidStatuses) ? $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s') : null;
            },
            'transaction_id' => $this->faker->optional()->bothify('txn_**************'), // Example transaction ID
            'notes' => $this->faker->optional(0.4)->paragraph(1),
        ];
    }

    /**
     * Indicate that the bill is fully paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::PAID') ? PaymentStatusEnum::PAID->value : 'Paid',
                'payment_date' => now()->format('Y-m-d H:i:s'),
                'transaction_id' => 'txn_test_' . Str::random(10),
            ];
        });
    }

    /**
     * Indicate that the bill payment is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_status' => class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::PENDING') ? PaymentStatusEnum::PENDING->value : 'Pending',
                'payment_date' => null,
                'transaction_id' => null,
            ];
        });
    }
}
