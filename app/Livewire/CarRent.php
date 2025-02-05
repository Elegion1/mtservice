<?php

namespace App\Livewire;

use DateTime;
use Carbon\Carbon;
use App\Models\Car;
use App\Models\Booking;
use Livewire\Component;
use App\Models\CarPrice;
use Illuminate\Support\Facades\Log;

class CarRent extends Component
{
    public $dateStart;
    public $timeStart;
    public $dateEnd;
    public $timeEnd;
    public $quantity = 1;
    public $carID;
    public $rentPrice;

    public $currentStep = 1; // Step iniziale

    public function rules()
    {
        return [
            'dateStart' => 'required|date|after_or_equal:today',
            'timeStart' => 'required',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'timeEnd' => 'required',
            'quantity' => 'required|integer|min:1',
            'carID' => 'required|exists:cars,id',
        ];
    }

    public function messages()
    {
        return [
            'dateStart.required' => __('ui.dateStart_required'),
            'timeStart.required' => __('ui.timeStart_required'),
            'dateStart.date' => __('ui.dateStart_date'),
            'dateStart.after_or_equal' => __('ui.dateStart_after_or_equal'),
            'dateEnd.required' => __('ui.dateEnd_required'),
            'timeEnd.required' => __('ui.timeEnd_required'),
            'dateEnd.date' => __('ui.dateEnd_date'),
            'dateEnd.after_or_equal' => __('ui.dateEnd_after_or_equal'),
            'quantity.required' => __('ui.quantity_required'),
            'quantity.integer' => __('ui.quantity_integer'),
            'quantity.min' => __('ui.quantity_min'),
            'carID.required' => __('ui.carID_required'),
            'carID.exists' => __('ui.carID_exists'),
        ];
    }

    public function goToStep($step)
    {
        $this->currentStep = $step;
    }

    public function submitDateSelection()
    {
        $this->validate([
            'dateStart' => 'required|date',
            'timeStart' => 'required',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'timeEnd' => 'required',
        ]);

        $this->currentStep = 2; // Passa allo step successivo

        if ($this->carID) {
            $this->calculatePriceRent();
        }
    }

    public function updated($field)
    {
        if (in_array($field, ['dateStart', 'dateEnd', 'quantity', 'carID'])) {
            $this->validateOnly($field);
            $this->calculatePriceRent();
        }
    }

    public function calculatePriceRent()
    {
        $this->validate();

        $startDate = $this->convertDate($this->combineDateAndTime($this->dateStart, $this->timeStart));
        $endDate = $this->convertDate($this->combineDateAndTime($this->dateEnd, $this->timeEnd));

        Log::info('Start date: ' . $startDate);
        Log::info('End date: ' . $endDate);

        // Calcolare la differenza in ore tra dateStart e dateEnd
        $hours = $startDate->diffInHours($endDate);
        // $hours = ($interval->days * 24) + $interval->h + ($interval->i / 60) + ($interval->s / 3600);

        // Arrotonda sempre per eccesso
        $rentDays = ceil($hours / 24);

        $car = Car::find($this->carID);
        if (!$car) {
            $this->rentPrice = 0;
            return;
        }

        Log::info('Rent days: ' . $rentDays . ' - ' . $hours);

        // Recupera tutti i prezzi e periodi associati all'auto
        $carPrices = CarPrice::where('car_id', $this->carID)->get();
        $totalPrice = 0;
        $currentDate = clone $startDate;
        $remainingDays = $rentDays;

        Log::info('Remaining days: ' . $remainingDays);

        while ($remainingDays >= 0 && $currentDate <= $endDate) {
            $found = false;
            Log::info('Remaining days While: ' . $remainingDays);

            foreach ($carPrices as $carPrice) {
                $periodStart = $this->convertDate($carPrice->timePeriod->start);
                $periodEnd = $this->convertDate($carPrice->timePeriod->end);
                // dd($periodStart, $periodEnd, $currentDate, $startDate, $endDate);

                Log::info('Period: ' . $periodStart . ' - ' . $periodEnd);

                // Controlla se la data corrente è all'interno del periodo (con ore, minuti e secondi)
                if ($currentDate >= $periodStart && $currentDate <= $periodEnd) {
                    Log::info('Applying price for period: ' . $currentDate . ' - ' . $carPrice->price);
                    // Somma il prezzo per il periodo corrente
                    $totalPrice += $carPrice->price * $this->quantity;
                    if ($currentDate->toDateString() == $endDate->toDateString()) {

                        while ($currentDate->format('H:i:s') < $endDate->format('H:i:s')) {
                            $totalPrice += $carPrice->price * $this->quantity;
                            Log::info('Applying price for period: ' . $currentDate . ' - ' . $carPrice->price);
                            $diff = $currentDate->diffInHours($endDate);
                            if ($diff < 24) {
                                Log::info('Remaining hours: ' . $diff . ' - ' . $currentDate->format('H:i:s') . ' - ' . $endDate->format('H:i:s'));
                                $found = true;
                                break;
                            }
                        }
                    }
                    $found = true;
                    break;
                }
            }

            // Se il giorno non rientra in nessun periodo, usa il prezzo base dell'auto
            if (!$found && $car->price) {
                Log::info('Applying base price for: ' . $currentDate . ' - ' . $car->price);
                $totalPrice += $car->price * $this->quantity;
            }

            // Passa al giorno successivo
            $currentDate->addDay();
            $remainingDays--; // Decrementa il numero di giorni rimanenti
        }

        Log::info('Total price: ' . $totalPrice);

        $this->rentPrice = $totalPrice;
    }

    public function convertDate($date)
    {
        return Carbon::parse($date);
    }

    public function getBookingDataRent()
    {
        $dateTimeStart = $this->combineDateAndTime($this->dateStart, $this->timeStart);
        $dateTimeEnd = $this->combineDateAndTime($this->dateEnd, $this->timeEnd);
        return [
            'type' => 'noleggio',
            'date_start' => $dateTimeStart,
            'date_end' => $dateTimeEnd,
            'quantity' => $this->quantity,
            'car_ID' => $this->carID,
            'price' => $this->rentPrice,
        ];
    }

    protected function combineDateAndTime($date, $time)
    {
        if ($date && $time) {
            return "{$date}T{$time}";
        }
        return null;
    }

    public function submitBookingRent()
    {
        $this->calculatePriceRent();
        $this->validate();

        $bookingData = $this->getBookingDataRent();

        $car = Car::find($this->carID);
        if ($car) {
            $bookingData['car_name'] = $car->name;
            $bookingData['car_description'] = $car->description;
        }

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $cars = Car::where('show', 1)->get();

        $bookings = Booking::whereIn('status', ['confirmed', 'pending'])
            ->whereJsonContains('bookingData->type', 'noleggio')
            ->get();
        
        $availableCars = $cars->map(function ($car) use ($bookings) {
            $isCarAvailable = true;

            foreach ($bookings as $booking) {
                if ($booking->bookingData['car_ID'] == $car->id) {
                    $bookingStartDate = strtotime($booking->bookingData['date_start']);
                    $bookingEndDate = strtotime($booking->bookingData['date_end']);
                    $selectedStartDate = strtotime($this->dateStart);
                    $selectedEndDate = strtotime($this->dateEnd);   

                    if (
                        ($selectedStartDate >= $bookingStartDate && $selectedStartDate <= $bookingEndDate) ||
                        ($selectedEndDate >= $bookingStartDate && $selectedEndDate <= $bookingEndDate) ||
                        ($selectedStartDate <= $bookingStartDate && $selectedEndDate >= $bookingEndDate)
                    ) {
                        $isCarAvailable = false;
                        break;
                    }
                }
            }

            $car->isAvailable = $isCarAvailable;
            return $car;
        });

        return view('livewire.car-rent', ['cars' => $availableCars]);
    }
}
