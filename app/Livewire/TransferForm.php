<?php

namespace App\Livewire;

use App\Models\Route;
use Livewire\Component;
use App\Models\Destination;

class TransferForm extends Component
{
    public $departure;
    public $return;
    public $transferPassengers = 1;
    public $transferPrice;
    public $solaAndata = true;
    public $andataRitorno = false;
    public $dateDeparture;
    public $dateReturn;

    public function mount()
    {
        $this->calculatePrice();
    }


    public function updated($field)
    {
        if ($field === 'departure' || $field === 'return' || $field === 'transferPassengers' || $field === 'solaAndata' || $field === 'andataRitorno') {
            $this->calculatePrice();
        }
    }

    public function setSolaAndata()
    {
        $this->solaAndata = true;
        $this->andataRitorno = false;
        $this->calculatePrice();
    }

    public function setAndataRitorno()
    {
        $this->solaAndata = false;
        $this->andataRitorno = true;
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->departure && $this->return) {
            // Cerca la route basandosi su departure_id e arrival_id
            $route = Route::where('departure_id', $this->departure)
                ->where('arrival_id', $this->return)
                ->first();

            if (!$route) {
                // Route non trovata
                $this->transferPrice = 0;
                return;
            }

            $basePrice = $route->price;
            $incrementPrice = $route->price_increment;
            $passengers = $this->transferPassengers;

            if ($passengers <= 4) {
                $totalPrice = $basePrice;
            } elseif ($passengers <= 8) {
                $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);
            } elseif ($passengers >= 9 && $passengers <= 12) {
                $totalPrice = ($basePrice * 2) + $incrementPrice * 4;
            } elseif ($passengers > 12 || $passengers <= 16) {
                $totalPrice = ($basePrice * 2) + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);
            }

            if ($this->andataRitorno) {
                $totalPrice *= 2;
            }


            $this->transferPrice = $totalPrice;
        } else {
            $this->transferPrice = 0;
        }
    }

    public function getBookingDataTransfer()
    {
        return [
            'type' => 'transfer', // Assuming 'transfer' for transfer bookings
            'departure_id' => $this->departure,
            'arrival_id' => $this->return,
            'passengers' => $this->transferPassengers,
            'sola_andata' => $this->solaAndata,
            'date_dep' => $this->dateDeparture, // Assuming you have a dateDeparture property
            'date_ret' => $this->dateReturn,  // Assuming you have a dateReturn property (if applicable)
            'price' => $this->transferPrice,
            // Add other relevant booking details here
        ];
    }

    public function submitBookingTransfer()
    {
        $bookingData = $this->getBookingDataTransfer();
        $departureName = Destination::find($bookingData['departure_id'])->name;
        $arrivalName = Destination::find($bookingData['arrival_id'])->name;
        $bookingData['departure_name'] = $departureName;
        $bookingData['arrival_name'] = $arrivalName;
        $route = Route::where('departure_id', $this->departure)->where('arrival_id', $this->return)->first();
       
        $bookingData['duration'] = $route->duration;

        // Formattare la data di partenza
        $departureDate = date('D d F Y', strtotime($bookingData['date_dep']));
        $bookingData['date_departure'] = $departureDate;
        
        $departureTime = date('H:i', strtotime($bookingData['date_dep']));
        $bookingData['time_departure'] = $departureTime;
        
        // Se c'è una data di ritorno, formattarla
        if (!empty($bookingData['date_ret'])) {

            $returnDate = date('D d F Y', strtotime($bookingData['date_ret']));
            $bookingData['date_return'] = $returnDate;

            $returnTime = date('H:i', strtotime($bookingData['date_ret']));
            $bookingData['time_return'] = $returnTime;
        }

        $this->dispatch('bookingSubmitted', $bookingData);
    }


    public function render()
    {
        $destinations = Destination::all();
        $routes = Route::with(['departure', 'arrival'])->get();
        return view('livewire.transfer-form', compact('destinations', 'routes'));
    }
}
