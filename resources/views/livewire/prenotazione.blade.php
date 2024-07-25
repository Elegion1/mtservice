<div>
    <div class="container-md">
        <div class="row p-lg-2">
            @if ($isHome)
                <div class="col-12 d-flex justify-content-center  py-2">
                    <button type="button" 
                        class="btn mx-1 btn_font_size
                        @if ($currentForm == 'escursioni') bg-d text-white 
                        @elseif ($currentForm == 'transfer') bg-a text-white 
                        @else bg-d text-white @endif"
                        wire:click="showTransfer">{{__('ui.transfer')}}</button>
                    <button type="button" 
                        class="btn mx-1 btn_font_size
                        @if ($currentForm == 'transfer') bg-d text-white 
                        @elseif ($currentForm == 'escursioni') bg-a text-white 
                        @else bg-d text-white @endif"
                        wire:click="showEscursioni">{{__('ui.excursions')}}</button>
                    <button type="button" 
                        class="btn mx-1 btn_font_size
                        @if ($currentForm == 'transfer') bg-d text-white 
                        @elseif ($currentForm == 'escursioni') bg-d text-white 
                        @elseif ($currentForm == 'noleggio') bg-a text-white 
                        @else bg-d text-white @endif"
                        wire:click="showRent">{{__('ui.carRent')}}</button>
                </div>
            @endif

            <div class="col-12 mt-2">
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
