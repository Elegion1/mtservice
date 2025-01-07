<?php

namespace App\Livewire;

use App\Models\Route;
use App\Models\Setting;
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

    public $departureName;
    public $arrivalName;
    public $route;

    public $currentStep = 1; // Step iniziale

    protected $listeners = ['prenota'];

    public function rules()
    {
        $rules = [
            'departure' => 'required|exists:destinations,id',
            'return' => 'required|exists:destinations,id',
            'transferPassengers' => 'required|integer|min:1|max:16',
            'dateDeparture' => 'required|date|after_or_equal:today',
            'timeDeparture' => 'required',
            'dateReturn' => 'nullable|date|after:dateDeparture',
            'timeReturn' => 'nullable',
        ];

        // Se andataRitorno è true, rendi dateReturn obbligatoria
        if ($this->dateDeparture == $this->dateReturn && $this->andataRitorno) {
            $rules['dateReturn'] = 'required|date';
            $rules['timeReturn'] = ['required', function ($attribute, $value, $fail) {
                $this->validateReturnTime($fail);
            }];
        } elseif ($this->andataRitorno) {
            $rules['dateReturn'] = 'required|date|after:dateDeparture';
            $rules['timeReturn'] = 'required';
        }

        return $rules;
    }

    private function validateReturnTime($fail)
    {
        // Verifica se tutti i parametri necessari sono presenti
        if (!$this->departure || !$this->return || !$this->dateDeparture || !$this->timeDeparture || !$this->dateReturn || !$this->timeReturn) {
            return;  // Se uno dei parametri non è presente, interrompi la funzione
        }

        // Recupera il tragitto dalla rotta
        $route = Route::where('departure_id', $this->departure)->where('arrival_id', $this->return)->first();

        // Se la rotta non esiste, segnala l'errore
        if (!$route) {
            $fail(__('ui.invalid_route')); // Traduzione per "Tragitto non valido"
            return;
        }

        // Recupera la durata del tragitto e il tempo minimo di attesa
        $durationMinutes = $route->duration;
        $minWaitTimeMinutes = Setting::getValue('transfer_return_minimum_wait_time_minutes', 60);
        $minMinutesWait = $durationMinutes + $minWaitTimeMinutes;  // Tempo minimo di attesa

        // Calcola la differenza in minuti tra l'orario di partenza e ritorno
        $departureTimestamp = strtotime($this->timeDeparture);
        $returnTimestamp = strtotime($this->timeReturn);
        $diffMinutes = ($returnTimestamp - $departureTimestamp) / 60;  // Differenza in minuti

        // Se il ritorno è valido (cioè la differenza è maggiore o uguale al minimo), prosegui
        if ($diffMinutes >= $minMinutesWait) {
            // Se il ritorno è valido, non fare nulla e continua il flusso
            return;
        } else {
            // Altrimenti, segnala l'errore con il tempo minimo di attesa
            $hours = floor($minMinutesWait / 60);
            $minutes = $minMinutesWait % 60;
            $formattedTime = sprintf('%02d:%02d', $hours, $minutes);

            $fail(__('ui.invalid_return_time', [
                'time' => $formattedTime,  // Mostra il tempo minimo formattato come ora
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
            'dateReturn.date' => __('ui.dateReturn_date'),
            'dateReturn.after' => __('ui.dateReturn_after'),
            'dateReturn.required' => __('ui.dateReturn_required'),
            'timeReturn.required' => __('ui.timeReturn_required'),
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);

        if (in_array($field, ['departure', 'return', 'transferPassengers', 'solaAndata', 'andataRitorno'])) {
            $this->calculatePrice();

            $this->departureName = $this->departure ? $this->getDestName($this->departure) : null;
            $this->arrivalName = $this->return ? $this->getDestName($this->return) : null;
            $this->route = $this->getRouteData($this->departure, $this->return);
        }
    }

    public function getRoutedata($departure, $arrival)
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
        $this->validate([
            'departure' => 'required|exists:destinations,id',
            'return' => 'required|exists:destinations,id',
            'dateDeparture' => 'required|date|after_or_equal:today',
            'timeDeparture' => 'required',
            'dateReturn' => 'nullable|date|after:dateDeparture',
            'timeReturn' => 'nullable',
        ]);

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
        $basePrice = $route->price;
        $incrementPrice = $route->price_increment;

        if ($passengers <= 4) {
            return $basePrice;
        } elseif ($passengers <= 8) {
            return $basePrice + $incrementPrice * ($passengers - 4);
        } elseif ($passengers <= 12) {
            return ($basePrice * 2) + $incrementPrice * 4;
        } else {
            return ($basePrice * 2) + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);
        }
    }

    public function calculatePrice()
    {
        if ($this->departure && $this->return) {
            $route = Route::where('departure_id', $this->departure)->where('arrival_id', $this->return)->first();
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
        $dateTimeDeparture = $this->combineDateAndTime($this->dateDeparture, $this->timeDeparture);

        // Combina data e ora di ritorno, se esistono
        $dateTimeReturn = $this->combineDateAndTime($this->dateReturn, $this->timeReturn);

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

    // Metodo per combinare data e ora in un unico formato datetime
    protected function combineDateAndTime($date, $time)
    {
        if ($date && $time) {
            return "{$date}T{$time}";
        }
        return null;
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
        $destinations = Destination::all();
        $routes = Route::with(['departure', 'arrival'])->get();
        return view('livewire.transfer-form', compact('destinations', 'routes'));
    }
}
