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
    public $dateReturn;
    public $selectedTratta;

    protected $listeners = ['prenota'];

    public function rules()
    {
        $rules = [
            'departure' => 'required|exists:destinations,id',
            'return' => 'required|exists:destinations,id',
            'transferPassengers' => 'required|integer|min:1|max:16',
            'dateDeparture' => 'required|date|after_or_equal:today',
            'dateReturn' => 'nullable|date|after:dateDeparture',
        ];

        // Se andataRitorno è true, rendi dateReturn obbligatoria
        if ($this->andataRitorno) {
            $rules['dateReturn'] = 'required|date|after:dateDeparture';
        }

        return $rules;
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
            'dateReturn.date' => __('ui.dateReturn_date'),
            'dateReturn.after' => __('ui.dateReturn_after'),
            'dateReturn.required' => __('ui.dateReturn_required'),
        ];
    }


    public function updated($field)
    {
        $this->validateOnly($field);
        if ($field === 'departure' || $field === 'return' || $field === 'transferPassengers' || $field === 'solaAndata' || $field === 'andataRitorno') {
            $this->calculatePrice();
        }
    }

    public function prenota($trattaId, $departure, $arrival)
    {
        // Gestisci i dati della tratta selezionata
        $this->selectedTratta = $trattaId;
        dd($this->selectedTratta);

        // Puoi anche fare qualcos'altro, come aggiornare il form o salvare i dati
    }

    public function setSolaAndata()
    {
        $this->solaAndata = true;
        $this->andataRitorno = false;
        $this->dateReturn = null;
        $this->calculatePrice();
    }

    public function setAndataRitorno()
    {
        $this->solaAndata = false;
        $this->andataRitorno = true;
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if ($this->departure && $this->return) {
            // Cerca la route basandosi su departure_id e arrival_id
            $route = Route::where('departure_id', $this->departure)
                ->where('arrival_id', $this->return)
                ->first();

            if (!$route) {
                // Route non trovata
                $this->transferPrice = 0;
                return;
            }

            $basePrice = $route->price;
            $incrementPrice = $route->price_increment;
            $passengers = $this->transferPassengers;

            if ($passengers <= 4) {
                $totalPrice = $basePrice;
            } elseif ($passengers <= 8) {
                $totalPrice = $basePrice + $incrementPrice * ($passengers - 4);
            } elseif ($passengers >= 9 && $passengers <= 12) {
                $totalPrice = ($basePrice * 2) + $incrementPrice * 4;
            } elseif ($passengers > 12 || $passengers <= 16) {
                $totalPrice = ($basePrice * 2) + $incrementPrice * 4 + $incrementPrice * ($passengers - 12);
            }

            if ($this->andataRitorno) {
                $totalPrice *= 2;
            }

            $this->transferPrice = $totalPrice;
        } else {
            $this->transferPrice = 0;
        }
    }

    public function getBookingDataTransfer()
    {
        return [
            'type' => 'transfer', // Assuming 'transfer' for transfer bookings
            'departure_id' => $this->departure,
            'arrival_id' => $this->return,
            'passengers' => $this->transferPassengers,
            'sola_andata' => $this->solaAndata,
            'date_dep' => $this->dateDeparture,
            'date_ret' => $this->dateReturn,
            'price' => $this->transferPrice,
        ];
    }

    public function submitBookingTransfer()
    {
        $this->validate();

        $bookingData = $this->getBookingDataTransfer();
        $departureName = Destination::find($bookingData['departure_id'])->name;
        $arrivalName = Destination::find($bookingData['arrival_id'])->name;
        $bookingData['departure_name'] = $departureName;
        $bookingData['arrival_name'] = $arrivalName;
        $route = Route::where('departure_id', $this->departure)->where('arrival_id', $this->return)->first();

        $bookingData['duration'] = $route->duration;

        // Formattare la data di partenza
        $departureDate = date('D d F Y', strtotime($bookingData['date_dep']));
        $bookingData['date_departure'] = $departureDate;

        $departureTime = date('H:i', strtotime($bookingData['date_dep']));
        $bookingData['time_departure'] = $departureTime;

        // Se c'è una data di ritorno, formattarla
        if (!empty($bookingData['date_ret'])) {

            $returnDate = date('D d F Y', strtotime($bookingData['date_ret']));
            $bookingData['date_return'] = $returnDate;

            $returnTime = date('H:i', strtotime($bookingData['date_ret']));
            $bookingData['time_return'] = $returnTime;
        }

        $this->dispatch('bookingSubmitted', $bookingData);
    }

    public function render()
    {
        $destinations = Destination::all();
        $routes = Route::with(['departure', 'arrival'])->get();
        return view('livewire.transfer-form', compact('destinations', 'routes'));
    }
}
