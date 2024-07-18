<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Excursion;

class EscursioniForm extends Component
{
    public $excursionSelect;
    public $excursionPassengers = 1;
    public $excursionPrice = 0;
    public $excursionDate;

    protected $rules = [
        'excursionSelect' => 'required|exists:excursions,id',
        'excursionPassengers' => 'required|integer|min:1|max:16',
        'excursionDate' => 'required|date|after_or_equal:today',
    ];

    protected $messages = [
        'excursionSelect.required' => 'Selezionare una escursione è obbligatorio.',
        'excursionSelect.exists' => 'L\'escursione selezionata non è valida.',
        'excursionPassengers.required' => 'Il numero di passeggeri è obbligatorio.',
        'excursionPassengers.integer' => 'Il numero di passeggeri deve essere un numero intero.',
        'excursionPassengers.min' => 'Il numero minimo di passeggeri è 1.',
        'excursionPassengers.max' => 'Il numero massimo di passeggeri è 16.',
        'excursionDate.required' => 'La data di partenza è obbligatoria.',
        'excursionDate.date' => 'La data di partenza deve essere una data valida.',
        'excursionDate.after_or_equal' => 'La data di partenza non può essere nel passato.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);

        if ($field === 'excursionSelect' || $field === 'excursionPassengers' || $field === 'excursionDate') {
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

    public function getBookingDataExcursion()
    {
        return [
            'type' => 'escursione', // Assuming 'transfer' for transfer bookings
            'departure_id' => $this->excursionSelect, 
            'passengers' => $this->excursionPassengers,
            'date_dep' => $this->excursionDate, // Assuming you have a dateDeparture property
            'price' => $this->excursionPrice,
        ];
    }

    public function submitBookingExcursion()
    {
        $this->validate();

        $bookingData = $this->getBookingDataExcursion();
        $departureName = Excursion::find($bookingData['departure_id'])->name_it;
        $duration = Excursion::find($bookingData['departure_id'])->duration;
        $bookingData['duration'] = $duration;
        $bookingData['departure_name'] = $departureName;

        // Formattare la data di partenza
        $departureDate = date('D d F Y', strtotime($bookingData['date_dep']));
        $bookingData['date_departure'] = $departureDate;

        $departureTime = date('H:i', strtotime($bookingData['date_dep']));
        $bookingData['time_departure'] = $departureTime;

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $excursions = Excursion::all();
        return view('livewire.escursioni-form', compact('excursions'));
    }
}
