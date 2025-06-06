<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Reservation;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct(StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    public function process($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $bill = Bill::where('reservation_id', $reservation->id)->firstOrFail();

        $user = $reservation->user;
        if (! $user || ! $user->stripe_payment_method_id) {
            return redirect()->back()->with('error', 'No payment method found for this user.');
        }

        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $bill->total_amount * 100,
                'currency' => 'inr',
                'payment_method' => $user->stripe_payment_method_id,
                'confirm' => true,
                'off_session' => true,
                'metadata' => ['bill_id' => $bill->id, 'reservation_id' => $reservation->id],
            ]);

            $bill->update(['payment_status' => 'paid']);
            Payment::create([
                'bill_id' => $bill->id,
                'method' => 'credit_card',
                'amount' => $bill->total_amount,
                'paid_at' => now(),
            ]);

            $reservation->update(['status' => 'checked_out']);

            return redirect()->route('checkout.success')->with('message', 'Check-out and payment successful!');
        } catch (\Stripe\Exception\CardException $e) {
            return redirect()->back()->with('error', 'Payment failed: '.$e->getMessage());
        }
    }
}
