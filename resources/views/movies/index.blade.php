@extends('layouts.app')

@section('content')
  <section class="relative overflow-hidden bg-slate-950 py-16">
    <div aria-hidden="true" class="absolute inset-0">
      <div class="pointer-events-none absolute left-1/2 top-0 h-80 w-80 -translate-x-1/2 rounded-full bg-amber-500/20 blur-3xl"></div>
      <div class="pointer-events-none absolute inset-x-10 bottom-0 h-48 rounded-full bg-amber-500/10 blur-3xl"></div>
    </div>

    <div class="relative container mx-auto px-4">
      <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="max-w-2xl">
          <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80">Agora nos cinemas</p>
          <h2 class="mt-3 text-4xl font-black text-white md:text-5xl">Em cartaz</h2>
          <p class="mt-4 text-base text-slate-300 md:text-lg">Descubra as produções mais comentadas do momento e garanta seu lugar na próxima sessão.</p>
        </div>

        <span class="self-start rounded-full border border-amber-500/20 bg-amber-500/10 px-4 py-2 text-xs font-medium uppercase tracking-[0.3em] text-amber-100">Atualizado diariamente</span>
      </div>

      @if($movies && count($movies))
        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          @foreach ($movies as $movie)
            @php
              $posterPath = $movie['poster_path'] ?? null;
              $posterUrl = $posterPath ? 'https://image.tmdb.org/t/p/w500' . $posterPath : 'https://via.placeholder.com/500x750?text=Sem+Imagem';
              $voteAverage = $movie['vote_average'] ?? null;
              $releaseDate = $movie['release_date'] ?? null;
              $formattedDate = $releaseDate ? \Carbon\Carbon::parse($releaseDate)->format('d/m/Y') : null;
              $overview = $movie['overview'] ?? null;
              $popularity = $movie['popularity'] ?? null;
            @endphp

            <article class="group relative overflow-hidden rounded-3xl bg-slate-900/70 shadow-2xl shadow-slate-950/40 ring-1 ring-white/10 transition-transform duration-300 hover:-translate-y-2 hover:ring-amber-400/40">
              <div class="relative h-80 overflow-hidden">
                <img loading="lazy" src="{{ $posterUrl }}" alt="Poster de {{ $movie['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                @if($voteAverage !== null)
                  <span class="absolute top-4 left-4 flex items-center gap-2 rounded-full bg-amber-400/90 px-3 py-1 text-sm font-semibold text-slate-900 shadow-lg shadow-amber-900/40">
                    <svg class="h-4 w-4 text-slate-900" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.107 3.401a1 1 0 00.95.69h3.58c.969 0 1.371 1.24.588 1.81l-2.897 2.104a1 1 0 00-.364 1.118l1.107 3.401c.3.921-.755 1.688-1.54 1.118l-2.897-2.104a1 1 0 00-1.175 0l-2.897 2.104c-.785.57-1.84-.197-1.54-1.118l1.107-3.401a1 1 0 00-.364-1.118L2.823 8.828c-.783-.57-.38-1.81.588-1.81h3.58a1 1 0 00.95-.69l1.107-3.401z"/>
                    </svg>
                    <span>{{ number_format($voteAverage, 1) }}</span>
                  </span>
                @endif
              </div>

              <div class="relative flex h-full flex-col justify-between gap-4 p-6">
                <div class="space-y-3">
                  <h3 class="text-xl font-bold text-white transition-colors duration-300 group-hover:text-amber-300">
                    {{ $movie['title'] }}
                  </h3>

                  <dl class="flex flex-wrap items-center gap-x-4 text-sm text-slate-300">
                    <div class="flex items-center gap-2">
                      <span class="inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                      <dt class="sr-only">Data de lançamento</dt>
                      <dd>{{ $formattedDate ?? 'Data não informada' }}</dd>
                    </div>

                    @if(!empty($movie['original_language']))
                      <div class="flex items-center gap-2 uppercase tracking-wide text-xs text-slate-400">
                        <span class="inline-flex h-2 w-2 rounded-full bg-slate-600"></span>
                        <dt class="sr-only">Idioma original</dt>
                        <dd>{{ $movie['original_language'] }}</dd>
                      </div>
                    @endif
                  </dl>

                  @if($overview)
                    <p class="text-sm text-slate-400">
                      {{ \Illuminate\Support\Str::limit($overview, 140) }}
                    </p>
                  @endif
                </div>

                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-400">
                    Popularidade: <strong class="text-white">{{ $popularity !== null ? number_format($popularity, 0, ',', '.') : '—' }}</strong>
                  </span>

                  <a href="https://www.themoviedb.org/movie/{{ $movie['id'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-amber-400/60 bg-amber-400/20 px-4 py-2 font-semibold text-amber-200 transition-colors duration-300 hover:border-amber-300 hover:bg-amber-300/30 hover:text-white">
                    Saiba mais
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H5a1 1 0 110-2h8.586l-3.293-3.293a1 1 0 010-1.414z"/>
                    </svg>
                  </a>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div class="mt-16 rounded-3xl border border-dashed border-slate-700 bg-slate-900/50 p-12 text-center">
          <p class="text-lg font-medium text-slate-300">Não há filmes em cartaz no momento. Volte em breve para ver as novidades!</p>
        </div>
      @endif
    </div>
  </section>
@endsection
