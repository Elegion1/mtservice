<?php

namespace App\Livewire;

use App\Models\Booking;
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

    public function rules()
    {
        return [
            'dateStart' => 'required|date|after_or_equal:today',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'quantity' => 'required|integer|min:1',
            'carID' => 'required|exists:cars,id',
        ];
    }

    public function messages()
    {
        return [
            'dateStart.required' => __('ui.dateStart_required'),
            'dateStart.date' => __('ui.dateStart_date'),
            'dateStart.after_or_equal' => __('ui.dateStart_after_or_equal'),
            'dateEnd.required' => __('ui.dateEnd_required'),
            'dateEnd.date' => __('ui.dateEnd_date'),
            'dateEnd.after_or_equal' => __('ui.dateEnd_after_or_equal'),
            'quantity.required' => __('ui.quantity_required'),
            'quantity.integer' => __('ui.quantity_integer'),
            'quantity.min' => __('ui.quantity_min'),
            'carID.required' => __('ui.carID_required'),
            'carID.exists' => __('ui.carID_exists'),
        ];
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

        $startDate = new DateTime($this->dateStart);
        $endDate = new DateTime($this->dateEnd);
        $rentInterval = $startDate->diff($endDate);

        // Garantisce che rentDays sia almeno 1
        $rentDays = max($rentInterval->days, 1);

        $car = Car::find($this->carID);

        if ($car && $car->price) {
            $totalPrice = $car->price * $rentDays * $this->quantity;
            $this->rentPrice = $totalPrice;
        } else {
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
        $this->calculatePriceRent();
        $this->validate();

        $bookingData = [
            'type' => 'noleggio',
            'car_id' => $this->carID,
            'date_start' => $this->dateStart,
            'date_end' => $this->dateEnd,
            'quantity' => $this->quantity,
            'price' => $this->rentPrice,
        ];

        $car = Car::find($this->carID);
        if ($car) {
            $bookingData['car_name'] = $car->name;
            $bookingData['car_description'] = $car->description;
        }

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $cars = Car::all();
        $bookings = Booking::all();

        $availableCars = $cars->map(function ($car) use ($bookings) {
            $isCarAvailable = true;

            foreach ($bookings as $booking) {
                if ($booking->bookingData['type'] == 'noleggio' && $booking->bookingData['car_id'] == $car->id) {
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

        return view('livewire.car-rent', ['cars' => $availableCars, 'bookings' => $bookings]);
    }
}
