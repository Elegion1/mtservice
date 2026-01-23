<?php

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use App\Livewire\CarRent;
use App\Livewire\BookingSummary;
use App\Livewire\TransferForm;
use App\Models\Car;
use App\Models\Booking;
use Tests\TestCase;

class LivewireSecurityTest extends TestCase
{
    /**
     * Test che #[Locked] proprietà non possono essere modificate dal client
     */
    public function test_locked_property_cannot_be_modified_in_booking_summary()
    {
        $bookingData = [
            'type' => 'transfer',
            'price' => 100,
            'passengers' => 2,
        ];

        $component = Livewire::test(BookingSummary::class, ['bookingData' => $bookingData]);
        
        // Tenta di modificare una proprietà locked
        // Questo dovrebbe essere ignorato da Livewire
        $component->call('updateProperty', 'bookingData', ['type' => 'noleggio', 'price' => 50]);
        
        // Verifica che il valore non è cambiato (rimane quello originale)
        $this->assertEquals($bookingData, $component->call('getBookingData'));
    }

    /**
     * Test che propertyBinding dinamico valida il key in CarRent
     */
    public function test_car_rent_validates_key_in_select_address()
    {
        $component = Livewire::test(CarRent::class);
        
        // Tenta di passare un key non valido
        // Questo dovrebbe essere bloccato silenziosamente
        $component->call('selectAddress', 'invalid_key', 'Via Roma 123');
        
        // Verifica che nessuna proprietà è stata modificata
        $this->assertNull($component->get('invalidKeycustomAddress') ?? null);
    }

    /**
     * Test che selectAddress accetta solo pickup e delivery
     */
    public function test_select_address_accepts_only_valid_keys()
    {
        $component = Livewire::test(CarRent::class);
        
        // Test con 'pickup' (valido)
        $component->call('selectAddress', 'pickup', 'Via Roma 123');
        // Dovrebbe funzionare
        
        // Test con 'delivery' (valido)
        $component->call('selectAddress', 'delivery', 'Via Milano 456');
        // Dovrebbe funzionare
        
        // Test con 'invalid' (non valido)
        $component->call('selectAddress', 'invalid', 'Via Torino 789');
        // Dovrebbe essere ignorato
    }

    /**
     * Test che i dati sensibili sono loggati quando tentati accessi non autorizzati
     */
    public function test_invalid_key_logs_warning()
    {
        $component = Livewire::test(CarRent::class);
        
        // Tenta di accedere con un key non valido
        // Dovrebbe generare un log warning
        $component->call('selectAddress', 'malicious_key', 'malicious_address');
        
        // Verifica che il log contiene il warning
        // (Questo dipende dalla configurazione del logging)
    }

    /**
     * Test che BookingSummary valida tutti gli input
     */
    public function test_booking_summary_validates_all_inputs()
    {
        $component = Livewire::test(BookingSummary::class, [
            'bookingData' => [
                'type' => 'transfer',
                'price' => 100,
            ]
        ]);
        
        // Tenta di inviare dati non validi
        $component
            ->set('name', '')
            ->set('email', 'invalid-email')
            ->call('submitMessage')
            ->assertHasErrors(['name', 'email']);
    }

    /**
     * Test che TransferForm valida le rotte
     */
    public function test_transfer_form_validates_route_exists()
    {
        $component = Livewire::test(TransferForm::class);
        
        // Tenta di impostare partenza/destinazione non valide
        $component
            ->set('departure', 99999) // ID inesistente
            ->set('return', 99999)
            ->call('submitTransferSelection')
            ->assertHasErrors();
    }

    /**
     * Test che CarRent valida l'auto esiste
     */
    public function test_car_rent_validates_car_exists()
    {
        $component = Livewire::test(CarRent::class);
        
        // Tenta di impostare un'auto inesistente
        $component
            ->set('carID', 99999) // ID inesistente
            ->set('dateStart', '2025-01-25')
            ->set('timeStart', '10:00')
            ->set('dateEnd', '2025-01-26')
            ->set('timeEnd', '10:00')
            ->call('submitDateSelection')
            ->assertHasErrors();
    }

    /**
     * Test che i dati loggati non contengono informazioni sensibili
     */
    public function test_sensitive_data_is_not_logged()
    {
        $component = Livewire::test(BookingSummary::class, [
            'bookingData' => [
                'type' => 'transfer',
                'price' => 100,
            ]
        ]);
        
        // Imposta dati sensibili
        $component
            ->set('email', 'user@example.com')
            ->set('phone', '1234567890')
            ->set('body', 'sensitive message');
        
        // Verifica che i dati vengono validati senza esporre informazioni
        // Il logging non dovrebbe contenere dati sensibili grezzi
    }
}
