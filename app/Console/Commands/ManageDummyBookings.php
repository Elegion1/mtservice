<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ManageDummyBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:manage-dummy 
                            {action : Azione da eseguire (list, delete, cleanup)}
                            {--id= : ID della prenotazione da eliminare}
                            {--days=30 : Giorni per la pulizia automatica}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestisce le prenotazioni fittizie (visualizza, elimina, pulizia automatica)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                return $this->listDummyBookings();
            case 'delete':
                return $this->deleteDummyBooking();
            case 'cleanup':
                return $this->cleanupOldDummyBookings();
            default:
                $this->error('Azione non valida. Usa: list, delete, cleanup');
                return 1;
        }
    }

    /**
     * Lista tutte le prenotazioni fittizie
     */
    private function listDummyBookings()
    {
        $dummyBookings = Booking::whereJsonContains('info->is_dummy_booking', true)
            ->orderBy('service_date', 'desc')
            ->get();

        if ($dummyBookings->isEmpty()) {
            $this->info('Nessuna prenotazione fittizia trovata.');
            return 0;
        }

        $this->info("📋 Prenotazioni fittizie trovate: " . $dummyBookings->count());
        
        $tableData = [];
        foreach ($dummyBookings as $booking) {
            $bookingData = $booking->bookingData;
            $startDate = isset($bookingData['date_start']) ? 
                Carbon::parse($bookingData['date_start'] . ' ' . ($bookingData['time_start'] ?? '00:00'))->format('d/m/Y H:i') : 
                'N/A';
            $endDate = isset($bookingData['date_end']) ? 
                Carbon::parse($bookingData['date_end'] . ' ' . ($bookingData['time_end'] ?? '23:59'))->format('d/m/Y H:i') : 
                'N/A';

            $tableData[] = [
                $booking->id,
                $booking->code,
                $bookingData['car_id'] ?? 'N/A',
                $startDate,
                $endDate,
                $booking->status,
                $bookingData['dummy_reason'] ?? $booking->body
            ];
        }

        $this->table(
            ['ID', 'Codice', 'Auto ID', 'Inizio', 'Fine', 'Status', 'Motivo'],
            $tableData
        );

        return 0;
    }

    /**
     * Elimina una prenotazione fittizia specifica
     */
    private function deleteDummyBooking()
    {
        $bookingId = $this->option('id');
        
        if (!$bookingId) {
            $bookingId = $this->ask('Inserisci l\'ID della prenotazione da eliminare');
        }

        $booking = Booking::where('id', $bookingId)
            ->whereJsonContains('info->is_dummy_booking', true)
            ->first();

        if (!$booking) {
            $this->error('Prenotazione fittizia non trovata con ID: ' . $bookingId);
            return 1;
        }

        $bookingData = $booking->bookingData;
        $this->info("Prenotazione trovata:");
        $this->line("- Codice: {$booking->code}");
        $this->line("- Auto ID: " . ($bookingData['car_id'] ?? 'N/A'));
        $this->line("- Periodo: " . ($bookingData['date_start'] ?? 'N/A') . " - " . ($bookingData['date_end'] ?? 'N/A'));
        $this->line("- Motivo: " . ($bookingData['dummy_reason'] ?? $booking->body));

        if ($this->confirm('Sei sicuro di voler eliminare questa prenotazione fittizia?')) {
            $booking->delete();
            $this->info("✅ Prenotazione fittizia eliminata con successo!");
        } else {
            $this->info("Operazione annullata.");
        }

        return 0;
    }

    /**
     * Pulisce le prenotazioni fittizie vecchie
     */
    private function cleanupOldDummyBookings()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $oldBookings = Booking::whereJsonContains('info->is_dummy_booking', true)
            ->where('service_date', '<', $cutoffDate->format('Y-m-d'))
            ->get();

        if ($oldBookings->isEmpty()) {
            $this->info("Nessuna prenotazione fittizia vecchia di più di {$days} giorni trovata.");
            return 0;
        }

        $this->info("🧹 Trovate {$oldBookings->count()} prenotazioni fittizie vecchie di più di {$days} giorni:");

        $tableData = [];
        foreach ($oldBookings as $booking) {
            $bookingData = $booking->bookingData;
            $tableData[] = [
                $booking->id,
                $booking->code,
                $bookingData['car_id'] ?? 'N/A',
                $booking->service_date,
                $bookingData['dummy_reason'] ?? $booking->body
            ];
        }

        $this->table(
            ['ID', 'Codice', 'Auto ID', 'Data Servizio', 'Motivo'],
            $tableData
        );

        if ($this->confirm("Vuoi eliminare tutte queste {$oldBookings->count()} prenotazioni fittizie?")) {
            $deletedCount = 0;
            foreach ($oldBookings as $booking) {
                $booking->delete();
                $deletedCount++;
            }
            $this->info("✅ Eliminate {$deletedCount} prenotazioni fittizie vecchie!");
        } else {
            $this->info("Operazione annullata.");
        }

        return 0;
    }
}
