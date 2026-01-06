@extends('layouts.app')

@section('content')
  <section class="relative overflow-x-hidden bg-slate-950">
    @if($movie->backdrop_url)
      <div aria-hidden="true" class="absolute inset-0">
        <img src="{{ $movie->backdrop_url }}" alt="" class="h-full w-full object-cover opacity-30" />
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/80 to-slate-900/80"></div>
      </div>
    @else
      <div aria-hidden="true" class="absolute inset-0 bg-slate-950"></div>
    @endif

    <div class="relative container mx-auto px-4 py-16">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('movies.show', $movie->tmdb_id) }}" class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-2 text-sm font-semibold text-amber-200 transition-colors hover:border-amber-300 hover:bg-amber-300/20 hover:text-white">
          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.707 5.293a1 1 0 010 1.414L7.414 9H15a1 1 0 010 2H7.414l2.293 2.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
          </svg>
          Voltar para o filme
        </a>

        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-300/80">Sessões disponíveis</p>
      </div>

      <div class="mt-12 grid gap-10 lg:grid-cols-[320px,1fr]">
        <div class="relative h-max mx-auto w-full max-w-sm overflow-hidden rounded-3xl bg-slate-900/80 shadow-2xl ring-1 ring-white/10">
          @if($movie->poster_url)
            <img src="{{ $movie->poster_url }}" alt="Poster de {{ $movie->title }}" class="w-full object-cover" loading="lazy" />
          @endif

          @if($movie->length)
            <div class="absolute top-4 right-4 rounded-full bg-slate-900/80 px-3 py-1 text-s font-semibold uppercase tracking-wide text-yellow-300">
              {{ sprintf('%dh %02dmin', floor($movie->length / 60), $movie->length % 60) }}
            </div>
          @endif

          <div class="p-6 text-white">
            <h1 class="text-2xl font-black sm:text-3xl">{{ $movie->title }}</h1>

            @if($movie->genres)
              <p class="mt-3 text-xs uppercase tracking-wide text-slate-400">{{ implode(' • ', $movie->genres->pluck('name')->toArray()) }}</p>
            @endif
          </div>
        </div>

        <div class="space-y-8 sm:space-y-10 min-w-0">
          <div class="w-full">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80 sm:text-sm">Escolha a melhor sessão</p>
            <h2 class="mt-4 text-3xl font-black text-white sm:text-4xl md:text-5xl">{{ $movie->title }}</h2>
            <p class="mt-6 text-sm text-slate-300 sm:text-base md:text-lg break-words">
              Escolha um horário disponível abaixo.
            </p>
          </div>

          <x-session-picker :movie="$movie" />
        </div>
      </div>
    </div>
  </section>
@endsection
