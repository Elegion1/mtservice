<div>

    @if ($isHome)
        <div id="form-top" class="d-flex justify-content-start ">
            @foreach (['transfer' => 'ui.transfer', 'escursioni' => 'ui.excursions', 'rent' => 'ui.carRent'] as $formType => $label)
                <button type="button"
                    class="btn btn_booking text-uppercase text-black {{ $currentForm == $formType ? 'bg-b' : 'bg-c' }}"
                    wire:click="show{{ ucfirst($formType) }}">
                    {{ __($label) }}
                </button>
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
            @if ($currentForm == 'escursioni')
                
                @livewire('escursioni-form')
            @elseif ($currentForm == 'transfer')
                @livewire('transfer-form')
            @elseif ($currentForm == 'rent')
                @livewire('car-rent')
            @elseif ($currentForm == 'bookingSummary')
                @livewire('booking-summary', ['bookingData' => $bookingData])
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
