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
use App\Models\Discount;

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
    public $discountType_it;
    public $discountType_en;

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
        // Inizializza il prezzo scontato con il prezzo originale
        $this->discountedPrice = $this->originalPrice;

        // Aggiungi una proprietà per il tipo di sconto
        // $this->discountType = null;

        // Verifica se il cliente esiste già nel database
        $customer = Customer::where('name', $this->name)
            ->where('surname', $this->surname)
            ->where('email', $this->email)
            ->where('phone', $this->phone)
            ->first();

        // Se il cliente ha uno sconto specifico, applicalo e bypassa tutti gli altri sconti
        if ($customer && $customer->discount > 0) {
            $discountAmount = ($this->originalPrice * $customer->discount) / 100;
            $this->discountedPrice = $this->originalPrice - $discountAmount;
            $this->discountPercentage = $customer->discount;
            $this->discountType_it = __('ui.customerDiscountMessage');
            $this->discountType_en = __('ui.customerDiscountMessage'); // Messaggio del tipo di sconto
            return;
        }

        // Ottieni tutti gli sconti attivi con i periodi associati
        $discounts = Discount::with('discount_periods')->get();

        // Controlla gli sconti disponibili
        foreach ($discounts as $discount) {
            $applyDiscount = false;

            // Verifica se lo sconto è applicabile ai clienti esistenti o a tutti
            if ($discount->applicable_to === 'customers' && $customer) {
                $applyDiscount = true;
            } elseif ($discount->applicable_to === 'all') {
                $applyDiscount = true;
            }

            // Controlla se lo sconto è applicabile al tipo di servizio specifico
            $serviceApplicable = false;
            if ($this->bookingData['type'] === 'transfer' && $discount->applies_to_transfer) {
                $serviceApplicable = true;
            } elseif ($this->bookingData['type'] === 'noleggio' && $discount->applies_to_rental) {
                $serviceApplicable = true;
            } elseif ($this->bookingData['type'] === 'escursione' && $discount->applies_to_excursion) {
                $serviceApplicable = true;
            }

            // Procede solo se lo sconto è applicabile al servizio richiesto
            if ($applyDiscount && $serviceApplicable) {
                if ($discount->discount_periods->isEmpty()) {
                    // Se non ci sono periodi di sconto, applica sempre lo sconto
                    $applyDiscount = true;
                } else {
                    // Se ci sono periodi di sconto, verifica se lo sconto è valido in questo momento
                    $now = now();
                    $validPeriod = false;
                    foreach ($discount->discount_periods as $period) {
                        if ($now->between($period->start, $period->end)) {
                            $validPeriod = true;
                            break;
                        }
                    }
                    $applyDiscount = $validPeriod;
                }

                // Se lo sconto è applicabile, calcola il prezzo scontato
                if ($applyDiscount && $discount->percentage > 0) {
                    $currentDiscountAmount = ($this->originalPrice * $discount->percentage) / 100;
                    $discountedPriceCandidate = $this->originalPrice - $currentDiscountAmount;

                    // Applica il miglior sconto disponibile
                    if ($discountedPriceCandidate < $this->discountedPrice) {
                        $this->discountedPrice = $discountedPriceCandidate;
                        $this->discountPercentage = $discount->percentage;
                        $this->discountType_it = $discount->name_it; // Memorizza il nome dello sconto
                        $this->discountType_en = $discount->name_en;
                    }
                }
            }
        }

        // Debugging (opzionale)
        // dd($this->originalPrice, $this->discountedPrice, $this->discountPercentage, $this->discountType);
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

        $language = app()->getLocale();
        // Generazione del PDF del riepilogo
        $pdfClient = $this->generatePDF($booking, $language);
        $pdfAdmin = $this->generatePDF($booking, 'it');

        // Invio dell'email con il PDF allegato
        Mail::to($this->email)->send(new BookingConfirmation($pdfClient));
        Mail::to($this->adminMail)->send(new BookingAdmin($pdfAdmin));

        // Messaggio di conferma
        session()->flash('message', __('ui.confirmation_message'));

        // Eventuale reindirizzamento
        return redirect()->to('/');
    }

    private function generatePDF($booking, $language)
    {
        $data = compact('booking');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pdf.booking-summary-pdf_' . $language, $data)->render());
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
