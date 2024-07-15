<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function invia(Request $request)
    {
        // Valida i dati del form
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:255',
            'servizio' => 'required|string|max:255',
            'messaggio' => 'required|string',
        ]);

        // Crea un nuovo contatto
        $contatto = new Contact();
        $contatto->fill($validatedData);
        $contatto->save();

        // Invia l'email
        Mail::to('tua@email.com')->send(new ContactMail($contatto));

        return redirect()->back()->with('message', 'Dati inseriti nel database e email inviata con successo!' );
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('dashboard.contact', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('dashboard.contact')->with('success', 'Messaggio eliminato con successo!');
    }
}
