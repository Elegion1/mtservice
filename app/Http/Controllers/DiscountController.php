<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\TimePeriod;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return view('dashboard.discount', compact('discounts'));
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
        // Valida i dati della richiesta
        $validated = $request->validate([
            'name_it' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'applicable_to' => 'required|in:all,customers',
            'applies_to_transfer' => 'sometimes|boolean',
            'applies_to_rental' => 'sometimes|boolean',
            'applies_to_excursion' => 'sometimes|boolean',
            'periods.*.start' => 'sometimes|date|nullable',
            'periods.*.end' => 'sometimes|date|nullable|after_or_equal:periods.*.start',
        ]);

        // Assegna il valore "0" per i checkbox non presenti nella richiesta
        $validated['applies_to_transfer'] = $request->has('applies_to_transfer') ? 1 : 0;
        $validated['applies_to_rental'] = $request->has('applies_to_rental') ? 1 : 0;
        $validated['applies_to_excursion'] = $request->has('applies_to_excursion') ? 1 : 0;

        // Crea lo sconto nel database
        $discount = Discount::create($validated);

        // Salva i periodi di validità associati solo se forniti
        if ($request->has('periods')) {
            $periods = $request->input('periods');
            foreach ($periods as $period) {
                if ($period['start'] && $period['end']) {
                    $discount->time_periods()->create([
                        'start' => $period['start'],
                        'end' => $period['end'],
                    ]);
                }
            }
        }

        return redirect()->route('dashboard.discount')->with('success', 'Sconto creato con successo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        // dd($request, $discount);
        // Valida i dati della richiesta
        $validated = $request->validate([
            'name_it' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'applicable_to' => 'required|in:all,customers',
            'applies_to_transfer' => 'sometimes|boolean',
            'applies_to_rental' => 'sometimes|boolean',
            'applies_to_excursion' => 'sometimes|boolean',
            'periods.*.start' => 'sometimes|date|nullable',
            'periods.*.end' => 'sometimes|date|nullable|after_or_equal:periods.*.start',
        ]);

        // Assegna il valore "0" per i checkbox non presenti nella richiesta
        $validated['applies_to_transfer'] = $request->has('applies_to_transfer') ? 1 : 0;
        $validated['applies_to_rental'] = $request->has('applies_to_rental') ? 1 : 0;
        $validated['applies_to_excursion'] = $request->has('applies_to_excursion') ? 1 : 0;

        // Aggiorna lo sconto nel database
        $discount->update($validated);

        // Gestisci i periodi di validità associati
        if ($request->has('periods')) {
            // Cancella i periodi esistenti
            $discount->time_periods()->delete();

            // Salva i nuovi periodi di validità
            $periods = $request->input('periods');
            foreach ($periods as $period) {
                // Solo salva il periodo se la data di fine è successiva o uguale alla data di inizio
                if (strtotime($period['end']) >= strtotime($period['start'])) {
                    $discount->time_periods()->create([
                        'start' => $period['start'],
                        'end' => $period['end'],
                    ]);
                } else {
                    // Gestisci l'errore, puoi anche aggiungere un messaggio di errore personalizzato
                    return redirect()->back()->withErrors(['periods' => 'La data di fine deve essere successiva o uguale alla data di inizio']);
                }
            }
        }

        return redirect()->route('dashboard.discount')->with('success', 'Sconto aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        // Cancellazione dei periodi di validità associati
        // $discount->time_periods()->delete();

        // Cancellazione dello sconto
        $discount->delete();

        // Reindirizzamento con messaggio di successo
        return redirect()->route('dashboard.discount')->with('success', 'Sconto eliminato con successo!');
    }
}
