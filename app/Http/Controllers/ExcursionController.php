<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use Illuminate\Http\Request;

class ExcursionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $excursions = Excursion::all();
        return view('dashboard.excursion', compact('excursions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'price_increment' => 'required|numeric',
        ]);

        $excursion = new Excursion();
        $excursion->name = $validated['name'];
        $excursion->price = $validated['price'];
        $excursion->price_increment = $validated['price_increment'];
        $excursion->save();

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione creata con successo!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Excursion $excursion)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'price_increment' => 'required|numeric',
        ]);

        $excursion->update($validated);

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Excursion $excursion)
    {
        $excursion->delete();

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione eliminata con successo!');
    }
}
