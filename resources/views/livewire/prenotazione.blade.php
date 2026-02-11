<div>
    @php
        $forms = [
            'transfer' => ['component' => 'transfer-form', 'label' => 'ui.transfer'],
            'escursioni' => ['component' => 'escursioni-form', 'label' => 'ui.excursions'],
            'rent' => ['component' => 'car-rent', 'label' => 'ui.carRent'],
            'bookingSummary' => ['component' => 'booking-summary', 'label' => 'ui.bookingSummary'],
        ];
        $isActiveSummary = $currentForm === 'bookingSummary';
    @endphp

    @if ($isHome)
        <div id="form-top" class="d-flex justify-content-start">
            @foreach ($forms as $type => $info)
                @if ($type !== 'bookingSummary' || $isActiveSummary)
                    <button type="button" wire:click="show{{ ucfirst($type) }}" onclick="scrollToTop()"
                        {{-- Richiamo immediato al click --}}
                        class="btn btn_booking text-uppercase text-black {{ $currentForm === $type ? 'bg-b' : 'bg-c' }} {{ $isActiveSummary ? 'z-2' : '' }}">
                        {{ __($info['label']) }}
                    </button>
                @endif
            @endforeach
        </div>
    @endif

    <div class="p-3 bg-b shadow form_width {{ $isHome ? 'rounded-bottom' : 'rounded' }}">
        <div class="container-fluid input_width z-2">
            @if (!$isHome)
                <p class="text-uppercase text-center bg-c text-dark rounded p-1">
                    {{ !$isActiveSummary ? __('ui.book') . ' ' : '' }}{{ __($forms[$currentForm]['label']) }}
                </p>
            @endif

            <livewire:dynamic-component :is="$forms[$currentForm]['component']" :bookingData="$isActiveSummary ? $bookingData : null" :key="'form-' . $currentForm" wire:init />
        </div>
    </div>
</div>

{{-- Script fuori dal div principale per essere sicuri che sia caricato una volta sola --}}
<script>
    /**
     * Rende la funzione disponibile globalmente.
     * Utile per altri componenti o chiamate dirette.
     */
    function scrollToTop() {
        // Usiamo un piccolo delay per assicurarci che Livewire 
        // abbia finito di renderizzare il nuovo componente
        setTimeout(() => {
            const element = document.getElementById('form-top') || document.getElementById('mainContent');
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Fallback all'inizio della pagina se gli ID non esistono
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }, 50);
    };
</script>
