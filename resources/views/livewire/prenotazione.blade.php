<div>
    @php
        $formLabels = [
            'transfer' => 'ui.transfer',
            'escursioni' => 'ui.excursions',
            'rent' => 'ui.carRent',
            'bookingSummary' => 'ui.bookingSummary',
        ];
    @endphp

    @if ($isHome)
        <div id="form-top" class="d-flex justify-content-start ">
            @foreach ($formLabels as $formType => $label)
                @if ($formType !== 'bookingSummary')
                    <button type="button"
                        class="btn btn_booking text-uppercase text-black {{ $currentForm == $formType ? 'bg-b' : 'bg-c' }}"
                        wire:click="show{{ ucfirst($formType) }}" id="{{ $formType }}-btn">
                        {{ __($label) }}
                    </button>
                @endif
            @endforeach

            {{-- Bottone "riepilogo prenotazione" visibile solo quando il form attivo è bookingSummary --}}
            @if ($currentForm == 'bookingSummary')
                <button type="button" class="btn btn_booking btn_font_size text-uppercase text-black bg-b z-2">
                    {{ __('ui.bookingSummary') }}
                </button>
            @endif
        </div>
    @endif

    <div class="p-3 bg-b shadow form_width @if ($isHome) rounded-bottom rounded-end @else rounded @endif"
        wire:model.live="module">
        <div class="container-fluid input_width z-2">
            @if (!$isHome)
                <p class="text-uppercase text-center bg-c text-dark rounded p-1">
                    @if ($currentForm !== 'bookingSummary')
                        {{ __('ui.book') }}
                    @endif
                    {{ __($formLabels[$currentForm]) }}
                </p>
            @endif
            @if ($currentForm == 'escursioni')
                <livewire:escursioni-form lazy />
            @elseif ($currentForm == 'transfer')
                <livewire:transfer-form lazy />
            @elseif ($currentForm == 'rent')
                <livewire:car-rent lazy />
            @elseif ($currentForm == 'bookingSummary')
                <livewire:booking-summary :bookingData="$bookingData" lazy />
            @endif
        </div>
    </div>

    <script>
        function scrollToTop() {
            setTimeout(() => {
                const formTop = document.getElementById('form-top');
                if (formTop) {
                    formTop.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }, 100);
        }
    </script>
</div>
