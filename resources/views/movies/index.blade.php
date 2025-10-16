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
        </div>
      </div>

      @if($movies && count($movies))
        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          @foreach ($movies as $movie)
            <article class="group relative overflow-hidden rounded-3xl bg-slate-900/70 shadow-2xl shadow-slate-950/40 ring-1 ring-white/10 transition-transform duration-300 hover:-translate-y-2 hover:ring-amber-400/40">
              <a href="{{ route('movies.show', $movie['id']) }}" class="absolute inset-0 z-10" aria-label="Ver detalhes de {{ $movie['title'] }}"></a>

              <div class="relative h-80 overflow-hidden">
                <img loading="lazy" src="{{ $movie['posterUrl'] }}" alt="Poster de {{ $movie['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
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
                      <dd>{{ $movie['formattedDate'] ?? 'Data não informada' }}</dd>
                    </div>
                  </dl>

                  @if($movie['overview'] ?? null)
                    <p class="text-sm text-slate-400">
                      {{ \Illuminate\Support\Str::limit($movie['overview'], 140) }}
                    </p>
                  @endif
                </div>

                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-400">
                    Popularidade: <strong class="text-white">{{ isset($movie['popularity']) ? number_format($movie['popularity'], 0, ',', '.') : '—' }}</strong>
                  </span>
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
