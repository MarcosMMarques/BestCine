<?php

namespace App\Payment;

interface PaymentGatewayInterface
{
    public function createCheckoutSession(string $title, string $successUrl, string $cancelUrl);
}
