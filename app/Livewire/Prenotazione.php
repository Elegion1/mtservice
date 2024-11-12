<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Route;

class Prenotazione extends Component
{
    #[Url]
    public $module = '';
    public $currentForm;
    public $bookingData = []; // Inizializza come array vuoto
    public $isHome = false; // Variabile per determinare se siamo nella home

    // public $bookingData = [
    //     "type" => "escursione",
    //     "price" => "150",
    //     "date_dep" => "2024-11-03T11:09",
    //     "duration" => "1",
    //     "passengers" => 1,
    //     "departure_id" => "2",
    //     "date_departure" => "Sun 03 November 2024",
    //     "departure_name" => "Marsala",
    //     "original_price" => "150",
    //     "time_departure" => "11:09",
    // ];

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
        } elseif ($route == 'home') {
            $this->showTransfer(); // Default
            // $this->showBookingSummary($this->bookingData);
        } elseif ($route == 'prezziDestinazioni') {
            $this->showTransfer();
        }
    }

    public function showEscursioni()
    {
        $this->currentForm = 'escursioni';
        $this->module = 'excursions';
    }

    public function showTransfer()
    {
        $this->currentForm = 'transfer';
        $this->module = 'transfer';
    }

    public function showRent()
    {
        $this->currentForm = 'rent';
        $this->module = 'carRent';
    }

    public function render()
    {
        return view('livewire.prenotazione', [
            'bookingData' => $this->bookingData,
            'module' => $this->module,
        ]);
    }

    protected $listeners = [
        'bookingSubmitted' => 'showBookingSummary',
    ];

    public function showBookingSummary($bookingData)
    {
        $this->bookingData = $bookingData;
        $this->currentForm = 'bookingSummary'; // Passa al modulo di riepilogo prenotazione
        $this->module = 'bookingSummary';
    }
}
