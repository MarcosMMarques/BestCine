<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Jobs\ProcessStripePayment;
use Illuminate\Support\Facades\Log;
use Exception;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);


            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    ProcessStripePayment::dispatch($paymentIntent->toArray());
                    break;
                default:
                    Log::info("Evento do Stripe não tratado: {$event->type}");
                    break;
            }

            return response('Webhook received successfully', 200);
        } catch (Exception $e) {
            Log::error('Erro no webhook do Stripe: '.$e->getMessage());
            return response('Webhook Error', 400);
        }
    }
}
