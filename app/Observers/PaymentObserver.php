<?php

namespace App\Observers;

use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use App\Models\Payment;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class PaymentObserver
{
    public function creating(Payment $payment)
    {
        $payment->status = self::determineStatus($payment);
        $payment->paid_at = self::determinePaidAt($payment);
    }

    public function created(Payment $payment)
    {
        if ($payment->method == PaymentMethod::CREDIT_CARD->value) {
            $this->processStripePayment($payment);
        } elseif ($payment->method == PaymentMethod::CASH->value) {
            $payment->bill()->update([
                'payment_status' => PaymentStatus::PAID->value,
            ]);
        }
    }

    protected static function determineStatus($payment)
    {
        if ($payment->method == PaymentMethod::CREDIT_CARD->value) {
            return 'pending';
        }
        if ($payment->method == PaymentMethod::CASH->value) {
            return 'completed';
        }

        return 'failed';
    }

    protected static function determinePaidAt($payment)
    {
        if ($payment->method == PaymentMethod::CASH->value) {
            return now();
        }

        return null;
    }

    private function processStripePayment(Payment $payment)
    {
        try {
            $bill = $payment->bill;
            $reservation = $bill->reservation;
            $user = $reservation->user;

            if (! $user || ! $user->stripe_customer_id || ! $user->has_stripe_payment_method) {
                Log::warning('Payment failed: User does not have Stripe payment method setup', [
                    'payment_id' => $payment->id,
                    'user_id' => $user?->id,
                    'has_stripe_customer' => (bool) $user?->stripe_customer_id,
                    'has_payment_method' => (bool) $user?->has_stripe_payment_method,
                ]);

                Notification::make()
                    ->title('User does not have a valid payment method setup - Please Retry Payment')
                    ->body('Please ensure your payment method is set up in your profile.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->duration(10000)
                    ->danger()
                    ->send();

                return;
            }

            $stripe = new StripeClient(config('services.stripe.secret'));

            $paymentMethods = $stripe->paymentMethods->all([
                'customer' => $user->stripe_customer_id,
                'type' => 'card',
            ]);

            if (empty($paymentMethods->data)) {
                Log::warning('No payment methods found for customer', [
                    'payment_id' => $payment->id,
                    'customer_id' => $user->stripe_customer_id,
                ]);
                throw new Exception('No payment methods found for customer');
            }

            $paymentMethod = $paymentMethods->data[0];

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $payment->amount * 100,
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $paymentMethod->id,
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'return_url' => config('app.url'),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'bill_id' => $bill->id,
                    'reservation_id' => $reservation->id,
                    'user_id' => $user->id,
                ],
            ]);

            if ($paymentIntent->status === 'succeeded') {
                Log::info('Stripe payment successful', [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount' => $payment->amount,
                    'payment_method_id' => $paymentMethod->id,
                ]);

                $payment->update([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                $bill->update(['payment_status' => PaymentStatus::PAID->value]);
            } elseif ($paymentIntent->status === 'requires_action') {
                Log::warning('Stripe payment requires action', [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'client_secret' => $paymentIntent->client_secret,
                ]);

                $payment->update([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'pending',
                ]);
            } else {
                Log::warning('Stripe payment in unexpected status', [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                ]);

                $payment->update([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'failed',
                ]);
            }
        } catch (\Stripe\Exception\CardException $e) {
            Log::error('Stripe card error', [
                'payment_id' => $payment->id,
                'error_code' => $e->getStripeCode(),
                'error_message' => $e->getMessage(),
            ]);

            $payment->update(['status' => 'failed']);
            throw $e;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe invalid request', [
                'payment_id' => $payment->id,
                'error_message' => $e->getMessage(),
            ]);

            $payment->update(['status' => 'failed']);
            throw $e;
        } catch (Exception $e) {
            Log::error('Stripe payment failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $payment->update(['status' => 'failed']);
            throw $e;
        }
    }
}
