<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Prenotazione extends Component
{
    public $currentForm;
    public $bookingData = []; // Inizializza come array vuoto
    public $isHome = false; // Variabile per determinare se siamo nella home

    public function mount()
    {
        $route = Route::currentRouteName();
        
        // Controlla se la rotta corrente è 'home'
        $this->isHome = $route == 'home';

        // Imposta il modulo corrente in base alla rotta
        if ($route == 'noleggio') {
            $this->showRent();
        } elseif ($route == 'transfer') {
            $this->showTransfer();
        } elseif ($route == 'escursioni') {
            $this->showEscursioni();
        } else {
            $this->showTransfer(); // Default
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
