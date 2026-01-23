<?php

namespace App\Livewire;

use Carbon\Carbon;

use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\OwnerData;
use App\Mail\BookingAdmin;

use App\Models\CountryDialCode;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;


class BookingSummary extends Component
{
    #[Locked]
    public $bookingData;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $body;
    #[Locked]
    public $info;
    public $accept_policy = false;

    #[Locked]
    public $adminMail;
    #[Locked]
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

    public $flightNumber;
    public $departureAirport;
    public $departureTime;
    public $arrivalAirport;
    public $arrivalTime;

    public $driverName;
    public $driverBirthDate;
    public $driverBirthPlace;
    public $driverAddress;
    public $driverCity;
    public $driverPostalCode;
    public $driverCountry;
    public $driverLicenseNumber;
    public $driverLicenseType;
    public $driverLicenseIssueDate;
    public $driverLicenseExpirationDate;
    public $driverLicenseCountry;
    public $driverLicenseProvince;

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
        Log::info('submitMessage() avviata', [
            'booking_type' => $this->bookingData['type'] ?? 'non specificato'
        ]);

        // 1. Regole di validazione base
        $validationRules = ['body' => 'required|string'];

        // 2. Campi opzionali del volo e del guidatore
        $flightFields = [
            'flightNumber',
            'departureAirport',
            'departureTime',
            'arrivalAirport',
            'arrivalTime',
        ];

        $driverFields = [
            'driverName',
            'driverBirthDate',
            'driverBirthPlace',
            'driverAddress',
            'driverCity',
            'driverPostalCode',
            'driverCountry',
            'driverLicenseNumber',
            'driverLicenseType',
            'driverLicenseIssueDate',
            'driverLicenseExpirationDate',
            'driverLicenseCountry',
            'driverLicenseProvince',
        ];

        $dateFields = [
            'driverBirthDate',
            'driverLicenseIssueDate',
            'driverLicenseExpirationDate',
        ];

        // 3. Validazione aggiuntiva se è noleggio
        if ($this->bookingData['type'] === 'noleggio') {
            foreach ($driverFields as $field) {
                $validationRules[$field] = in_array($field, $dateFields) ? 'required|date' : 'required|string';
            }

            Log::info('Validazione estesa per il noleggio attivata', [
                'validation_rules' => $validationRules
            ]);
        }

        // 4. Validazione iniziale
        $validatedData = $this->validate($validationRules);
        Log::info('Validazione completata con successo');

        // 5. Validazioni custom post-validazione
        if ($this->bookingData['type'] === 'noleggio') {
            $startDate = Carbon::parse($this->bookingData['date_start']);
            $endDate = Carbon::parse($this->bookingData['date_end']);
            $birthDate = Carbon::parse($this->driverBirthDate);
            $licenseIssue = Carbon::parse($this->driverLicenseIssueDate);
            $licenseExpiry = Carbon::parse($this->driverLicenseExpirationDate);

            $errors = [];

            if ($birthDate->diffInYears($startDate) < 18) {
                $errors['driverBirthDate'] = __('ui.driverAgeLimit');
            }

            if ($licenseIssue->gt($startDate)) {
                $errors['driverLicenseIssueDate'] = __('ui.driverLicenseIssueLimit');
            }

            if ($licenseExpiry->lt($endDate)) {
                $errors['driverLicenseExpirationDate'] = __('ui.driverLicenseExpireLimit');
            }

            if (!empty($errors)) {
                Log::warning('Validazioni custom fallite', $errors);
                throw ValidationException::withMessages($errors);
            }
        }

        // 6. Costruzione dati info
        $infoData = [
            'flight' => $this->extractFields($flightFields),
        ];
        Log::info('Dati volo raccolti', $infoData['flight']);

        if ($this->bookingData['type'] === 'noleggio') {
            $infoData['driver'] = $this->extractFields($driverFields);
            Log::info('Dati guidatore raccolti', $infoData['driver']);
        }

        // 7. Salvataggio dati
        $this->info = json_encode($infoData);
        $this->body = $this->body;

        Log::info('Dati salvati su info e body', [
            'info' => $this->info,
            'body' => $this->body,
        ]);

        // 8. Step successivo
        Log::info('Passaggio allo step successivo');
        goToStep(2, $this->currentStep);
    }

    private function extractFields(array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->$field ?? null;
        }
        return $data;
    }

    public function goToStep($step)
    {
        goToStep($step, $this->currentStep);
    }


    public function goBack()
    {
        $this->dispatch('goBack');
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
        if (in_array($field, ['name', 'surname', 'email', 'phone', 'body'])) {
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

        // Verifica se il cliente ha uno sconto specifico
        $this->applyCustomerDiscount();

        // Applica gli sconti generali
        $this->applyGeneralDiscounts();
    }

    private function applyCustomerDiscount()
    {
        // Verifica se il cliente esiste già nel database
        $customer = $this->getCustomer();

        // Se il cliente ha uno sconto specifico, applicalo e bypassa gli altri sconti
        if ($customer && $customer->discount > 0) {
            $discountAmount = ($this->originalPrice * $customer->discount) / 100;
            $this->discountedPrice = $this->originalPrice - $discountAmount;
            $this->discountPercentage = $customer->discount;
            $this->discountType_it = __('ui.customerDiscountMessage');
            $this->discountType_en = __('ui.customerDiscountMessage'); // Messaggio del tipo di sconto
        }
    }

    private function applyGeneralDiscounts()
    {
        // Ottieni tutti gli sconti attivi con i periodi associati
        $discounts = Discount::with('time_periods')->get();

        // Controlla gli sconti disponibili
        foreach ($discounts as $discount) {
            $applyDiscount = $this->isDiscountApplicable($discount);

            // Procede solo se lo sconto è applicabile al servizio richiesto
            if ($applyDiscount && $this->isServiceApplicable($discount)) {
                $this->applyDiscountToPrice($discount);
            }
        }
    }

    private function getCustomer()
    {
        return Customer::where('name', $this->name)
            ->where('surname', $this->surname)
            ->where('email', $this->email)
            ->where('phone', $this->phone)
            ->first();
    }

    private function isDiscountApplicable($discount)
    {
        // Verifica se lo sconto è applicabile ai clienti esistenti o a tutti
        if (($discount->applicable_to === 'customers' && $this->getCustomer()) || $discount->applicable_to === 'all') {
            // Verifica se lo sconto è valido in un periodo specifico
            return $this->isDiscountInValidPeriod($discount);
        }

        return false;
    }

    private function isDiscountInValidPeriod($discount)
    {
        // Se ci sono periodi di sconto, verifica se lo sconto è valido in questo momento
        if ($discount->time_periods->isEmpty()) {
            return true;
        }

        $now = now();
        foreach ($discount->time_periods as $period) {
            if ($now->between($period->start, $period->end)) {
                return true;
            }
        }

        return false;
    }

    private function isServiceApplicable($discount)
    {
        // Controlla se lo sconto è applicabile al tipo di servizio specifico
        switch ($this->bookingData['type']) {
            case 'transfer':
                return $discount->applies_to_transfer;
            case 'noleggio':
                return $discount->applies_to_rental;
            case 'escursione':
                return $discount->applies_to_excursion;
            default:
                return false;
        }
    }

    private function applyDiscountToPrice($discount)
    {
        // Se lo sconto è applicabile, calcola il prezzo scontato
        if ($discount->percentage > 0) {
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
        $dateKeyMap = [
            'transfer' => !empty($bookingData['date_ret']) ? 'date_ret' : 'date_dep',
            'escursione' => 'date_dep',
            'noleggio' => 'date_end',
        ];

        // Determina la chiave corretta basata sul tipo di prenotazione
        $dateKey = $dateKeyMap[$bookingData['type']] ?? null;

        // Se la chiave esiste, calcola e restituisce la data
        return $dateKey ? Carbon::parse($bookingData[$dateKey])->addDay()->toDateString() : null;
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

            $this->info = is_string($this->info) ? json_decode($this->info, true) : $this->info;
            
            // Crea un hash idempotent dei dati per prevenire duplicati
            $idempotencyKey = hash('sha256', json_encode([
                'email' => $this->email,
                'phone' => $this->phone,
                'name' => $this->name,
                'bookingData' => $this->bookingData,
                'service_date' => $this->generateServiceDate($this->bookingData),
            ]));
            
            // Usa una transazione per garantire atomicità
            $booking = DB::transaction(function () use ($idempotencyKey) {
                // 1. Verifica se una booking identica è stata creata negli ultimi 10 secondi
                $existingBooking = Booking::where('email', $this->email)
                    ->where('phone', $this->phone)
                    ->where('name', $this->name)
                    ->where('created_at', '>=', now()->subSeconds(10))
                    ->first();
                
                if ($existingBooking) {
                    Log::warning('[BookingSummary] Duplicate booking detected within 10 seconds', [
                        'email' => $this->email,
                        'existing_code' => $existingBooking->code,
                        'idempotency_key' => $idempotencyKey,
                    ]);
                    // Ritorna la booking esistente invece di crearne una nuova
                    return $existingBooking;
                }
                
                // 2. Se non esiste, crea una nuova booking
                $bookingCode = generateUniqueCode();
                
                // Verifica che il codice non esista già (protezione aggiuntiva)
                while (Booking::where('code', $bookingCode)->exists()) {
                    Log::warning('[BookingSummary] Duplicate code detected, generating new one');
                    $bookingCode = generateUniqueCode();
                }
                
                // Salvataggio della prenotazione
                return Booking::create([
                    'bookingData' => $this->bookingData,
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'dial_code' => $this->dialCode,
                    'body' => $this->body,
                    'info' => $this->info,
                    'code' => $bookingCode,
                    'locale' => app()->getLocale(),
                    'service_date' => $this->generateServiceDate($this->bookingData),
                ]);
            });

            // Invio email (solo per booking nuove, non duplicati)
            if (!$booking->wasRecentlyCreated || $booking->created_at->diffInSeconds(now()) < 2) {
                // Booking appena creata
                $this->sendBookingEmails($booking);
                Log::info('[BookingSummary] User created a booking: type: ' . $booking->bookingData['type'] . ' name: ' . $booking->name . ' ' . $booking->surname);
            } else {
                Log::info('[BookingSummary] Duplicate booking detected, using existing booking code: ' . $booking->code);
            }

            // Messaggio di conferma e redirect
            session()->flash('message', __('ui.confirmation_message'));

            $this->createCustomer($this->name, $this->surname, $this->email, $this->dialCode, $this->phone);

            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('[BookingSummary] Booking confirmation error: ' . $e->getMessage());
            session()->flash('error', __('ui.booking_error_message'));
        }
    }

    private function createCustomer($name, $surname, $email, $dialCode, $phone)
    {
        $createCustomer = getSetting('create_customer');
        if ($createCustomer) {
            Customer::firstOrCreate([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'dial_code' => $dialCode,
                'phone' => $phone,
            ]);
            Log::info('[BookingSummary] Customer created');
        } else {
            Log::info('[BookingSummary] Customer not created, setting disabled');
        }
    }

    private function normalizeInputs()
    {
        $this->name = trim($this->name);
        $this->surname = trim($this->surname);
        $this->email = trim($this->email);

        $this->phone = preg_replace('/[^\d+]/', '', $this->phone);
        $this->phone = ltrim($this->phone, '+');
    }

    private function sendBookingEmails($booking)
    {
        $sendWPMessage = getSetting('send_whatsapp_message');

        if ($sendWPMessage) {
            sendWPMessage($booking, 'booking_notification');
        }

        $language = app()->getLocale();

        $pdfClient = null;
        $pdfAdmin = null;

        try {
            $pdfClient = generatePDF($booking, $language);
        } catch (\Throwable $e) {
            logger()->error('Errore nella generazione del PDF per il cliente: ' . $e->getMessage());
        }

        try {
            $pdfAdmin = generatePDF($booking, 'it');
        } catch (\Throwable $e) {
            logger()->error('Errore nella generazione del PDF per l\'admin: ' . $e->getMessage());
        }


        // Invia email all'amministrazione
        sendEmail($this->adminMail, new BookingAdmin($booking, $pdfAdmin), 'Failed to send admin email', 'it');

        // Invia email al cliente
        sendEmail($this->email, new BookingConfirmation($booking, $pdfClient), 'Failed to send booking confirmation email', $language);
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
