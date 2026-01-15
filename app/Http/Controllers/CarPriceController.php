<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarPrice;
use Illuminate\Http\Request;

class CarPriceController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        // Recupera l'auto selezionata
        $car = Car::findOrFail($request->car_id);

        if (!isset($car)) {
            return redirect()->route('dashboard.car')->with('error', 'Auto non trovata!');
        }

        $validated = $request->validate([
            'time_period_id' => 'required|exists:time_periods,id',
            'car_id' => 'required|exists:cars,id',
            'price' => 'required|numeric|min:0',
        ]);

        CarPrice::create([
            'car_id' => $validated['car_id'],
            'time_period_id' => $validated['time_period_id'],
            'price' => $validated['price'], // Usa il prezzo base o specifica un altro input
        ]);

        // Reindirizza con un messaggio di successo
        return redirect()->route('dashboard.car')->with('success', 'Periodi associati con successo!');
    }

    public function update(Request $request, CarPrice $carPrice)
    {
        $carPrice = CarPrice::findOrFail($carPrice->id);

        $validated = $request->validate([
            'time_period_id' => 'required|exists:time_periods,id',
            'car_id' => 'required|exists:cars,id',
            'price' => 'required|numeric|min:0',
        ]);

        $carPrice->update([
            'car_id' => $validated['car_id'],
            'time_period_id' => $validated['time_period_id'],
            'price' => $validated['price'], // Usa il prezzo base o specifica un altro input
        ]);

        return redirect()->route('dashboard.car')->with('success', 'CarPrice aggiornato con successo!');
    }

    public function destroy($id)
    {
        $carPrice = CarPrice::findOrFail($id);
        $carPrice->delete();

        return redirect()->route('dashboard.car')->with('success', 'CarPrice eliminato con successo!');
    }

    /**
     * Ottiene le auto associate a un periodo (per AJAX)
     */
    public function getPeriodCars($timePeriodId)
    {
        $timePeriod = \App\Models\TimePeriod::findOrFail($timePeriodId);
        $cars = $timePeriod->carPrices()->with('car')->get();

        return response()->json([
            'cars' => $cars->map(fn($cp) => [
                'id' => $cp->car->id,
                'name' => $cp->car->name,
                'pivot' => [
                    'price' => $cp->price
                ]
            ])
        ]);
    }

    /**
     * Sincronizza le auto di un periodo (aggiorna/aggiunge/elimina)
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'time_period_id' => 'required|exists:time_periods,id',
            'price' => 'required|numeric|min:0',
            'cars' => 'array',
            'cars.*' => 'exists:cars,id',
        ]);

        $timePeriod = \App\Models\TimePeriod::findOrFail($validated['time_period_id']);
        $selectedCars = $validated['cars'] ?? [];

        // Ottieni le auto attualmente associate
        $currentCars = $timePeriod->carPrices()->pluck('car_id')->toArray();

        // Elimina le auto non selezionate
        $carsToDelete = array_diff($currentCars, $selectedCars);
        if (!empty($carsToDelete)) {
            CarPrice::where('time_period_id', $timePeriod->id)
                ->whereIn('car_id', $carsToDelete)
                ->delete();
        }

        // Aggiungi o aggiorna le auto selezionate
        foreach ($selectedCars as $carId) {
            CarPrice::updateOrCreate(
                [
                    'car_id' => $carId,
                    'time_period_id' => $timePeriod->id,
                ],
                [
                    'price' => $validated['price'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Associazioni aggiornate con successo!');
    }
}
