<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Bill;
use App\Enum\PaymentMethodEnum; // Assuming this Enum exists
use App\Enum\PaymentStatusEnum; // Assuming this Enum exists
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paymentMethods = ['credit_card', 'debit_card', 'bank_transfer', 'paypal', 'cash', 'stripe', 'crypto'];
        $paymentStatuses = ['pending', 'completed', 'failed', 'refunded', 'cancelled', 'requires_action'];

        $amount = $this->faker->randomFloat(2, 10, 2000);
        $status = class_exists(PaymentStatusEnum::class) ? Arr::random(array_column(PaymentStatusEnum::cases(), 'value')) : Arr::random($paymentStatuses);

        return [
            'bill_id' => Bill::factory(),
            'payment_gateway' => $this->faker->optional(0.8)->randomElement(['stripe', 'paypal', 'braintree', 'custom_gateway']), // Added field
            'method' => class_exists(PaymentMethodEnum::class) ? Arr::random(array_column(PaymentMethodEnum::cases(), 'value')) : Arr::random($paymentMethods),
            'amount' => $amount,
            'currency' => 'USD', // Added field
            'transaction_id' => $this->faker->optional(0.9)->bothify('txn_##??##??##??##??'), // 90% chance of having a transaction ID
            'status' => $status,
            'paid_at' => ($status === (PaymentStatusEnum::COMPLETED->value ?? 'completed') || $status === (PaymentStatusEnum::SUCCESS->value ?? 'success')) // Assuming 'success' is also a completed state
                            ? $this->faker->dateTimeThisMonth()
                            : null,
            'gateway_response' => $this->faker->optional()->text(100), // Added field for gateway messages
            'notes' => $this->faker->optional(0.3)->paragraph(1),
            'created_by_user_id' => null, // Optional: User who initiated/recorded payment
            'updated_by_user_id' => null, // Optional: User who last updated payment
        ];
    }

    /**
     * Indicate a completed payment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::COMPLETED')
                                ? PaymentStatusEnum::COMPLETED->value
                                : (class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::SUCCESS')
                                    ? PaymentStatusEnum::SUCCESS->value
                                    : 'completed'),
                'paid_at' => now(),
                'transaction_id' => 'txn_completed_' . Str::random(10),
            ];
        });
    }

    /**
     * Indicate a failed payment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::FAILED') ? PaymentStatusEnum::FAILED->value : 'failed',
                'paid_at' => null,
                'gateway_response' => 'Payment failed due to insufficient funds.',
            ];
        });
    }

    /**
     * Indicate a pending payment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => class_exists(PaymentStatusEnum::class) && defined(PaymentStatusEnum::class . '::PENDING') ? PaymentStatusEnum::PENDING->value : 'pending',
                'paid_at' => null,
            ];
        });
    }
}
