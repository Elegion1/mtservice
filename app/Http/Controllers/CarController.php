<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Image;
use App\Models\CarPrice;
use App\Models\TimePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        App::setlocale('it');
        $cars = Car::all();
        $carPrices = CarPrice::with('timePeriod')->get();
        $timePeriods = TimePeriod::all();
        return view('dashboard.car', compact('cars', 'carPrices', 'timePeriods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug iniziale
        // dd($request->all());

        // Validazione dei dati
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        // Crea la macchina
        $car = Car::create($validated);

        // Salva le immagini
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'car_id' => $car->id,
                ]);
            }
        }

        return redirect()->route('dashboard.car')->with('success', 'Auto creata con successo!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $car->update($request->all());

        // // Associa periodi esistenti
        // if ($request->has('existing_periods') && is_array($request->input('existing_periods'))) {
        //     foreach ($request->input('existing_periods') as $periodId) {
        //         CarPrice::create([
        //             'car_id' => $car->id,
        //             'time_period_id' => $periodId,
        //             'price' => $car->price, // Usa il prezzo base o specifica un altro input
        //         ]);
        //     }
        // }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'car_id' => $car->id,
                ]);
            }
        }


        return redirect()->route('dashboard.car')->with('success', 'Auto aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verifica se l'auto ha un'immagine associata e elimina l'immagine
        $car = Car::findOrFail($id);

        // Elimina l'auto dal database
        $car->delete();

        return redirect()->route('dashboard.car')->with('success', 'Auto eliminata con successo!');
    }
}
