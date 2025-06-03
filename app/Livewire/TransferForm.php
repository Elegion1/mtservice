<?php

namespace App\Livewire;

use App\Models\Route;
use Livewire\Component;
use App\Models\Destination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class TransferForm extends Component
{
    public $departure;
    public $return;
    public $transferPassengers = 1;
    public $transferPrice;
    public $solaAndata = true;
    public $andataRitorno = false;
    public $dateDeparture;
    public $timeDeparture;
    public $dateReturn;
    public $timeReturn;

    public $minReturnTime;

    public $departureName;
    public $arrivalName;
    public $route;

    public $currentStep = 1; // Step iniziale

    #[On('populateForm')]
    public function populateFields($data)
    {
        Log::info('populateFields called with data: ' . json_encode($data));
        // $this->departure = $data['departure'];
        // $this->return = $data['return'];
        // $this->transferPassengers = $data['passengers'];
        // $this->solaAndata = $data['sola_andata'];
        // $this->andataRitorno = !$data['sola_andata'];
        // $this->dateDeparture = $data['date_dep'];
        // $this->timeDeparture = date('H:i', strtotime($data['date_dep']));
        // $this->dateReturn = $data['date_ret'];
        // $this->timeReturn = $data['date_ret'] ? date('H:i', strtotime($data['date_ret'])) : null;

        // $this->calculatePriceTransfer();
    }

    public function rules()
    {
        return array_merge([
            'departure' => 'required|exists:destinations,id',
            'return' => 'required|exists:destinations,id',
            'transferPassengers' => 'required|integer|min:1|max:16',
            'dateDeparture' => 'required|date|after_or_equal:today',
            'timeDeparture' => 'required',
        ], $this->getReturnRules());
    }

    private function getReturnRules()
    {
        if (!$this->andataRitorno) {
            return [
                'dateReturn' => 'nullable|date|after:dateDeparture',
                'timeReturn' => 'nullable',
            ];
        }

        return [
            'dateReturn' => 'required|date|after_or_equal:dateDeparture',
            'timeReturn' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateReturnTime($fail);
                },
            ],
        ];
    }

    private function validateReturnTime($fail)
    {
        if (
            !$this->departure || !$this->return ||
            !$this->dateDeparture || !$this->timeDeparture ||
            !$this->dateReturn || !$this->timeReturn
        ) {
            return;  // Dati insufficienti
        }

        $route = $this->getRouteData($this->departure, $this->return);
        if (!$route) {
            $fail(__('ui.invalid_route'));
            return;
        }

        $durationMinutes = $route->duration;
        $minWaitTimeMinutes = getSetting('transfer_return_minimum_wait_time_minutes', 60);
        $minMinutesWait = $durationMinutes + $minWaitTimeMinutes;

        // Combina data e ora
        $departureDateTime = strtotime("{$this->dateDeparture} {$this->timeDeparture}");
        $returnDateTime = strtotime("{$this->dateReturn} {$this->timeReturn}");

        if (!$departureDateTime || !$returnDateTime) {
            return; // Evita errori di parsing
        }

        $diffMinutes = ($returnDateTime - $departureDateTime) / 60;
        $this->minReturnTime = date('Y-m-d H:i', strtotime("{$this->dateDeparture} {$this->timeDeparture} +{$minMinutesWait} minutes"));

        if ($diffMinutes < $minMinutesWait) {
            $fail(__('ui.invalid_return_time', [
                'time' => sprintf('%02d:%02d', floor($minMinutesWait / 60), $minMinutesWait % 60),
            ]));
        }
    }

    public function messages()
    {
        return [
            'departure.required' => __('ui.departure_required'),
            'departure.exists' => __('ui.departure_exists'),
            'return.required' => __('ui.return_required'),
            'return.exists' => __('ui.return_exists'),
            'transferPassengers.required' => __('ui.transferPassengers_required'),
            'transferPassengers.integer' => __('ui.transferPassengers_integer'),
            'transferPassengers.min' => __('ui.transferPassengers_min'),
            'transferPassengers.max' => __('ui.transferPassengers_max'),
            'dateDeparture.required' => __('ui.dateDeparture_required'),
            'dateDeparture.date' => __('ui.dateDeparture_date'),
            'dateDeparture.after_or_equal' => __('ui.dateDeparture_after_or_equal'),
            'timeDeparture.required' => __('ui.timeDeparture_required'),
            'timeDeparture.after_or_equal' => __('ui.timeDeparture_after'),
            'dateReturn.date' => __('ui.dateReturn_date'),
            'dateReturn.after' => __('ui.dateReturn_after'),
            'dateReturn.required' => __('ui.dateReturn_required'),
            'timeReturn.required' => __('ui.timeReturn_required'),
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);

        if (in_array($field, ['departure', 'return', 'transferPassengers', 'solaAndata', 'andataRitorno', 'timeDeparture'])) {
            $this->validateOnly($field);
            $this->calculatePriceTransfer();

            $this->departureName = $this->departure ? $this->getDestName($this->departure) : null;
            $this->arrivalName = $this->return ? $this->getDestName($this->return) : null;
            $this->route = $this->getRouteData($this->departure, $this->return);
        }
    }

    public function getRouteData($departure, $arrival)
    {
        return Route::where('departure_id', $departure)->where('arrival_id', $arrival)->first();
    }

    public function getDestName($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return __('ui.unknown_destination'); // Messaggio di fallback
        }

        return $destination->name;
    }

    public function goToStep($step)
    {
        goToStep($step, $this->currentStep);
    }

    public function submitTransferSelection()
    {
        $this->validate();

        $this->goToStep(2);
    }

    // Funzione per incrementare i passeggeri
    public function updatePassengers($change)
    {
        passengerNumber($change, $this->transferPassengers, [$this, 'calculatePriceTransfer']);
    }

    public function setSolaAndata()
    {
        $this->solaAndata = true;
        $this->andataRitorno = false;
        $this->dateReturn = null;
        $this->timeReturn = null;
        $this->calculatePriceTransfer();
    }

    public function setAndataRitorno()
    {
        $this->solaAndata = false;
        $this->andataRitorno = true;
        $this->calculatePriceTransfer();
    }

    public function calculatePriceTransfer()
    {
        // Se partenza o destinazione non sono impostate, prezzo a 0
        if (!$this->departure || !$this->return) {
            $this->transferPrice = 0;
            return;
        }

        // Recupera i dati della tratta
        $route = $this->getRouteData($this->departure, $this->return);
        if (!$route) {
            $this->transferPrice = 0;
            return;
        }

        // Calcola il prezzo con la funzione helper
        $price = calculateBasePrice($route->price, $route->price_increment, $this->transferPassengers, $route->increment_passengers);

        // Se andata e ritorno, raddoppia il prezzo
        $this->transferPrice = $this->andataRitorno ? $price * 2 : $price;
    }

    public function getBookingDataTransfer()
    {
        // Combina data e ora di partenza in un unico datetime
        $dateTimeDeparture = combineDateAndTime($this->dateDeparture, $this->timeDeparture);

        // Combina data e ora di ritorno, se esistono
        $dateTimeReturn = combineDateAndTime($this->dateReturn, $this->timeReturn);

        return [
            'type' => 'transfer', // Assuming 'transfer' for transfer bookings
            'departure_id' => $this->departure,
            'arrival_id' => $this->return,
            'passengers' => $this->transferPassengers,
            'sola_andata' => $this->solaAndata,
            'date_dep' => $dateTimeDeparture,
            'date_ret' => $dateTimeReturn,
            'price' => $this->transferPrice,
        ];
    }

    public function submitBookingTransfer()
    {
        $this->validate();

        $bookingData = $this->getBookingDataTransfer();
        $bookingData['departure_name'] = $this->getDestName($this->departure);
        $bookingData['arrival_name'] = $this->getDestName($this->return);

        $route = $this->getRouteData($this->departure, $this->return);
        $bookingData['duration'] = $route->duration;

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $routes = Route::with(['departure', 'arrival'])->where('show', 1)->get();
        return view('livewire.transfer-form', compact('routes'));
    }
}
