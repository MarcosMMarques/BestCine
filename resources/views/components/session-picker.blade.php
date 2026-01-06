@props([
    'movie',
    'days' => 10,
])

@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    $startDate = now();
    $dates = collect(range(0, $days - 1))->map(function ($offset) use ($startDate) {
        $date = $startDate->copy()->addDays($offset)->locale('pt_BR');

        return [
            'label' => ucfirst($date->translatedFormat('l')),
            'value' => $date->toDateString(),
            'displayDate' => $date->translatedFormat('d \\d\\e F'),
        ];
    });

    $showtimes = ['18:00', '21:00'];
    $carouselId = 'session-carousel-' . Str::random(8);
@endphp

<section class="w-full max-w-full overflow-hidden rounded-3xl bg-slate-900/80 p-6 shadow-2xl ring-1 ring-white/10">
    <header class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80">Escolha sua sessão</p>
        <h2 class="mt-2 text-2xl font-black text-white sm:text-3xl">
            {{ $movie->title }}
        </h2>
    </header>

    <form method="POST" action="{{ route('sessions.check', $movie) }}" class="mt-8 space-y-6">
        @csrf
        <fieldset class="min-w-0">
            <legend class="sr-only">Datas disponíveis</legend>
            <div class="relative w-full overflow-visible">
                <button
                    type="button"
                    class="absolute left-0 top-1/2 hidden h-12 w-12 -translate-y-1/2 rounded-full bg-slate-900/80 text-white shadow-lg shadow-slate-900/50 ring-1 ring-white/10 transition hover:bg-slate-800 md:flex items-center justify-center"
                    data-session-carousel-button
                    data-target="{{ $carouselId }}"
                    data-direction="prev"
                    aria-label="Datas anteriores"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    id="{{ $carouselId }}"
                    class="overflow-x-auto scroll-smooth py-2 pl-4 pr-4 snap-x snap-mandatory"
                    data-session-carousel
                >
                    <div class="flex w-max gap-4">
                        @foreach($dates as $index => $date)
                            <div class="min-w-[220px] flex-none rounded-2xl border border-white/5 bg-slate-950/40 p-4 shadow-lg shadow-slate-900/30 snap-start">
                                <div class="flex items-center justify-between text-white">
                                    <div>
                                        <p class="text-sm uppercase tracking-wide text-amber-300/80">{{ $date['label'] }}</p>
                                        <p class="text-base font-semibold">{{ $date['displayDate'] }}</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-300">
                                        2 sessões
                                    </span>
                                </div>

                                <div class="mt-4 flex flex-col gap-3">
                                    @foreach($showtimes as $time)
                                        @php
                                            $sessionId = 'session-' . $index . '-' . str_replace(':', '', $time);
                                        @endphp
                                        <label for="{{ $sessionId }}" class="block">
                                            <input
                                                type="radio"
                                                name="session"
                                                id="{{ $sessionId }}"
                                                value="{{ $date['value'] }}|{{ $time }}"
                                                class="peer sr-only"
                                            >
                                            <span class="flex h-12 items-center justify-center rounded-xl border border-white/10 bg-slate-900/70 text-sm font-semibold text-white transition peer-checked:border-amber-400/80 peer-checked:bg-amber-400/20 peer-checked:text-amber-100">
                                                {{ $time }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button
                    type="button"
                    class="absolute right-0 top-1/2 hidden h-12 w-12 -translate-y-1/2 rounded-full bg-slate-900/80 text-white shadow-lg shadow-slate-900/50 ring-1 ring-white/10 transition hover:bg-slate-800 md:flex items-center justify-center"
                    data-session-carousel-button
                    data-target="{{ $carouselId }}"
                    data-direction="next"
                    aria-label="Próximas datas"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </fieldset>

        @error('session')
            <p class="text-sm text-rose-300">{{ $message }}</p>
        @enderror

        <button
            type="submit"
            class="w-full rounded-2xl bg-amber-500 px-5 py-3 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/40 transition hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            Reservar ingresso
        </button>
    </form>
</section>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('[data-session-carousel-button]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var targetId = button.getAttribute('data-target');
                        var direction = button.getAttribute('data-direction');
                        var container = document.getElementById(targetId);

                        if (!container) {
                            return;
                        }

                        var scrollAmount = container.clientWidth * 0.8;
                        var delta = direction === 'next' ? scrollAmount : -scrollAmount;

                        container.scrollBy({
                            left: delta,
                            behavior: 'smooth'
                        });
                    });
                });
            });
        </script>
    @endpush
@endonce
