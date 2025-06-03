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
    public $selectedCar; // Auto selezionata


    public $handOffOptions = [
        'airport' => ['trapani' => ['price' => 25], 'palermo' => ['price' => 50]],
        'custom_address' => ['variable_price' => true], // Segnala che il prezzo è variabile
        'garage' => ['price' => 0],
    ];

    public $pickupLocation;    // Luogo di ritiro
    public $deliveryLocation;  // Luogo di consegna
    public $pickupCustomAddress;
    public $deliveryCustomAddress;
    public $deliveryCost = 0;  // Costo della consegna
    public $pickupCost = 0;    // Costo del ritiro
    public $pickuphandOffCost;
    public $deliveryhandOffCost;
    public $pickuphandOffDistance;
    public $deliveryhandOffDistance;
    public $handOffCost = 0;
    public $pickupcorrectedCustomAddress = [];
    public $deliverycorrectedCustomAddress = [];

    public $kaskoEnabled = false; // Abilita o disabilita la kasko

    public $carRentPrice = 0; // Prezzo del noleggio dell'auto
    public $kaskoPrice = 0; // Prezzo della kasko
    public $totalPrice = 0;


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
            'kaskoEnabled' => 'boolean',
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
        if ($field == 'pickupLocation' || $field == 'deliveryLocation' || $field == 'pickupCustomAddress' || $field == 'deliveryCustomAddress') {
            if ($this->pickupLocation == 'custom_address' || $this->deliveryLocation == 'custom_address') {
                if (mb_strlen($this->pickupCustomAddress) > 10 || mb_strlen($this->deliveryCustomAddress) > 10) {
                    $this->applyDeliveryPickupCosts();
                }
            } else {
                $this->applyDeliveryPickupCosts();
            }
        }

    }

    public function calculatePriceRent()
    {
        $this->kaskoPrice = 0; // Reset kasko price
        $this->carRentPrice = 0; // Reset car rent price    

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
        $this->selectedCar = $car;

        if (!$this->selectedCar) {
            $this->rentPrice = 0;
            Log::info('Car not found: ' . $this->carID);
            return;
        }

        Log::info('Rent days: ' . $rentDays . ' - Total Hours: ' . $hours);

        // Recupera tutti i prezzi e periodi associati all'auto
        $carPrices = CarPrice::where('car_id', $this->carID)->get();
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
                    $this->carRentPrice += $carPrice->price * $this->quantity;

                    // if ($currentDate->toDateString() == $endDate->toDateString()) {
                    //     $diff = $currentDate->diffInHours($endDate);
                    // if ($diff < 24) {
                    //     $carRentPrice += ($carPrice->price * $this->quantity) * ($diff / 24);
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
                $this->carRentPrice += $car->price * $this->quantity;
            }

            $currentDate->addDay();
            $remainingDays--;
        }

        Log::info('Total price: ' . $this->carRentPrice);

        // calcolo kasko
        $kaskoBasePrice = 0;
        if ($this->selectedCar && $this->selectedCar->kasko) {
            $kaskoBasePrice = $this->selectedCar->kasko_price * $rentDays;
        }

        // Se la kasko è abilitata, aggiungi al totale
        $this->kaskoPrice = $this->kaskoEnabled ? $kaskoBasePrice : 0;

        // Calcola il totale
        $this->rentPrice = $this->carRentPrice + $this->kaskoPrice;
    }

    public function applyDeliveryPickupCosts()
    {
        $this->totalPrice = 0;
        $this->handOffCost = 0;

        if ($this->pickupLocation && $this->deliveryLocation) {
            // Calcola i costi di consegna e ritiro usando una chiave dinamica
            foreach (['pickup', 'delivery'] as $key) {
                $locationKey = $key . 'Location';
                $addressKey = $key . 'CustomAddress';
                $this->{$key . 'Cost'} = $this->calculateHandOffCost($this->{$locationKey}, $this->{$addressKey}, $key);
            }
        } else {

            if (!$this->pickupLocation) {
                $this->addError('pickupLocation', __('ui.pickupLocation_required'));
            }

            if (!$this->deliveryLocation) {
                $this->addError('deliveryLocation', __('ui.deliveryLocation_required'));
            }
        }

        // Somma i costi di consegna e ritiro al prezzo totale
        $this->handOffCost = $this->deliveryCost + $this->pickupCost;
        $this->totalPrice = $this->rentPrice + $this->handOffCost;
    }

    /**
     * Calcola il costo in base alla location selezionata.
     */
    private function calculateHandOffCost($location, $address, $key)
    {
        if (!$location) {
            return 0;
        }

        [$type, $city] = explode('_', $location) + [1 => null];

        if ($location == 'custom_address' && !empty($address)) {
            return $this->calculateVariablePrice($address, $key);
        }

        if (!isset($this->handOffOptions[$type])) {
            return 0;
        }

        if ($city && isset($this->handOffOptions[$type][$city]['price'])) {
            return $this->handOffOptions[$type][$city]['price'];
        }

        if (isset($this->handOffOptions[$type]['price'])) {
            return $this->handOffOptions[$type]['price'];
        }

        return 0;
    }

    public function calculateVariablePrice($location, $key)
    {
        $origin = getSetting('garage_address');

        $distance = $this->getDistanceFromAPI($origin, $location, $key);
        if ($distance === null) {
            return 0; // Evita di sommare null e prevenire errori
        }

        Log::info('Distanza: ' . $distance);

        $this->{$key . 'handOffDistance'} = $distance;

        $pricePerDistance = $distance * 0.5;
        return ceil($pricePerDistance);
    }

    public function selectAddress($key, $address)
    {
        // Assegna l'indirizzo selezionato alla variabile dinamica
        $this->{$key . 'CustomAddress'} = $address;

        // Puoi anche svuotare la lista delle previsioni
        $this->{$key . 'correctedCustomAddress'} = [];
    }

    public function getDistanceFromAPI($origin, $destination, $key)
    {
        $this->{$key . 'correctedCustomAddress'} = [];
        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Leggi la chiave API da .env
        $origin = urlencode($origin);
        $destination = urlencode($destination);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations={$destination}&origins={$origin}&key={$apiKey}";

        $response = file_get_contents($url);
        Log::info('Risposta: ' . $response);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            // Verifica che ci siano effettivamente gli indirizzi di destinazione
            if (isset($data['destination_addresses'][0]) && isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                $this->{$key . 'correctedCustomAddress'} = $data['destination_addresses'];
                return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // Converti in KM
            } else {
                Log::error('Destinazione non trovata o formato risposta non corretto');
                return null; // In caso di errore
            }
        } else {
            Log::error('Errore nella risposta di Google Maps API: ' . $data['status']);
            return null; // In caso di errore
        }
    }

    public function convertDate($date)
    {
        return Carbon::parse($date);
    }

    public function getBookingDataRent()
    {
        $dateTimeStart = combineDateAndTime($this->dateStart, $this->timeStart);
        $dateTimeEnd = combineDateAndTime($this->dateEnd, $this->timeEnd);

        $specialLocations = [
            'custom_address' => fn($type) => $type === 'pickup' ? $this->pickupCustomAddress : $this->deliveryCustomAddress,
            'garage' => fn() => getSetting('garage_address'),
            'airport_trapani' => fn() => __('ui.airport_trapani'),
            'airport_palermo' => fn() => __('ui.airport_palermo'),
        ];

        foreach (['pickup', 'delivery'] as $type) {
            $property = $type . 'Location';
            $value = $this->$property;

            if (isset($specialLocations[$value])) {
                $this->$property = $specialLocations[$value]($type);
            }
        }

        if ($this->totalPrice == 0) {
            Log::info('Total price is 0, calculating delivery and pickup costs');
            $this->applyDeliveryPickupCosts();
        }

        return [
            'type' => 'noleggio',
            'date_start' => $dateTimeStart,
            'date_end' => $dateTimeEnd,
            'pickup' => $this->pickupLocation,
            'delivery' => $this->deliveryLocation,
            'quantity' => $this->quantity,
            'car_ID' => $this->carID,
            'price' => $this->totalPrice,
            'kasko_enabled' => $this->kaskoEnabled,
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

        if ($this->currentStep == 3) {
            $this->applyDeliveryPickupCosts();
        }

        return view('livewire.car-rent', ['cars' => $availableCars]);
    }
}
