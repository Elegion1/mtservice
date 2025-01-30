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
        ]);

        // Crea il periodo
        TimePeriod::create([
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

        return redirect()->back()->with('success', 'Periodo creato con successo!');
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
        ]);

        // Aggiorna il periodo esistente
        $timePeriod->update([
            'start' => $validated['start'],
            'end' => $validated['end'],
        ]);

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
