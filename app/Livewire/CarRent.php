<?php

namespace App\Livewire;

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

    public $startDateMin;
    public $endDateMin;
    public $startTimeMin;
    public $endTimeMin;
    public $minimumDays;

    public $currentStep = 1; // Step iniziale

    public function rules()
    {
        return [
            'dateStart' => 'required|date|after_or_equal:today',
            'timeStart' => 'required',
            'dateEnd' => 'required|date|after:dateStart',
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
        goToStep($step, $this->currentStep);
    }

    public function submitDateSelection()
    {
        $this->resetErrorBag();
        $this->minDates();

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->validate([
            'dateStart' => 'required|date',
            'timeStart' => 'required',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            'timeEnd' => 'required',
        ]);

        $this->goToStep(2);

        if ($this->carID) {
            $this->calculatePriceRent();
        }
    }

    public function updated($field)
    {
        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
            $this->calculatePriceRent();
        }
    }

    public function calculatePriceRent()
    {
        $this->validate();

        $startDate = $this->convertDate(combineDateAndTime($this->dateStart, $this->timeStart));
        $endDate = $this->convertDate(combineDateAndTime($this->dateEnd, $this->timeEnd));

        Log::info('Rent Start date: ' . $startDate);
        Log::info('Rent End date: ' . $endDate);

        // Calcolare la differenza in ore tra dateStart e dateEnd
        $hours = $startDate->diffInHours($endDate);

        // Arrotonda sempre per eccesso
        $rentDays = ceil($hours / 24);

        $car = Car::find($this->carID);
        if (!$car) {
            $this->rentPrice = 0;
            Log::info('Car not found: ' . $this->carID);
            return;
        }

        Log::info('Rent days: ' . $rentDays . ' - Total Hours: ' . $hours);

        // Recupera tutti i prezzi e periodi associati all'auto
        $carPrices = CarPrice::where('car_id', $this->carID)->get();
        $totalPrice = 0;
        $currentDate = clone $startDate;
        $remainingDays = $rentDays;

        while ($remainingDays > 0 && $currentDate <= $endDate) {
            $found = false;
            Log::info('Remaining days While: ' . $remainingDays);

            foreach ($carPrices as $carPrice) {
                $periodStart = $this->convertDate($carPrice->timePeriod->start);
                $periodEnd = $this->convertDate($carPrice->timePeriod->end);

                Log::info('Period: ' . $periodStart . ' - ' . $periodEnd);

                if ($currentDate >= $periodStart && $currentDate <= $periodEnd) {
                    Log::info('Applying price for period: ' . $currentDate . ' - ' . $carPrice->price);
                    $totalPrice += $carPrice->price * $this->quantity;

                    // if ($currentDate->toDateString() == $endDate->toDateString()) {
                    //     $diff = $currentDate->diffInHours($endDate);
                        // if ($diff < 24) {
                        //     $totalPrice += ($carPrice->price * $this->quantity) * ($diff / 24);
                        //     Log::info('Applying proportional price for last day: ' . $diff . ' hours');
                        //     $found = true;
                        // }
                    // }

                    $found = true;
                    break;
                }
            }

            if (!$found && $car->price) {
                Log::info('Applying base price for: ' . $currentDate . ' - ' . $car->price);
                $totalPrice += $car->price * $this->quantity;
            }

            $currentDate->addDay();
            $remainingDays--;
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
        $dateTimeStart = combineDateAndTime($this->dateStart, $this->timeStart);
        $dateTimeEnd = combineDateAndTime($this->dateEnd, $this->timeEnd);
        return [
            'type' => 'noleggio',
            'date_start' => $dateTimeStart,
            'date_end' => $dateTimeEnd,
            'quantity' => $this->quantity,
            'car_ID' => $this->carID,
            'price' => $this->rentPrice,
        ];
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

    public function minDates()
    {
        $minimumRentDays = getSetting('minimum_rent_days');
        $this->minimumDays = $minimumRentDays;
        $minimumRentHours = $minimumRentDays * 24; // Calcola le ore minime

        $startDate = $this->convertDate(combineDateAndTime($this->dateStart, $this->timeStart));
        $endDate = $this->convertDate(combineDateAndTime($this->dateEnd, $this->timeEnd));

        $rentHours = $startDate->diffInHours($endDate);

        // Imposta date e ore minime
        $this->startDateMin = date('Y-m-d');
        $this->startTimeMin = date('H:i');

        if ($this->dateStart && $this->timeStart) {
            $this->endTimeMin = $this->timeStart;
        }

        if ($this->dateStart) {
            $this->endDateMin = date('Y-m-d', strtotime($this->dateStart . ' + ' . $minimumRentDays . ' days'));
        } else {
            $this->endDateMin = date('Y-m-d', strtotime(date('Y-m-d') . ' + ' . $minimumRentDays . ' days'));
        }

        if ($this->dateEnd && !$this->dateStart) {
            $this->startDateMin = date('Y-m-d', strtotime($this->dateEnd . ' - ' . $minimumRentDays . ' days'));
        }

        if ($this->dateStart && $this->dateEnd) {
            $startDate = new \DateTime($this->dateStart);
            $endDate = new \DateTime($this->dateEnd);
            $diff = $startDate->diff($endDate)->days;

            if ($diff < $minimumRentDays) {
                $this->addError('dateEnd', __('ui.minimum_days_error', ['days' => $minimumRentDays]));
                return;
            }

            if ($rentHours < $minimumRentHours) {
                $this->addError('timeEnd', __('ui.minimum_hours_error', ['hours' => $minimumRentHours]));
                return;
            }

            if ($diff == $minimumRentDays) {
                $this->endTimeMin = $this->timeStart;
            } else {
                $this->endTimeMin = null;
            }
        }
    }

    public function render()
    {
        $this->minDates();

        $cars = Car::where('show', 1)->get();

        $bookings = Booking::whereIn('status', ['confirmed', 'pending'])
            ->where('bookingData->type', 'noleggio')
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
