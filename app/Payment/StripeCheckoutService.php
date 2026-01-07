<?php

namespace App\Payment;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutService implements PaymentGatewayInterface
{
    protected const float AMOUNT = 20.00;
    protected const string CURRENCY = 'brl';

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(string $title, string $successUrl, string $cancelUrl)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => self::CURRENCY,
                            'product_data' => [
                                'name' => $title,
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
