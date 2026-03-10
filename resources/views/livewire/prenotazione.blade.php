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

    <div class=" form_width {{ $isHome ? 'rounded' : 'rounded' }} p-3">

        <span id="formData" data-currentForm="{{ $currentForm }}" data-currentStep="{{ $currentStep }}"></span>


        @if ($isHome)
            {{-- I tasti ora sono dentro il contenitore con larghezza fissa --}}
            <div id="form-top" class="d-flex flex-wrap" style="">
                @foreach ($forms as $type => $info)
                    @if ($type !== 'bookingSummary' || $isActiveSummary)
                        <button type="button" wire:click="show{{ ucfirst($type) }}" onclick="scrollToTarget('form-top')"
                            {{-- flex-fill fa sì che si dividano lo spazio equamente senza uscire --}}
                            class="btn btn_booking text-uppercase text-black flex-fill {{ $currentForm === $type ? 'bg-white border' : 'bg-c' }}">
                            <small>{{ __($info['label']) }}</small>
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="container-fluid input_width p-4 rounded-bottom bg-b shadow">
            @if (!$isHome)
                <p class="text-uppercase text-center bg-c text-dark rounded p-1">
                    {{ !$isActiveSummary ? __('ui.book') . ' ' : '' }}{{ __($forms[$currentForm]['label']) }}
                </p>
            @endif

            <livewire:dynamic-component :is="$forms[$currentForm]['component']" :bookingData="$isActiveSummary ? $bookingData : null" :key="'form-' . $currentForm" wire:init />
        </div>
    </div>
</div>


{{-- <script>
    /**
     * Rende la funzione disponibile globalmente.
     * Utile per altri componenti o chiamate dirette.
     */
    function  {
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
</script> --}}
