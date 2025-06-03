<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Excursion;

class EscursioniForm extends Component
{
    public $excursionSelect;
    public $excursionPassengers = 1;
    public $excursionPrice;
    public $excursionDate;
    public $excursionTime;

    public function rules()
    {
        return [
            'excursionSelect' => 'required|exists:excursions,id',
            'excursionPassengers' => 'required|integer|min:1|max:16',
            'excursionDate' => 'required|date|after_or_equal:today',
            'excursionTime' => 'required',
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
            'excursionTime.required' => __('ui.excursionTime_required'),
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);

        if ($field === 'excursionSelect' || $field === 'excursionPassengers' || $field === 'excursionDate') {
            $this->calculatePriceExcursion();
        }
    }

    // Funzione per incrementare i passeggeri
    public function updatePassengers($change)
    {
        passengerNumber($change, $this->excursionPassengers, [$this, 'calculatePriceExcursion']);
    }

    public function calculatePriceExcursion()
    {
        if (!$this->excursionSelect || !$this->excursionPassengers) {
            $this->excursionPrice = 0;
            return;
        }

        $excursion = Excursion::find($this->excursionSelect);

        if (!$excursion) {
            $this->excursionPrice = 0;
            return;
        }

        $this->excursionPrice = calculateBasePrice($excursion->price, $excursion->price_increment, $this->excursionPassengers, $excursion->increment_passengers);
    }

    public function getBookingDataExcursion()
    {
        // Combina data e ora di partenza in un unico datetime
        $dateTimeDeparture = combineDateAndTime($this->excursionDate, $this->excursionTime);

        return [
            'type' => 'escursione',
            'departure_id' => $this->excursionSelect,
            'passengers' => $this->excursionPassengers,
            'date_dep' => $dateTimeDeparture,
            'price' => $this->excursionPrice,
        ];
    }

    public function submitBookingExcursion()
    {
        $this->validate();

        $bookingData = $this->getBookingDataExcursion();
        $excursion = Excursion::find($bookingData['departure_id']);
        $bookingData['departure_name'] = $excursion->name_it;
        $bookingData['duration'] = $excursion->duration;

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $excursions = Excursion::where('show', 1)->get();
        return view('livewire.escursioni-form', compact('excursions'));
    }
}
