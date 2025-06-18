<?php

namespace App\Services;

use App\Models\User;
use Filament\Notifications\Notification;
use Log;
use Stripe\StripeClient;
use Exception;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Ensure user has a Stripe customer ID
     */
    public function ensureCustomer(User $user): ?string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        try {
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);

            return $customer->id;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to create Stripe customer', $e->getMessage());
            return null;
        }
    }

    /**
     * Generate Stripe Customer Portal URL
     */
    public function generateCustomerPortalUrl(User $user, string $returnUrl = null): ?string
    {
        $customerId = $this->ensureCustomer($user);

        if (!$customerId) {
            return null;
        }

        try {
            $session = $this->stripe->billingPortal->sessions->create([
                'customer' => $customerId,
                'return_url' => $returnUrl ?? url('/customer/profile'),
            ]);

            return $session->url;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to generate Stripe Customer Portal link', $e->getMessage());
            return null;
        }
    }

    /**
     * Update Stripe customer information
     */
    public function updateCustomer(User $user, array $data = []): bool
    {
        $customerId = $user->stripe_customer_id;

        if (!$customerId) {
            return false;
        }

        // Default to user's current data if not provided
        $updateData = array_merge([
            'email' => $user->email,
            'name' => $user->name,
        ], $data);

        try {
            $this->stripe->customers->update($customerId, $updateData);
            return true;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to update Stripe customer', $e->getMessage());
            return false;
        }
    }

    /**
     * Get Stripe customer details
     */
    public function getCustomer(User $user): ?object
    {
        $customerId = $user->stripe_customer_id;

        if (!$customerId) {
            return null;
        }

        try {
            return $this->stripe->customers->retrieve($customerId);
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to retrieve Stripe customer', $e->getMessage());
            return null;
        }
    }

    /**
     * Create a checkout session
     */
    public function createCheckoutSession(User $user, array $lineItems, string $successUrl, string $cancelUrl): ?string
    {
        $customerId = $this->ensureCustomer($user);

        if (!$customerId) {
            return null;
        }

        try {
            $session = $this->stripe->checkout->sessions->create([
                'customer' => $customerId,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            return $session->url;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to create checkout session', $e->getMessage());
            return null;
        }
    }

    /**
     * Create a subscription checkout session
     */
    public function createSubscriptionCheckoutSession(User $user, string $priceId, string $successUrl, string $cancelUrl): ?string
    {
        $customerId = $this->ensureCustomer($user);

        if (!$customerId) {
            return null;
        }

        try {
            $session = $this->stripe->checkout->sessions->create([
                'customer' => $customerId,
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'subscription',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            return $session->url;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to create subscription checkout session', $e->getMessage());
            return null;
        }
    }

    /**
     * Get customer's active subscriptions
     */
    public function getActiveSubscriptions(User $user): array
    {
        $customerId = $user->stripe_customer_id;

        if (!$customerId) {
            return [];
        }

        try {
            $subscriptions = $this->stripe->subscriptions->all([
                'customer' => $customerId,
                'status' => 'active',
            ]);

            return $subscriptions->data ?? [];
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to retrieve subscriptions', $e->getMessage());
            return [];
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->stripe->subscriptions->cancel($subscriptionId);
            return true;
        } catch (Exception $e) {
            $this->sendErrorNotification('Failed to cancel subscription', $e->getMessage());
            return false;
        }
    }

    /**
     * Send error notification
     */
    protected function sendErrorNotification(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->send();
    }

    /**
     * Get the raw Stripe client for advanced operations
     */
    public function getStripeClient(): StripeClient
    {
        return $this->stripe;
    }
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
        }
        return $savedPaymentMethods;
    }
}
