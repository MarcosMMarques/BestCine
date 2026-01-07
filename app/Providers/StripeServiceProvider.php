<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Payment\PaymentGatewayInterface;
use App\Payment\StripeCheckoutService;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, StripeCheckoutService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
