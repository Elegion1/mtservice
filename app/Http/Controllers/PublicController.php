<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Review;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home() {
        return view('welcome');
    }

    public function dashboard() {
        return view('dashboard.index');
    }

    public function noleggio() {
        return view('pages.noleggio-auto');
    }

    public function transfer() {
        return view('pages.transfer');
    }

    public function escursioni() {
        return view('pages.escursioni');
    }

    public function prezziDestinazioni() {        
        return view('pages.prezzi-destinazioni');
    }

    public function diconoDiNoi() {
        return view('pages.dicono-di-noi');
    }

    public function contattaci() {
        return view('pages.contattaci');
    }

    public function partners() {
        return view('pages.partners');
    }

    public function faq() {
        return view('pages.faq');
    }

    public function pdf() {
        $booking = [
            'name' => 'Mario',
            'surname' => 'Rossi',
            'email' => 'mario.rossi@example.com',
            'phone' => '+39 123 456 7890',
            'body' => 'Queste sono alcune note per la prenotazione.',
            'bookingData' => [
                'type' => 'transfer', // Può essere 'transfer', 'escursione' o 'noleggio'
                'departure_name' => 'Roma',
                'arrival_name' => 'Milano',
                'date_departure' => '2024-07-01',
                'time_departure' => '10:00',
                'date_return' => '2024-07-02',
                'time_return' => '18:00',
                'duration' => 60,
                'passengers' => 4,
                'price' => 150.00,
                'car_name' => 'Fiat 500',
                'car_description' => 'Auto compatta, perfetta per la città',
                'date_start' => '2024-07-01',
                'date_end' => '2024-07-07',
                'quantity' => 1
            ]
        ];
        return view('pdf.booking-summary-pdf', compact('booking'));
    }
}
