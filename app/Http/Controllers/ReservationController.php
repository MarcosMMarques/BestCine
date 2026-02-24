<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Seat;
use App\Models\Session;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Payment\PaymentGatewayInterface;
use Illuminate\Support\Carbon;
use App\Exceptions\SeatAlreadyReservedException;
use App\Exceptions\UserAlreadyHasReservationException;
use App\Http\Requests\CheckoutRequest;

class ReservationController extends Controller
{
    protected $paymentGateway;
    protected $reservationService;

    public function __construct(
        PaymentGatewayInterface $paymentGateway,
        ReservationService $reservationService
    ) {
        $this->paymentGateway = $paymentGateway;
        $this->reservationService = $reservationService;
    }

    public function checkout(CheckoutRequest $request, Movie $movie)
    {
        $validated = $request->validated();
        $date = Carbon::parse($validated['session'], config('app.timezone'));
        $seats = Seat::getSeatsByIdentifiers($validated['seats']);

        if (count($seats) == 0) {
            return redirect()->route('movies.sessions', compact('movie'))
                ->with('error', 'Pelo menos um assento válido precisa ser selecionado.');
        }

        try {
            $this->reservationService->validateReservation(
                $request->user(),
                $movie,
                $date,
                $seats
            );

            $metadata = [
                'user_id' => (string) $request->user()->id,
                'movie_id' => (string) $movie->id,
                'seat_ids' => collect($seats)->pluck('id'),
                'session_datetime' => $date->toIso8601String(),
            ];
            $successUrl = route('reservation.success');
            $cancelUrl = route('reservation.cancel');
            $session = $this->paymentGateway->createCheckoutSession($movie->title, $successUrl, $cancelUrl, $metadata);

            return redirect()->away($session->url);
        } catch (SeatAlreadyReservedException | UserAlreadyHasReservationException $e) {
            return redirect()->route('movies.sessions', compact('movie'))
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('movies.sessions', compact('movie'))
                ->with('error', 'Ocorreu um erro ao processar sua reserva. Tente novamente.');
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

    public function getReservedSeats(Request $request, Movie $movie)
    {
        $sessionDatetime = $request->query('session');

        if (!$sessionDatetime) {
            return response()->json(['reserved_seats' => []], 200);
        }

        $date = Carbon::parse($sessionDatetime, config('app.timezone'));

        $session = Session::where('movie_id', $movie->id)
            ->where('datetime', $date)
            ->first();

        if (!$session) {
            return response()->json(['reserved_seats' => []], 200);
        }

        return response()->json([
            'reserved_seats' => $session->getReservedSeats()
        ], 200);
    }

    public function getUserTickets(Request $request, User $user)
    {
        $orders = Order::with([
            'reservation',
            'reservation.session',
            'reservation.session.movie',
            'reservation.session.room',
            'reservation.seats'
        ])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('reservation.tickets', compact('orders'));
    }
}
