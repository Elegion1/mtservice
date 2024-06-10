<div>
    <div class="container @if ($isHome) my-3 d-flex justify-content-center align-items-center @endif">
        <div class="row @if ($isHome) rounded-5 border @endif p-2">
            @if ($isHome)
                <div class="col-12 d-flex justify-content-center align-items-center p-2">
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'escursioni') bg-b text-white 
                        @elseif ($currentForm == 'transfer') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showTransfer">Prenota Transfer</button>
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'transfer') bg-b text-white 
                        @elseif ($currentForm == 'escursioni') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showEscursioni">Prenota Escursioni</button>
                    <button type="button" 
                        class=" m-1 btn
                        @if ($currentForm == 'transfer') bg-b text-white 
                        @elseif ($currentForm == 'escursioni') bg-b text-white 
                        @elseif ($currentForm == 'noleggio') bg-a text-white 
                        @else bg-b text-white @endif"
                        wire:click="showRent">Noleggio Auto</button>
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
