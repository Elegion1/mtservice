<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log;
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
    //     "departure_name" => "Marsala",
    //     "original_price" => "150",
    // ];

    // public $bookingData = [
    //     'type' => 'transfer',
    //     'departure_name' => 'Roma Termini',
    //     'arrival_name' => 'Fiumicino Airport',
    //     'date_dep' => '2024-12-05 14:30:00',  // Data e ora di partenza
    //     'duration' => 45,  // Durata in minuti
    //     'date_ret' => '2024-12-05 16:15:00',  // Data e ora di ritorno
    //     'price' => 45,  // Prezzo in euro
    //     'passengers' => 1,
    // ];

    // public $bookingData = [
    //     'type' => 'noleggio',
    //     'car_name' => 'Fiat 500',
    //     'car_description' => 'Cabriolet, Blu',
    //     'date_start' => '2024-12-10 10:00:00',  // Data e ora di ritiro
    //     'date_end' => '2024-12-12 18:00:00',    // Data e ora di restituzione
    //     'quantity' => 1,  // Quantità (nella maggior parte dei casi sarà 1)
    //     'price' => 45,  // Prezzo in euro
    // ];

    public function mount()
    {
        $route = Route::currentRouteName();

        // Imposta isHome a false solo per noleggio, transfer o escursioni
        $this->isHome = !in_array($route, ['noleggio', 'transfer', 'escursioni']);

        // Imposta il modulo corrente in base alla rotta
        if ($route == 'noleggio') {
            $this->showRent();
        } elseif ($route == 'transfer') {
            $this->showTransfer();
        } elseif ($route == 'escursioni') {
            $this->showEscursioni();
        } elseif ($this->isHome) {
            $this->showTransfer(); // Default
            // $this->showBookingSummary($this->bookingData);
        } elseif ($route == 'prezziDestinazioni') {
            $this->showTransfer();
        }
        Log::info('[LivewirePrenotazione] User is choosing a service ' . $this->module . ' ' . json_encode(session()->all()));
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

    // protected $listeners = [
    //     'bookingSubmitted' => 'showBookingSummary',
    //     'goBack' => 'goBack',
    // ];
    
    #[On('bookingSubmitted')]
    public function showBookingSummary($bookingData)
    {
        $this->bookingData = $bookingData;
        $this->currentForm = 'bookingSummary'; // Passa al modulo di riepilogo prenotazione
        $this->module = 'bookingSummary';

        Log::info('[LivewirePrenotazione] User entered Booking Summary: ' . json_encode($bookingData));
    }

    public function dispatchData($data)
    {
        Log::info('Emitting populateForm event with data: ' . json_encode($data));
        $this->dispatch('populateForm', $data);
    }

    #[On('goBack')]
    public function goBack()
    {
        if (isset($this->bookingData['type'])) {
            switch ($this->bookingData['type']) {
                case 'escursione':
                    $this->showEscursioni();
                    $this->dispatchData($this->bookingData);
                    break;
                case 'transfer':
                    $this->showTransfer();
                    $this->dispatchData($this->bookingData);
                    break;
                case 'noleggio':
                    $this->showRent();
                    $this->dispatchData($this->bookingData);
                    break;
                default:
                    $this->showTransfer(); // Default, puoi personalizzare come preferisci
                    $this->dispatchData($this->bookingData);
            }
        }
    }
}
