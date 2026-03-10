<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Prenotazione extends Component
{
    #[Url]
    public $module = '';

    #[Locked]
    public $currentForm;

    #[Locked]
    public $bookingData = []; // Inizializza come array vuoto

    #[Locked]
    public $isHome = false; // Variabile per determinare se siamo nella home

    public $currentStep = 1; // Step iniziale

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

    private array $formMap = [
        'home' => ['form' => 'transfer', 'module' => 'transfer'],
        'noleggio' => ['form' => 'rent', 'module' => 'carRent'],
        'transfer' => ['form' => 'transfer', 'module' => 'transfer'],
        'prezziDestinazioni' => ['form' => 'transfer', 'module' => 'transfer'],
        'escursioni' => ['form' => 'escursioni', 'module' => 'excursions'],
    ];

    public function mount()
    {
        $route = Route::currentRouteName();
        $this->isHome = ! array_key_exists($route, $this->formMap) || $route === 'home';

        $formData = $this->formMap[$route] ?? $this->formMap['home'];

        $this->currentForm = $formData['form'];
        $this->module = $formData['module'];

        // Dispatch initial state so surrounding JS/Blade can pick it up
        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);

        Log::info("[Prenotazione] Loaded route {$route} → form {$this->currentForm}");
    }

    public function showEscursioni()
    {
        $this->currentForm = 'escursioni';
        $this->module = 'excursions';
        $this->currentStep = 1; // Reset step quando cambia form

        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);
    }

    public function showTransfer()
    {
        $this->currentForm = 'transfer';
        $this->module = 'transfer';
        $this->currentStep = 1; // Reset step quando cambia form

        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);
    }

    public function showRent()
    {
        $this->currentForm = 'rent';
        $this->module = 'carRent';
        $this->currentStep = 1; // Reset step quando cambia form

        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);
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
        $this->currentForm = 'bookingSummary';
        $this->module = 'bookingSummary';

        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);

        Log::info('[LivewirePrenotazione] User entered Booking Summary...');
    }

    public function dispatchData($data)
    {
        Log::info('Emitting populateForm event with data: '.json_encode($data));
        $this->dispatch('populateForm', data: $data);
    }

    #[On('stepChanged')]
    public function updateCurrentStep($step)
    {
        $this->currentStep = $step;

        $this->dispatch('prenotazione-state', currentForm: $this->currentForm, currentStep: $this->currentStep);
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
