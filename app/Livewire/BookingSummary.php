<?php

namespace App\Livewire;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Booking;
use Livewire\Component;
use App\Models\OwnerData;
use App\Mail\BookingAdmin;
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
    public $privacy_policy = false;
    public $terms_conditions = false;
    public $adminMail;

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
    }

    public function render()
    {
        return view('livewire.booking-summary');
    }

    public function confirmBooking()
    {
        $this->validate();

        // Salvataggio della prenotazione nel database
        $booking = Booking::create([
            'bookingData' => $this->bookingData,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'body' => $this->body,
        ]);

        // Generazione del PDF del riepilogo
        $pdf = $this->generatePDF($booking);

        // Invio dell'email con il PDF allegato
        Mail::to($this->email)->send(new BookingConfirmation($pdf));
        Mail::to($this->adminMail)->send(new BookingAdmin($pdf)); // $this->adminMail

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
}
