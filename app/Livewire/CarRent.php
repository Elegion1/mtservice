<?php

namespace App\Livewire;

use DateTime;
use App\Models\Car;
use Livewire\Component;

class CarRent extends Component
{
    public $dateStart;
    public $dateEnd;
    public $quantity = 1;
    public $carID;
    public $rentPrice;

    public function updated($field)
    {
        if ($field === 'dateStart' || $field === 'dateEnd' || $field === 'quantity' || $field === 'carID') {
            $this->calculatePriceRent();
        }
    }

    public function calculatePriceRent()
    {
        // Controlla se le date di inizio e fine del noleggio sono state fornite
        if ($this->dateStart && $this->dateEnd && $this->carID && $this->quantity) {
            // Converti le date in oggetti DateTime per facilitare il calcolo
            $startDate = new DateTime($this->dateStart);
            $endDate = new DateTime($this->dateEnd);

            // Calcola la differenza di giorni tra la data di inizio e la data di fine
            $rentInterval = $startDate->diff($endDate);
            $rentDays = $rentInterval->days;

            // Recupera l'auto selezionata
            $car = Car::find($this->carID);

            // Controlla se l'auto è stata trovata e ha un prezzo
            if ($car && $car->price) {
                // Calcola il prezzo totale del noleggio
                $totalPrice = $car->price * $rentDays * $this->quantity;

                // Imposta il prezzo totale nell'input
                $this->rentPrice = $totalPrice;
            } else {
                // Se l'auto non è stata trovata o non ha un prezzo, reimposta il prezzo totale a zero
                $this->rentPrice = 0;
            }
        } else {
            // Se mancano informazioni necessarie, reimposta il prezzo totale a zero
            $this->rentPrice = 0;
        }
    }

    public function getBookingDataRent()
    {
        return [
            'type' => 'noleggio',
            'date_start' => $this->dateStart,
            'date_end' => $this->dateEnd,
            'quantity' => $this->quantity,
            'car_ID' => $this->carID,
            'price' => $this->rentPrice,
        ];
    }

    public function submitBookingRent()
    {
        $bookingData = $this->getBookingDataRent();
        $carName = Car::find($bookingData['car_ID'])->name;
        $bookingData['car_name'] = $carName;

        // Formattare la data di ritiro
        $startDate = date('D d F Y', strtotime($bookingData['date_start']));
        $bookingData['start_date'] = $startDate;

        // Formattare la data di consegna
        $endDate = date('D d F Y', strtotime($bookingData['date_end']));
        $bookingData['end_date'] = $endDate;


        $this->dispatch('bookingSubmitted', $bookingData);
    }


    public function render()
    {
        $cars = Car::all();

        return view('livewire.car-rent', compact('cars'));
    }
}
