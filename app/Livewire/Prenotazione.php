<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Prenotazione extends Component
{
    public $currentForm = 'transfer';
    public $bookingData = []; // Inizializza come array vuoto

    public function mount()
    {
        if (Route::currentRouteName() == 'noleggio') {
            $this->showRent();
        }
        if (Route::currentRouteName() == 'transfer') {
            $this->showTransfer();
        }
        if (Route::currentRouteName() == 'escursioni') {
            $this->showEscursioni();
        }
    }

    public function showEscursioni()
    {
        $this->currentForm = 'escursioni';
    }

    public function showTransfer()
    {
        $this->currentForm = 'transfer';
    }

    public function showRent()
    {
        $this->currentForm = 'noleggio';
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
