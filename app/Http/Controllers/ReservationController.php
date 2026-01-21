<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Services\ReservationService;
use Exception;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Payment\PaymentGatewayInterface;
use Illuminate\Support\Carbon;
use App\Exceptions\SeatAlreadyReservedException;
use App\Exceptions\UserAlreadyHasReservationException;

class ReservationController extends Controller
{
    protected $paymentGateway;

    public function __construct(
        PaymentGatewayInterface $paymentGateway,
        ReservationService $reservationService
    ) {
        $this->paymentGateway = $paymentGateway;
        $this->reservationService = $reservationService;
    }

    public function checkout(Request $request, Movie $movie)
    {
        //TODO: Validar Dados
        $date = Carbon::parse($request['session'], config('app.timezone'));
        $seat = Seat::find(1);

        try {
            $this->reservationService->validateReservation(
                $request->user(),
                $movie,
                $date,
                $seat
            );

            $metadata = [
                'user_id' => (string) $request->user()->id,
                'movie_id' => (string) $movie->id,
                'seat_id' => (string) $seat->id,
                'session_datetime' => $date->toIso8601String(),
            ];
            $successUrl = route('reservation.success');
            $cancelUrl = route('reservation.cancel');
            $session = $this->paymentGateway->createCheckoutSession($movie->title, $successUrl, $cancelUrl, $metadata);

            return redirect()->away($session->url);
        } catch (SeatAlreadyReservedException | UserAlreadyHasReservationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('movies.sessions', compact('movie'))->with('error', $e->getMessage());
        }
    }

    public function success(Request $request, Movie $movie)
    {
        return view('reservation.success');
    }

    public function cancel(Request $request)
    {
        return view('reservation.cancel');
    }
}
