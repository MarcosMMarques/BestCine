<?php

namespace App\Payment;

interface PaymentGatewayInterface
{
    public function createCheckoutSession(string $currency, string $successUrl, string $cancelUrl);
}
