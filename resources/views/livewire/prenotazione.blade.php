<div>
    <div class="container my-3 d-flex justify-content-center align-items-center">
        <button type="button" class="btn 
        @if ($currentForm == 'escursioni') 
            bg-b text-white
        @elseif ($currentForm == 'transfer')
            bg-a text-white
        @endif" wire:click="showTransfer">Prenota Transfer</button>

        <button type="button" class="btn 
        @if ($currentForm == 'transfer') 
            bg-b text-white
        @elseif ($currentForm == 'escursioni')
            bg-a text-white
        @endif" wire:click="showEscursioni">Prenota Escursioni</button>
    </div>

    @if ($currentForm == 'escursioni')
        @livewire('escursioni-form')
    @elseif ($currentForm == 'transfer')
        @livewire('transfer-form')
    @elseif ($currentForm == 'bookingSummary')
        @livewire('booking-summary', ['bookingData' => $bookingData])
    @endif
</div>
