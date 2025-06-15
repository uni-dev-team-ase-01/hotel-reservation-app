<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Get saved card payment methods for a Stripe customer.
     *
     * @param string $stripeCustomerId
     * @return array
     */
    public function getSavedPaymentMethods(string $stripeCustomerId): array
    {
        $savedPaymentMethods = [];
        if (empty($stripeCustomerId)) {
            return $savedPaymentMethods;
        }

        try {
            $customerPaymentMethods = PaymentMethod::all([
                'customer' => $stripeCustomerId,
                'type' => 'card',
            ]);

            foreach ($customerPaymentMethods->data as $pm) {
                if ($pm->card) { // Ensure card details exist
                    $savedPaymentMethods[] = [
                        'id' => $pm->id,
                        'brand' => $pm->card->brand,
                        'last4' => $pm->card->last4,
                        'exp_month' => $pm->card->exp_month,
                        'exp_year' => $pm->card->exp_year,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching Stripe payment methods for customer ' . $stripeCustomerId . ': ' . $e->getMessage());
            // Return empty array or throw a custom exception if preferred
        }
        return $savedPaymentMethods;
    }
}
