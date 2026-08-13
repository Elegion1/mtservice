<?php

namespace App\Http\Controllers;

use App\Mail\AdminContactMail;
use App\Mail\ContactMail;
use App\Models\Contact;
use App\Models\OwnerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function invia(Request $request)
    {
        // 1. VERIFICA GOOGLE RECAPTCHA
        $recaptchaToken = $request->input('g-recaptcha-response');

        if (! $recaptchaToken) {
            Log::warning('Invio form bloccato: Token reCAPTCHA assente.');

            return redirect()
                ->route('contattaci', ['locale' => app()->getLocale()])
                ->withErrors(['recaptcha' => 'Verifica bot fallita. Riprova.'])
                ->withInput();
        }

        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        $recaptchaData = $recaptchaResponse->json();

        // Verifichiamo che la risposta sia positiva e che lo score sia >= 0.3 (o 0.5)
        if (! ($recaptchaData['success'] ?? false) || ($recaptchaData['score'] ?? 0) < getSetting('min_recaptcha_score', 0.5)) {
            Log::warning('Spam rilevato via reCAPTCHA v3', [
                'ip' => $request->ip(),
                'score' => $recaptchaData['score'] ?? 'N/A',
            ]);

            return redirect()
                ->route('contattaci', ['locale' => app()->getLocale()])
                ->withErrors(['recaptcha' => 'Attività sospetta rilevata. Riprova.'])
                ->withInput(); // Mantiene i dati già inseriti nei campi
        }

        // 2. PROCEDI CON IL NORMALE FLUSSO
        $ownerData = OwnerData::first();
        $adminMail = $ownerData->email;

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
        $contatto = new Contact;
        $contatto->fill($validatedData);
        $contatto->save();

        // Invia le email...
        sendEmail(
            $contatto->email,
            new ContactMail($contatto),
            'Errore nell\'invio dell\'email al contatto',
            $contatto->locale
        );

        sendEmail(
            $adminMail,
            new AdminContactMail($contatto, $contatto->email),
            'Errore nell\'invio dell\'email all\'amministratore',
            $contatto->locale
        );

        Log::info('User sent a contact form '.$validatedData['nome'].' '.$validatedData['cognome']);

        // REDIRECT DI SUCCESSO SULLA STESSA ROTTA
        return redirect()
            ->route('contattaci', ['locale' => app()->getLocale()])
            ->with('message', __('ui.contactMailMessage'));
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
        $contact->read = ! $contact->read;
        $contact->save();

        return response()->json([
            'success' => true,
            'read' => $contact->read,
        ]);
    }

    // public function markAllRead()
    // {
    //     try {
    //         $updated = Contact::where('read', false)->update(['read' => true]);
    //         return response()->json(['success' => $updated > 0]);
    //     } catch (Exception $e) {
    //         Log::error('Errore durante l\'aggiornamento dei messaggi: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Errore durante l\'aggiornamento.'], 500);
    //     }
    // }

    // public function markAllUnread()
    // {
    //     try {
    //         $updated = Contact::where('read', true)->update(['read' => false]);
    //         return response()->json(['success' => $updated > 0]);
    //     } catch (Exception $e) {
    //         Log::error('Errore durante l\'aggiornamento dei messaggi: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Errore durante l\'aggiornamento.'], 500);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('dashboard.contact')->with('success', 'Messaggio eliminato con successo!');
    }
}
