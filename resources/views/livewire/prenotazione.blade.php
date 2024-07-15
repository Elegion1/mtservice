<div>
    <div class="container">
        <div class="row p-2">
            @if ($isHome)
                <div class="col-12 d-flex justify-content-center align-items-center p-md-2">
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'escursioni') bg-b text-white 
                        @elseif ($currentForm == 'transfer') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showTransfer">{{__('ui.transfer')}}</button>
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'transfer') bg-b text-white 
                        @elseif ($currentForm == 'escursioni') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showEscursioni">{{__('ui.excursions')}}</button>
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'transfer') bg-b text-white 
                        @elseif ($currentForm == 'escursioni') bg-b text-white 
                        @elseif ($currentForm == 'noleggio') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showRent">{{__('ui.carRent')}}</button>
                </div>
            @endif

            <div class="col-12">
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
