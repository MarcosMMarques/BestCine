@extends('layouts.app')

@section('content')
  <section class="relative overflow-hidden bg-slate-950">
    @if($backdropUrl)
      <div aria-hidden="true" class="absolute inset-0">
        <img src="{{ $backdropUrl }}" alt="" class="h-full w-full object-cover opacity-30" />
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950/80 to-slate-900/80"></div>
      </div>
    @else
      <div aria-hidden="true" class="absolute inset-0 bg-slate-950"></div>
    @endif

    <div class="relative container mx-auto px-4 py-16">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-2 text-sm font-semibold text-amber-200 transition-colors hover:border-amber-300 hover:bg-amber-300/20 hover:text-white">
          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.707 5.293a1 1 0 010 1.414L7.414 9H15a1 1 0 010 2H7.414l2.293 2.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
          </svg>
          Voltar
        </a>
      </div>

      <div class="mt-12 grid gap-10 lg:grid-cols-[320px,1fr]">
        <div class="relative mx-auto w-full max-w-sm overflow-hidden rounded-3xl bg-slate-900/80 shadow-2xl ring-1 ring-white/10">
          @if($posterUrl)
            <img src="{{ $posterUrl }}" alt="Poster de {{ $movie['title'] }}" class="w-full object-cover" loading="lazy" />
          @endif

          @if($runtimeLabel)
            <div class="absolute top-4 right-4 rounded-full bg-slate-900/80 px-3 py-1 text-s font-semibold uppercase tracking-wide text-yellow-300">
              {{ $runtimeLabel }}
            </div>
          @endif
        </div>

        <div class="space-y-10">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-amber-400/80">{{ filled($movie['tagline'] ?? null) ? $movie['tagline'] : 'Detalhes do filme' }}</p>
            <h1 class="mt-4 text-4xl font-black text-white md:text-5xl">{{ $movie['title'] }}</h1>

            <dl class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-300">
              @if($formattedDate)
                <div class="flex items-center gap-2">
                  <span class="inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                  <dt class="sr-only">Data de lançamento</dt>
                  <dd>{{ $formattedDate }}</dd>
                </div>
              @endif

              @if($genres)
                <div class="flex items-center gap-2 text-slate-300">
                  <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                  <dt class="sr-only">Gêneros</dt>
                  <dd>{{ implode(' • ', $genres) }}</dd>
                </div>
              @endif
            </dl>

            @if(!empty($movie['overview']))
              <p class="mt-6 text-base text-slate-300 md:text-lg">{{ $movie['overview'] }}</p>
            @endif
          </div>

          @if($productionCompanies)
            <div class="rounded-3xl bg-slate-900/80 p-6 ring-1 ring-white/5">
              <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-300">Produtoras</h2>
              <ul class="mt-4 flex flex-wrap gap-3">
                @foreach($productionCompanies as $company)
                  <li class="rounded-full bg-slate-800/80 px-4 py-2 text-sm font-medium text-slate-200">{{ $company }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
