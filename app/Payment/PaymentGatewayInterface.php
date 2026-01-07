<?php

namespace App\Payment;

interface PaymentGatewayInterface
{
    public function createCheckoutSession(float $amount, string $currency, string $successUrl, string $cancelUrl);
}
