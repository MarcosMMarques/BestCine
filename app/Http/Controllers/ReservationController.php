<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

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
        $successUrl = route('ticket.success');
        $cancelUrl = route('ticket.cancel');

        try {
            $session = $this->paymentGateway->createCheckoutSession('brl', $successUrl, $cancelUrl);

            // Redirecionar para a página de checkout do Stripe
            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function success()
    {
        // Lógica para criar a reserva

        return view('ticket.success');
    }

    public function cancel()
    {
        return view('ticket.cancel');
    }
}
