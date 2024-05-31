<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Excursion;

class EscursioniForm extends Component
{
    public $excursionSelect;
    public $excursionPassengers = 1;
    public $excursionPrice = 0;

    public function updated($field)
    {
        if ($field === 'excursionSelect' || $field === 'excursionPassengers') {
            $this->calculatePriceExcursion();
        }
    }

    public function calculatePriceExcursion()
    {
        if ($this->excursionSelect && $this->excursionPassengers) {
            $excursion = Excursion::find($this->excursionSelect);
            if ($excursion) {
                $basePrice = $excursion->price;
                $incrementPrice = $excursion->price_increment;
                $passengers = $this->excursionPassengers;

                if ($passengers <= 4) {
                    $totalPrice = $basePrice;
                } elseif ($passengers <= 8) {
                    $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);
                } elseif ($passengers >= 9 && $passengers <= 12) {
                    $totalPrice = ($basePrice * 2) + $incrementPrice * 4;
                } elseif ($passengers > 12 && $passengers <= 16) {
                    $totalPrice = ($basePrice * 2) + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);
                }

                $this->excursionPrice = $totalPrice;
            } else {
                $this->excursionPrice = 0;
            }
        } else {
            $this->excursionPrice = 0;
        }
    }

    public function render()
    {
        $excursions = Excursion::all();
        return view('livewire.escursioni-form', compact('excursions'));
    }
}
