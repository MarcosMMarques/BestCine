@extends('layouts.app')

@section('content')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 px-4 py-12 sm:px-6 lg:px-8">
        {{-- Background Effects --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/2 h-[1000px] w-[1000px] rounded-full bg-emerald-500/5 blur-3xl"></div>
            <div class="absolute -bottom-1/2 -left-1/2 h-[1000px] w-[1000px] rounded-full bg-amber-500/5 blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-lg rounded-3xl border border-white/10 bg-slate-900/80 p-8 text-center shadow-2xl backdrop-blur-xl sm:p-12">
            <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-500/10 ring-1 ring-emerald-500/20">
                <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <h1 class="mb-4 text-3xl font-black text-white sm:text-4xl">Reserva Confirmada!</h1>
            
            <p class="mb-8 text-lg text-slate-400">
                Sua reserva foi realizada com sucesso. Aproveite o filme!
            </p>

            <div class="space-y-4">
                <a href="{{ route('movies.index') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-amber-500 px-6 py-4 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/20 transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                    Voltar para o Início
                </a>
            </div>
        </div>
    </div>
@endsection
