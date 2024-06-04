<?php

namespace App\Livewire;

use Livewire\Component;

class Prenotazione extends Component
{
    public $currentForm = 'transfer';
    public $bookingData = []; // Inizializza come array vuoto

    public function showEscursioni()
    {
        $this->currentForm = 'escursioni';
    }

    public function showTransfer()
    {
        $this->currentForm = 'transfer';
    }

    
    public function render()
    {
        return view('livewire.prenotazione', [
            'bookingData' => $this->bookingData,
        ]);
    }

    protected $listeners = [
        'bookingSubmitted' => 'showBookingSummary',
    ];

    public function showBookingSummary($bookingData)
    {
        $this->bookingData = $bookingData;
        $this->currentForm = 'bookingSummary'; // Passa al modulo di riepilogo prenotazione
    }
}
