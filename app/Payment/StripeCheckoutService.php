<?php

namespace App\Payment;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutService implements PaymentGatewayInterface
{
    protected const float AMOUNT = 20.00;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(string $currency, string $successUrl, string $cancelUrl)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $currency,
                            'product_data' => [
                                'name' => 'Cinema Ticket',
                            ],
                            'unit_amount' => (int)(self::AMOUNT * 100)
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            return $session;
        } catch (\Exception $e) {
            throw new \Exception('Error creating Stripe Checkout session: ' . $e->getMessage());
        }
    }
}
