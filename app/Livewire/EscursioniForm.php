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
    public function incrementPassengers()
    {
        if ($this->excursionPassengers < 16) {
            $this->excursionPassengers++;
            $this->calculatePriceExcursion();   
        }
    }

    // Funzione per decrementare i passeggeri
    public function decrementPassengers()
    {
        if ($this->excursionPassengers > 1) {
            $this->excursionPassengers--;
            $this->calculatePriceExcursion();
        }
    }

    public function calculatePriceExcursion()
    {
        // Se non è stata selezionata un'escursione o se il numero di passeggeri è zero, imposta il prezzo a 0
        if (!$this->excursionSelect || !$this->excursionPassengers) {
            $this->excursionPrice = 0;
            return;
        }

        // Recupera l'escursione selezionata
        $excursion = Excursion::find($this->excursionSelect);

        // Se l'escursione non esiste, imposta il prezzo a 0
        if (!$excursion) {
            $this->excursionPrice = 0;
            return;
        }

        // Prezzo di base e incremento per passeggero extra
        $basePrice = $excursion->price;
        $incrementPrice = $excursion->price_increment;
        $passengers = $this->excursionPassengers;
        
        // Calcolo del prezzo in base al numero di passeggeri
        if ($passengers <= 4) {
            $totalPrice = $basePrice;  // Prezzo base per fino a 4 passeggeri
        } elseif ($passengers <= 8) {
            $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);  // Prezzo base + incremento per i passeggeri sopra 4
        } elseif ($passengers <= 12) {
            $totalPrice = $basePrice + $incrementPrice * 4;  // Prezzo base + incremento per i 4 passeggeri extra (fino a 8)
        } else {
            $totalPrice = $basePrice + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);  // Prezzo base + incremento per i passeggeri sopra 12
        }

        // Assegna il prezzo totale calcolato
        $this->excursionPrice = $totalPrice;
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
