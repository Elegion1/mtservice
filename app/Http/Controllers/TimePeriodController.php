<?php

namespace App\Http\Controllers;

use App\Models\CarPrice;
use App\Models\TimePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimePeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validazione dei dati
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'base_price' => 'required|numeric|min:0',
            'cars' => 'array',
            'cars.*' => 'exists:cars,id',
        ]);

        // Crea il periodo
        $period = TimePeriod::create([
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

        // Se sono state selezionate auto, crea i CarPrice automaticamente
        if (!empty($validated['cars'])) {
            foreach ($validated['cars'] as $carId) {
                CarPrice::create([
                    'car_id' => $carId,
                    'time_period_id' => $period->id,
                    'price' => $validated['base_price'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Periodo creato e auto associate con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimePeriod $TimePeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TimePeriod $TimePeriod) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimePeriod $timePeriod)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'base_price' => 'nullable|numeric|min:0',
        ]);

        // Aggiorna il periodo esistente
        $timePeriod->update([
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

        // Se è stato inserito un prezzo, aggiorna tutti i CarPrice associati a questo periodo
        if (!empty($validated['base_price'])) {
            $timePeriod->carPrices()->update([
                'price' => $validated['base_price'],
            ]);
        }

        return redirect()->back()->with('success', 'Periodo aggiornato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimePeriod $timePeriod)
    {
        $timePeriod->delete();

        return redirect()->back()->with('success', 'Periodo eliminato con successo!');
    }
}
