<?php

namespace App\Http\Controllers;

use App\Listeners\HandleStripePaymentMethodWebhook;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        logger()->info('Stripe webhook received');
        $stripe = new StripeClient(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if (! $payload || ! $sigHeader || ! $endpointSecret) {
            logger()->error('Missing webhook data: payload, sigHeader, or endpointSecret');

            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );

            logger()->info('Stripe event type: '.$event->type);

            if (in_array($event->type, ['payment_method.attached', 'payment_method.detached'])) {
                $listener = new HandleStripePaymentMethodWebhook;
                $listener->handle($event);
            }

            return response()->json(['status' => 'success']);
        } catch (SignatureVerificationException $e) {
            logger()->error('Stripe webhook signature verification failed: '.$e->getMessage());

            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            logger()->error('Stripe webhook error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
