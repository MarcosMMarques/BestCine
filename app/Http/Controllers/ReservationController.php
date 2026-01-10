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


            $successUrl = route('reservation.success', [
                'movie'   => $movie->id,
                'session' => $date->toIso8601String(),
                'seat_id' => $seat->id,
            ]);
            $cancelUrl = route('reservation.cancel');
            $session = $this->paymentGateway->createCheckoutSession($movie->title, $successUrl, $cancelUrl);

            return redirect()->away($session->url);
        } catch (SeatAlreadyReservedException | UserAlreadyHasReservationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 409);
        } catch (\Exception $e) {
            return redirect()->route('movies.sessions', compact('movie'))->with('error', $e->getMessage());
        }
    }

    public function success(Request $request, Movie $movie)
    {
        //TODO: Validar Dados
        $seat = Seat::find(1);
        $date = Carbon::parse($request->session, config('app.timezone'));

        try {
            $this->reservationService->createReservation($movie, $date, $seat, $request->user());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possivel criar a reserva.'
            ], 500);
        }

        return view('reservation.success');
    }

    public function cancel(Request $request)
    {
        return view('reservation.cancel');
    }
}
