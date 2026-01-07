<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Payment\PaymentGatewayInterface;

class ReservationController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function checkout(Request $request, Movie $movie)
    {
        //Buscar sessão usando filme, horário e sala

        // URLs para sucesso e cancelamento
        $successUrl = route('reservation.success');
        $cancelUrl = route('reservation.cancel');

        try {
            $session = $this->paymentGateway->createCheckoutSession($movie->title, $successUrl, $cancelUrl);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()->route('movies.sessions', compact('movie'))->with('error', $e->getMessage());
        }
    }

    public function success()
    {
        // Lógica para criar a reserva
        dd('passo');

        return view('reservation.success');
    }

    public function cancel()
    {
        dd('recusou');
        return view('reservation.cancel');
    }
}
