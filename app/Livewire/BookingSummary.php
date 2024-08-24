<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\OwnerData;
use Livewire\Component;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingAdmin;
use App\Mail\BookingConfirmation;

class BookingSummary extends Component
{
    public $bookingData;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $body;
    public $privacy_policy = false;
    public $terms_conditions = false;
    public $adminMail;

    public $originalPrice;
    public $discountedPrice;
    public $discountPercentage;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'body' => 'required|string|max:1000',
            'privacy_policy' => 'accepted',
            'terms_conditions' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('ui.name_required'),
            'surname.required' => __('ui.surname_required'),
            'email.required' => __('ui.email_required'),
            'email.email' => __('ui.email_email'),
            'phone.required' => __('ui.phone_required'),
            'body.required' => __('ui.body_required'),
            'privacy_policy.accepted' => __('ui.privacy_policy_accepted'),
            'terms_conditions.accepted' => __('ui.terms_conditions_accepted'),
        ];
    }

    public function mount($bookingData)
    {
        $this->bookingData = $bookingData;

        $ownerData = OwnerData::first();
        $this->adminMail = $ownerData->email;

        $this->originalPrice = $this->bookingData['price'];
        $this->discountedPrice = $this->bookingData['price'];
    }

    public function updated($field)
    {
        // Se i campi necessari per il calcolo del prezzo vengono aggiornati, ricalcola il prezzo
        if (in_array($field, ['name', 'surname', 'email', 'phone'])) {
            $this->validateOnly($field);
            $this->calculatePrice();
        }
    }

    public function calculatePrice()
    {
        $this->discountedPrice = $this->originalPrice; // Inizializza il prezzo scontato con il prezzo originale

        // Verifica se il cliente esiste già nel database
        $customer = Customer::where('name', $this->name)
            ->where('surname', $this->surname)
            ->where('email', $this->email)
            ->where('phone', $this->phone)
            ->first();
        // Se il cliente esiste, applica uno sconto
        if ($customer) {
            $discountPercentage = $customer->discount ?? 0;

            if ($discountPercentage > 0 ) {
                $discountAmount = ($this->originalPrice * $discountPercentage) / 100;
                $this->discountedPrice = $this->originalPrice - $discountAmount;
            }
        }
        // dd($this->originalPrice, $this->discountedPrice);
    }

    public function confirmBooking()
    {
        $this->validate();
        $this->bookingData['original_price'] = $this->originalPrice;
        $this->bookingData['price'] = $this->discountedPrice;
        // Salvataggio della prenotazione nel database
        $booking = Booking::create([
            'bookingData' => $this->bookingData,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'body' => $this->body,
        ]);

        // Se il cliente non esiste, crea un nuovo record nel database
        $customer = Customer::firstOrCreate([
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        // Generazione del PDF del riepilogo
        $pdf = $this->generatePDF($booking);

        // Invio dell'email con il PDF allegato
        Mail::to($this->email)->send(new BookingConfirmation($pdf));
        Mail::to($this->adminMail)->send(new BookingAdmin($pdf));

        // Messaggio di conferma
        session()->flash('message', __('ui.confirmation_message'));

        // Eventuale reindirizzamento
        return redirect()->to('/');
    }

    private function generatePDF($booking)
    {
        $data = compact('booking');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pdf.booking-summary-pdf', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function render()
    {
        return view('livewire.booking-summary', [
            'originalPrice' => $this->originalPrice,
            'discountedPrice' => $this->discountedPrice,
        ]);
    }
}