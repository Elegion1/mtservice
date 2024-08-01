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

    public function rules()
    {
        return [
            'excursionSelect' => 'required|exists:excursions,id',
            'excursionPassengers' => 'required|integer|min:1|max:16',
            'excursionDate' => 'required|date|after_or_equal:today',
        ];
    }
    public function messages()
    {
        return [
            'excursionSelect.required' => __('ui.excursionSelect_required'),
            'excursionSelect.exists' => __('ui.excursionSelect_exists'),
            'excursionPassengers.required' => __('ui.excursionPassengers_required'),
            'excursionPassengers.integer' => __('ui.excursionPassengers_integer'),
            'excursionPassengers.min' => __('ui.excursionPassengers_min'),
            'excursionPassengers.max' => __('ui.excursionPassengers_max'),
            'excursionDate.required' => __('ui.excursionDate_required'),
            'excursionDate.date' => __('ui.excursionDate_date'),
            'excursionDate.after_or_equal' => __('ui.excursionDate_after_or_equal'),
        ];
    }

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
