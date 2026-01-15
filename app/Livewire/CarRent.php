<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Car;
use App\Models\Booking;
use App\Jobs\CalculateDistanceJob;
use Livewire\Component;
use App\Models\CarPrice;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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

    private $lastUpdateTime = 0; // Per implementare throttling manuale
    private $updateThrottleMs = 500; // Throttle di 500ms
    
    public $pendingDistanceCalculations = []; // Traccia i calcoli in corso

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
        // Implementa throttling manuale per evitare aggiornamenti troppo frequenti
        $currentTime = microtime(true) * 1000; // Tempo in millisecondi
        if ($currentTime - $this->lastUpdateTime < $this->updateThrottleMs) {
            Log::debug('Update throttled for field: ' . $field);
            return;
        }
        $this->lastUpdateTime = $currentTime;

        if (array_key_exists($field, $this->rules())) {
            $this->validateOnly($field);
            $this->calculatePriceRent();
        }
        if (in_array($field, ['pickupLocation', 'deliveryLocation', 'pickupCustomAddress', 'deliveryCustomAddress'])) {
            // If the user changed the location select away from custom, clear related inputs/suggestions
            if ($field === 'pickupLocation' && $this->pickupLocation !== 'custom_address') {
                $this->pickupCustomAddress = '';
                $this->pickupcorrectedCustomAddress = [];
            }

            if ($field === 'deliveryLocation' && $this->deliveryLocation !== 'custom_address') {
                $this->deliveryCustomAddress = '';
                $this->deliverycorrectedCustomAddress = [];
            }

            // Trigger autocomplete suggestions as the user types (after 3 chars)
            if ($field === 'pickupCustomAddress' && $this->pickupLocation === 'custom_address' && mb_strlen($this->pickupCustomAddress) > 3) {
                $this->getSuggestedAddresses($this->pickupCustomAddress, 'pickup');
            }

            if ($field === 'deliveryCustomAddress' && $this->deliveryLocation === 'custom_address' && mb_strlen($this->deliveryCustomAddress) > 3) {
                $this->getSuggestedAddresses($this->deliveryCustomAddress, 'delivery');
            }

            // Only calculate delivery/pickup costs when both sides are present and ready.
            $bothLocationsSet = $this->pickupLocation && $this->deliveryLocation;
            if ($bothLocationsSet) {
                $pickupReady = $this->pickupLocation !== 'custom_address' || mb_strlen($this->pickupCustomAddress) > 3;
                $deliveryReady = $this->deliveryLocation !== 'custom_address' || mb_strlen($this->deliveryCustomAddress) > 3;

                if ($pickupReady && $deliveryReady) {
                    $this->applyDeliveryPickupCosts();
                }
            }
        }

    }

    /**
     * Controlla se una data cade in un periodo, ignorando l'anno
     * Usa solo mese e giorno per il confronto
     */
    private function isDateInPeriod($currentDate, $periodStart, $periodEnd)
    {
        $currentMonth = $currentDate->month;
        $currentDay = $currentDate->day;

        $startMonth = $periodStart->month;
        $startDay = $periodStart->day;

        $endMonth = $periodEnd->month;
        $endDay = $periodEnd->day;

        // Se il periodo non attraversa l'anno (es: 1 Gen - 30 Apr)
        if ($startMonth < $endMonth || ($startMonth == $endMonth && $startDay <= $endDay)) {
            // Controlla se il mese è nello stesso mese e il giorno è maggiore o uguale al giorno inizio
            if ($currentMonth == $startMonth && $currentDay >= $startDay) {
                return true;
            }
            // Controlla se il mese è tra inizio e fine
            if ($currentMonth > $startMonth && $currentMonth < $endMonth) {
                return true;
            }
            // Controlla se il mese è lo stesso di fine e il giorno è minore o uguale al giorno fine
            if ($currentMonth == $endMonth && $currentDay <= $endDay) {
                return true;
            }
        }
        // Se il periodo attraversa l'anno (es: 1 Nov - 31 Mar)
        else {
            // Controlla se la data è dopo la data inizio oppure prima della data fine
            if ($currentMonth > $startMonth || ($currentMonth == $startMonth && $currentDay >= $startDay)) {
                return true;
            }
            if ($currentMonth < $endMonth || ($currentMonth == $endMonth && $currentDay <= $endDay)) {
                return true;
            }
        }

        return false;
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

        // Recupera tutti i prezzi e periodi associati all'auto con eager loading
        $carPrices = CarPrice::where('car_id', $this->carID)->with('timePeriod')->get();
        $currentDate = clone $startDate;
        $remainingDays = $rentDays;

        while ($remainingDays > 0 && $currentDate <= $endDate) {
            $found = false;
            Log::info('Remaining days While: ' . $remainingDays);

            foreach ($carPrices as $carPrice) {
                $periodStart = $this->convertDate($carPrice->timePeriod->start);
                $periodEnd = $this->convertDate($carPrice->timePeriod->end);

                Log::info('Period: ' . $periodStart . ' - ' . $periodEnd);

                // Confronta ignorando l'anno (usa solo mese e giorno)
                if ($this->isDateInPeriod($currentDate, $periodStart, $periodEnd)) {
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

        // Caso indirizzo personalizzato: se abbiamo già la distanza calcolata
        // restituiamo subito il prezzo, altrimenti avviamo il calcolo asincrono
        if ($location == 'custom_address' && !empty($address)) {
            $distanceProp = $key . 'handOffDistance';
            $distance = $this->{$distanceProp} ?? null;

            if ($distance !== null && is_numeric($distance) && $distance > 0) {
                $pricePerDistance = $distance * 0.5;
                return ceil($pricePerDistance);
            }

            // Proviamo a leggere la distanza dalla cache (potrebbe essere già calcolata dal job)
            $origin = getSetting('garage_address');
            $cacheKey = 'distance_' . md5($origin . '|' . $address . '|' . $key);
            $cached = Cache::get($cacheKey);

            if ($cached && isset($cached['status']) && $cached['status'] === 'completed' && isset($cached['distance']) && is_numeric($cached['distance']) && $cached['distance'] > 0) {
                $this->{$distanceProp} = $cached['distance'];
                $pricePerDistance = $cached['distance'] * getSetting('hand_off_price_per_km', 0.5);
                return ceil($pricePerDistance);
            }

            // non abbiamo ancora la distanza: avvia il calcolo (o lo ri-avvia)
            $this->calculateVariablePrice($address, $key);
            return 0;
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

        // SINCRONO: Ottieni i suggerimenti di indirizzo per l'autocompletamento
        $this->getSuggestedAddresses($location, $key);
        
        // ASINCRONO: Dispatcha il Job per calcolare la distanza in background
        $this->dispatchDistanceCalculation($origin, $location, $key);
        
        // Ritorna 0 per ora, il calcolo arriverà dopo
        return 0;
    }

    private function getSuggestedAddresses($destination, $key)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $destination = urlencode($destination);
        
        // Usa Google Places Autocomplete API per migliori risultati
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input={$destination}&key={$apiKey}";

        try {
            $response = @file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK' && isset($data['predictions'])) {
                // Estrai i nomi dei luoghi dai predictions
                $addresses = array_map(fn($p) => $p['description'], $data['predictions']);
                $this->{$key . 'correctedCustomAddress'} = array_slice($addresses, 0, 5); // Limita a 5 risultati
                Log::info('Address suggestions retrieved for ' . $key . ': ' . json_encode($this->{$key . 'correctedCustomAddress'}));
            } else {
                Log::warning('No address suggestions found for ' . $destination);
                $this->{$key . 'correctedCustomAddress'} = [];
            }
        } catch (\Exception $e) {
            Log::error('Error getting address suggestions: ' . $e->getMessage());
            $this->{$key . 'correctedCustomAddress'} = [];
        }
    }

    private function dispatchDistanceCalculation($origin, $destination, $key)
    {
        // Genera una chiave deterministica per la cache (così possiamo ritrovarla dopo)
        $cacheKey = 'distance_' . md5($origin . '|' . $destination . '|' . $key);

        // Se non esiste già un calcolo in cache o in corso, dispatchiamo il job
        $existing = Cache::get($cacheKey);
        if (!$existing) {
            dispatch(new CalculateDistanceJob($origin, $destination, $cacheKey, $key));
            Log::info('Dispatched CalculateDistanceJob for ' . $key . ' with cache key: ' . $cacheKey);
        } else {
            Log::info('Existing cache entry found for ' . $key . ' with cache key: ' . $cacheKey);
        }

        // Traccia il calcolo in corso per il polling (sovrascrive la stessa chiave, ok)
        $this->pendingDistanceCalculations[$cacheKey] = [
            'key' => $key,
            'createdAt' => now(),
        ];
    }

    public function selectAddress($key, $address)
    {
        // Assegna l'indirizzo selezionato alla variabile dinamica
        $this->{$key . 'CustomAddress'} = $address;

        // Svuota la lista delle previsioni
        $this->{$key . 'correctedCustomAddress'} = [];
    }

    public function getDistanceFromAPI($origin, $destination, $key)
    {
        // Questo metodo è deprecato - usare calculateVariablePrice() invece
        $this->dispatchDistanceCalculation($origin, $destination, $key);
        return null;
    }

    #[On('check-distance-cache')]
    public function checkDistanceCache()
    {
        // Controlla tutti i calcoli in corso
        foreach ($this->pendingDistanceCalculations as $cacheKey => $data) {
            $cachedResult = Cache::get($cacheKey);
            
            if ($cachedResult) {
                if ($cachedResult['status'] === 'completed') {
                    // Risultato pronto!
                    $this->handleDistanceCalculated($cachedResult);
                    unset($this->pendingDistanceCalculations[$cacheKey]);
                } elseif ($cachedResult['status'] === 'error') {
                    // Errore nel calcolo
                    Log::error('Distance calculation error: ' . $cachedResult['message']);
                    unset($this->pendingDistanceCalculations[$cacheKey]);
                }
            }
            
            // Timeout dopo 30 secondi
            if (now()->diffInSeconds($data['createdAt']) > 30) {
                Log::warning('Distance calculation timeout for ' . $data['key']);
                unset($this->pendingDistanceCalculations[$cacheKey]);
            }
        }
    }

    public function handleDistanceCalculated($data)
    {
        $key = $data['key'];
        $distance = $data['distance'];

        Log::info('Distance calculated for ' . $key . ': ' . $distance . ' km');

        $this->{$key . 'handOffDistance'} = $distance;

        // Ricalcola i costi con la nuova distanza
        $this->applyDeliveryPickupCosts();
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

        // Filtra prenotazioni solo se date sono state selezionate, per ridurre il carico
        if (!$this->dateStart || !$this->dateEnd) {
            $availableCars = $cars->map(function ($car) {
                $car->isAvailable = true;
                return $car;
            });
        } else {
            // Seleziona solo prenotazioni che potrebbero conflittare con le date scelte
            $bookings = Booking::whereIn('status', ['confirmed', 'pending'])
                ->where('bookingData->type', 'noleggio')
                ->get();

            $availableCars = $cars->map(function ($car) use ($bookings) {
                $isCarAvailable = true;
                $selectedStartDate = strtotime($this->dateStart);
                $selectedEndDate = strtotime($this->dateEnd);

                foreach ($bookings as $booking) {
                    if ($booking->bookingData['car_ID'] == $car->id) {
                        $bookingStartDate = strtotime($booking->bookingData['date_start']);
                        $bookingEndDate = strtotime($booking->bookingData['date_end']);

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
        }

        if ($this->currentStep == 3) {
            $this->applyDeliveryPickupCosts();
        }

        return view('livewire.car-rent', ['cars' => $availableCars]);
    }
}
