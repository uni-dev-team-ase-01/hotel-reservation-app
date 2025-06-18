<?php

namespace Tests\Unit\Models;

use App\Models\Bill;
use App\Models\Payment;
use App\Enum\PaymentStatusEnum; // Assuming this Enum might exist
use App\Enum\PaymentMethodEnum; // Assuming this Enum might exist
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_payment_belongs_to_a_bill()
    {
        // BillFactory should exist from the previous subtask
        $bill = Bill::factory()->create();
        $payment = Payment::factory()->create(['bill_id' => $bill->id]);

        $this->assertInstanceOf(Bill::class, $payment->bill);
        $this->assertEquals($bill->id, $payment->bill->id);
    }

    /** @test */
    public function payment_has_fillable_attributes()
    {
        // Extended based on the created PaymentFactory
        $fillable = [
            'bill_id', 'payment_gateway', 'method', 'amount', 'currency',
            'transaction_id', 'status', 'paid_at', 'gateway_response',
            'notes', 'created_by_user_id', 'updated_by_user_id'
        ];
        $payment = new Payment();
        $this->assertEqualsCanonicalizing($fillable, $payment->getFillable());
    }

    /** @test */
    public function payment_casts_attributes()
    {
        $payment = Payment::factory()->make(); // Using make for cast checking

        $expectedCasts = [
            'paid_at' => 'datetime',
            'amount' => 'float', // Or 'decimal:2' based on model
            // 'id' => 'int' // Default
        ];
        // If PaymentStatusEnum or PaymentMethodEnum are used with Enum casting in the model:
        // if (class_exists(PaymentStatusEnum::class)) {
        //     $expectedCasts['status'] = PaymentStatusEnum::class;
        // }
        // if (class_exists(PaymentMethodEnum::class)) {
        //     $expectedCasts['method'] = PaymentMethodEnum::class;
        // }

        $actualCasts = array_intersect_key($payment->getCasts(), $expectedCasts);
        $this->assertEquals($expectedCasts, $actualCasts);

        // Test actual casting behavior for 'paid_at'
        $paidAtString = '2024-07-15 10:00:00';
        $paymentWithDate = Payment::factory()->create(['paid_at' => $paidAtString]);
        $this->assertInstanceOf(Carbon::class, $paymentWithDate->paid_at);
        $this->assertEquals(Carbon::parse($paidAtString)->toDateTimeString(), $paymentWithDate->paid_at->toDateTimeString());

        // Test 'amount' casting
        $paymentWithAmount = Payment::factory()->create(['amount' => 123.45]);
        $this->assertIsFloat($paymentWithAmount->amount); // Or assertIsNumeric if decimal
        $this->assertEquals(123.45, $paymentWithAmount->amount);
    }

    /** @test */
    public function payment_status_can_use_enum_if_defined_in_model()
    {
        if (!class_exists(PaymentStatusEnum::class)) {
            $this->markTestSkipped('PaymentStatusEnum not found, skipping enum test.');
            return;
        }

        // This test assumes the Payment model has a cast for 'status' to PaymentStatusEnum::class
        $payment = Payment::factory()->create(['status' => PaymentStatusEnum::COMPLETED]);

        // Retrieve the model again to ensure casts are applied from database value
        $retrievedPayment = Payment::find($payment->id);

        if ($retrievedPayment->getCasts()['status'] === PaymentStatusEnum::class) {
            $this->assertInstanceOf(PaymentStatusEnum::class, $retrievedPayment->status);
            $this->assertEquals(PaymentStatusEnum::COMPLETED, $retrievedPayment->status);
        } else {
            $this->assertEquals(PaymentStatusEnum::COMPLETED->value, $retrievedPayment->status, "Status stored as value, not enum instance. Check model cast.");
        }
    }

    /** @test */
    public function payment_method_can_use_enum_if_defined_in_model()
    {
        if (!class_exists(PaymentMethodEnum::class)) {
            $this->markTestSkipped('PaymentMethodEnum not found, skipping enum test.');
            return;
        }

        // This test assumes the Payment model has a cast for 'method' to PaymentMethodEnum::class
        $payment = Payment::factory()->create(['method' => PaymentMethodEnum::CREDIT_CARD]);
        $retrievedPayment = Payment::find($payment->id);

        if ($retrievedPayment->getCasts()['method'] === PaymentMethodEnum::class) {
            $this->assertInstanceOf(PaymentMethodEnum::class, $retrievedPayment->method);
            $this->assertEquals(PaymentMethodEnum::CREDIT_CARD, $retrievedPayment->method);
        } else {
             $this->assertEquals(PaymentMethodEnum::CREDIT_CARD->value, $retrievedPayment->method, "Method stored as value, not enum instance. Check model cast.");
        }
    }
}
