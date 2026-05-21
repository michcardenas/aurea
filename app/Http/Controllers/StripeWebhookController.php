<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret'),
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature.', 400);
        }

        $this->handleEvent($event);

        return response('', 200);
    }

    private function handleEvent(\Stripe\Event $event): void
    {
        $paymentIntentId = $event->data->object->id ?? null;

        if (! $paymentIntentId) {
            return;
        }

        $order = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if (! $order) {
            return;
        }

        match ($event->type) {
            'payment_intent.succeeded' => $order->update(['payment_status' => 'paid']),
            'payment_intent.payment_failed' => $order->update(['payment_status' => 'failed']),
            default => null,
        };
    }
}
