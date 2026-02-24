@extends('layouts.app')

@section('content')
<div class="relative min-h-[calc(100vh-theme(spacing.16))] bg-slate-950 px-4 py-12 sm:px-6 lg:px-8">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-1/2 -right-1/2 h-[1000px] w-[1000px] rounded-full bg-emerald-500/5 blur-3xl"></div>
        <div class="absolute -bottom-1/2 -left-1/2 h-[1000px] w-[1000px] rounded-full bg-amber-500/5 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-5xl">
        <h1 class="mb-8 text-3xl font-black text-white sm:text-4xl text-center">Meus Ingressos</h1>

        @if($orders->isEmpty())
            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-8 text-center shadow-2xl backdrop-blur-xl sm:p-12">
                <p class="mb-8 text-lg text-slate-400">Você ainda não possui nenhum ingresso.</p>
                <a href="{{ route('movies.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-6 py-4 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    Ver Filmes em Cartaz
                </a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($orders as $order)
                    @php
                        $reservation = $order->reservation;
                        $session = $reservation ? $reservation->session : null;
                        $movie = $session ? $session->movie : null;
                        $room = $session ? $session->room : null;
                        $seats = $reservation ? $reservation->seats : collect();
                        
                        $statusColors = [
                            'SUCCEEDED' => 'bg-emerald-500/20 text-emerald-400 ring-emerald-500/30',
                            'PENDING'   => 'bg-amber-500/20 text-amber-400 ring-amber-500/30',
                            'FAILED'    => 'bg-red-500/20 text-red-400 ring-red-500/30',
                            'CANCELLED' => 'bg-slate-500/20 text-slate-400 ring-slate-500/30',
                            'EXPIRED'   => 'bg-rose-500/20 text-rose-400 ring-rose-500/30',
                        ];
                        
                        $statusLabels = [
                            'SUCCEEDED' => 'Pago',
                            'PENDING'   => 'Pendente',
                            'FAILED'    => 'Falhou',
                            'CANCELLED' => 'Cancelado',
                            'EXPIRED'   => 'Expirado',
                        ];
                        
                        $statusName = $order->status->name;
                        $statusClass = $statusColors[$statusName] ?? 'bg-slate-500/20 text-slate-400 ring-slate-500/30';
                        $statusLabel = $statusLabels[$statusName] ?? $statusName;
                    @endphp
                    @if($movie && $reservation)
                        <div class="flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-slate-900/80 shadow-2xl backdrop-blur-xl transition hover:border-white/20 hover:bg-slate-900">
                            <!-- Movie Poster/Header -->
                            <div class="relative h-48 w-full shrink-0 overflow-hidden bg-slate-800">
                                @if($movie->poster_url)
                                    <img src="{{ str_starts_with($movie->poster_url, 'http') ? $movie->poster_url : asset('storage/' . $movie->poster_url) }}" alt="{{ $movie->title }}" class="absolute inset-0 h-full w-full object-cover opacity-80" />
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="line-clamp-2 text-xl font-bold text-white">{{ $movie->title }}</h3>
                                </div>
                            </div>

                            <!-- Ticket Details -->
                            <div class="flex flex-1 flex-col p-6">
                                <div class="mb-4 flex items-center justify-between">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    <span class="text-sm font-medium text-slate-400">
                                        Pedido #{{ $order->id }}
                                    </span>
                                </div>

                                <dl class="mb-6 grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                                    <div>
                                        <dt class="font-medium text-slate-500">Data e Hora</dt>
                                        <dd class="mt-1 font-semibold text-white">
                                            {{ \Carbon\Carbon::parse($session->datetime)->format('d/m/Y H:i') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-slate-500">Sala</dt>
                                        <dd class="mt-1 font-semibold text-white">
                                            {{ $room ? $room->name : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div class="col-span-2">
                                        <dt class="font-medium text-slate-500">Assentos ({{ $seats->count() }})</dt>
                                        <dd class="mt-1 font-semibold text-amber-500">
                                            {{ $seats->map(function($seat) { return chr(ord('A') + $seat->row - 1) . '-' . $seat->number; })->join(', ') }}
                                        </dd>
                                    </div>
                                </dl>

                                <div class="mt-auto border-t border-slate-800 pt-4 flex items-center justify-between">
                                    <span class="text-xs text-slate-500">Valor Total:</span>
                                    <span class="text-lg font-bold text-white">R$ {{ number_format(($order->amount_total ?? 0) / 100, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
