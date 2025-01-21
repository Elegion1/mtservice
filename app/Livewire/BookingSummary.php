<?php

namespace App\Livewire;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\OwnerData;
use App\Mail\BookingAdmin;
use App\Mail\ReviewRequest;
use Illuminate\Support\Str;
use App\Models\CountryDialCode;
use App\Mail\BookingConfirmation;
use App\Jobs\SendReviewRequestJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BookingSummary extends Component
{
    public $bookingData;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $body;
    public $accept_policy = false;

    public $adminMail;
    public $serviceDate;

    public $currentStep = 1; // Step iniziale

    public $dialCodes = [];
    public $dialCode = '+39';
    public $dialFlag = [
        'png' => null,
        'alt' => null,
    ];

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
            'accept_policy' => 'accepted',
            'dialCode' => 'required|string',
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
            'accept_policy.accepted' => __('ui.privacy_policy_accepted'),
        ];
    }

    public function submitMessage()
    {
        $this->validate([
            'body' => 'required|string|max:1000',
        ]);

        $this->currentStep = 2;
    }

    public function goToStep($step)
    {
        $this->currentStep = $step;
    }

    public function mount($bookingData)
    {
        $this->bookingData = $bookingData;
        $ownerData = OwnerData::first();
        $this->adminMail = $ownerData->email;

        $this->originalPrice = $this->bookingData['price'];
        $this->discountedPrice = $this->bookingData['price'];
        $this->dialCodes = $this->getCountryDialCodes();
        $this->dialFlag = $this->getDialFlag($this->dialCode);
    }

    public function updated($field)
    {
        if (in_array($field, ['name', 'surname', 'email', 'phone'])) {
            $this->validateOnly($field);
            $this->calculatePrice();
        }
        if ($field === 'dialCode') {
            $this->dialFlag = $this->getDialFlag($this->dialCode);
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

    public function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Booking::where('code', $code)->exists());

        return $code;
    }

    public function getCountryDialCodes()
    {
        // Prova a recuperare i codici dialettali dal database
        $dialCodes = CountryDialCode::all()->toArray();

        // Se non ci sono codici nel database, scarica i codici dal servizio esterno
        if (empty($dialCodes)) {
            $url = 'https://restcountries.com/v3.1/all?fields=name,idd,flags';
            $response = Http::get($url);

            if ($response->successful()) {
                $countries = $response->json();
                $dialCodes = [];

                foreach ($countries as $country) {
                    // Controlla se 'idd' esiste e ha la chiave 'root'
                    if (isset($country['idd']['root'])) {
                        $root = $country['idd']['root'];
                        $suffixes = $country['idd']['suffixes'] ?? [];

                        // Se c'è un solo suffisso, lo concateno al prefisso
                        if (count($suffixes) === 1) {
                            $code = $root . $suffixes[0];
                        } else {
                            // Se ci sono più suffissi, prendo solo il root
                            $code = $root;
                        }

                        // Seleziona il nome nativo
                        $nativeName = '';
                        if (isset($country['name']['nativeName']) && is_array($country['name']['nativeName'])) {
                            $firstNativeLang = array_key_first($country['name']['nativeName']); // Prendi la prima lingua nativa
                            if (isset($country['name']['nativeName'][$firstNativeLang]['common'])) {
                                $nativeName = $country['name']['nativeName'][$firstNativeLang]['common'];
                            }
                        }

                        // Tronca il valore 'alt' se supera i 254 caratteri
                        $alt = isset($country['flags']['alt']) ? $country['flags']['alt'] : '';
                        $alt = strlen($alt) > 254 ? substr($alt, 0, 254) : $alt;

                        $dialCodes[] = [
                            'name' => $nativeName ?: $country['name']['common'], // Usa il nome nativo o il nome comune
                            'code' => $code,
                            'flag' => $country['flags']['png'],
                            'alt' => $alt,
                        ];
                    }
                }

                // Ordina i dial codes per nome del paese in ordine alfabetico
                usort($dialCodes, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });

                // Salva i codici nel database
                foreach ($dialCodes as $dialCode) {
                    DB::table('country_dial_codes')->updateOrInsert(
                        ['code' => $dialCode['code']], // Condizione di esistenza
                        [
                            'name' => $dialCode['name'],
                            'flag' => $dialCode['flag'],
                            'alt' => $dialCode['alt'],
                        ]
                    );
                }
            }
        }

        // Restituisci i codici dialettali (dal database)
        return CountryDialCode::all()->toArray(); // Recupera di nuovo i dati dal database
    }

    protected function getDialFlag($dialCode)
    {
        foreach ($this->dialCodes as $code) {
            if ($code['code'] === $dialCode) {
                return [
                    'png' => $code['flag'],
                    'alt' => $code['alt'],
                ];
            }
        }
        return ['png' => null, 'alt' => null]; // Restituisci null se non trovi una corrispondenza
    }

    public function generateServiceDate($bookingData)
    {
        if ($bookingData['type'] === 'transfer') {
            if (!empty($bookingData['date_ret'])) {
                return Carbon::parse($bookingData['date_ret'])->addDay()->toDateString();
            } else {
                return Carbon::parse($bookingData['date_dep'])->addDay()->toDateString();
            }
        }

        if ($bookingData['type'] === 'escursione') {
            return Carbon::parse($bookingData['date_dep'])->addDay()->toDateString();
        }

        if ($bookingData['type'] === 'noleggio') {
            return Carbon::parse($bookingData['date_end'])->addDay()->toDateString();
        }
    }

    public function confirmBooking()
    {
        try {
            // Pulizia e normalizzazione input
            $this->normalizeInputs();

            // Validazione dati
            $this->validate();

            // Preparazione dati prenotazione
            $this->bookingData['original_price'] = $this->originalPrice;
            $this->bookingData['price'] = $this->discountedPrice;

            // Salvataggio della prenotazione
            $booking = Booking::create([
                'bookingData' => $this->bookingData,
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'phone' => $this->phone,
                'dial_code' => $this->dialCode,
                'body' => $this->body,
                'code' => $this->generateUniqueCode(),
                'locale' => app()->getLocale(),
                'service_date' => $this->generateServiceDate($this->bookingData),
            ]);
            // Gestione cliente
            $createCustomer = Setting::where('create_customer', 'name')->value('value')->get();
            
            if($createCustomer)
            {
            Customer::firstOrCreate([
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'dial_code' => $this->dialCode,
                'phone' => $this->phone,
            ]);
            }

            // Invio email
            $this->sendBookingEmails($booking);
            Log::info('User created a booking: type: ' . $booking->bookingData['type'] . ' name: ' . $booking->name . ' ' . $booking->surname);
            // Messaggio di conferma e redirect
            session()->flash('message', __('ui.confirmation_message'));
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Booking confirmation error: ' . $e->getMessage());
            session()->flash('error', __('ui.booking_error_message'));
        }
    }

    private function normalizeInputs()
    {
        $this->name = trim($this->name);
        $this->surname = trim($this->surname);
        $this->email = trim($this->email);

        $this->phone = preg_replace('/[^\d+]/', '', $this->phone);
        $this->phone = ltrim($this->phone, '+');
        // $this->phone = $this->dialCode . $this->phone;

        // if (!str_starts_with($this->phone, '+')) {
        //     $this->phone = '+' . $this->phone;
        // }
    }

    private function sendBookingEmails($booking)
    {
        $language = app()->getLocale();
        $pdfClient = $this->generatePDF($booking, $language);

        // Invio email all'amministrazione
        $this->sendAdminEmails($booking, $language);
        // Invio email al cliente
        $this->sendEmailSafely($this->email, new BookingConfirmation($booking, $pdfClient), 'Failed to send booking confirmation email');
        // $this->sendEmailSafely($this->email, new ReviewRequest($booking), 'Failed to send review request email');
    }

    private function sendAdminEmails($booking, $language)
    {
        $pdfAdmin = $this->generatePDF($booking, 'it');

        // Cambia lingua e invia email
        App::setLocale('it');
        try {
            Mail::to($this->adminMail)->send(new BookingAdmin($booking, $pdfAdmin));
        } catch (\Exception $e) {
            Log::error('Failed to send admin email: ' . $e->getMessage());
        } finally {
            // Ripristina la lingua originale
            App::setLocale($language);
        }
    }

    private function sendEmailSafely($recipient, $mailable, $errorMessage)
    {
        try {
            Mail::to($recipient)->send($mailable);
        } catch (\Exception $e) {
            Log::error($errorMessage . ': ' . $e->getMessage());
        }
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
        // dd($this->dialFlag);
        return view('livewire.booking-summary', [
            'originalPrice' => $this->originalPrice,
            'discountedPrice' => $this->discountedPrice,
            'dialFlag' => $this->dialFlag,
        ]);
    }
}
