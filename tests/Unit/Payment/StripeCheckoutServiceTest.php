<?php

use App\Payment\StripeCheckoutService;

it('has CURRENCY constant set to brl', function () {
    $reflection = new ReflectionClass(StripeCheckoutService::class);
    $constant = $reflection->getReflectionConstant('CURRENCY');

    expect($constant)->not->toBeFalse();
    expect($constant->getValue())->toBe('brl');
    expect($constant->isProtected())->toBeTrue();
});
