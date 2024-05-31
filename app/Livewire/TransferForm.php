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

    public function mount()
    {
        $this->calculatePrice();
    }


    public function updated($field)
    {
        if ($field === 'return' || $field === 'transferPassengers' || $field === 'solaAndata' || $field === 'andataRitorno') {
            $this->calculatePrice();
        }
    }

    // public function updated($field)
    // {
    //     if (in_array($field, ['return', 'transferPassengers', 'solaAndata', 'andataRitorno'])) {
    //         $this->calculatePrice();
    //     }
    // }

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

            // $totalPrice = $basePrice;

            // if ($passengers > 4 && $passengers <= 8) {
            //     $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);
            // }

            // if ($passengers >= 9 ) {
            //     $totalPrice = $basePrice + $incrementPrice * ($passengers - 8);
            //     $totalPrice = $totalPrice * 2;
            // }

            // if ($passengers >= 12) {
            //     $totalPrice = $totalPrice + $incrementPrice * ($passengers - 8);
            // }

            if ($passengers <= 4) {
                $totalPrice = $basePrice;
            } elseif ($passengers <= 8) {
                $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);
            } elseif ($passengers >= 9 && $passengers <= 12 ) {
                $totalPrice = ($basePrice*2) + $incrementPrice * 4;
            } elseif ($passengers > 12 || $passengers <=16) {
                $totalPrice = ($basePrice*2) + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);
            }

            if ($this->andataRitorno) {
                $totalPrice *= 2;
            }


            $this->transferPrice = $totalPrice;
        } else {
            $this->transferPrice = 0;
        }
    }

    public function render()
    {
        $destinations = Destination::all();
        $routes = Route::with(['departure', 'arrival'])->get();
        return view('livewire.transfer-form', compact('destinations', 'routes'));
    }
}
