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

  <div class="relative container mx-auto px-4 py-16 max-w-full">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-2 text-sm font-semibold text-amber-200 transition-colors hover:border-amber-300 hover:bg-amber-300/20 hover:text-white">
          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.707 5.293a1 1 0 010 1.414L7.414 9H15a1 1 0 010 2H7.414l2.293 2.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
          </svg>
          Voltar
        </a>

        <div class="flex flex-wrap items-center gap-3">
          @if($movie->trailer_url)
            <a href="{{ $movie->trailer_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-rose-400/40 bg-rose-400/10 px-4 py-2 text-sm font-semibold text-rose-100 transition-colors hover:border-rose-300 hover:bg-rose-300/20 hover:text-white">
              <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M6.5 5.5l9 4.5-9 4.5v-9z" />
              </svg>
              Assistir trailer
            </a>
          @endif
        </div>
      </div>

  <div class="mt-12 grid gap-10 lg:grid-cols-[320px,1fr] max-w-full">
        <div class="relative h-max mx-auto w-full max-w-sm overflow-hidden rounded-3xl bg-slate-900/80 shadow-2xl ring-1 ring-white/10">
          @if($movie->poster_url)
            <img src="{{ $movie->poster_url }}" alt="Poster de {{ $movie->title }}" class="w-full object-cover" loading="lazy" />
          @endif

          @if($movie->length)
            <div class="absolute top-4 right-4 rounded-full bg-slate-900/80 px-3 py-1 text-s font-semibold uppercase tracking-wide text-yellow-300">
              {{ sprintf('%dh %02dmin', floor($movie->length / 60), $movie->length % 60) }}
            </div>
          @endif
        </div>

  <div class="space-y-8 sm:space-y-10 min-w-0">
          <div class="w-full">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80 sm:text-sm">{{ filled($movie['tagline'] ?? null) ? $movie['tagline'] : 'Detalhes do filme' }}</p>
            <h1 class="mt-4 text-3xl font-black text-white sm:text-4xl md:text-5xl">{{ $movie['title'] }}</h1>

            <a
              href="{{ route('movies.sessions', $movie) }}"
              class="mt-6 inline-flex items-center justify-center rounded-full bg-amber-500 px-6 py-3 text-sm font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/40 transition hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
            >
              Ver Sessões
            </a>

            <dl class="mt-6 flex flex-wrap items-center gap-3 text-xs text-slate-300 sm:gap-4 sm:text-sm">
              @if($movie->release_date)
                <div class="flex items-center gap-2">
                  <span class="inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                  <dt class="sr-only">Data de lançamento</dt>
                  <dd>{{ date('d/m/Y', strtotime($movie->release_date)) }}</dd>
                </div>
              @endif

              @if($movie->genres)
                <div class="flex items-center gap-2 text-slate-300">
                  <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                  <dt class="sr-only">Gêneros</dt>
                  <dd>{{ implode(' • ', $movie->genres->pluck('name')->toArray()) }}</dd>
                </div>
              @endif
            </dl>

            @if(!empty($movie->synopsis))
              <p class="mt-6 text-sm text-slate-300 sm:text-base md:text-lg break-words">{{ $movie->synopsis }}</p>
            @endif
          </div>

          @if($movie->actors)
            <div class="rounded-3xl bg-slate-900/80 p-5 ring-1 ring-white/5 sm:p-6">
              <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-300">Elenco principal {{count($movie->actors)}}</h2>
              <div class="mt-4 -mx-5 px-5 py-2 overflow-x-auto sm:-mx-6 sm:px-6">
                <div class="flex gap-4 sm:gap-6">
                  @foreach($movie->actors as $actor)
                    <article class="flex flex-none w-24 flex-col items-center text-center sm:w-28 md:w-32">
                      <div class="relative h-20 w-20 overflow-hidden rounded-full ring-2 ring-amber-400/40 ring-offset-1 ring-offset-slate-900 sm:h-24 sm:w-24 sm:ring-offset-2">
                        <img src="{{ !empty($actor->profile_path) ? 'https://image.tmdb.org/t/p/w185' . $actor->profile_path : asset('images/no-photo.png') }}" alt="{{ $actor->name ?? 'Integrante do elenco' }}" class="h-full w-full object-cover" loading="lazy">
                      </div>
                      <p class="mt-3 text-xs font-semibold text-white line-clamp-2 sm:text-sm">{{ $actor->name ?? 'Nome não informado' }}</p>
                    </article>
                  @endforeach
                </div>
              </div>
            </div>
          @endif

          @if($movie->productionCompanies)
            <div class="w-full rounded-3xl bg-slate-900/80 p-5 ring-1 ring-white/5 sm:p-6 max-w-full">
              <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-300">Produtoras</h2>
              <ul class="mt-4 flex flex-wrap gap-2 sm:gap-3 break-words max-w-full">
                @foreach($movie->productionCompanies as $company)
                  <li class="rounded-full bg-slate-800/80 px-3 py-1.5 text-xs font-medium text-slate-200 sm:px-4 sm:py-2 sm:text-sm break-words">{{ $company->name }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
