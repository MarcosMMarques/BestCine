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
            'value' => $date,
            'displayDate' => $date->translatedFormat('d \\d\\e F'),
        ];
    });

    $showtimes = ['18:00', '21:00'];
    $carouselId = 'session-carousel-' . Str::random(8);
    $modalId = 'payment-modal-' . Str::random(8);
    $formId = 'reservation-form-' . Str::random(8);
@endphp

<section class="w-full max-w-full overflow-hidden rounded-3xl bg-slate-900/80 p-6 shadow-2xl ring-1 ring-white/10">
    <header class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80">Escolha sua sessão</p>
        <h2 class="mt-2 text-2xl font-black text-white sm:text-3xl">
            {{ $movie->title }}
        </h2>
    </header>

    <form id="{{ $formId }}" method="POST" action="{{ route('reservation.checkout', $movie) }}" class="mt-8 space-y-6">
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
                                                value="{{ $date['value']->setTimeFromTimeString($time)->format('Y-m-d\TH:i') }}"
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
            type="button"
            data-modal-target="{{ $modalId }}"
            data-form-id="{{ $formId }}"
            class="w-full rounded-2xl bg-amber-500 px-5 py-3 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/40 transition hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            Reservar ingresso
        </button>
    </form>

    {{-- Simulation Modal --}}
    <div
        id="{{ $modalId }}"
        class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-950/90 backdrop-blur-sm p-4 transition-opacity"
        role="dialog"
        aria-modal="true"
    >
        <div class="w-full max-w-md transform overflow-hidden rounded-3xl bg-slate-900 border border-white/10 p-6 text-center shadow-2xl transition-all">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-500/10 mb-6 ring-1 ring-amber-500/20">
                <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-white mb-2">Aviso</h3>
            <p class="text-slate-400 mb-8 leading-relaxed">
                A tela de pagamento é apenas uma simulação! Os campos podem ser preenchidos com dados aleatórios.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    data-modal-close
                    class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    data-modal-confirm-for="{{ $formId }}"
                    class="rounded-xl bg-amber-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-400"
                >
                    Ir para Pagamento
                </button>
            </div>
        </div>
    </div>
</section>

@once
    @push('scripts')
        <script>
    document.addEventListener('DOMContentLoaded', function () {
                // Carousel Logic
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

                // Modal Simulation Logic
                const modalTriggers = document.querySelectorAll('[data-modal-target]');
                const modalClosers = document.querySelectorAll('[data-modal-close]');
                const modalConfirmers = document.querySelectorAll('[data-modal-confirm-for]');

                function toggleModal(modalId, show) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        if (show) {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            document.body.style.overflow = 'hidden'; // Prevent background scroll
                        } else {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                            document.body.style.overflow = '';
                        }
                    }
                }

                modalTriggers.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const formId = btn.getAttribute('data-form-id');
                        const form = document.getElementById(formId);

                        // Basic HTML5 validation check before opening modal
                        if (form && form.checkValidity()) {
                            toggleModal(btn.getAttribute('data-modal-target'), true);
                        } else if (form) {
                            form.reportValidity();
                        }
                    });
                });

                modalClosers.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const modal = btn.closest('[role="dialog"]');
                        if (modal) {
                            toggleModal(modal.id, false);
                        }
                    });
                });

                modalConfirmers.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const formId = btn.getAttribute('data-modal-confirm-for');
                        const form = document.getElementById(formId);
                        if (form) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
