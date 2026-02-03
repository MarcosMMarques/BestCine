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
    $seatPickerId = 'seat-picker-' . Str::random(8);

    // Seat configuration: 5 rows (A-E) x 10 seats per row
    $rows = ['A', 'B', 'C', 'D', 'E'];
    $seatsPerRow = 10;

    // Route for fetching reserved seats via AJAX
    $reservedSeatsUrl = route('reservation.reserved-seats', $movie);
@endphp

<section class="w-full max-w-full overflow-hidden rounded-3xl bg-slate-900/80 p-6 shadow-2xl ring-1 ring-white/10">
    <header class="text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-amber-400/80">Escolha sua sessão</p>
        <h2 class="mt-2 text-2xl font-black text-white sm:text-3xl">
            {{ $movie->title }}
        </h2>
    </header>

    <div class="mt-6 flex items-center justify-center gap-4">
        <div class="flex items-center gap-2" id="step-1-indicator">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-500 text-sm font-bold text-slate-950">1</span>
            <span class="text-sm font-semibold text-amber-400">Horário</span>
        </div>
        <div class="h-px w-12 bg-white/20"></div>
        <div class="flex items-center gap-2 opacity-50" id="step-2-indicator">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-700 text-sm font-bold text-white">2</span>
            <span class="text-sm font-semibold text-slate-400">Cadeiras</span>
        </div>
    </div>

    <form id="{{ $formId }}" method="POST" action="{{ route('reservation.checkout', $movie) }}" class="mt-8 space-y-6">
        @csrf

        {{-- Step 1: Session Time Selection --}}
        <fieldset class="min-w-0" id="step-1-content">
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
                                                data-session-radio
                                            >
                                            <span class="flex h-12 items-center justify-center rounded-xl border border-white/10 bg-slate-900/70 text-sm font-semibold text-white transition peer-checked:border-amber-400/80 peer-checked:bg-amber-400/20 peer-checked:text-amber-100 cursor-pointer hover:bg-slate-800">
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

        {{-- Step 2: Seat Selection --}}
        <fieldset class="min-w-0 hidden" id="{{ $seatPickerId }}">
            <legend class="sr-only">Seleção de cadeiras</legend>

            <div class="mb-4 flex items-center justify-between">
                <button
                    type="button"
                    class="flex items-center gap-2 text-sm text-amber-400 hover:text-amber-300 transition"
                    data-back-to-step-1
                >
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Voltar
                </button>
                <p class="text-sm text-slate-400">
                    Sessão selecionada: <span class="text-white font-semibold" id="selected-session-display"></span>
                </p>
            </div>

            {{-- Screen Indicator --}}
            <div class="mb-8 text-center">
                <div class="mx-auto w-3/4 h-2 rounded-t-full bg-gradient-to-r from-slate-700 via-slate-500 to-slate-700 shadow-lg shadow-slate-500/20"></div>
                <p class="mt-2 text-xs uppercase tracking-widest text-slate-500">Tela</p>
            </div>

            {{-- Seats Grid --}}
            <div class="flex flex-col items-center gap-3">
                @foreach($rows as $rowLetter)
                    <div class="flex items-center gap-2">
                        <span class="w-6 text-center text-sm font-bold text-slate-500">{{ $rowLetter }}</span>
                        <div class="flex gap-2">
                            @for($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++)
                                @php
                                    $seatId = $rowLetter . '-' . $seatNum;
                                @endphp
                                <label
                                    class="relative cursor-pointer"
                                    data-seat-label="{{ $seatId }}"
                                >
                                    <input
                                        type="checkbox"
                                        name="seats[]"
                                        value="{{ $seatId }}"
                                        class="peer sr-only"
                                        data-seat-checkbox
                                        data-seat-id="{{ $seatId }}"
                                    >
                                    <span
                                        class="seat-display flex h-8 w-8 items-center justify-center rounded-md text-xs font-semibold transition-all duration-200 transform hover:scale-110 bg-emerald-500/80 text-emerald-100 peer-checked:bg-amber-500 peer-checked:text-slate-950 peer-checked:scale-110 peer-checked:ring-2 peer-checked:ring-amber-300"
                                        title="Cadeira {{ $seatId }}"
                                    >
                                        {{ $seatNum }}
                                    </span>
                                </label>
                            @endfor
                        </div>
                        <span class="w-6 text-center text-sm font-bold text-slate-500">{{ $rowLetter }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Seat Legend --}}
            <div class="mt-6 flex justify-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded bg-emerald-500/80"></span>
                    <span class="text-xs text-slate-400">Disponível</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded bg-amber-500"></span>
                    <span class="text-xs text-slate-400">Selecionada</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded bg-rose-500/80"></span>
                    <span class="text-xs text-slate-400">Reservada</span>
                </div>
            </div>

            {{-- Selected Seats Summary --}}
            <div class="mt-6 rounded-xl border border-white/10 bg-slate-950/40 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-400">
                        Cadeiras selecionadas: <span class="text-white font-bold" id="selected-seats-count">0</span>
                    </p>
                    <p class="text-sm text-slate-400" id="selected-seats-list"></p>
                </div>
            </div>
        </fieldset>

        @error('seats')
            <p class="text-sm text-rose-300">{{ $message }}</p>
        @enderror

        <button
            type="button"
            id="continue-to-seats-btn"
            class="w-full rounded-2xl bg-amber-500 px-5 py-3 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/40 transition hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
        >
            Continuar para escolha de assentos
        </button>

        <button
            type="button"
            id="reserve-btn"
            data-modal-target="{{ $modalId }}"
            data-form-id="{{ $formId }}"
            class="hidden w-full rounded-2xl bg-amber-500 px-5 py-3 text-base font-bold uppercase tracking-wide text-slate-950 shadow-lg shadow-amber-500/40 transition hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
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
            <p class="text-slate-400 mb-4 leading-relaxed">
                A tela de pagamento é apenas uma simulação! Os campos podem ser preenchidos com dados aleatórios.
            </p>

            {{-- Show selected seats in modal --}}
            <div class="mb-6 rounded-xl bg-slate-950/60 p-4 text-left">
                <p class="text-xs uppercase tracking-wider text-slate-500 mb-2">Resumo da reserva</p>
                <p class="text-sm text-slate-400">
                    Sessão: <span class="text-white font-semibold" id="modal-session-display"></span>
                </p>
                <p class="text-sm text-slate-400">
                    Cadeiras: <span class="text-amber-400 font-semibold" id="modal-seats-display"></span>
                </p>
            </div>

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
        const seatPickerId = '{{ $seatPickerId }}';
        const formId = '{{ $formId }}';
        const modalId = '{{ $modalId }}';
        const reservedSeatsUrl = '{{ $reservedSeatsUrl }}';

        // Elements
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById(seatPickerId);
        const step1Indicator = document.getElementById('step-1-indicator');
        const step2Indicator = document.getElementById('step-2-indicator');
        const continueBtn = document.getElementById('continue-to-seats-btn');
        const reserveBtn = document.getElementById('reserve-btn');
        const backBtn = document.querySelector('[data-back-to-step-1]');
        const sessionRadios = document.querySelectorAll('[data-session-radio]');
        const seatCheckboxes = document.querySelectorAll('[data-seat-checkbox]');
        const selectedSeatsCount = document.getElementById('selected-seats-count');
        const selectedSeatsList = document.getElementById('selected-seats-list');
        const selectedSessionDisplay = document.getElementById('selected-session-display');
        const modalSessionDisplay = document.getElementById('modal-session-display');
        const modalSeatsDisplay = document.getElementById('modal-seats-display');

        let currentStep = 1;
        let selectedSeats = [];
        let reservedSeats = [];

        // Reset all seats to available state
        function resetSeatsToAvailable() {
            seatCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = false;
                checkbox.checked = false;
                const label = checkbox.closest('[data-seat-label]');
                const display = label.querySelector('.seat-display');
                display.classList.remove('bg-rose-500/80', 'text-rose-200', 'cursor-not-allowed');
                display.classList.add('bg-emerald-500/80', 'text-emerald-100');
                display.title = 'Cadeira ' + checkbox.dataset.seatId;
            });
            selectedSeats = [];
            updateSeatSelection();
        }

        // Mark seats as reserved based on API response
        function markSeatsAsReserved(reservedSeatIds) {
            reservedSeats = reservedSeatIds;
            reservedSeatIds.forEach(function(seatId) {
                const checkbox = document.querySelector('[data-seat-id="' + seatId + '"]');
                if (checkbox) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    const label = checkbox.closest('[data-seat-label]');
                    const display = label.querySelector('.seat-display');
                    display.classList.remove('bg-emerald-500/80', 'text-emerald-100');
                    display.classList.add('bg-rose-500/80', 'text-rose-200', 'cursor-not-allowed');
                    display.title = 'Cadeira reservada';
                }
            });
        }

        // Fetch reserved seats from the server
        async function fetchReservedSeats(sessionDatetime) {
            try {
                // The sessionDatetime is in format 'YYYY-MM-DDTHH:mm'
                // We need to encode it properly for the URL
                const url = new URL(reservedSeatsUrl, window.location.origin);
                url.searchParams.set('session', sessionDatetime);

                console.log('Fetching reserved seats for session:', sessionDatetime);
                console.log('URL:', url.toString());

                const response = await fetch(url.toString());
                const data = await response.json();
                console.log('Reserved seats response:', data);
                return data.reserved_seats || [];
            } catch (error) {
                console.error('Erro ao buscar cadeiras reservadas:', error);
                return [];
            }
        }

        // Step navigation functions
        function goToStep(step) {
            currentStep = step;

            if (step === 1) {
                step1Content.classList.remove('hidden');
                step2Content.classList.add('hidden');
                continueBtn.classList.remove('hidden');
                reserveBtn.classList.add('hidden');

                step1Indicator.classList.remove('opacity-50');
                step1Indicator.querySelector('span:first-child').classList.remove('bg-slate-700');
                step1Indicator.querySelector('span:first-child').classList.add('bg-amber-500');
                step1Indicator.querySelector('span:last-child').classList.remove('text-slate-400');
                step1Indicator.querySelector('span:last-child').classList.add('text-amber-400');

                step2Indicator.classList.add('opacity-50');
                step2Indicator.querySelector('span:first-child').classList.remove('bg-amber-500');
                step2Indicator.querySelector('span:first-child').classList.add('bg-slate-700');
                step2Indicator.querySelector('span:last-child').classList.remove('text-amber-400');
                step2Indicator.querySelector('span:last-child').classList.add('text-slate-400');
            } else {
                step1Content.classList.add('hidden');
                step2Content.classList.remove('hidden');
                continueBtn.classList.add('hidden');
                reserveBtn.classList.remove('hidden');

                step1Indicator.classList.add('opacity-50');
                step1Indicator.querySelector('span:first-child').classList.remove('bg-amber-500');
                step1Indicator.querySelector('span:first-child').classList.add('bg-emerald-500');
                step1Indicator.querySelector('span:first-child').innerHTML = '✓';
                step1Indicator.querySelector('span:last-child').classList.remove('text-amber-400');
                step1Indicator.querySelector('span:last-child').classList.add('text-emerald-400');

                step2Indicator.classList.remove('opacity-50');
                step2Indicator.querySelector('span:first-child').classList.remove('bg-slate-700');
                step2Indicator.querySelector('span:first-child').classList.add('bg-amber-500');
                step2Indicator.querySelector('span:last-child').classList.remove('text-slate-400');
                step2Indicator.querySelector('span:last-child').classList.add('text-amber-400');

                // Update session display
                const selectedSession = document.querySelector('[data-session-radio]:checked');
                if (selectedSession) {
                    const sessionDate = new Date(selectedSession.value);
                    const options = { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' };
                    selectedSessionDisplay.textContent = sessionDate.toLocaleDateString('pt-BR', options);
                }
            }
        }

        // Continue button click
        continueBtn.addEventListener('click', async function() {
            const selectedSession = document.querySelector('[data-session-radio]:checked');
            if (!selectedSession) {
                alert('Por favor, selecione um horário de sessão.');
                return;
            }

            // Reset seats and fetch reserved ones from database
            resetSeatsToAvailable();
            continueBtn.disabled = true;
            continueBtn.textContent = 'Carregando...';

            const reservedSeatIds = await fetchReservedSeats(selectedSession.value);
            markSeatsAsReserved(reservedSeatIds);

            continueBtn.disabled = false;
            continueBtn.textContent = 'Continuar para escolha de assentos';

            goToStep(2);
        });

        // Back button click
        if (backBtn) {
            backBtn.addEventListener('click', function() {
                // Reset step 1 indicator
                step1Indicator.querySelector('span:first-child').innerHTML = '1';
                goToStep(1);
            });
        }

        // Update seat selection UI
        function updateSeatSelection() {
            selectedSeats = [];
            seatCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    selectedSeats.push(checkbox.dataset.seatId);
                }
            });

            selectedSeatsCount.textContent = selectedSeats.length;
            selectedSeatsList.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '';
        }

        // Seat checkbox change
        seatCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateSeatSelection);
        });

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
                    document.body.style.overflow = 'hidden';

                    // Update modal displays
                    const selectedSession = document.querySelector('[data-session-radio]:checked');
                    if (selectedSession) {
                        const sessionDate = new Date(selectedSession.value);
                        const options = { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' };
                        modalSessionDisplay.textContent = sessionDate.toLocaleDateString('pt-BR', options);
                    }
                    modalSeatsDisplay.textContent = selectedSeats.join(', ') || 'Nenhuma';
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            }
        }

        modalTriggers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Check if at least one seat is selected
                if (selectedSeats.length === 0) {
                    alert('Por favor, selecione pelo menos uma cadeira.');
                    return;
                }

                toggleModal(btn.getAttribute('data-modal-target'), true);
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
