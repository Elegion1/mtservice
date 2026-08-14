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
        // 0. HONEYPOT: Se un bot ha compilato il campo invisibile, abortiamo silenziosamente
        if ($request->filled('website_hp')) {
            Log::info('Spam bloccato via Honeypot dall\'IP: '.$request->ip());

            return redirect()
                ->route('contattaci', ['locale' => app()->getLocale()])
                ->with('message', __('ui.contactMailMessage'));
        }

        // 1. VERIFICA GOOGLE RECAPTCHA v3
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

        // Force del cast a FLOAT per evitare confronti stringa errati
        $minScore = (float) getSetting('min_recaptcha_score', 0.8);
        $scoreArrivato = (float) ($recaptchaData['score'] ?? 0);

        if (! ($recaptchaData['success'] ?? false) || $scoreArrivato < $minScore) {
            Log::warning('Spam rilevato via reCAPTCHA v3', [
                'ip' => $request->ip(),
                'score_ricevuto' => $scoreArrivato,
                'score_minimo' => $minScore,
            ]);

            return redirect()
                ->route('contattaci', ['locale' => app()->getLocale()])
                ->withErrors(['recaptcha' => 'Attività sospetta rilevata. Riprova.'])
                ->withInput();
        }

        // 2. VALIDAZIONE
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:255',
            'servizio' => 'required|string|max:255',
            'messaggio' => 'required|string',
        ]);

        // 3. LOCK CONTRO DOPPIO SUBMIT CASUALE (15 secondi)
        $lockKey = 'contact_submit_'.md5($request->ip().$validatedData['email'].$validatedData['messaggio']);

        if (Cache::has($lockKey)) {
            Log::warning('Rilevato invio duplicato bloccato per l\'IP: '.$request->ip());

            return redirect()
                ->route('contattaci', ['locale' => app()->getLocale()])
                ->with('message', __('ui.contactMailMessage'));
        }

        Cache::put($lockKey, true, 15);

        // 4. SALVATAGGIO E INVIO
        $ownerData = OwnerData::first();
        $adminMail = $ownerData->email ?? config('mail.from.address');

        $contatto = new Contact;
        $contatto->fill($validatedData);
        $contatto->save();

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
