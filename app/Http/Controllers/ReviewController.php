<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReviewController extends Controller
{

    public function createReview($locale, $booking_code)
    {
        $booking = Booking::where('code', $booking_code)->first();
        // dd($locale, $booking_code, $booking);

        if (!$booking || $booking->status !== 'confirmed') {
            return redirect()->route('home', ['locale' => $locale])->with('error', 'Codice di prenotazione non valido.');
        }

        return view('pages.reviews.create', compact('booking'));
    }

    public function create()
    {
        return view('dashboard.review');
    }

    public function saveReview(Request $request)
    {   
        // Validazione dei dati in ingresso
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'booking' => 'required|string|exists:bookings,code', // Assicurati che il booking_code esista
        ]);
        
        // Trova la prenotazione in base al codice
        $booking = Booking::where('code', $request->booking)->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Codice di prenotazione non valido.');
        }
        
        // Aggiungi il valore "pending" al campo status e associa la recensione alla prenotazione
        $validatedData['status'] = 'pending';
        $validatedData['booking'] = $booking->code; // Associa la recensione alla prenotazione
        
        // Crea la recensione
        Review::create($validatedData);
        
        // Redirect con messaggio di successo
        return redirect()->route('home')->with('success', 'La tua recensione è stata inviata ed è in attesa di approvazione.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,confirmed,rejected',
        ]);

        $review = new Review();
        $review->name = $validated['name'];
        $review->title = $validated['title'];
        $review->body = $validated['body'];
        $review->rating = $validated['rating'];
        $review->status = $validated['status'];
        $review->save();

        return redirect()->route('dashboard.review')->with('success', 'Recensione creata con successo!');
    }

    public function acceptReview(Review $review)
    {
        $review->status = 'confirmed';
        $review->save();

        return redirect()->route('dashboard.review')->with('success', 'Recensione accettata con successo!');
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,confirmed,rejected',
        ]);

        $review->update($validated);

        return redirect()->route('dashboard.review')->with('success', 'Recensione aggiornata con successo!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('dashboard.review')->with('success', 'Recensione eliminata con successo!');
    }
}
