<?php

namespace App\Livewire;

use App\Models\Route;
use Livewire\Component;
use App\Models\Destination;

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

    protected $listeners = ['prenota'];

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
        if (!$this->departure || !$this->return || !$this->timeDeparture || !$this->timeReturn) {
            return;  // Interrompi se mancano dati
        }

        $route = $this->getRouteData($this->departure, $this->return);
        if (!$route) {
            $fail(__('ui.invalid_route'));
            return;
        }

        $durationMinutes = $route->duration;
        $minWaitTimeMinutes = getSetting('transfer_return_minimum_wait_time_minutes', 60);
        $minMinutesWait = $durationMinutes + $minWaitTimeMinutes;

        $departureTimestamp = strtotime($this->timeDeparture);
        $returnTimestamp = strtotime($this->timeReturn);

        if (!$departureTimestamp || !$returnTimestamp) {
            return;  // Evita errori se il formato dell'ora è errato
        }

        $diffMinutes = ($returnTimestamp - $departureTimestamp) / 60;

        $this->minReturnTime = date('H:i', strtotime($this->timeDeparture . " + {$minMinutesWait} minutes"));

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
            $this->calculatePrice();

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
        $this->currentStep = $step;
    }

    public function submitTransferSelection()
    {
        $this->validate();

        $this->goToStep(2);
    }

    // Funzione per incrementare i passeggeri
    public function incrementPassengers()
    {
        if ($this->transferPassengers < 16) {
            $this->transferPassengers++;
            $this->calculatePrice();
        }
    }

    // Funzione per decrementare i passeggeri
    public function decrementPassengers()
    {
        if ($this->transferPassengers > 1) {
            $this->transferPassengers--;
            $this->calculatePrice();
        }
    }

    public function setSolaAndata()
    {
        $this->solaAndata = true;
        $this->andataRitorno = false;
        $this->dateReturn = null;
        $this->timeReturn = null;
        $this->calculatePrice();
    }

    public function setAndataRitorno()
    {
        $this->solaAndata = false;
        $this->andataRitorno = true;
        $this->calculatePrice();
    }

    private function calculateBasePrice($route, $passengers)
    {
        if ($passengers <= 4) return $route->price;

        $incrementPrice = $route->price_increment;
        $extraPassengers = max(0, $passengers - 4);

        if ($passengers <= 8) {
            return $route->price + ($incrementPrice * $extraPassengers);
        }

        return ($route->price * 2) + ($incrementPrice * ($extraPassengers > 8 ? $extraPassengers - 8 : 4));
    }

    public function calculatePrice()
    {
        if ($this->departure && $this->return) {
            $route = $this->getRouteData($this->departure, $this->return);
            if (!$route) {
                $this->transferPrice = 0;
                return;
            }

            $price = $this->calculateBasePrice($route, $this->transferPassengers);
            $this->transferPrice = $this->andataRitorno ? $price * 2 : $price;
        } else {
            $this->transferPrice = 0;
        }
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
