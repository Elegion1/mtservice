<?php

namespace App\Livewire;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Livewire\Component;
use Barryvdh\DomPDF\PDF;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;

class BookingSummary extends Component
{
    public $bookingData;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $body;

    public function mount($bookingData)
    {
        $this->bookingData = $bookingData;
    }

    public function render()
    {
        return view('livewire.booking-summary');
    }

    public function confirmBooking()
    {
        // Salvataggio della prenotazione nel database
        $booking = Booking::create([
            'bookingData' => $this->bookingData,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'body' => $this->body,

            // Altri campi della prenotazione
        ]);

        // Generazione del PDF del riepilogo
        $pdf = $this->generatePDF( $booking );

        // Invio dell'email con il PDF allegato
        Mail::to($this->email)->send(new BookingConfirmation($pdf));

        // Messaggio di conferma
        session()->flash('message', 'Prenotazione confermata. Ti è stata inviata una email di conferma.');

        // Eventuale reindirizzamento
        return redirect()->to('/');
    }

    private function generatePDF($booking)
    {
        $data = compact('booking');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('pdf.booking-summary-pdf', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

}
