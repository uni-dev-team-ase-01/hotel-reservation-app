<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class HandleStripePaymentMethodWebhook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $stripeCustomerId = $event->data->object->customer ?? $event->data->previous_attributes->customer ?? null;

        if (! $stripeCustomerId) {
            Log::warning('Stripe customer ID not found in event data');

            return;
        }

        $user = User::where('stripe_customer_id', $stripeCustomerId)->first();

        if (! $user) {
            Log::warning('User not found for Stripe customer ID: '.$stripeCustomerId);

            return;
        }

        Log::info('Handling Stripe payment method event for user: '.$user->id.', event type: '.$event->type.', customer ID: '.$stripeCustomerId);

        if ($event->type === 'payment_method.attached') {
            Log::info('Payment method attached for user: '.$user->id);
            $user->update(['has_stripe_payment_method' => 1]);
        } elseif ($event->type === 'payment_method.detached') {
            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $stripeCustomerId,
                'type' => 'card',
            ]);

            Log::info('Payment method detached for user: '.$user->id.', remaining payment methods: '.count($paymentMethods->data));

            $hasPaymentMethod = $paymentMethods->data ? count($paymentMethods->data) > 0 : 0;
            $user->update(['has_stripe_payment_method' => $hasPaymentMethod ? 1 : 0]);
        }
    }
}
