<div>
    <div class="container-md">
        <div class="row p-lg-2">
            @if ($isHome)
                <div class="col-12 d-flex justify-content-center py-2">
                    @foreach (['transfer' => 'ui.transfer', 'escursioni' => 'ui.excursions', 'rent' => 'ui.carRent'] as $formType => $label)
                        <button type="button"
                            class="btn mx-1 btn_font_size text-uppercase text-white {{ $currentForm == $formType ? 'bg-a' : 'bg-d' }}"
                            wire:click="show{{ ucfirst($formType) }}">
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="col-12 mt-2" wire:model.live="module">
                @if ($currentForm == 'escursioni')
                    @livewire('escursioni-form')
                @elseif ($currentForm == 'transfer')
                    @livewire('transfer-form')
                @elseif ($currentForm == 'noleggio')
                    @livewire('car-rent')
                @elseif ($currentForm == 'bookingSummary')
                    @livewire('booking-summary', ['bookingData' => $bookingData])
                @endif
            </div>
        </div>
    </div>
</div>
