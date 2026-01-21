<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Services\ReservationService;
use App\Models\User;
use App\Models\Movie;
use App\Models\Seat;

class ProcessStripePayment implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $paymentIntent;

    public function __construct($paymentIntent)
    {
        $this->paymentIntent = $paymentIntent;
    }

    public function handle(ReservationService $reservationService)
    {
        $paymentIntent = $this->paymentIntent;
        $paymentStatus = $paymentIntent['status'] ?? null;

        if ($paymentStatus !== OrderStatus::SUCCEEDED &&
            Order::where('stripe_payment_intent_id', $paymentIntent['id'])->exists()) {
            Log::info("PaymentIntent {$paymentIntent['id']} already processed or not succeeded.");
            return;
        }


        $metadata = $paymentIntent['metadata'];

        $user = User::find((int) $metadata['user_id']);
        $movie = Movie::find((int) $metadata['movie_id']);
        $seat = Seat::find((int) $metadata['seat_id']);
        $date = Carbon::parse($metadata['session_datetime'], config('app.timezone'));


        DB::transaction(function () use (
            $reservationService,
            $paymentIntent,
            $user,
            $movie,
            $seat,
            $date
        ) {

            $reservation = $reservationService->createReservation($movie, $date, $seat, $user);

            Order::create([
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'status' => OrderStatus::SUCCEEDED,
                'amount_total' => $paymentIntent['amount_received'],
                'stripe_checkout_session_id' => null,
                'stripe_payment_intent_id' => $paymentIntent['id'],
                'stripe_customer_id' => $paymentIntent['customer'],
                'idempotency_key' => null,
                'metadata' => $paymentIntent['metadata'],
            ]);
        });
    }
}
